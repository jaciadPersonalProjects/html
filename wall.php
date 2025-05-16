<?php
error_reporting(E_ERROR | E_PARSE);
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: index.html');
    exit;
}

$conn = new mysqli('localhost', 'root', 'StrongDBPassword@', 'nitc');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['user']['email'];

// check user info in db
$stmt = $conn->prepare("SELECT fname, lname, dob, gender, password FROM register WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (empty($user['dob']) || empty($user['gender']) || empty($user['password'])) {
    header("Location: profile_update.php");
    exit;
}

?>

<a href="github_login.php" class="btn btn-default" style="background-color: #24292e; color: white; margin: 5px 0;">Login with GitHub</a>
<a href="linkedin_login.php" class="btn btn-default" style="background-color: #0077b5; color: white; margin: 5px 0;">Login with LinkedIn</a>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Social Network Wall</title>
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/font-awesome.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style>
.show-on-hover:hover > ul.dropdown-menu { display: block; }
.dropbtn { color: white; padding: 16px; font-size: 16px; border: none; cursor: pointer; }
.dropdown { position: relative; display: inline-block; }
.dropdown-content { display: none; position: absolute; background-color: #f9f9f9; min-width: 300px; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); z-index: 1; }
.dropdown-content a { color: black; text-decoration: none; display: block; }
</style>
</head>
<body>

<?php include_once "header.php"; ?>

<section>
<div class="container">
<div class="row">
<div class="col-md-8">
<div class="panel panel-default">
<div class="panel-heading"><h3 class="panel-title">Wall</h3></div>
<div class="panel-body">
<div class="form-group">
<textarea class="form-control" placeholder="Write on the wall" id="txta"></textarea>
</div>
<button type="submit" class="btn btn-default" id="submit">Submit</button>
<div class="pull-right">
<div class="btn-toolbar">
<input type="button" class="btn btn-primary btn-large" onclick="ShowUploadDialog();" value="upload" />
<input type="hidden" id="imgSmall" class="form-control" placeholder="Image">
<div id="msg" style="text-align: center;"></div>
<input type="hidden" id="email" value="<?php echo $email; ?>">
</div>
</div>
</div>
</div>

<div class="panel panel-default post">
<?php
$query = "SELECT * FROM `post` WHERE status='TRUE' ORDER BY `id` DESC";
$result = $conn->query($query);
while ($row = mysqli_fetch_assoc($result)) {
    $profile = 'img/user.png';
    $name = 'Unknown';
    $query1 = "SELECT `fname`,`image` FROM `register` WHERE email ='" . $row['email'] . "'";
    $result1 = $conn->query($query1);
    if ($result1 && $row1 = mysqli_fetch_assoc($result1)) {
        $profile = $row1['image'];
        $name = $row1['fname'];
    }
    $id = $row['id'];
    echo '<div class="panel-body">
    <div class="row">
    <div class="col-sm-2">
    <a href="#" class="post-avatar thumbnail"><img src="' . $profile . '" alt=""><div class="text-center">' . $name . '</div></a>
    <div class="likes text-center">' . $row['like'] . ' Like</div>
    </div>
    <div class="col-sm-10">
    <div class="bubble" style="margin-bottom: 7px;">
    <div class="pointer"><p>' . $row['text'] . '</p></div>
    <div class="pointer-border"></div>
    </div>
    <div class="cms">
    <div class="cms-photo"><img src="' . $row['image'] . '" class="img-responsive" style="width:100%; height:30%;"></div>
    </div>
    <p class="post-actions"><a href="like.php?id=' . $row['id'] . '">Like</a></p>
    <div class="comment-form">
    <form action="comment.php" method="post" class="form-inline">
    <input type="hidden" name="id" value="' . $row['id'] . '">
    <input type="hidden" name="email" value="' . $email . '">
    <div class="form-group">
    <input type="text" name="comment" class="form-control" placeholder="Enter comment">
    </div>
    <button type="submit" class="btn btn-default">Comment</button>
    </form>
    </div>
    <div class="clearfix"></div>
    <div class="comments">';

    $query2 = "SELECT * FROM `comment` as c, `register` as r WHERE c.id=$id AND c.email=r.email ORDER BY `count` DESC";
    $result2 = $conn->query($query2);
    while ($row2 = mysqli_fetch_assoc($result2)) {
        echo '<div class="comment">
        <a href="seeprofile.php?mail=' . $row2['email'] . '" class="comment-avatar pull-left"><img src="' . $row2['image'] . '" alt=""></a>
        <div class="comment-text"><p>' . $row2['comment'] . '</p></div>
        </div><div class="clearfix"></div>';
    }
    echo '</div>
    </div>
    </div>
    </div>';
}
?>
</div>
</div>

<div class="col-md-4">
<?php include_once "my-friend.php"; ?>
<?php include_once "allmember.php"; ?>
</div>
</div>
</div>
</section>

<footer><div class="footer"><p></p></div></footer>

<script>
function ShowUploadDialog() {
    window.open("uploadform.php?pageId=reItems", "_blank", "toolbar=no,scrollbars=no,resizable=no,top=300,left=500,width=300,height=300");
}

$(document).ready(function() {
    $("#submit").click(function() {
        var txt = $("#txta").val();
        var img = $("#imgSmall").val();
        var email = $("#email").val();
        var dataString = "img=" + img + "&txt=" + txt + "&email=" + email;
        if (txt == "") {
            alert("Please Fill All the Fields");
        } else {
            $.ajax({
                type: "POST",
                url: "postupload.php",
                data: dataString,
                cache: false,
                success: function(result) {
                    $("#msg").html(result).css("color", "green");
                    $("#txta").val(" ");
                },
                error: function() {
                    alert("Error");
                }
            });
        }
    });
});
</script>
</body>
</html>
