<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PolicyController;

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
    return view('main');
});
Route::get('/contact_form', function () {
    return view('contact_form');
});

Route::get('/policy/print', [PolicyController::class, 'print']);

