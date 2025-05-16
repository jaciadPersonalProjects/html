<?php
session_start();
include 'config.php'; // config file

$client_id = "8607ei1zskiiu6";
$client_secret = "WPL_AP1.bxXrbzgFstJB59O4.i1j7EQ==";
$redirect_uri = "http://localhost/html/linkedin_callback.php";

// check
if (!isset($_GET['code'])) {
    die("[linkedin_callback.php] Authorization code not received");
}

$code = $_GET['code'];

// 1. exchange  access token
$token_url = "https://www.linkedin.com/oauth/v2/accessToken";

$post_fields = http_build_query([
    "grant_type" => "authorization_code",
    "code" => $code,
    "redirect_uri" => $redirect_uri,
    "client_id" => $client_id,
    "client_secret" => $client_secret
]);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $token_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/x-www-form-urlencoded"
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    die("[linkedin_callback.php] CURL Error: " . curl_error($ch));
}
curl_close($ch);

if ($http_code != 200) {
    die("[linkedin_callback.php] Failed to get access_token. HTTP Code: $http_code. Response: $response");
}

$token_data = json_decode($response, true);

if (!isset($token_data['access_token'])) {
    die("[linkedin_callback.php] Failed to get access_token: " . htmlspecialchars($response));
}

$access_token = $token_data['access_token'];

// 2. pull userinfo
$userinfo_url = "https://api.linkedin.com/v2/userinfo";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $userinfo_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $access_token"
]);

$userinfo_response = curl_exec($ch);
curl_close($ch);

$userinfo = json_decode($userinfo_response, true);

if (!$userinfo) {
    die("[linkedin_callback.php] Failed to get userinfo: " . htmlspecialchars($userinfo_response));
}

// 3. extract info 
$linkedin_id = $userinfo['sub'] ?? '';
$email = $userinfo['email'] ?? '';
$fname = $userinfo['given_name'] ?? '';
$lname = $userinfo['family_name'] ?? '';
$picture = $userinfo['picture'] ?? 'img/user.png'; // default avatar

// 4. database 
$stmt = $conn->prepare("SELECT * FROM register WHERE linkedin_id = ? OR email = ?");
$stmt->bind_param("ss", $linkedin_id, $email);
$stmt->execute();
$result = $stmt->get_result();
$existing_user = $result->fetch_assoc();

if ($existing_user) {
    // update linkedin_id
    if (empty($existing_user['linkedin_id'])) {
        $update = $conn->prepare("UPDATE register SET linkedin_id = ? WHERE email = ?");
        $update->bind_param("ss", $linkedin_id, $email);
        $update->execute();
    }
    $_SESSION['user'] = $existing_user;
} else {
    // add new user
    $insert = $conn->prepare("INSERT INTO register (linkedin_id, email, fname, lname, image) VALUES (?, ?, ?, ?, ?)");
    $insert->bind_param("sssss", $linkedin_id, $email, $fname, $lname, $picture);
    $insert->execute();

    $new_user_id = $conn->insert_id;
    $_SESSION['user'] = [
        'id' => $new_user_id,
        'email' => $email,
        'fname' => $fname,
        'lname' => $lname,
        'image' => $picture
    ];
}

// 5. redirct
header("Location: wall.php");
exit;
?>
