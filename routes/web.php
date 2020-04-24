<?php

use Illuminate\Support\Facades\Route;

Route::get('oauth/{provider}/login', 'Api\OAuth\LoginController@redirectToProvider');
Route::get('oauth/{provider}/login/callback', 'Api\OAuth\LoginController@handleProviderCallback');

Route::get('oauth/{provider}/register', 'Api\OAuth\RegisterController@redirectToProvider');
Route::get('oauth/{provider}/register/callback', 'Api\OAuth\RegisterController@handleProviderCallback');

Route::get('register', 'AuthController@register')->name('register');
Route::get('login', 'AuthController@login')->name('login');

Route::get('test', function () {
	return (new App\Notifications\VerifyEmail)->locale(\App::getLocale())->toMail(App\Models\User::orderBy('created_at')->first());
	
})->name('verification.verify');