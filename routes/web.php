<?php

use Illuminate\Support\Facades\Route;

Route::get('register', 'AuthController@register')->name('register');
Route::get('login', 'AuthController@login')->name('login');