<?php
// github_login.php
include 'config.php';

$github_auth_url = "https://github.com/login/oauth/authorize?" . http_build_query([
    'client_id' => GITHUB_CLIENT_ID,
    'redirect_uri' => GITHUB_REDIRECT_URI,
    'scope' => 'user:email',
    'response_type' => 'code'
]);

header('Location: ' . $github_auth_url);
exit;
?>
