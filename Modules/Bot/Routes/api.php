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
		'middleware' => 'bot'
	],
	function () {

		/*|==========| Users |==========|*/

		Route::group(
			[
				'prefix' => 'users',
				'as' => 'users.',
			],
			function () {

				/*|=====| Billings |=====|*/

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

		/*|==========| League |==========|*/

		Route::group(
			[
				'prefix' => 'league/members',
				'as' => 'league.members.',
			],
			function () {

				Route::resource('', 'LeagueRowController')
					->parameters(['' => 'leagueRow'])
					->only(['store', 'show', 'index']);

				Route::put(
					'{leagueRow}/add-score',
					'LeagueRowController@addScore'
				)->name('add-score');
			}
		);
	}
);

