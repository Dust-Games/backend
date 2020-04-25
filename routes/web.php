<?php

use Illuminate\Support\Facades\Route;

Route::get('register', 'AuthController@register')->name('register');
Route::get('login', 'AuthController@login')->name('login');

/*
Route::get('test', function () {
	return (new App\Notifications\VerifyEmail)->locale(\App::getLocale())->toMail(App\Models\User::orderBy('created_at')->first());
	
});
*/