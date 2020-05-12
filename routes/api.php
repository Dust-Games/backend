<?php

use Illuminate\Support\Facades\Route;

/*|==========| Frontend <-> Backend API |==========|*/

Route::group(
	[
		'domain' => 'api.dust.game',
		'as' => 'api.',
		'namespace' => 'App\Http\Controllers'
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

		/*|=====| League |=====|*/

		Route::group(
			[
				'prefix' => 'league',
				'as' => 'league.',
			],
			function () {

				Route::get('week/{week}/members', 'LeagueRowController@getByWeek')
					->name('members.by-week');

				Route::get('current-week', 'LeagueRowController@getCurrentWeek')
					->name('current-week');
			}
		);
	}
);

/*|==========| Bots |==========|*/

Route::group(
	[
		'domain' => 'bot.dust.game',
		'as' => 'bot.',
		'namespace' => 'App\Modules\Bot\Http\Controllers',
	],
	base_path('Modules/Bot/Routes/api.php')
);

/*|==========| Admin panel |==========|*/

Route::group(
	[
		'domain' => 'api.admin.dust.game',
		'as' => 'admin.',
		'namespace' => 'App\Modules\Admin\Http\Controllers',
	],
	base_path('Modules/Admin/Routes/api.php')
);