<?php

use Illuminate\Support\Facades\Route;


/*|=====| Auth |=====|*/

Route::group(
	[
		'prefix' => 'auth',
		'as' => 'auth.',
	],
	function () {
		Route::post('login', 'AuthController@login')->name('login');
	}
);

/*|=====| Users |=====|*/

Route::group(
	[
		'prefix' => 'users',
		'as' => 'users.',
		'middleware' => 'bot'
	],
	function () {

		/*|==| Billing |==|*/

		Route::group(
			[
				'prefix' => 'billing',
				'as' => 'billing',
			],
			function () {

				Route::post('', 'BillingController@show')
					->name('billing');
				Route::put('set-coins', 'BillingController@setCoins')
					->name('set-coins');
				Route::put('add-coins', 'BillingController@addCoins')
					->name('add-coins');
				Route::put('reduce-coins', 'BillingController@reduceCoins')
					->name('reduce-coins');
				Route::put('multi-add-coins', 'BillingController@multipleAddCoins')
					->name('multi-add-coins');
			}
		);
	}
);
