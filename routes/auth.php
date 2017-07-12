<?php

use Illuminate\Http\Request;


Route::get('/'.config('app.keymd5'), function (Request $request) {

    $request->session()->put('key', config('app.key'));

    return redirect('/');
});