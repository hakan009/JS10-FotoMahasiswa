<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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

Route::resource('mahasiswa', MahasiswaController::class);
Route::get('/mahasiswa/nilai/{nim}',[MahasiswaController::class,"nilai"])->name('nilai');
 

Route::get('/', function () {
    return view('home');
});

Route::get('/search',[MahasiswaController::class, 'search'])->name('search');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
