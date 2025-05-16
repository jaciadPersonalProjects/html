<!-- signupform.php -->
<div class="panel-heading">
  <h3 class="panel-title">Sign Up Here</h3>
</div>
<div class="panel-body">
  <form action="register.php" method="POST">
    <input class="form-control" name="fname" type="text" placeholder="First Name" required><br>
    <input class="form-control" name="lname" type="text" placeholder="Last Name" required><br>
    <input class="form-control" name="mobile" type="number" placeholder="Mobile" required><br>
    <input class="form-control" name="email" type="email" placeholder="E-mail" required><br>
    <input class="form-control" name="pass" type="password" placeholder="Password (6 chars min)" required><br>
    <input class="form-control" name="dob" type="date" placeholder="DD-MM-YYYY"><br>
    <div>
      <input type="radio" name="gender" value="male" checked> Male
      <input type="radio" name="gender" value="female"> Female
    </div><br>
    <button type="submit" class="btn btn-primary">Sign Up</button>
  </form>
  <br>
  <div class="text-muted text-center">
    By signing up, you agree to our
    <a href="#" class="text-muted">Terms and Conditions</a> and
    <a href="#" class="text-muted">Privacy Policy</a>
  </div>
  <div class="text-center">
    <a class="text-muted" href="#">About</a> |
    <a class="text-muted" href="#">FAQ</a> |
    <a class="text-muted" href="#">Privacy</a> |
    <a class="text-muted" href="#">Terms</a>
  </div>
</div>
