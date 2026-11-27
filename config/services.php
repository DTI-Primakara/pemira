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

    /*
    |--------------------------------------------------------------------------
    | SISKA (Academic Information System)
    |--------------------------------------------------------------------------
    |
    | Source of truth for who is an active student, read through its public
    | student API. The key belongs to PUBLIC_API_KEYS on the SISKA side, not to
    | its service token — that one is the SSO credential and is not ours to
    | hold.
    |
    | status_aktif_id is the id of the "Aktif" lifecycle status in SISKA's
    | reference table. It is sent as a filter, and the status code on every row
    | that comes back is checked again on our side, so a wrong id here empties
    | the run instead of quietly enrolling alumni.
    |
    */

    'siska' => [
        'host' => env('SISKA_URI'),
        'api_key' => env('SISKA_API_KEY'),
        'status_aktif_id' => env('SISKA_STATUS_AKTIF_ID', 1),
        'per_page' => env('SISKA_PER_PAGE', 100),
        'timeout' => env('SISKA_TIMEOUT', 30),
    ],

];
