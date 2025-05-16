<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSO Login</title>
</head>
<body>
    <h1>Login with Single Sign-On (SSO)</h1>
    <button onclick="window.location.href='controllers/SSOController.php?action=linkedin_login'">Login with LinkedIn</button>
    <button onclick="window.location.href='controllers/SSOController.php?action=github_login'">Login with GitHub</button>
</body>
</html>
