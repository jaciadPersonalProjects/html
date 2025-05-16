<?php
session_start();
error_reporting(E_ERROR | E_PARSE);

// 必须有登录信息
if (!isset($_SESSION['user'])) {
    header('Location: index.html');
    exit;
}

// db
$conn = new mysqli('localhost', 'root', 'StrongDBPassword@', 'nitc');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// get email from session
$email = $_SESSION['user']['email'] ?? null;
if (!$email) {
    die('No email found in session.');
}

// query user
$stmt = $conn->prepare("SELECT * FROM register WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// if null, redirct to index
if (!$user) {
    header('Location: index.html');
    exit;
}

// var
$fname = $user['fname'] ?? '';
$lname = $user['lname'] ?? '';
$mobile = $user['mobile'] ?? '';
$dob = $user['dob'] ?? '';
$gender = $user['gender'] ?? '';
$image = $user['image'] ?? 'img/user.png';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Complete Your Profile</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/font-awesome.css" rel="stylesheet">
</head>
<body>

<?php include_once "header.php"; ?>

<div class="container">
    <h2 class="text-center">Complete Your Profile</h2>
    <form method="post" action="updateprofile.php" enctype="multipart/form-data">

        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="fname" class="form-control" value="<?php echo htmlspecialchars($fname); ?>" required>
        </div>

        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="lname" class="form-control" value="<?php echo htmlspecialchars($lname); ?>" required>
        </div>

        <div class="form-group">
            <label>Mobile</label>
            <input type="text" name="mobile" class="form-control" value="<?php echo htmlspecialchars($mobile); ?>">
        </div>

        <div class="form-group">
            <label>Date of Birth</label>
            <input type="date" name="dob" class="form-control" value="<?php echo htmlspecialchars($dob); ?>" required>
        </div>

        <div class="form-group">
            <label>Gender</label>
            <select name="gender" class="form-control" required>
                <option value="">Select Gender</option>
                <option value="male" <?php if($gender=='male') echo 'selected'; ?>>Male</option>
                <option value="female" <?php if($gender=='female') echo 'selected'; ?>>Female</option>
                <option value="other" <?php if($gender=='other') echo 'selected'; ?>>Other</option>
            </select>
        </div>

        <div class="form-group">
            <label>Profile Picture (Optional)</label><br>
            <img src="<?php echo htmlspecialchars($image); ?>" alt="Profile Picture" width="120" height="120"><br><br>
            <input type="file" name="profile_pic" class="form-control">
        </div>

        <div class="form-group">
            <label>Create a Password (Optional for OAuth Users)</label>
            <input type="password" name="password" class="form-control" placeholder="Leave empty if not needed">
        </div>

        <button type="submit" class="btn btn-success btn-block">Update Profile</button>

    </form>
</div>

<footer class="footer text-center">
    <p>NITC Social Network &copy; 2025</p>
</footer>

<script src="js/bootstrap.js"></script>
</body>
</html>
