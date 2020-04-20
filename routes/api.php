<?php

use Illuminate\Support\Facades\Route;

/*|==========| Frontend <-> Backend API |==========|*/

Route::group(
	[
		'prefix' => 'api',
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
					Route::get('me/wallet', 'UserController@billing')->name('billing');
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
		'prefix' => 'bot',
		'as' => 'bot',
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
				Route::post('refresh-token', 'AuthController@refreshToken')->name('login');
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
					}
				);
			}
		);
	}
);

