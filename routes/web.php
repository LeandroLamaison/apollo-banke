<?php

use App\Http\Controllers\ClientController;
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

require __DIR__.'/auth.php';
