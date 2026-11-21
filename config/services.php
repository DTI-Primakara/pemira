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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Campus Single Sign-On
    |--------------------------------------------------------------------------
    |
    | sso.primakara.ac.id, a Laravel Passport authorisation-code server. This
    | is how every student signs in; PEMIRA keeps no student passwords of its
    | own. The redirect URI must match the one registered for this client on
    | the SSO side exactly, or the exchange is refused.
    |
    */

    'sso' => [
        'host' => env('SSO_URI', 'https://sso.primakara.ac.id'),
        'client_id' => env('SSO_CLIENT_ID'),
        'client_secret' => env('SSO_CLIENT_SECRET'),
        'redirect' => env('SSO_REDIRECT_URI'),
        'timeout' => env('SSO_TIMEOUT', 15),
    ],

];
