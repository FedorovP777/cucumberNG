<?php
/**
 * Created by PhpStorm.
 * User: pavel
 * Date: 06.07.17
 * Time: 20:58
 */

use Illuminate\Http\Request;


Route::get('/'.config('app.keymd5'), function (Request $request) {

    $request->session()->put('key', config('app.key'));
    return redirect('/');
});