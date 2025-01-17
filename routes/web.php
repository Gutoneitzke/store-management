<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployersController;
use App\Http\Controllers\QrCodeGeneratorController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\StocksController;
use App\Http\Controllers\StoresController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\UsersController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/qr-code/{productCode}', [QrCodeGeneratorController::class, 'generate'])->name('qrcode.generate');

    Route::prefix('categories')->controller(CategoriesController::class)->name('categories.')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->where('id', '[0-9]+')->name('edit');
        Route::put('/update/{id}', 'update')->where('id', '[0-9]+')->name('update');
        Route::delete('/{id}', 'destroy')->where('id', '[0-9]+')->name('destroy');
    });

    Route::prefix('stores')->controller(StoresController::class)->name('stores.')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->where('id', '[0-9]+')->name('edit');
        Route::put('/update/{id}', 'update')->where('id', '[0-9]+')->name('update');
        Route::delete('/{id}', 'destroy')->where('id', '[0-9]+')->name('destroy');
        Route::get('/{id}/selected-store', 'selectedStore')->where('id', '[0-9]+')->name('selected');
    });

    Route::prefix('stocks')->controller(StocksController::class)->name('stocks.')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->where('id', '[0-9]+')->name('edit');
        Route::put('/update/{id}', 'update')->where('id', '[0-9]+')->name('update');
        Route::delete('/{id}', 'destroy')->where('id', '[0-9]+')->name('destroy');
    });

    Route::prefix('suppliers')->controller(SuppliersController::class)->name('suppliers.')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->where('id', '[0-9]+')->name('edit');
        Route::put('/update/{id}', 'update')->where('id', '[0-9]+')->name('update');
        Route::delete('/{id}', 'destroy')->where('id', '[0-9]+')->name('destroy');
    });

    Route::prefix('employers')->controller(EmployersController::class)->name('employers.')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->where('id', '[0-9]+')->name('edit');
        Route::put('/update/{id}', 'update')->where('id', '[0-9]+')->name('update');
        Route::delete('/{id}', 'destroy')->where('id', '[0-9]+')->name('destroy');
    });

    Route::prefix('sales')->controller(SalesController::class)->name('sales.')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::get('/create/{id}', 'createAccordingQrcode')->name('create.qrcode');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->where('id', '[0-9]+')->name('edit');
        Route::put('/update/{id}', 'update')->where('id', '[0-9]+')->name('update');
        Route::delete('/{id}', 'destroy')->where('id', '[0-9]+')->name('destroy');
    });

    Route::prefix('customers')->controller(CustomersController::class)->name('customers.')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->where('id', '[0-9]+')->name('edit');
        Route::put('/update/{id}', 'update')->where('id', '[0-9]+')->name('update');
        Route::delete('/{id}', 'destroy')->where('id', '[0-9]+')->name('destroy');
    });

    Route::prefix('users')->controller(UsersController::class)->name('users.')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->where('id', '[0-9]+')->name('edit');
        Route::put('/update/{id}', 'update')->where('id', '[0-9]+')->name('update');
        Route::delete('/{id}', 'destroy')->where('id', '[0-9]+')->name('destroy');
    });
});
