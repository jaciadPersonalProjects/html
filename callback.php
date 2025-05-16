<?php
// Handle GitHub OAuth cancellation
if (isset($_GET['error']) && $_GET['error'] === 'access_denied') {
    echo "<script>
        alert('User cancelled the login');
        window.location.href = 'index.php';
    </script>";
    exit;
}

session_start();

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Exchange code for access token
    $tokenUrl = "https://github.com/login/oauth/access_token";
    $data = array(
        "client_id" => "YOUR_CLIENT_ID",
        "client_secret" => "YOUR_CLIENT_SECRET",
        "code" => $code,
        "redirect_uri" => "http://localhost/html/callback.php"
    );

    $options = array(
        "http" => array(
            "header" => "Content-Type: application/x-www-form-urlencoded\r\nAccept: application/json\r\n",
            "method" => "POST",
            "content" => http_build_query($data),
        ),
    );

    $context = stream_context_create($options);
    $result = file_get_contents($tokenUrl, false, $context);
    $response = json_decode($result, true);

    if (isset($response["access_token"])) {
        $access_token = $response["access_token"];
        $_SESSION['access_token'] = $access_token;

        // Get user info
        $opts = [
            "http" => [
                "header" => "User-Agent: MyCatchUpApp\r\nAuthorization: token $access_token\r\n"
            ]
        ];
        $ctx = stream_context_create($opts);
        $userInfo = file_get_contents("https://api.github.com/user", false, $ctx);
        $user = json_decode($userInfo, true);

        $_SESSION['user'] = $user;
        header("Location: loggedin.php");
        exit();
    } else {
        echo "Error retrieving access token.";
    }
} else {
    echo "Authorization code not found.";
}
?>
