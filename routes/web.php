<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ExternalTransferController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\InternalTransferController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\TransactionController;
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

Route::group(['middleware'=> ['auth', 'client', 'locale']], function () {
    Route::get('/dashboard', [ClientController::class, 'view'])->name('dashboard');
    
    Route::get('/transaction/{type}', [TransactionController::class, 'create'])->name('transaction');
    Route::post('/transaction/{type}', [TransactionController::class, 'store'])->name('transaction');
    
    Route::get('/transfers', [InternalTransferController::class, 'create'])->name('transfer');
    Route::post('/transfers', [InternalTransferController::class, 'store'])->name('transfer');

    Route::get('/external-transfers', [ExternalTransferController::class, 'create'])->name('external-transfer');
    Route::post('/external-transfers', [ExternalTransferController::class, 'store'])->name('external-transfer');
    
    Route::get('/history', [HistoryController::class, 'view'])->name('history');

    Route::get('/', function () {
        return redirect('/dashboard');
    });
});

Route::group(['middleware'=> ['auth', 'locale']], function () {
    Route::get('/client', [ClientController::class, 'create'])->name('client');
    Route::post('/client', [ClientController::class, 'store'])->name('client'); 
});


Route::group(['middleware' => ['auth', 'admin', 'locale']], function () {
    Route::get('/admin', [AdminController::class, 'view'])->name('admin');
});

Route::group([], function () {
    Route::get('/set-locale/{locale}', [LocaleController::class, 'set'])->name('set-locale');
});

require __DIR__.'/auth.php';
