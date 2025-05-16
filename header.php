<!-- header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>"My Catch-up Space" Social Network Registration</title>

  <!-- Bootstrap and styles -->
  <link href="css/bootstrap.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <link href="css/font-awesome.css" rel="stylesheet">

  <!-- Scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
</head>
<body>
<header>
  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <a href="index.php">
          <h3 style="color:white; font-family:monospace; font-size: 31px"><b>&nbsp;My Catch-up Network</b></h3>
        </a>
      </div>
      <div class="col-md-8">
        <form class="form-inline" action="loggedin.php" method="POST">
          <div class="form-group">
            <input type="email" class="form-control" name="email" placeholder="Enter email" required>
          </div>
          <div class="form-group">
            <input type="password" class="form-control" name="password" placeholder="Password" required>
          </div>
          <button type="submit" class="btn btn-primary">Sign in</button>
          <div class="checkbox">
            <label><input type="checkbox"> Remember me</label>
          </div>
        </form>
        <a href="github_login.php" class="btn btn-default" style="background-color: #24292e; color: white; margin: 5px 0;">Login with GitHub</a>
        <a href="linkedin_login.php" class="btn btn-default" style="background-color: #0077b5; color: white; margin: 5px 0;">Login with LinkedIn</a>
      </div>
    </div>
  </div>
</header>
