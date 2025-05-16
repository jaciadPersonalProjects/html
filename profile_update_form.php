<?php
session_start();
error_reporting(E_ERROR | E_PARSE);

// session check - if logged in 
if (!isset($_SESSION['user'])) {
    header('Location: index.html');
    exit;
}

// db 
$conn = new mysqli('localhost', 'root', 'StrongDBPassword@', 'nitc');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// get email 
$email = $_SESSION['user']['email'];

// check 
$query = "SELECT fname, lname, mobile, dob, gender FROM register WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$fname = $user['fname'] ?? '';
$lname = $user['lname'] ?? '';
$mobile = $user['mobile'] ?? '';
$dob = $user['dob'] ?? '';
$gender = $user['gender'] ?? '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Complete Your Profile</title>
  <link href="css/bootstrap.css" rel="stylesheet">
</head>
<body>
<div class="container">
  <h2 class="mt-4">Complete Your Profile</h2>
  <form method="post" action="profile_update.php">
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
      <input type="text" name="mobile" class="form-control" value="<?php echo htmlspecialchars($mobile); ?>" required>
    </div>

    <div class="form-group">
      <label>Date of Birth</label>
      <input type="date" name="dob" class="form-control" value="<?php echo htmlspecialchars($dob); ?>" required>
    </div>

    <div class="form-group">
      <label>Gender</label>
      <select name="gender" class="form-control" required>
        <option value="">Select</option>
        <option value="male" <?php if($gender=='male') echo 'selected'; ?>>Male</option>
        <option value="female" <?php if($gender=='female') echo 'selected'; ?>>Female</option>
        <option value="other" <?php if($gender=='other') echo 'selected'; ?>>Other</option>
      </select>
    </div>

    <div class="form-group">
      <label>Create Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Save and Continue</button>
  </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
