<?php
  include("connection.php");
  session_start();

  if(!$_SESSION["username_s"]) {
    header("Location: index.php");
    exit;
  }

  $username = $_SESSION['username_s'];
  $userID = $_SESSION["user_id_s"];

  $sql = "SELECT * FROM user_profile_t WHERE username = '$username'";
  $query = mysqli_query($conn, $sql);
  $result = mysqli_fetch_array($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Profile</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />

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

</head>
<body style="background-color: #f9f9f9;">
  <!-- ======= Top Bar ======= -->

  <!-- ======= Header ======= -->

  <section style="padding: 0;">
    <div class="rt-container">
      <div class="col-rt-12">
        <div class="Scriptcontent">
          <nav class="navbar navbar-expand-sm bg-faded navbar-light fixed-top px-4 py-2" id="first-nav-2">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar1">
              <span class="navbar-toggler-icon"></span>
            </button>
            <a href="index.php" class="logo-name" style="color:white;"><h5 class="mb-0"><b>comunidad<span style="color:orange;">. </span></b></h5></a>
    
            <div class="navbar-collapse collapse" id="navbar1">
              <ul class="navbar-nav ms-auto d-flex align-items-center justify-content-center">
                <li class="px-2">
                  <a>
                    <form action='page-search.php' method="get">
                      <div class='input-group py-2' style='margin-bottom: 0'>
                        <input name='search' class='form-control' placeholder='Try Logo Design' aria-label='Search' required>
                        <button type="submit" class='btn btn-searchbar' type='submit'>Search</button>
                      </div> 
                    </form>
                  </a>
                </li>
                <li class="nav-item">
                  <a style="color:white;" href="page-browse.php" class="nav-item nav-link active">Browse</a>
                </li>
                <li class='nav-item dropdown'>
                  <a class='nav-link dropdown-toggle' id='navbarDropdownMenuLink' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                  <?php
                    if($result["profile_pic"] != NULL){
                      echo "<img src='./assets/img/profile/".$result["profile_pic"]."' width='30' height='30' class='rounded-circle' style='object-fit:cover;'>";
                      }
                    else{
                      echo "<img src='assets/img/SVG/profile-icon.svg' width='30' height='30' class='rounded-circle' style='object-fit:cover;'>";
                      }
                  ?>
                  </a>
                  <div class='dropdown-menu dropdown-menu-style' aria-labelledby='navbarDropdownMenuLink'>
                    <a class='dropdown-item' href='page-profile-client.php'><?php echo $_SESSION["fname_s"]. " ". $_SESSION["lname_s"] ?></a>
                    <a class='dropdown-item' href='page-profile-client-edit.php'>Edit Profile</a>
                    <a class='dropdown-item' href='logout.php'>Log Out</a>
                  </div>
                </li>
                <?php 
                    if($_SESSION["verified_s"] != 1){
                        echo "<a href='page-verify.php' class='nav-item nav-link'><button class='verify-btn' style='vertical-align:middle'><span>Verify</span></button></a>
                        ";
                      }
                    ?>
              </ul>
            </div>
          </nav>
        </div>
        <?php  // CHECK HERE DI PA FINAL THIS
          if($result["profile_pic"] != NULL){
            $pfp = $result["profile_pic"];
            echo "<div class='img' style='
                  background-image: linear-gradient(to bottom, rgba(245, 246, 252, 0.255), rgba(6, 6, 6, 0.73)),url(./assets/img/profile/". $pfp .");
                  height: 255px;background-size: contain;
                    top: 10px;
                    padding: 10px; 
                    z-index: 1;'></div>
                </div>";
          }
          else{
            
            echo "<div class='img ' style='  background-image: linear-gradient(to bottom right,#113b3a , #447271, #447271 , rgb(13, 55, 54));
                  height: 255px;background-size: cover;
                    background-position: center;
                    top: 10px;
                    padding: 10px; 
                    z-index: 1;'></div>
                </div>";
          }

          
          ?>
      </div>
    </div>
  </section>  
  <!-- ======= Profile Section ======= -->
  <div class="container-fluid pb-5">
    <div class="row pb-5">
      <div class="col-md-4" style="padding: 0px 20px 20px 20px;">
        <!-- Profile Image -->
        <div class="card card-outline" style="margin-top:-50px;">
          <div class="card-body box-profile" style="padding:0px;">
            <div class="text-center">
              <img class="profile-pic" style="margin-top: -95px;" src="
                  <?php 
                    if($result["profile_pic"] != NULL){
                      $pfp = $result["profile_pic"];
                      echo "./assets/img/profile/". $pfp;
                    }
                    else{
                      echo "assets/img/SVG/profile-icon.svg";
                    }
                    
                    
                  ?>"
                   alt="User profile picture">
              <br>
              <a href="page-profile-client-edit.php" class="btn-custom-prof"><b>Edit Profile</b></a>
              <br>
              <br>
            </div>
            <h1 class="profile-name-format"><?php echo $_SESSION["fname_s"]. " ". $_SESSION["lname_s"] ?></h1>

            <p class="card-text-custom-1">Client</p>
            <p class="card-text-custom-1"><img src="assets/img/SVG/location.svg" class="icon-size">
              <b><?php echo $result["location"] ?></b></p>
            <h5 class="card-text-custom-1 margb-5"> <b>Joined <?php echo date("F Y", strtotime($result["date_joined"])) ?></b></h5>
            <?php
              $sql2 = "SELECT COUNT(*) AS counts FROM commission_t WHERE (status = 'Complete') AND (client_id = '$userID') UNION SELECT AVG(A.artist_rating) FROM feedback_t A INNER JOIN commission_t B ON A.req_id = B.req_id WHERE B.client_id = '$userID'";
              $query2 = mysqli_query($conn, $sql2);
              $result2 = mysqli_fetch_assoc($query2);

              
            ?>
            <h1 class="profile-name-format bolder"><?php echo (int) $result2["counts"] ?></h1>
            <p class="card-text card-text-custom">Hired Artists</p>
            <div class="text-center">
              <?php 
                mysqli_data_seek($query2, 1);
                $result2 = mysqli_fetch_assoc($query2);

                $stars = 5;
                for($i = 0; $i < (int) $result2["counts"]; $i++){
                  echo "<span class='fa fa-star w3-xxlarge star-fill'></span>";
                  $stars--;
                }
                for($i = 0; $i < $stars; $i++){
                  echo "<span class='fa fa-star w3-xxlarge star'></span>";
                }
              ?>
            </div>
            <p class="card-text-custom">Rating</p>
          </div>
          <div class="card-body card-body-custom user-about">
            <h5 class="card-text-custom-2"> <b>ABOUT</b></h5>
            <p class="card-text-custom-2"><?php echo $result["about"] ?></p>
            <h5 class="card-text-custom-2"> <b>CONTACT</b></h5>
            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item">
                <b>Email Address:</b> <a class="float-right"><?php echo $result["email"] ?></a>
              </li>
              <li class="list-group-item">
                <b>Contact Number:</b> <a class="float-right"><?php echo $result["contact"] ?></a>
              </li>
            </ul>
            <br>
          </div>
        </div>
      </div>

      <div class="col-md-8" style="padding: 0px 20px 20px 20px;">
        
        <div class="card" style="border-width: 0px; background-color: rgba(255, 255, 255, 0);">
          <div class="card-header card-header-bg-custom p-2" style="border-width: 0px; padding: 15px;"> 
            <ul class="nav nav-pills nav-pills-nbg">
              <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Activity</a></li>
              <li class="nav-item"><a class="nav-link" href="page-dashboard-client.php">Dashboard</a></li>
            </ul>
          </div><!-- /.card-header -->
          <div class="card-body" style="border-width: 0px; padding: 5px 0px 0px 0px;">
            <div class="tab-content">
              <div class="active tab-pane" id="activity">
                <section class="pt-1 pb-1 " style="padding: 0px;">
                    <div class="container">
                        <div class="row">
                            <div class="">
                            <h3 class="mb-3" style="margin: 10px 0px 15px 5px;">Artists Hired</h3>
                            </div>
                            <div class="card" style="border-radius: 7px; border-width: 0ch; background-color: #ffffff;" >
                  <div class="card-body">
                    <?php             
                            $sql = "SELECT * FROM commission_t A INNER JOIN feedback_t B ON A.req_id = B.req_id INNER JOIN artist_profile_t C ON A.artist_id = C.artist_id INNER JOIN user_profile_t D ON C.user_id = D.user_id  WHERE A.client_id = '$userID'";
                            $query = mysqli_query($conn, $sql);

                            while($result = mysqli_fetch_array($query)){
                              $stars = 5;

                              echo "<div class='row px-3'>
                                      <div class='col-1 d-flex justify-content-center px-0 mr-5'>";
                                      if($result["profile_pic"] != NULL && $result["status"] == "Active"){
                                        echo "<img src='./assets/img/profile/".$result["profile_pic"]."' class='profile-pic-rate'>";
                                      }
                                      else{
                                        echo "<img src='./assets/img/SVG/profile-icon.svg' class='profile-pic-rate'>";
                                      }
                                        echo "
                                      </div>
                                      <div class='col-11 ml-5'>
                                        <div class='row'>
                                          <div class='col-md-12 d-flex align-items-center justify-content-start'>";
                                            for($i = 0; $i < $result["artist_rating"]; $i++){
                                              echo "<span class='fa fa-star w3-xlarge star-fill'></span>";
                                              $stars--;
                                            }
                                            for($i = 0; $i < $stars; $i++){
                                              echo "<span class='fa fa-star w3-xlarge star'></span>";
                                            }
                                            echo "
                                            </div>
                                          </div>
                                          <div class='row'>
                                            <p class='text-muted'><small>".date('M d, Y H:i', strtotime($result["completion_date"]))."</small></p>
                                          </div>
                                          <div class='row'><p>".$result["artist_comment"]."</p></div>
                                        </div>
                                      </div>
                                    </div>";
                              
                            }
                          

                            
                            ?>
                  </div>
            </div>
                        </div>
                    </div>
                </section>
            </div>
            <!-- /.tab-content -->
          </div><!-- /.card-body -->
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
    </div>
  </div>
  

  <!-- ======= Footer ======= -->
  <footer class="footer-custom fixed-bottom d-none d-sm-block" style="height:35px; padding:0px 10px;">
    <div class="container py-2 ">
        <div class="row ">
            <div class="col-4">
                <strong><span>Comunidad</span></strong>
            </div>
            <div class="col-2 footer-link-float">
                <p>Help and Support</p>
            </div>
            <div class="col-2 footer-link-float">
                <p>Terms of Service</p>
            </div>
            <div class="col-2 footer-link-float">
              <a href="privacy-policy.html">>Privacy Policy</a>
            </div>
            <div class="col-2 footer-link-float">
                <p>Community Standards</p>
            </div>
        </div>
    </div>
  </footer>

  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>   

  </script>
  <script>
    window.onscroll = function() {scrollFunction()};
  
    function scrollFunction() {
      if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        
        document.getElementById("first-nav-2").style.background = "#203c3b";
      } else {
      
        document.getElementById("first-nav-2").style.background = "none";
      }
    }
  </script>
</body>
</html>