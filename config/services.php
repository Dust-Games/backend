<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*|==========| OAuth providers |==========|*/

    'steam' => [
        'client_id' => null,
        'client_secret' => env('STEAM_KEY'),
        'redirect' => null,
        'register_redirect' => env('STEAM_REGISTER_REDIRECT_URI'),
        'login_redirect' => env('STEAM_LOGIN_REDIRECT_URI'),
    ],

    'battlenet' => [
        'client_id' => env('BATTLE.NET_KEY'),
        'client_secret' => env('BATTLE.NET_SECRET'),
        'redirect' => null,
        'register_redirect' => env('BATTLE.NET_REGISTER_REDIRECT_URI'),
        'login_redirect' => env('BATTLE.NET_LOGIN_REDIRECT_URI'),
    ],

    'discord' => [
        'client_id' => env('DISCORD_KEY'),
        'client_secret' => env('DISCORD_SECRET'),
        'redirect' => null,
        'register_redirect' => env('DISCORD_REGISTER_REDIRECT_URI'),
        'login_redirect' => env('DISCORD_LOGIN_REDIRECT_URI'),
    ],

    'twitch' => [
        'client_id' => env('TWITCH_KEY'),
        'client_secret' => env('TWITCH_SECRET'),
        'redirect' => null,
        'register_redirect' => env('TWITCH_REGISTER_REDIRECT_URI'),
        'login_redirect' => env('TWITCH_LOGIN_REDIRECT_URI'),
    ],
];
