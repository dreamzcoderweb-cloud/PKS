<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
Route::get('/', function () {
    return view('welcome');
});
Route::get('/server-commands/clear-cache', function () {

    // // Laravel caches
     Artisan::call('optimize:clear');

     return 'All caches cleared successfully!';
});
