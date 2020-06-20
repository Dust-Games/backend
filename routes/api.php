<?php

use Illuminate\Support\Facades\Route;
use App\Models\Order;
use App\Exceptions\NotFoundException;

/*|==========| Frontend <-> Backend API |==========|*/

Route::group(
	[
		'domain' => 'api.dust.' . env('APP_DOMAIN'),
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

	/* Order */

     Route::group(
         [
             'prefix' => 'order',
             'as' => 'order.',
         ],
         function () {

             Route::group(
                 [
                     'middleware' => 'auth:api',
                 ],
                 function() {
                     Route::bind('order', function ($value) {
                         try {
                             return Order::query()
                                 ->where([
                                     'id' => $value,
                                     'closed' => false,
                                 ])
                                 ->firstOrFail();
                         } catch (Exception $e) {
                             throw new NotFoundException();
                         }
                     });

                     Route::get('me', 'OrderChangeController@meShow')->name('user-orders');
                     Route::post('create', 'OrderChangeController@create')->name('create-order');
                     Route::put('close/{order}', 'OrderChangeController@close')->name('close-order');
                     Route::put('credit/{order}', 'OrderChangeController@credit')->name('credit-order');
                     Route::put('debit/{order}', 'OrderChangeController@debit')->name('debit-order');

                     Route::put('exchange/{order}', 'OrderChangeController@exchange')
                         ->name('exchange-order');
                 }
             );

             Route::get('', 'OrderChangeController@index')->name('orders');
             Route::get('/{freeOrder}', 'OrderChangeController@show')->name('order');

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
		'domain' => 'bot.dust.' . env('APP_DOMAIN'),
		'as' => 'bot.',
		'namespace' => 'App\Modules\Bot\Http\Controllers',
	],
	base_path('Modules/Bot/Routes/api.php')
);

/*|==========| Admin panel |==========|*/

Route::group(
	[
		'domain' => 'api.admin.dust.' . env('APP_DOMAIN'),
		'as' => 'admin.',
		'namespace' => 'App\Modules\Admin\Http\Controllers',
	],
	base_path('Modules/Admin/Routes/api.php')
);
