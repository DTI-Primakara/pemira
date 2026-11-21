<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Campus single sign-on, the only way a student reaches their ballot.
 *
 * The SSO is a Laravel Passport authorisation-code server, so a login is the
 * standard three legs: send the student there, swap the returned code for an
 * access token, then read their profile with that token. Nothing here touches
 * the local database — see User::syncFromSso() for that half.
 */
class SsoService
{
    private string $host;

    private string $clientId;

    private string $clientSecret;

    private string $redirectUri;

    public function __construct()
    {
        $this->host = rtrim((string) config('services.sso.host'), '/');
        $this->clientId = (string) config('services.sso.client_id');
        $this->clientSecret = (string) config('services.sso.client_secret');
        $this->redirectUri = (string) config('services.sso.redirect');
    }

    /**
     * Whether this deployment has SSO credentials at all. Checked before the
     * redirect so a missing .env sends the student back with a readable
     * message instead of to an SSO error page.
     */
    public function isConfigured(): bool
    {
        return $this->host !== '' && $this->clientId !== '' && $this->clientSecret !== '' && $this->redirectUri !== '';
    }

    /**
     * Where to send the student to identify themselves.
     *
     * `prompt=login` is deliberate: on a shared lab machine the SSO session of
     * whoever used it last would otherwise be handed straight to the next
     * student, who would then cast a ballot as them.
     */
    public function authorizeUrl(string $state): string
    {
        return $this->host.'/sso/oauth/authorize?'.http_build_query([
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => '*',
            'prompt' => 'login',
            'state' => $state,
        ]);
    }

    /**
     * Trade the one-time code from the callback for an access token.
     */
    public function exchangeCodeForToken(string $code): ?string
    {
        try {
            // Form encoding, not JSON: that is what the OAuth2 token endpoint
            // is specified to read.
            $response = $this->http()->asForm()->post($this->host.'/oauth/token', [
                'grant_type' => 'authorization_code',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'redirect_uri' => $this->redirectUri,
                'code' => $code,
            ]);
        } catch (\Throwable $e) {
            Log::error('SSO token request failed to reach the server.', ['message' => $e->getMessage()]);

            return null;
        }

        if ($response->failed()) {
            // The code itself is a credential, so only the status is logged.
            Log::warning('SSO refused the authorisation code.', ['status' => $response->status()]);

            return null;
        }

        $token = $response->json('access_token');

        return is_string($token) && $token !== '' ? $token : null;
    }

    /**
     * The signed-in student's identity, reduced to the three fields PEMIRA
     * keeps. Returns null when the response carries no usable e-mail, since
     * that is what every local account is keyed on.
     *
     * @return array{name: string, email: string, nim: ?string}|null
     */
    public function fetchProfile(string $accessToken): ?array
    {
        try {
            $response = $this->http()
                ->withToken($accessToken)
                ->acceptJson()
                ->get($this->host.'/api/user/profile');
        } catch (\Throwable $e) {
            Log::error('SSO profile request failed to reach the server.', ['message' => $e->getMessage()]);

            return null;
        }

        if ($response->failed()) {
            Log::warning('SSO rejected the profile request.', ['status' => $response->status()]);

            return null;
        }

        $data = $response->json('data') ?? $response->json();

        if (! is_array($data)) {
            return null;
        }

        $email = trim((string) ($data['email'] ?? ''));

        if ($email === '') {
            Log::warning('SSO returned a profile without an e-mail address.');

            return null;
        }

        $nim = trim((string) ($data['nim'] ?? ''));

        return [
            'name' => trim((string) ($data['name'] ?? '')),
            'email' => $email,
            'nim' => $nim === '' ? null : $nim,
        ];
    }

    /**
     * Timeouts are mandatory: without them one hanging SSO server would pin a
     * PHP worker per waiting student until the whole pool is gone.
     */
    private function http(): PendingRequest
    {
        return Http::connectTimeout(5)->timeout((int) config('services.sso.timeout', 15));
    }
}
