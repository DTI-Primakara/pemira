<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SSOController extends Controller
{
    public function callback(Request $request)
    {
        Log::info('Auth callback route hit');

        $code = $request->query('code');
        Log::info('Authorization code received: ' . ($code ?? 'NULL'));

        if (!$code) {
            Log::error('No code provided');
            abort(400, 'No code provided');
        }

        try {
            Log::info('Requesting access token...');
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'X-Service-Key' => env('SSO_API_TOKEN'),
            ])->post(env('SSO_URI') . '/oauth/token', [
                'grant_type' => 'authorization_code',
                'client_id' => env('SSO_CLIENT_ID'),
                'client_secret' => env('SSO_CLIENT_SECRET'),
                'redirect_uri' => env('SSO_REDIRECT_URI'),
                'code' => $code,
            ]);

            Log::info('Token response status: ' . $response->status());
            Log::info('Token response body: ' . $response->body());

            if ($response->failed()) {
                Log::error('Failed to get access token');
                abort(500, 'Failed to get access token');
            }

            $accessToken = $response->json('access_token');
            Log::info('Access token: ' . ($accessToken ?? 'NULL'));

            if (!$accessToken) {
                abort(500, 'No access token');
            }

            Log::info('Requesting user profile...');
            $userResponse = Http::withToken($accessToken)
                ->withHeaders([
                    'X-Service-Key' => env('SSO_API_TOKEN'),
                    'Accept' => 'application/json',
                ])
                ->get(env('SSO_URI') . '/api/user/profile');

            Log::info('User response status: ' . $userResponse->status());
            Log::info('User response body: ' . $userResponse->body());

            if ($userResponse->failed()) {
                abort(500, 'Failed to fetch user profile');
            }

            $user = $userResponse->json();
            Log::info('User data: ', $user);

            session(['user' => $user]);
            Log::info('User saved to session, redirecting to home');

            return redirect()->route('home');
        } catch (\Exception $e) {
            Log::error('Exception in /auth/callback: ' . $e->getMessage());
            abort(500, 'Unexpected error: ' . $e->getMessage());
        }
    }
}
