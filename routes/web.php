<?php

Route::get('/', function () {
    return view('index');
});
Route::resource('/nginx-configuration', 'NginxConfiguration');