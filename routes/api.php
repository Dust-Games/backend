<?php

use Illuminate\Support\Facades\Route;

/*|==========| Frontend <-> Backend API |==========|*/

Route::group(
	[
		'domain' => 'api.dust.games',
		'as' => 'api.',
		'namespace' => 'Api',
	],
	function () {

	/*|=====| Auth |=====|*/

	Route::group(
		[
			'prefix' => 'auth',
			'as' => 'auth.',
			'middleware' => 'localization',
		],
		function () {
			Route::post('login', 'AuthController@login')->name('login');
			Route::post('register', 'AuthController@register')->name('register');
			Route::post('refresh-token', 'AuthController@refreshToken')->name('refresh');


			Route::group(
				[
					'middleware' => 'auth:api'
				],
				function () {
				    Route::post('logout', 'AuthController@logout')->name('logout');
				    Route::get('email/verify', 'VerifyEmailController@verify')->name('email.verify');
				    Route::get('email/verify/resend', 'VerifyEmailController@resend')->name('email.verify.resend');
				}
			);		
		}
	);

	/*|=====| Users |=====|*/

	Route::group(
		[
			'prefix' => 'users',
			'as' => 'users.',
		],
		function () {

			Route::group(
				[
					'middleware' => 'auth:api',
				],
				function () {
					Route::get('me', 'UserController@me')->name('me');
					Route::get('me/sessions', 'UserController@sessions')->name('sessions');
					Route::get('me/billing', 'UserController@billing')->name('billing');
					Route::get('me/accounts', 'UserController@accounts')->name('accounts');
				}
			);
		}
	);

	}
);

/*|==========| Bots |==========|*/

Route::group(
	[
		'domain' => 'bot.dust.games',
		'as' => 'bot.',
		'namespace' => 'Bot',
	],
	function () {

		/*|=====| Auth |=====|*/

		Route::group(
			[
				'prefix' => 'auth',
				'as' => 'auth.',
			],
			function () {

				Route::post('login', 'AuthController@login')->name('login');
				Route::post('refresh-token', 'AuthController@refreshToken')
					->name('refresh-token')->middleware('bot');
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

						Route::post('', 'BillingController@show')->name('billing');

						Route::put('set-coins', 'BillingController@setCoins')->name('set-coins');
						Route::put('add-coins', 'BillingController@addCoins')->name('add-coins');
						Route::put('reduce-coins', 'BillingController@reduceCoins')->name('reduce-coins');
					}
				);
			}
		);
	}
);

