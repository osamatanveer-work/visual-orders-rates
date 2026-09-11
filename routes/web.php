<?php

use App\Http\Controllers\BoxController;
use App\Http\Controllers\CarrierController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\MarkupController;
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function () {

    Route::view('home', 'index')->name('dashboard');
    Route::prefix('company')->group(function () {
        Route::get('all', [CompanyController::class, 'index'])->name('company.all');
        Route::get('/{slug}', [CompanyController::class, 'show'])->name('company.show');
        Route::post('store', [CompanyController::class, 'store'])->name('company.store');
        Route::get('edit/{id}', [CompanyController::class, 'edit'])->name('company.edit');
        Route::get('delete/{id}', [CompanyController::class, 'destroy'])->name('company.destroy');
        Route::put('update/{id}', [CompanyController::class, 'update'])->name('company.update');
        Route::put('/markup-status-update/{id}', [CompanyController::class, 'markupUpdate'])->name('markup.status.update');
        Route::prefix('store')->group(function () {
            Route::post('/update/{id}', [StoreController::class, 'update'])->name('store.update');
            Route::get('/delete/{id}', [StoreController::class, 'destroy'])->name('store.destroy');
            Route::post('/store/{id}', [StoreController::class, 'store'])->name('store.create');
        });
    });

    Route::prefix('markup-templates')->group(function () {
        Route::get('all', [MarkupController::class, 'index'])->name('markup.all');
        Route::get('create', [MarkupController::class, 'create'])->name('markup.create');
        Route::post('store', [MarkupController::class, 'store'])->name('markup.store');
        Route::get('delete/{id}', [MarkupController::class, 'destroy'])->name('markup.destroy');
        Route::post('update/{id}', [MarkupController::class, 'update'])->name('markup.update');
        Route::get('more-detail/{slug}', [MarkupController::class, 'show'])->name('markup.show');

        // Carriers

        Route::post('attach-carrier/{id}', [MarkupController::class, 'attachCarrier'])->name('markup.carrier');
        Route::post('update-carrier/{id}', [MarkupController::class, 'updateCarrier'])->name('markup.update.carrier');
        Route::get('delete-carrier/{id}', [MarkupController::class, 'deleteMarkupCarrier'])->name('markup.delete.carrier');
        Route::post('carrier-allow', [MarkupController::class, 'carrierAllow'])->name('markup.carrier.allow');
        // Services

        Route::post('attach-services/{id}', [MarkupController::class, 'attachServices'])->name('markup.services');
        Route::post('update-services/{id}', [MarkupController::class, 'updateServices'])->name('markup.update.service');
        Route::get('delete-services/{id}', [MarkupController::class, 'deleteServices'])->name('markup.delete.service');

    });

    Route::prefix('carrier')->group(function () {
        Route::get('all', [CarrierController::class, 'index'])->name('carrier.all');
        Route::get('create', [CarrierController::class, 'create'])->name('carrier.create');
        Route::post('store', [CarrierController::class, 'store'])->name('carrier.store');
        Route::get('delete/{id}', [CarrierController::class, 'destroy'])->name('carrier.destroy');
        Route::put('update/{id}', [CarrierController::class, 'update'])->name('carrier.update');
        Route::get('show/{slug}', [CarrierController::class, 'show'])->name('carrier.show');
        //Carriers
        Route::get('ups-shipping', [CarrierController::class, 'upsShipping'])->name('ups-shipping');
        Route::put('update-service/{id}', [CarrierController::class, 'updateService'])->name('update.service');
        Route::get('/select-service/{id}', [CarrierController::class, 'selectService'])->name('selectService');
        Route::post('/service-allow', [CarrierController::class, 'ServiceAllow'])->name('service.allow');

    });

    Route::prefix('users')->group(function () {
        Route::get('all', [UserController::class, 'index'])->name('users.all');
        Route::get('create', [UserController::class, 'create'])->name('users.create');
        Route::post('store', [UserController::class, 'store'])->name('users.store');
        Route::get('delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::put('update/{id}', [UserController::class, 'update'])->name('users.update');
    });

    Route::prefix('boxes')->group(function () {
        Route::get('all', [BoxController::class, 'index'])->name('box.all');
        Route::post('store', [BoxController::class, 'store'])->name('box.store');
        Route::get('delete/{id}', [BoxController::class, 'destroy'])->name('box.destroy');
        Route::put('update/{id}', [BoxController::class, 'update'])->name('box.update');
    });

    Route::prefix('rate-qoutes')->group(function () {
        Route::get('all', [CompanyController::class, 'rateQoutes'])->name('rateqoute.all');
        Route::get('services-list/{id}', [CompanyController::class, 'rateQoutesList'])->name('rateqoutes.list');
        Route::get('show/{slug}', [CompanyController::class, 'showStoreRateQoute'])->name('rateqoute.show');
        Route::get('delete/{id}', [CompanyController::class, 'deleteRateQoute'])->name('rateqoute.delete');
    });

    Route::get('getups', [CarrierController::class, 'getUPS'])->name('getUPS');

});








