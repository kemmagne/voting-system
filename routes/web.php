<?php

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
Route::get('/posts', 'ElectionController@index');
Route::get('/posts/{post}', 'ElectionController@show');
Route::post('/vote', 'VoteController@store');



Route::get('/', function () {


    return view('welcome');
});
