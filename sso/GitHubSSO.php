<?php

class GitHubSSO {
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
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'state' => $this->state,
            'scope' => 'user'
        ];

        return 'https://github.com/login/oauth/authorize?' . http_build_query($params);
    }

    public function getAccessToken($code) {
        $params = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $code,
            'redirect_uri' => $this->redirectUri,
            'state' => $this->state
        ];

        $url = 'https://github.com/login/oauth/access_token';
        $response = $this->makeHttpRequest($url, $params);

        parse_str($response, $output);
        return $output['access_token'];
    }

    public function getUserData($accessToken) {
        $url = 'https://api.github.com/user';
        $headers = [
            'Authorization: token ' . $accessToken,
            'User-Agent: MyCatchUpSpace'
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
