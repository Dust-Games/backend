<?php

use Illuminate\Support\Facades\Route;

/*|==========| Auth |==========|*/

Route::group(
	[
		'prefix' => 'auth',
		'as' => 'auth.',
	],
	function () {
		Route::post('login', 'AuthController@login')->name('login');
	}
);


Route::group(
	[
		'middleware' => 'admin',
	],
	function () {	
		
		/*|==========| OAuth accounts |==========|*/

		Route::group(
			[
				'prefix' => 'accounts',
				'as' => 'accounts.',
				'middleware' => 'admin',
			],
			function () {
				Route::resource('', 'OAuthAccountController')
					->parameters(['' => 'oAuthAccount'])
					->only(['show', 'index']);
			}
		);	
	}
);