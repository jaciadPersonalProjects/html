<?php
session_start();
error_reporting(E_ERROR | E_PARSE);

if (!isset($_SESSION['user'])) {
    header('Location: index.html');
    exit;
}

// db 
$conn = new mysqli('localhost', 'root', 'StrongDBPassword@', 'nitc');
if ($conn->connect_error) {
    die("[profile_update.php] Connection failed: " . $conn->connect_error);
}

// get user's email
$email = $_SESSION['user']['email'];

// only under POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: profile_update_form.php");
    exit;
}

// FORM INPUT CHECK 
if (
    empty($_POST['fname']) || empty($_POST['lname']) ||
    empty($_POST['mobile']) || empty($_POST['dob']) ||
    empty($_POST['gender']) || empty($_POST['password'])
) {
    echo "[profile_update.php] All fields are required.";
    exit;
}

// Form 
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$mobile = $_POST['mobile'];
$dob = $_POST['dob'];
$gender = $_POST['gender'];
$password = $_POST['password'];
$password_hash = password_hash($password, PASSWORD_DEFAULT);

//  update DB
$stmt = $conn->prepare("UPDATE register SET fname=?, lname=?, mobile=?, dob=?, gender=?, password=? WHERE email=?");
$stmt->bind_param("sssssss", $fname, $lname, $mobile, $dob, $gender, $password_hash, $email);

if ($stmt->execute()) {
    // refresh session from update 
    $query = $conn->prepare("SELECT * FROM register WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();
    $updated_user = $result->fetch_assoc();

    $_SESSION['user'] = $updated_user; 

    header("Location: wall.php");
    exit;
} else {
    echo "[profile_update.php] Error updating profile.";
}
?>
