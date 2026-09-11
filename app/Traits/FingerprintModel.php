<?php

namespace App\Traits;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Events\QueuedClosure;

/**
 * FingerprintModel
 * This adds a fingerprint to the model.
 * This is accomplished by removing some attributes
 * such as ['id', 'created_at', 'updated_at', 'deleted_at', 'fingerprint']
 * and json_encodes the remaining attributes. I am fairly certain this
 * is expensive. However, it sure beats having to change your
 * queries to add additional columns in the future.
 *
 * @TODO: we may have to make some abstract methods to ensure contracts
 */
trait FingerprintModel
{
    /**
     * findOrCreateByFingerprint
     * Take a model (that shouldn't have been saved yet)
     * get the fingerprint and query to see if we have a record that matches.
     * If we have a match we return the matched ($check) model. If we don't
     * have a match then we save the model ($model) and return it
     *
     * @param FingerprintModel $model
     *
     * @return FingerprintModel
     */
    public static function findOrCreateByFingerprint(self $model)
    {
        $check = (new self)->where('fingerprint', '=', $model->getFingerprint())->first();

        if (!is_null($check)) {
            return $check;
        }

        $model->save();

        return $model;
    }

    /**
     * bootFingerprintModel
     * This is the "autoboot" method that allows
     * us to tap into the model events. We
     * will tap into the "saving" event and set our
     * fingerprint
     *
     * @return void
     */
    protected static function bootFingerprintModel(): void
    {
        static::creating(function ($model) {
            $model->fingerprint = $model->getFingerprint();
        });
    }

    /**
     * getFingerprint
     * This method takes the existing attributes of the model
     * casts them to an array, json_encodes them and then
     * does a hash (sha256) of that string. I would think
     * this is relatively expensive but I don't have a better way
     * at this moment in time.
     *
     * @TODO removeKeys is a static array - while in most cases this should work we should make it more customizable
     * @TODO removeKeys has a hardcoded "id" column... this should call $this->>primaryKey
     * @TODO removeKeys has a hardcoded "fingerprint" column - we should do this differently
     *
     * @return string
     */
    public function getFingerprint(): string
    {
        $removeKeys = ['id', 'created_at', 'updated_at', 'deleted_at', 'fingerprint'];
        $attributes = $this->toArray();

        foreach ($removeKeys as $key) {
            if (array_key_exists($key, $attributes)) {
                unset($attributes[$key]);
            }
        }

        return hash('sha256', json_encode($attributes));
    }

    /**
     * Register a creating model event with the dispatcher.
     *
     * @found ./vendor/laravel/framework/src/illuminate/Database/Eloquent/Concerns/HasEvents.php
     *
     * @param QueuedClosure|Closure|string|array $callback
     * @return void
     */
    #abstract public static function creating($callback): void;

    /**
     * Convert the model instance to an array.
     *
     * @found ./vendor/laravel/framework/src/illuminate/Database/Eloquent/Model.php
     *
     * @return array
     */
    #abstract public function toArray(): array;

    /**
     * scopeOfFingerprint
     * scope a query to only include a fingerprinted record
     *
     * @param Builder $query
     * @param string $fingerprint
     *
     * @return void
     */
    public function scopeOfFingerprint(Builder $query, string $fingerprint): void
    {
        $query->where('fingerprint', '=', $fingerprint);
    }
}