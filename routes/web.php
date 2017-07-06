<?php

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
use Illuminate\Http\Request;
Route::get('/', function () {

    return view('index');
});


//Route::get('/key', function (Request $request) {
//
//dd($request->session()->get('key'));
//    echo config('app.keymd5');
//
//});

Route::resource('/config', 'ConfigController');
Route::resource('/setting', 'SettingsController');

