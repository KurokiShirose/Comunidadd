<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Login</title>
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

  <link rel="stylesheet" href="dist/css/adminlte.min.css">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>

</head>

<?php
    include("connection.php");
    session_start();

    // restarts session variables
    unset($_SESSION["username_s"]);
    unset($_SESSION["fname_s"]);
    unset($_SESSION["lname_s"]);

    function validate($data, $conn){
        $data = trim($data);
        $data = stripcslashes($data);
        $data = mysqli_real_escape_string($conn, $data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if(isset($_POST["username"]) && isset($_POST["password"])){

        if(empty($_POST["username"]) || empty($_POST["password"])){
            echo "<div class='alert alert-danger' role='alert'>Both Username and Password are required.</div>";
        }
        else{
            $username_v = validate($_POST["username"], $conn);
            $password_v = validate($_POST["password"], $conn);

            $sql = "SELECT * FROM account_t WHERE username = '$username_v' AND type != 'removed'";  
            $result = mysqli_query($conn, $sql);
            $row_data = mysqli_fetch_assoc($result);

            // for forgot password
            $sql2 = "SELECT * FROM tmp_password_t WHERE username = '$username_v'";  
            $result2 = mysqli_query($conn, $sql2);
            $row_data2 = mysqli_fetch_assoc($result2);

            // if username is found
            if(isset($row_data["password"])){
                // if input password and hashed password match
                if(password_verify($password_v, $row_data["password"]) || password_verify($password_v, $row_data2["temp_password"])){
                    if($row_data["type"] == "admin"){
                        $_SESSION["username_s"] = $row_data["username"];
                        
                        header("Location: page-dashboard-admin.php");  // if user is admin
                        exit;
                    }

                    $uname = $row_data['username'];

                    $sql = "SELECT * FROM user_profile_t where username = '$uname'";  
                    $result = mysqli_query($conn, $sql);
                    $row_data = mysqli_fetch_assoc($result);

                    // sets session variables
                    $_SESSION["fname_s"] = $row_data["first_name"];
                    $_SESSION["lname_s"] = $row_data["last_name"];
                    $_SESSION["username_s"] = $row_data["username"];
                    $_SESSION["user_type_s"] = $row_data["user_type"];
                    $_SESSION["verified_s"] = $row_data["verified"];
                    $_SESSION["user_id_s"] = $row_data["user_id"];

                    $sql = "INSERT INTO activity_t(user_id, activity) VALUES (".$_SESSION['user_id_s'].", 'has logged in')";  
                    mysqli_query($conn, $sql);

                    if($_SESSION["verified_s"] != 1){
                        $_SESSION["visits_s"] = 0;
                    }

                    if($row_data["user_type"] == "artist"){
                        $userID = $row_data["user_id"];

                        $sql = "SELECT artist_id, expertise FROM artist_profile_t WHERE user_id = '$userID'";  
                        $result = mysqli_query($conn, $sql);
                        $row_data = mysqli_fetch_assoc($result);

                        $_SESSION["artist_id_s"] = $row_data["artist_id"];

                        if($row_data["expertise"] == NULL){
                            header("Location: page-signup-artist.php"); 
                            exit;
                        }
                    }

                    header("Location: page-browse.php"); 
                    exit;
                }
                else{  // toggles login error modal
                    echo "<script type='text/javascript'>
                    $(document).ready(function(){
                        $('#loginErrorModal').modal('toggle');
                    });
                    </script>";
                }
            }
            else{  // toggles login error modal
                 echo "<script type='text/javascript'>
                $(document).ready(function(){
                    $('#loginErrorModal').modal('toggle');
                });
                </script>";
            }
        }
    }
?>
<body class="overflow-hidden login-bg">
    <div class="modal fade" id="loginErrorModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Login Failed</h4>
                        <button type="button" class="close" data-dismiss="modal" onclick="$('#loginErrorModal').modal('toggle');">&times;</button>
                    </div>
                    <div class="modal-body">
                        Account does not exist. Invalid username or password.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="$('#loginErrorModal').modal('toggle');">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container overflow-hidden p-0 vh-100 d-flex align-content-center flex-wrap">
            <div class="d-flex align-content-center flex-wrap vw-100 vh-100" style="width:100%; height:100%;">
                <section class="col-lg-7 d-flex align-items-center p-0">
                    <div class="d-none d-md-block text-white">
                        <h4 class="m-0">Welcome to</h4>
                        <h1><b>comunidad.</b></h1>
                        <p>Know and Hire Filipino Freelance Artists in <br><b>Photography, Video & Animation, and Graphics & Design.</b></p>
                    </div>
                </section>
                <section class="col-lg-5 px-3 py-5 bg-white" style="border-radius:5px;" >
                    <div class="card m-0 py-5" style="box-shadow:none;">
                        <form class="p-4" action="" method="post">
                            <div class="text-center mb-3">
                                <a href="index.php">
                                    <img src="assets/img/1x/logo-only-black.png" width='50px'>
                                </a>
                            </div>
                            <div class="mb-4 text-center text-black">
                                <h1><b>Log-in</b></h1>
                            </div>
                            <div class="mb-1">
                                <label for="username" class="lbl">Username</label>
                                <input type="text" name="username" class="form-control" placeholder="Enter username here" maxlength="25" required>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="lbl">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="******" required>
                            </div>


                            <div class="mb-4">
                                <button type="submit" class="lbtn">Sign in</button>
                            </div>
                            <div class="mb-4 reset">
                                <a href="page-forgot-password.php" class="reset">Forgot Password?</a>
                            </div>
                            <div class="mb-4 newacc">
                                Don't have an account? <a href="page-signup.php" class="newacc"><b>Click here to sign up.</b></a>
                            </div>

                        </form>
                    </div>
                </section>
            </div>
        </div>

  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.min.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>`
  <script src="assets/vendor/php-email-form/validate.js"></script>
  
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>

</body>

</html>