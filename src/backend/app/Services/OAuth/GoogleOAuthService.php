<?php

namespace App\Services\OAuth;

use Google_Client;
use Google_Service_Oauth2;

class GoogleOAuthService
{
    private Google_Client $client;

    private string $clientId;

    private string $clientSecret;

    private string $redirectUri;

    public function __construct()
    {
        $this->clientId = config('services.google.client_id');
        $this->clientSecret = config('services.google.client_secret');
        $this->redirectUri = config('services.google.redirect_uri');

        if (is_null($this->clientId) || is_null($this->clientSecret) || is_null($this->redirectUri)) {
            throw new \Exception('Google OAuth service configuration is missing.');
        }

        $this->client = new Google_Client;
        $this->client->setClientId($this->clientId);
        $this->client->setClientSecret($this->clientSecret);
        $this->client->setRedirectUri($this->redirectUri);
        $this->client->addScope('email');
    }

    public function getAuthUrl(): string
    {
        return $this->client->createAuthUrl();
    }

    public function authenticate(string $code): array
    {
        $this->client->fetchAccessTokenWithAuthCode($code);

        if ($this->getAccessToken()) {
            $oauth2Service = new Google_Service_Oauth2($this->client);
            $userInfo = $oauth2Service->userinfo_v2_me->get();

            return [
                'id' => $userInfo['id'],
                'email' => $userInfo['email'],
                'name' => $userInfo['name'],
            ];
        }

        throw new \Exception('Falha na autenticação com o google.');
    }

    public function getAccessToken(): string
    {
        return $this->client->getAccessToken()['access_token'];
    }
}
