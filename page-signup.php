

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
      
        <title>Register or Sign up</title>
        <meta content="" name="description">
        <meta content="" name="keywords">
      
        <!-- Favicons -->
        <link href="assets/img/SVG/logo-black.svg" rel="icon">

        <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
      
        <!-- Vendor CSS Files -->
        <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

        <!-- jQuery -->
        <script src="plugins/jquery/jquery.min.js"></script>

        <link rel="stylesheet" href="dist/css/adminlte.min.css">
      
        <!-- Template Main CSS File -->
        <link href="assets/css/style.css" rel="stylesheet">
        
        <!-- BS Stepper -->
        <link rel="stylesheet" href="plugins/bs-stepper/css/bs-stepper.min.css">
        <script src="city.js"></script>

    </head>

<?php
    include("connection.php");
    session_start();

    function validate($data, $conn){
        $data = trim($data);
        $data = stripcslashes($data);
        $data = mysqli_real_escape_string($conn, $data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if(isset($_POST["submit"])){

        $message = "";
        if($_POST["password"] != $_POST["passwordRepeat"]){
            $message = "Passwords do not match. Try again.";
            echo "<script type='text/javascript'>
                $(document).ready(function(){
                    $('#passwordModal').modal('toggle');
                });
                </script>";
        }
        elseif(strlen($_POST["password"]) < 6){
            $message = "Passwords must be 6 characters or more. Try again.";
            echo "<script type='text/javascript'>
                $(document).ready(function(){
                    $('#passwordModal').modal('toggle');
                });
                </script>";
        }
        elseif(strlen($_POST["username"]) > 25){
            $message = "Username must not exceed 25 characters. Try again.";
            echo "<script type='text/javascript'>
                $(document).ready(function(){
                    $('#passwordModal').modal('toggle');
                });
                </script>";
        }
        else{
            $fname_v = validate($_POST["fname"], $conn);
            $lname_v = validate($_POST["lname"], $conn);
            $sex_v = validate($_POST["sex"], $conn);
            $birthdate_v = validate($_POST["birthdate"], $conn);
            $username_v = validate($_POST["username"], $conn);
            $password_v = validate($_POST["password"], $conn);
            $mobile_v = validate($_POST["mobile"], $conn);

            $city_v = validate($_POST["city"], $conn);
            $province_v = validate($_POST["province"], $conn);

            $location_v = $city_v . ', ' . $province_v;
            $education_v = validate($_POST["education"], $conn);
            $email_v = validate($_POST["email"], $conn);

            $sqlCheckBan = "SELECT * FROM user_profile_t A INNER JOIN removed_users_t B ON A.user_id = B.user_id WHERE (A.email = '$email_v' OR A.username = '$username_v') AND B.remarks = 'Banned'";  
            $resultBan = mysqli_query($conn, $sqlCheckBan);

            $sqlCheckEmail = "SELECT * FROM user_profile_t WHERE email = '$email_v'";  
            $resultEmail = mysqli_query($conn, $sqlCheckEmail);

            $sqlCheckUname = "SELECT username FROM account_t WHERE username = '$username_v'";  
            $resultUname = mysqli_query($conn, $sqlCheckUname);

            if(mysqli_num_rows($resultBan) > 0){
                $message = "Email address or username is suspended for violating community standards. Try again.";
            echo "<script type='text/javascript'>
                $(document).ready(function(){
                    $('#passwordModal').modal('toggle');
                });
                </script>";
            }
            elseif(mysqli_num_rows($resultEmail) > 0){
                $message = "Email address is already used. Try again.";
            echo "<script type='text/javascript'>
                $(document).ready(function(){
                    $('#passwordModal').modal('toggle');
                });
                </script>";
            }
            elseif(mysqli_num_rows($resultUname) > 0){
                 $message = "Username already exists. Try again.";
            echo "<script type='text/javascript'>
                $(document).ready(function(){
                    $('#passwordModal').modal('toggle');
                });
                </script>";
            }
            else{
                $_SESSION["fname_s"] = $fname_v;
                $_SESSION["lname_s"] = $lname_v;
                $_SESSION["username_s"] = $username_v;
                $_SESSION["password_s"] = $password_v;
                $_SESSION["sex_s"] = $sex_v;
                $_SESSION["birthdate_s"] = $birthdate_v;
                $_SESSION["mobile_s"] = $mobile_v;
                $_SESSION["location_s"] = $location_v;
                $_SESSION["education_s"] = $education_v;
                $_SESSION["email_s"] = $_POST['email'];

                header("Location: page-signup-account-type.php"); 
                exit;
            }
        }
    }
?>
    <body class="overflow-auto login-bg">
        <div class="modal fade" id="passwordModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Signup Failed</h4>
                        <button type="button" class="close" data-dismiss="modal" onclick="$('#passwordModal').modal('toggle');">&times;</button>
                    </div>
                    <div class="modal-body">
                        <?php echo $message ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="$('#passwordModal').modal('toggle');">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container overflow-auto p-0 vh-100 d-flex align-content-center flex-wrap">
            <div class="d-flex align-content-center flex-wrap vw-100 vh-100" style="width:100%; height:100%;">
                <section class="col-lg-7 d-flex align-items-center p-0">
                    <div class="d-none d-sm-block text-white">
                        <h1 class="m-0">Join Us Here in</h1>
                        <h1><b>comunidad.</b></h1>
                        <p class="text-lg">Be our <b>Artist</b> or <b>Client</b></p>
                    </div>
                </section>
                <section class="col-lg-5 px-5 py-0r bg-white" style="border-radius:5px;" >
                    <div class="card" style="box-shadow:none;">
                        <div class="bs-stepper">
                            <div class="bs-stepper-header" role="tablist" style="visibility:hidden;" >
                                <div class="step" data-target="#commission-part" >
                                    <button type="button" class="step-trigger p-0 btn-sm" role="tab" aria-controls="commission-part" id="commission-part-trigger">
                                    <span class="bs-stepper-circle">1</span>
                                    </button>
                                </div>
                                <div class="line"></div>
                                <div class="step" data-target="#payment-part">
                                    <button type="button" class="step-trigger p-0 btn-sm" role="tab" aria-controls="payment-part" id="payment-part-trigger">
                                    <span class="bs-stepper-circle" style="background-color:orange;">2</span>
                                    </button>
                                </div>
                            </div>
                            <div class="bs-stepper-content p-0">
                                <form action="page-signup.php" method="post">
                                    <div id="commission-part" class="content p-0" role="tabpanel" aria-labelledby="commission-part-trigger" style="margin:auto;">
                                        <div class="">
                                            <h6 class="text-secondary"><b>Step 1 of 2</b></h6>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <a style="font-size:xx-large;"><i class="bi bi-file-plus-fill"></i><b>Create an Account</b></a>
                                                <p class="text-muted m-0 pb-4">Already have an account?<a href="page-login.php"><span class="span-dot"><b> <em> Log-in</em></b></span></a></p>
                                                
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <label for="emailad" class="lbl">Email Address</label>
                                            <input type="email" id="email" name="email" onkeyup="stepOneCheck()" class="form-control" placeholder="" value="" required/>
                                        </div>
                                        <div class="col-lg-12">
                                            <label for="username" class="lbl">Username</label>
                                            <input type="text" id="username" name="username" onkeyup="stepOneCheck()" class="form-control" placeholder="" value="" maxlength="25" required/>
                                        </div>
                                        <div class="col-lg-12">
                                            <label for="password" class="lbl">Password</label>
                                            <input type="password" id="password" name="password" onkeyup="stepOneCheck()" class="form-control" placeholder=".........." value="" required/>
                                        </div>
                                        <div class="col-lg-12">
                                            <label for="repeatpassword" class="lbl">Repeat Password</label>
                                            <input type="password" id="passwordRepeat" name="passwordRepeat" onkeyup="stepOneCheck()" class="form-control" placeholder=".........." value="" required/>
                                        </div>
                                        <div class="col-lg-12">
                                            <small class="text-muted"><em>Username & password should be minimum of six (6) characters</em></small>
                                        </div>
                                        <div class="py-5">
                                            <a class="btn disabled" id="step_one" onclick="stepper.next()" style="background-color:#447271; float:right; color:white;">Proceed</a>
                                        </div>

                                        <script>
                                            function stepOneCheck(){
                                                var email = document.getElementById('email').value;
                                                var username = document.getElementById('username').value;
                                                var password = document.getElementById('password').value;
                                                var passwordRepeat = document.getElementById('passwordRepeat').value;
                                                var e = document.getElementById("step_one");

                                                if(document.getElementById('email').value != "" && email.includes("@") && (username.length<=25 && username.length>=6) && (password == passwordRepeat) && (password.length<=25 && password.length>=6)){
                                                    e.classList.remove("disabled");
                                                }
                                                else{
                                                    e.classList.add("disabled");
                                                }
                                            }
                                            
                                        </script>

                                    </div>
                                    
                                    <div id="payment-part" class="content" role="tabpanel" aria-labelledby="payment-part-trigger" style="margin:auto;">
                                        <div class="">
                                            <h6 class="text-secondary"><b>Step 2 of 2</b></h6>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <a style="font-size:x-large;"><i class="bi bi-file-plus-fill"></i><b>Create an Account</b></a>
                                                <p class="text-muted m-0 pb-4">Already have an account?<a href="page-login.php"><span class="span-dot"><b> <em> Log-in</em></b></span></a></p>
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="firstname" class="lbl">First Name</label>
                                                <input type="text" name="fname" class="form-control" placeholder="" value="" maxlength="100" required/>
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="firstname" class="lbl">Last Name</label>
                                                <input type="text" name="lname" class="form-control" placeholder="" value="" maxlength="100"  required/>
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="Contact" class="lbl">Mobile Number</label>
                                                <input type="text" name="mobile" class="form-control" placeholder="" value="" maxlength="20"  required/>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="sex" class="lbl">Sex</label>
                                                <select name="sex" class="form-control form-select" required>
                                                    <option class="hidden"  selected disabled>Select</option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-8">
                                                <label for="birthdate" class="lbl">Birthdate</label>
                                                <input type="date" name="birthdate" class="form-control" required>
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="location" class="lbl">Location</label>
                                                <select id="province" name="province" class="form-control form-select mb-2" required></select>
                                                <select id="city" name="city" class="form-control form-select" required></select>	
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="highest_ed" class="lbl">Highest Educational Attainment</label>
                                                <select name="education" class="form-control form-select">
                                                    <option class="hidden"  selected disabled>Select</option>
                                                    <option value="College Graduate">College Graduate</option>
                                                    <option value="Senior High School">Senior High School</option>
                                                    <option value="Junior High school">Junior High school</option>
                                                    <option value="Elementary">Elementary</option>
                                                    <option value="No Education">No Education</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-4 newacc">
                                                    <div class="form-check" style="text-align: left;">
                                                        <input name="agreement" class="form-check-input" type="checkbox" value="" id="flexCheckDefault" required>
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                        I agree to <a href=""><span class="span-dot"><b>Terms and Conditions</b></span></a>
                                                        and <a href=""><span class="span-dot"><b>Privacy Policy</b></span></a>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12"></div>
                                            <div class="col-lg-12"></div>

                                        </div>
                                        <button type="submit" name="submit" class="btn" style="background-color: #203C3B; color:white; float:right;">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
<script>	
    window.onload = function() {	
        var $ = new City();
        $.showProvinces("#province");
        $.showCities("#city");
        var $ = new Course();
        $.showColleges("#college");
        $.showCourses("#course");
        console.log($.getColleges());
        console.log($.getAllCourses());
        console.log($.getProvinces());
        console.log($.getAllCities());
        console.log($.getCities("Batangas"));	
    }
    
</script>

  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.min.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>

  <script src="plugins/bs-stepper/js/bs-stepper.min.js"></script>
   <script>
    
        // BS-Stepper Init
    document.addEventListener('DOMContentLoaded', function () {
        window.stepper = new Stepper(document.querySelector('.bs-stepper'))
    })
  </script>


  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>    
</body>
</html>