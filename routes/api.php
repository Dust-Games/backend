<?php

use Illuminate\Support\Facades\Route;

/*|==========| Frontend <-> Backend API |==========|*/

Route::group(
	[
		'domain' => 'api.dust.game',
		'as' => 'api.',
		'namespace' => 'Api',
	],
	function () {

	/*|=====| Auth |=====|*/

	Route::get('add-coins', function () {
		
		return view('add_coins');
	});
	
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

	/*|=====| OAuth |=====|*/

	Route::group(
		[
			'prefix' => 'oauth',
			'as' => 'oauth.',
			'namespace' => 'OAuth',
		],
		function () {

			Route::get('{provider}/login', 'LoginController@redirectToProvider');
			Route::get('{provider}/login/callback', 'LoginController@handleProviderCallback');

			Route::get('{provider}/register', 'RegisterController@redirectToProvider');
			Route::get('{provider}/register/callback', 'RegisterController@handleProviderCallback');

			Route::get('{provider}/bind', 'BindController@redirectToProvider');
			Route::get('{provider}/bind/callback', 'BindController@handleProviderCallback');

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
		'domain' => 'bot.dust.game',
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
	}
);

/*|==========| Admin panel |==========|*/

Route::group(['domain' => 'admin.dust.game'], base_path('Modules/Admin/Routes/api.php'));