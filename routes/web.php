<?php

use Illuminate\Support\Facades\Route;

Route::get('oauth/{provider}/login', 'OAuth\LoginController@redirectToProvider');
Route::get('oauth/{provider}/login/callback', 'OAuth\LoginController@handleProviderCallback');

Route::get('register', 'AuthController@register')->name('register');
Route::get('login', 'AuthController@login')->name('login');