<?php

namespace App\Helpers;

class OAuthProviders
{
    public const PROVIDER_IDS = [1, 2, 3, 4];

    public const PROVIDERS = [
    	'steam' => [
    		'id' => 1,
    	],
    	'twitch' => [
    		'id' => 2,
    	],
    	'discord' => [
    		'id' => 3,
    	],
        'battlenet' => [
            'id' => 4,
        ],
    ];

    public static function ids()
    {
        return static::PROVIDER_IDS;    
    }

    public static function __callStatic($method, $args)
    {
        if (empty($args)) {
            return static::PROVIDERS[$method];
        }

        return static::PROVIDERS[$method][$args[0]];
    }

    private function __construct()
    {
        # (o_o) #
    }
}
