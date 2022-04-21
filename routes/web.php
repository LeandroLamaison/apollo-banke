<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\InternalTransferController;
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

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', [ClientController::class, 'view'])->middleware(['auth', 'client'])->name('dashboard');

Route::get('/client', [ClientController::class, 'create']) -> middleware(['auth'])->name('client');
Route::post('/client', [ClientController::class, 'store']) -> middleware(['auth'])->name('client');

Route::get('transaction/{type}', [TransactionController::class, 'create'])->middleware(['auth'])->name('transaction');
Route::post('transaction/{type}', [TransactionController::class, 'store'])->middleware(['auth'])->name('transaction');

Route::get('transfers', [InternalTransferController::class, 'create'])->middleware(['auth'])->name('transfer');
Route::post('transfers', [InternalTransferController::class, 'store'])->middleware(['auth'])->name('transfer');


require __DIR__.'/auth.php';
