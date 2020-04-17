<?php

use Illuminate\Support\Facades\Route;

Route::get('oauth/{provider}/login', 'OAuthController@redirectToProvider');
Route::get('oauth/{provider}/callback', 'OAuthController@handleProviderCallback');