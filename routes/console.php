<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('secret {length=20}', function ($length) {

	$this->comment(bin2hex(random_bytes($length)));

})->describe('Generate cryptographically secure string');

Artisan::command('bot:keys {length=20}', function ($length) {

	$this->info('ID: ' . \Ramsey\Uuid\Uuid::uuid4());
	$this->comment('Secret: ' . bin2hex(random_bytes($length)));

})->describe('Generate id and secret for bot');

Artisan::command('make:admin', function(){
    $user =  \App\Models\User::query()->where('email', 'spanri.dev@mail.ru')->firstOrFail();
    $user->role_id = 2;
    $user->save();
})->describe('Make admin');

Artisan::command('test', function (\App\Models\Settings $s) {
    dd($s->leagueWeek());
})->describe('test something');
