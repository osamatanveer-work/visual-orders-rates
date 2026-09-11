<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class ApiRequestNote extends Model
{
    use HasFactory;

    /*
     * $table->bigInteger('request_id')->unsigned();
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->string('level');
            $table->string('message');
            $table->json('data')->nullable();
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'request_id',
        'parent_id',
        'level',
        'message',
        'data'
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'request_id' => 'integer',
        'parent_id' => 'integer',
        'level' => 'string',
        'message' => 'string',
        'data' => 'array'
    ];

    public function calculateBenchmark(int $note_id): float {
        #proudly stolen from: https://laracasts.com/discuss/channels/laravel/get-difference-of-two-carbon-time
        $firstNote = self::find($note_id);

        if (is_null($firstNote)) {
            throw new \Exception('Unable to locate firstNote');
        }

        $date1 = Carbon::createFromFormat('Y-m-d H:i:s', $firstNote->created_at);
        $date2 = Carbon::createFromFormat('Y-m-d', $this->created_at);

        return $date1->diffInSeconds($date2);
    }

    public static function newNote(string $level, string $message, array $data = [], ?self $parent = null): self
    {
        if (!App::bound('apiRequestHeader')) {
            throw new Exception('IOC apiRequestHeader');
        }

        $apiRequestHeader = App::make('apiRequestHeader');

        $level = trim(strtolower($level));
        $validLevels = ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'];
        if (!in_array($level, $validLevels)) {
            $level = 'info';
        }

        Log::$level($message, $data);

        $instance = new self;
        $instance->request_id = $apiRequestHeader->id;
        $instance->level = $level;
        $instance->message = substr($message, 0, 254);
        $instance->data = $data;

        if (!is_null($parent)) {
            $instance->parent_id = $parent->id;
        }

        $instance->save();

        return $instance;
    }

    public function request(): HasOne
    {
        return $this->hasOne(ApiRequestHeader::class, 'id', 'request_id');
    }

    public function parent(): HasOne
    {
        return $this->hasOne(self::class, 'id', 'parent_id');
    }
}
