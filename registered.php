<!-- registered.php -->
<?php include 'header.php'; ?>

<section id="sec" style="background: linear-gradient(white, #D3D8E8);">
  <div class="container">
    <div class="row">
      <div class="col-md-8" style="padding-top: 10px;">
        <a href="http://nitc.ac.in/">
          <img class="logo" src="img/nitc-logo.png" alt="logo" style="width:50%;" />
        </a>
      </div>
      <div class="col-md-4" style="padding-top: 10px;">
        <?php include 'signupform.php'; ?>
        <br>
        <div class="alert alert-success" role="alert">
          <strong>Well done!</strong> You Have Successfully Registered.
        </div>
      </div>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>
