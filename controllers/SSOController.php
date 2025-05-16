<?php

require_once 'sso/LinkedInSSO.php';
require_once 'sso/GitHubSSO.php';
require_once 'config/sso_config.php';

class SSOController {
    private $linkedinSSO;
    private $githubSSO;

    public function __construct() {
        $config = require 'config/sso_config.php';
        $this->linkedinSSO = new LinkedInSSO(
            $config['linkedin']['client_id'],
            $config['linkedin']['client_secret'],
            $config['linkedin']['redirect_uri']
        );
        $this->githubSSO = new GitHubSSO(
            $config['github']['client_id'],
            $config['github']['client_secret'],
            $config['github']['redirect_uri']
        );
    }

    public function handleLinkedInLogin() {
        $loginUrl = $this->linkedinSSO->getLoginUrl();
        header("Location: $loginUrl");
        exit();
    }

    public function handleLinkedInCallback() {
        if (isset($_GET['code'])) {
            $code = $_GET['code'];
            $accessToken = $this->linkedinSSO->getAccessToken($code);
            $userData = $this->linkedinSSO->getUserData($accessToken);
            // Handle user data (e.g., create or update user in the database)
        } else {
            // Handle error
        }
    }

    public function handleGitHubLogin() {
        $loginUrl = $this->githubSSO->getLoginUrl();
        header("Location: $loginUrl");
        exit();
    }

    public function handleGitHubCallback() {
        if (isset($_GET['code'])) {
            $code = $_GET['code'];
            $accessToken = $this->githubSSO->getAccessToken($code);
            $userData = $this->githubSSO->getUserData($accessToken);
            // Handle user data (e.g., create or update user in the database)
        } else {
            // Handle error
        }
    }
}
