<?php
// config.php

// Database config
$servername = "127.0.0.1";
$username = "root";
$password = "StrongDBPassword@";
$dbname = "nitc";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// GitHub OAuth config
define('GITHUB_CLIENT_ID', 'Ov23liGVG1v3lmoAvorv');
define('GITHUB_CLIENT_SECRET', '69845b3345d73b6ece3ee75465dd3ea5422fd957');
define('GITHUB_REDIRECT_URI', 'http://localhost/html/callback.php');

// LinkedIn OAuth config
define('LINKEDIN_CLIENT_ID', '8607ei1zskiiu6');
define('LINKEDIN_CLIENT_SECRET', 'WPL_AP1.bxXrbzgFstJB59O4.i1j7EQ==');
define('LINKEDIN_REDIRECT_URI', 'http://localhost/html/linkedin_callback.php');
?>
