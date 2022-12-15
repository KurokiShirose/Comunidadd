<?php
    include("connection.php");
    session_start();

    if(isset($_POST["submit"])){
      $_SESSION["otp_s"] = rand(100000,999999);

      if($_POST["tabs"] == "artist"){
        $_SESSION["type_s"] = "artist"; 
        header("Location: email.php");
      }
      elseif($_POST["tabs"] == "client"){
        $_SESSION["type_s"] = "client";
        header("Location: email.php");
      }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Account Type Selection</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/SVG/logo-black.svg" rel="icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <link rel="stylesheet" href="dist/css/adminlte.min.css">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  <link href="assets/css/style-select-acc.css" rel="stylesheet">

</head>

<body class="login-bg" >

  <main>

      <!-- ======= Verify Section ======= -->

      <section class="d-none d-sm-block">
        <div class="container">
          <div class="row d-flex justify-content-center align-items-center">
            <div>
              <div class="card" style="border-radius: 1rem;">
                <div class="card-body p-5-10 text-center">
                  <div>
                    <form action="page-signup-account-type.php" method="post">
                      <h2><b>User Account Type</b></h2>
                      <h5>Thank you</b><span class="span-dot"> <?php echo $_SESSION['fname_s'], ' ', $_SESSION['lname_s']; ?></span> for signing-up here at <b>Communidad!</b> </h5>
                      <h6>We're excited for you to part of our community.</h6>
                      <p>To continue the process, please select your account type below.</p><br>
                      <div class="row d-flex justify-content-center">
                        <div class="col-sm-6">
                            <div class="card card-selection">
                                <img src="assets/img/SVG/artist-acc.svg" class="user-logo">
                                <p class="caption text-muted">Showcase the Filipino talent across the world. Get hired and earn money through commissions</p>
                                <input class="cstm-label" id="tab1" type="radio" name="tabs" value="artist" required >
                                <label for="tab1" style="padding:5px 15px; "><i class="bi bi-credit-card"></i>Artist</label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card card-selection">
                                <img src="assets/img/SVG/client-acc.svg" class="user-logo">
                                <p class="caption text-muted"> Explore our pool of talented Filipino Freelancers and hire them according to your needs.</p>
                                <input class="cstm-label" id="tab2" type="radio" name="tabs" value="client">
                                <label for="tab2" style="padding:5px 15px; "><i class="bi bi-person-fill"></i> Client</label>
                            </div>
                        </div>
                      </div>
                    
                  </div>
                  
                </div>
                <div class="card-footer">
                    <button type="submit" name="submit" class="btn" style="background-color: #203C3B; color:white; float:right;">
                        NEXT</button>
                </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="d-block d-sm-none p-0">
        <div class="container p-0">
          <div class="row d-flex justify-content-center align-items-center">
            <div>
              <div class="card mb-0" style="background-color:rgba(255, 255, 255, 0.634);" >
                <div class="card-body text-center">
                  <div>
                    <form action="page-signup-account-type.php" method="post">
                      <h3 class="m-0 py-2"><b>Select User Type</b></h3>
                      <h6><small>Thank you</b><span class="span-dot"> <?php echo $_SESSION['fname_s'], ' ', $_SESSION['lname_s']; ?></span> for signing-up here at <b>Communidad!</b></small> </h6>
                      <p class="px-4"><small>Please select your account type below.</small></p>
                      <div class="row px-3">
                        <div class="col-sm-6">
                            <div class="card" style="border:none;">
                                <img src="assets/img/SVG/artist-acc.svg" class="user-logo">
                                <div class="p-1">
                                  <button name="tabs" class="btn-mob" type="submit" value="artist"><b>Artist</b></button>
                                  <p class="caption text-muted">Showcase the Filipino talent across the world. Get hired and earn money through commissions</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card">
                                <img src="assets/img/SVG/client-acc.svg" class="user-logo">
                                <div class="p-1">
                                  <button name="tabs" class="btn-mob" type="submit" value="client"><b>Client</b></button>
                                  <p class="caption text-muted"> Explore our pool of talented Filipino Freelancers and hire them according to your needs.</p>
                                </div>
                            </div>
                        </div>
                      </div>
                    
                  </div>
                  
                </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </section>


      <!-- Verify Section -->

  </main><!-- End #main -->

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  <!-- bs-custom-file-input -->
  <script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
  <!-- AdminLTE App -->
  <script>
      $(function () {
        bsCustomFileInput.init();
      });
  </script>
</body>

</html>