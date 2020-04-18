<?php

use Illuminate\Support\Facades\Route;

Route::get('oauth/{provider}/login', 'Api\OAuth\LoginController@redirectToProvider');
Route::get('oauth/{provider}/login/callback', 'Api\OAuth\LoginController@handleProviderCallback');

Route::get('oauth/{provider}/register', 'Api\OAuth\RegisterController@redirectToProvider');
Route::get('oauth/{provider}/register/callback', 'Api\OAuth\RegisterController@handleProviderCallback');

Route::get('register', 'AuthController@register')->name('register');
Route::get('login', 'AuthController@login')->name('login');