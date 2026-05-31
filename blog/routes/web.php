<?php

use Illuminate\Support\Facades\Route;

Route::get('/articles', function() {
    return "Article Route";
});

Route::get('/articles/detail/{id}', function ($id) {
    return "Article Detail Route - $id";
});

Route::get('/', function () {
    return view('welcome');
});
