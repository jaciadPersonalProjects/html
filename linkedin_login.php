<?php
session_start();
include 'config.php';

$client_id = "8607ei1zskiiu6"; 
$redirect_uri = "http://localhost/html/linkedin_callback.php"; 
$scope = "openid profile email"; 

$auth_url = "https://www.linkedin.com/oauth/v2/authorization?" . http_build_query([
    "response_type" => "code",
    "client_id" => $client_id,
    "redirect_uri" => $redirect_uri,
    "scope" => $scope,
    "state" => "xyz123" // csrf prevent
]);

header("Location: $auth_url");
exit;
?>
