<?php

class LinkedInSSO {
    private $clientId;
    private $clientSecret;
    private $redirectUri;
    private $state;

    public function __construct($clientId, $clientSecret, $redirectUri) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
        $this->state = bin2hex(random_bytes(16));
    }

    public function getLoginUrl() {
        $params = [
            'response_type' => 'code',
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'state' => $this->state,
            'scope' => 'r_liteprofile r_emailaddress'
        ];

        return 'https://www.linkedin.com/oauth/v2/authorization?' . http_build_query($params);
    }

    public function getAccessToken($code) {
        $params = [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $this->redirectUri,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret
        ];

        $url = 'https://www.linkedin.com/oauth/v2/accessToken';
        $response = $this->makeHttpRequest($url, $params);

        return json_decode($response, true)['access_token'];
    }

    public function getUserData($accessToken) {
        $url = 'https://api.linkedin.com/v2/me';
        $headers = [
            'Authorization: Bearer ' . $accessToken
        ];

        $response = $this->makeHttpRequest($url, [], $headers);

        return json_decode($response, true);
    }

    private function makeHttpRequest($url, $params = [], $headers = []) {
        $ch = curl_init();

        if (!empty($params)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}
