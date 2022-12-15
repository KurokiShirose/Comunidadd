<?php
  include("connection.php");
  session_start();

  if(!$_SESSION["username_s"]) {
    header("Location: index.php");
    exit;
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Verified Users Only</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/SVG/logo-black.svg" rel="icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

   <!-- Style CSS -->
   <link rel="stylesheet" href="css/style-profile.css">

</head>

<body style="background-color: rgb(229, 229, 229) ;">

  <!-- ======= Top Bar ======= -->
  <section id="topbar" class="d-flex align-items-center">
    <div class="container d-flex justify-content-center justify-content-md-between">
      <div class="contact-info d-flex align-items-center">
        <i class="bi bi-envelope d-flex align-items-center"><a href="mailto:comunidadPH@gmail.com">comunidadPH@gmail.com</a></i>
      </div>
      <div class="social-links d-none d-md-flex align-items-center">
        <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
        <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
      </div>
    </div>
  </section>
  <div class="container d-none d-sm-block" style="margin-top:50px; margin-bottom: 50px;">
    <div class="row d-flex justify-content-center" >
        <div class="col-md-12">
            <div class="card card-default" >
                <div class="card-body " style="padding: 50px 75px 50px 75px;">
                    <div class="row">
                        <div>
                            <span><h1 style="color: #F69312"><b>:(</b></h1></span>
                            <span><h1><b>Sorry, <span style="color: #F69312;"><?php echo $_SESSION["fname_s"]." ".$_SESSION["lname_s"] ?></span>.</b></h1></span>
                        </div>
                        <hr class="style1">
                    </div>
                    <div class="row">
                        <h3 style="margin-left: 0px;"><b><i>Verified Users Only.</i></b></h3>
                        <p> You are seeing this because you either trying to access a verified only page, reached the maximum (5) profile visits per hour, or posted three (3) posts which is the limit for an unverified account.</p>
                        <br>
                        <p>To unlock all other features of <b>Comunidad</b>, please <a class="btn-link" href="page-verify.php">verify your account</a>.</p>
                        <br>
                        <h6>Thank you and Mabuhay!</h6>
                    </div>
                    <hr class="style1">
                    <a href="page-verify.php"><button class="btn btn-custom-reg" style="background-color: #F69312; font-size:medium;">
                      <i class="fa fa-check-circle"></i> <b>Verify Now</b></button></a>
                      <a href="page-browse.php"><button class="btn btn-custom-reg" style="background-color: #efefef; font-size:medium; color:black;">
                      <i class="fa fa-arrow-left"></i> <b>Continue Browsing</b></button></a>
                </div>
            </div>
        </div>
      </div>
  </div>

  <div class="container p-3 d-block sm-none">
    <div class="row d-flex justify-content-center" >
        <div class="col-md-12">
            <div class="card card-default" >
                <div class="card-body px-5 py-4">
                    <div class="row">
                        <div>
                            <span><h1 style="color: #F69312"><b>:(</b></h1></span>
                            <span><h1><b>Sorry, <span style="color: #F69312;"><?php echo $_SESSION["fname_s"]." ".$_SESSION["lname_s"] ?></span>.</b></h1></span>
                        </div>
                        <hr class="style1">
                    </div>
                    <div class="row">
                        <h3 style="margin-left: 0px;"><b><i>Verified Users Only.</i></b></h3>
                        <p> You are seeing this because you either trying to access a verified only page, reached the maximum (5) profile visits per hour, or posted three (3) posts which is the limit for an unverified account.</p>
                        <br>
                        <p>To unlock all other features of <b>Comunidad</b>, please <a class="btn-link" href="page-verify.php">verify your account</a>.</p>
                        <br>
                        <h6>Thank you and Mabuhay!</h6>
                    </div>
                    <hr class="style1">
                    <a href="page-verify.php"><button class="btn btn-custom-reg" style="background-color: #F69312; font-size:medium;">
                      <small><i class="fa fa-check-circle"></i> <b>Verify Now</b></small></button></a> <br>
                      <a href="page-browse.php"><button class="btn btn-custom-reg" style="background-color: #efefef; font-size:medium; color:black;">
                      <small><i class="fa fa-arrow-left"></i> <b>Continue Browsing</b></small></button></a>
                </div>
            </div>
        </div>
      </div>
  </div>


    <!-- ======= Footer ======= -->
<footer id="footer">

  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>