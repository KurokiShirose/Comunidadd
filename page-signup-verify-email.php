<?php
    include("connection.php");
    session_start();

    if(isset($_POST["digit-1"]) && isset($_POST["digit-2"]) && isset($_POST["digit-3"]) && isset($_POST["digit-4"]) && isset($_POST["digit-5"]) && isset($_POST["digit-6"])){
      
      $enteredOTP =  $_POST["digit-1"].$_POST["digit-2"].$_POST["digit-3"].$_POST["digit-4"].$_POST["digit-5"].$_POST["digit-6"];

      if($_SESSION["otp_s"] == $enteredOTP){

        $fname_v = $_SESSION["fname_s"];
        $lname_v = $_SESSION["lname_s"];
        $sex_v = $_SESSION["sex_s"];
        $birthdate_v = $_SESSION["birthdate_s"];
        $username_v = $_SESSION["username_s"];
        $password_v = password_hash($_SESSION["password_s"], PASSWORD_BCRYPT);
        $mobile_v = $_SESSION["mobile_s"];
        $location_v = $_SESSION["location_s"];
        $education_v = $_SESSION["education_s"];
        $email_v = $_SESSION["email_s"];

        $type = $_SESSION["type_s"];

        $sql = "INSERT INTO account_t(username, password) VALUES('$username_v', '$password_v')";  
        $query = mysqli_query($conn, $sql);

        $sql = "INSERT INTO user_profile_t(username, first_name, last_name, sex, dob, age, education, email, contact, location, user_type) VALUES('$username_v', '$fname_v', '$lname_v', '$sex_v', '$birthdate_v', DATE_FORMAT(FROM_DAYS(DATEDIFF(now(),'$birthdate_v')), '%Y')+0, '$education_v', '$email_v', '$mobile_v', '$location_v', '$type')";  
        $query = mysqli_query($conn, $sql);

        $sql = "SELECT user_id FROM user_profile_t WHERE username = '$username_v'";  
        $query = mysqli_query($conn, $sql);
        $result = mysqli_fetch_array($query);
        $userID = $result[0];

        if($type == "artist"){
          $sql = "INSERT INTO artist_profile_t(user_id) VALUE('$userID')";  
          $query = mysqli_query($conn, $sql);
        }

        $sql = "INSERT INTO activity_t(user_id, activity) VALUES (".$userID.", 'has created new account')";  
        mysqli_query($conn, $sql);

        session_unset();
        session_destroy();

        header("Location: page-login.php"); 
        exit;
    }
    else {
            echo "<div class='alert alert-danger' role='alert'>Incorrect OTP.</div>";
    }
  }

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Email Account Verification</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/SVG/logo-black.svg" rel="icon">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <link rel="stylesheet" href="dist/css/adminlte.min.css">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <style>
    input {
      width: 2em;
      height: 2em;
      background-color: lighten($BaseBG, 5%);
      line-height: 50px;
      text-align: center;
      font-size: 24px;
      font-weight: 500;
      margin: 0 2px;
      }
  </style>

</head>

<body class="login-bg">

        <div class="container d-flex justify-content-center align-items-center vh-100 overflow-hidden px-5">
          <div class="row bg-white py-5 px-3" style="border-radius:10px;">
            <div class="col-lg-12 py-5">
              <div class="card" style="box-shadow:none; border:none;">
                <div class="card-body p-0">
                  <form action="page-signup-verify-email.php" method="post" class="digit-group" data-group-name="digits" data-autosubmit="false" autocomplete="off">
                    <div class="text-center">
                      <i class="bi bi-envelope-check-fill" style="font-size:xxx-large;"></i>
                      <h2 class="fw-bold">Email Verification</h2>
                      <h5 class="mb-3">Welcome<span class="span-dot"> <?php echo $_SESSION['fname_s'], ' ', $_SESSION['lname_s']; ?>!</span></h5>
                      <p class="px-3"> To proceed with account creation, verify your email address.</p>
                      <p> We have already sent the verification code on</p>
                      <h6><span class="span-dot"><?php echo $_SESSION['email_s']; ?></span></h6>
                      <br>
                      <p>Please input the code below.</p>
                      <br>
                    </div>
                    <div class="d-flex align-items-center justify-content-center ">
                      <div class="code_group mb-4 "> 
                          <input type="text" id="digit-1" name="digit-1" data-next="digit-2" placeholder="#"/>
                          <input type="text" id="digit-2" name="digit-2" data-next="digit-3" data-previous="digit-1" placeholder="#"/>
                          <input type="text" id="digit-3" name="digit-3" data-next="digit-4" data-previous="digit-2" placeholder="#"/>
                          <input type="text" id="digit-4" name="digit-4" data-next="digit-5" data-previous="digit-3" placeholder="#"/>
                          <input type="text" id="digit-5" name="digit-5" data-next="digit-6" data-previous="digit-4" placeholder="#"/>
                          <input type="text" id="digit-6" name="digit-6" data-previous="digit-5" placeholder="#"/>
                      </div>
                    </div>
                    <div class="text-center">
                      <a href="email.php" class="text-muted"><p>Resend Code</p></a>
                    </div>
                    <div class="px-5">
                      <button type="submit" class="cbtn-mob">Submit</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>

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

    $('.digit-group').find('input').each(function() {
        $(this).attr('maxlength', 1);
        $(this).on('keyup', function(e) {
            var parent = $($(this).parent());
            
            if(e.keyCode === 8 || e.keyCode === 37) {
                var prev = parent.find('input#' + $(this).data('previous'));
                
                if(prev.length) {
                    $(prev).select();
                }
            }else if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
                var next = parent.find('input#' + $(this).data('next'));
                
                if(next.length) {
                    $(next).select();
                }else {
                    if(parent.data('autosubmit')) {
                        parent.submit();
                    }
                }
            }
        });
      });
  </script>
    
</body>

</html>