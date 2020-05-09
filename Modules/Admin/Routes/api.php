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
		Route::post('refresh-token', 'AuthController@refreshToken')->name('refresh');

		Route::group(
			[
				'middleware' => 'admin',
			],
			function () {
				Route::post('logout', 'AuthController@logout')->name('logout');
			}
		);
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
			],
			function () {
				Route::resource('', 'OAuthAccountController')
					->parameters(['' => 'oAuthAccount'])
					->only(['show', 'index']);
			}
		);

		/*|==========| Unregistered billings |==========|*/

		Route::group(
			[
				'prefix' => 'unreg-billings',
				'as' => 'unreg-billings.',
			],
			function () {
				Route::resource('', 'UnregisteredBillingController')
					->parameters(['' => 'unregisteredBilling'])
					->only(['show', 'index', 'update']);

				Route::put('{unregisteredBilling}/add', 'UnregisteredBillingController@add')
					->name('add');

				Route::put('{unregisteredBilling}/reduce', 'UnregisteredBillingController@reduce')->name('reduce');
			}
		);

		/*|==========| Transactions |==========|*/

		Route::group(
			[
				'prefix' => 'transactions',
				'as' => 'transactions.',
			],
			function () {
				Route::resource('', 'TransactionController')
					->parameters(['' => 'transaction'])
					->only(['show', 'index']);
			}
		);
	}
);