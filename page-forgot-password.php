<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password</title>

  <!-- Favicons -->
  <link href="assets/img/SVG/logo-black.svg" rel="icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <!-- Icons -->
  <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

   <!-- Style CSS -->
   <link rel="stylesheet" href="css/style-profile.css">

   <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>

</head>

<?php
  include("connection.php");
  session_start();

  if(isset($_POST["email"])){
    $email = $_POST["email"];

    $sql = "SELECT email FROM user_profile_t WHERE email = '$email'";
    $query = mysqli_query($conn, $sql);
    
    if(mysqli_num_rows($query) > 0){
      $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $new_pass = substr(str_shuffle($permitted_chars), 0, 7);
      $new_pass_hashed = password_hash($new_pass, PASSWORD_BCRYPT);

      $sql = "SELECT * FROM user_profile_t WHERE email = '$email'";
      $query = mysqli_query($conn, $sql);
      $result = mysqli_fetch_array($query);
      $first_name = $result["first_name"];
      $username = $result["username"];

      $sql = "SELECT * FROM tmp_password_t WHERE username = '$username'";
      $query = mysqli_query($conn, $sql);

      if(mysqli_num_rows($query) > 0){
        $sql = "UPDATE tmp_password_t SET temp_password = '$new_pass_hashed' WHERE username = '$username'";
        mysqli_query($conn, $sql);
      }
      else{
        $sql = "INSERT INTO tmp_password_t(username, email, temp_password) VALUES ('$username', '$email', '$new_pass_hashed')";
        mysqli_query($conn, $sql);
      }

      $sql = "UPDATE account_t SET type = '*user' WHERE username = '$username'";
      mysqli_query($conn, $sql);

      header("Location: email-new-password.php?email=$email&pass=$new_pass&name=$first_name");
      exit;
    }
    else{
      echo "<script type='text/javascript'>
                $(document).ready(function(){
                    $('#notFound').modal('toggle');
                });
                </script>";
    }
  }

?>
<body class="login-bg">

  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="row ">
      <div class="col-lg-12">
          <div class="card p-3">
            <div class="card-body">
                <form action="page-forgot-password.php" method="post">
                  <a class="text-center"><h1><i class="fa fa-lock" style="font-size:70px; aria-hidden="true"></i></h1></a>
                  <h1 class="text-center"><b>Trouble Logging in?</b></h1>
                  <p class="p-2">Please enter your email address and <br> we'll send you a <b><em>new password.</em></b></p>
                  <div class="form-group py-4">
                    <label for="exampleInputEmail1"><b>Email Address</b></label>
                    <input type="email" class="form-control" name="email" required>
                    <small id="emailHelp" class="form-text text-muted">Use the email you entered during signup.</small>
                  </div>
                  <button type="submit" class="lbtn">Submit</button>
                </form>
            </div>
          </div>
      </div>
    </div>
  </div>

  <div id='notFound' class='modal fade' tabindex='-1'>
    <div class='modal-dialog'>
      <div class='modal-content'>
        <div class='modal-header'>
          <h5 class='modal-title'><b>User Not Found</b></h5>
          <button type='button' class='btn-close' data-dismiss='modal' aria-label='Close'></button>
        </div>
        <div class='modal-body'>
          <p>User with entered email not found.</p>
        </div>
        <div class='modal-footer'>
          <button type='button' class='btn' style='background-color: maroon; color:white;' data-dismiss='modal'>Close</button>
        </div>
      </div>
    </div>
  </div>



  <!-- Bootstrap 4 -->
      <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
      <!-- AdminLTE App -->
      <script src="dist/js/adminlte.min.js"></script>   
</body>
</html>