<?php
  include("connection.php");
  include("validate.php");
  session_start();

  if(!$_SESSION["username_s"]) {
    header("Location: page-login.php");
    exit;
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  
  <title>Search Artists</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/SVG/logo-black.svg" rel="icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>

</head>
<body style="background-color: #fefefe;">

    <nav class="navbar navbar-expand-sm bg-faded navbar-light sticky-top first-nav px-4 py-1">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar1">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a href="index.php" class="logo-name"><h5 class="mb-0"><b>comunidad<span style="color:orange;">. </span></b></h5></a>
        <?php 
          if(isset($_SESSION["user_type_s"])){
            if($_SESSION["user_type_s"] == "client"){
              $profileView = "page-profile-client.php";
              $profileEdit = "page-profile-client-edit.php";
            }
            elseif($_SESSION["user_type_s"] == "artist"){
              $profileView = "page-profile-artist.php";
              $profileEdit = "page-profile-artist-edit.php";
            }
    
            if($_SESSION["user_type_s"] == "artist" || $_SESSION["user_type_s"] == "client"){ 
              echo "<div class='navbar-collapse collapse' id='navbar1'>
                  <ul class='navbar-nav ms-auto d-flex align-items-center'>
                  <li class='nav-item active px-2'>
                  <a href='page-browse.php' class='nav-item nav-link active'><b>Browse</b></a>
                  </li>";

                    echo"
                      <li class='nav-item dropdown px-2'>
                      <a class='nav-link dropdown-toggle' id='navbarDropdownMenuLink' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>";
                      
                      $username = $_SESSION["username_s"];
                      $sql = "SELECT profile_pic FROM user_profile_t WHERE username = '$username'";
                      $query = mysqli_query($conn, $sql);
                      $result = mysqli_fetch_array($query);
    
                      if($result[0] != NULL){
                        echo "<img src='./assets/img/profile/".$result[0]."' width='30' height='30' class='rounded-circle' style='object-fit:cover;'>";
                      }
                      else{
                        echo "<img src='assets/img/SVG/profile-icon.svg' width='30' height='30' class='rounded-circle' style='object-fit:cover;'>";
                      }
                      echo"
                      </a>
                      <div class='dropdown-menu dropdown-menu-style' aria-labelledby='navbarDropdownMenuLink'>
                      <a class='dropdown-item' href='".$profileView."'>".$_SESSION["fname_s"]." ".$_SESSION["lname_s"]."</a>
                      <a class='dropdown-item' href='".$profileEdit."'>Edit Profile</a>
                      <a class='dropdown-item' href='logout.php'>Log Out</a>
                      </div>
                      </li>";
                      if($_SESSION["verified_s"] != 1){
                      echo"
                      <a href='page-verify.php' class='nav-item nav-link'><button class='verify-btn' style='vertical-align:middle'><span>Verify</span></button></a>";
                      }
                      echo"
                      </ul>
                      </div>
                      </nav>";}
                    }
                      ?>

        <div class="container-fluid pt-3 pb-2 px-4 bg-white">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="col-md-8">
                    <form action='page-search.php' method='get'>
                        <div class='input-group ' style='margin-bottom: 0'>
                            <input name='search' class='form-control' placeholder='Search & Explore Filipino Talents' aria-label='Search' required>
                            <button class='btn btn-searchbar' type='submit'>Search</button>
                        </div> 
                    </form>
                </div>
                <div class="col-md-4 d-none d-sm-block" style="padding:4px 0px;">
                    <div class="btn-group" role="group" aria-label="Categories">
                    <a type="button" href="page-search.php?search=Photography" class="btn btn-category">Photography</a>
                    <a type="button" href="page-search.php?search=Graphics & Design" class="btn btn-category">Graphics & Design</a>
                    <a type="button" href="page-search.php?search=Video & Animation" class="btn btn-category">Video & Animation</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="px-4 pb-5 bg-light">
        <div class="row">
            <section class="col-lg-7">
              <div class='row px-2'>
                <?php
                    $flag = (empty($_GET["min"]) && empty($_GET["max"])) ? 1 : 0;

                    if($flag == 0){
                      $min = !empty($_GET["min"]) ? $_GET["min"] : 0;
                      $max = !empty($_GET["max"]) ? $_GET["max"] : 0;

                      if($min == 20000){
                        $sql = "SELECT * FROM user_profile_t A INNER JOIN artist_profile_t B ON A.user_id = B.user_id WHERE (A.user_type = 'artist') AND (A.status = 'Active') AND B.min_charge <= '$min' ORDER BY A.date_joined";
                      }
                      elseif($max == 1000){
                        $sql = "SELECT * FROM user_profile_t A INNER JOIN artist_profile_t B ON A.user_id = B.user_id WHERE (A.user_type = 'artist') AND (A.status = 'Active') AND B.max_charge <= '$max' ORDER BY A.date_joined";
                      }
                      else{
                        $sql = "SELECT * FROM user_profile_t A INNER JOIN artist_profile_t B ON A.user_id = B.user_id WHERE (A.user_type = 'artist') AND (A.status = 'Active') AND B.min_charge >= '$min' AND B.max_charge <= '$max' ORDER BY A.date_joined";
                      }

                      $query = mysqli_query($conn, $sql);
                      $count = mysqli_num_rows($query);

                      echo "<div class=''>
                              <div class='card px-0 ' style='height:50vh;'>
                                  <div class='card-header bg-white'>
                                      <a><i class=''></i><b><i class='bi bi-cash'></i> Budget</b></a>
                                  </div>
                                  <div class='card-body px-0 overflow-auto'>";
                      

                      if($count > 0){
                        while($result = mysqli_fetch_array($query)){
                          echo "<div class='d-flex align-content-start flex-wrap px-2' style='border-bottom:1px solid rgb(221, 221, 221);'>
                                  <div class='p-2 d-flex justify-content-center align-items-center'>";

                          if($result["profile_pic"] != NULL && $result["status"] == "Active"){
                            echo "<img src='./assets/img/profile/".$result["profile_pic"]."' alt='' class='profile-pic-rate'>";
                          }
                          else{
                            echo "<img src='./assets/img/SVG/profile-icon.svg' alt='' class='profile-pic-rate'>";
                          }
                          echo "</div>
                                    <div class='p-2' style='width:62%;'>
                                        <p class='dashboard-item' style='font-size: small;'><span class='text-muted'></span><b>".$result["first_name"]." ".$result["last_name"]."</b></p>
                                        <p class='dashboard-item' style='font-size: small;'><span class='text-muted'></span>".$result["expertise"]."</p>
                                        <p class='dashboard-item' style='font-size: small;'><span class='text-muted'></span>Rate: <b>P".$result["min_charge"]." - P".$result["max_charge"]."</b></p>
                                    </div>
                                    <div class='m-auto p-2 float-right'>
                                        <a href='page-client-request.php?artistID=".$result["artist_id"]."' class='btn btn-success'>Hire</a>
                                        <a href='page-view-artist.php?artistID=".$result["artist_id"]."' class='btn btn-secondary'>Visit</a>
                                    </div>
                                </div>";
                        }
                      }
                      else{
                        echo "<div class='text-center'>Oops! Nothing to see here.</div>";
                      }
                    }
                    else{
                      $location = $_GET["location"];

                      echo "<div class='pt-4'>
                              <div class='card px-0' style='height:50vh;'>
                                  <div class='card-header bg-white'>
                                      <a><i class=''></i><b><i class='bi bi-geo-alt-fill'></i> Location</b></a>
                                  </div>
                                  <div class='card-body px-0 overflow-auto'>";

                      $sql = "SELECT * FROM user_profile_t A INNER JOIN artist_profile_t B ON A.user_id = B.user_id WHERE (A.user_type = 'artist') AND (A.status = 'Active') AND (location LIKE '%".$location."%') ORDER BY A.date_joined";
                      $query = mysqli_query($conn, $sql);
                      $count = mysqli_num_rows($query);

                      if($count > 0){
                        while($result = mysqli_fetch_array($query)){
                          echo "<div class='d-flex align-content-start flex-wrap px-2' style='border-bottom:1px solid rgb(221, 221, 221);'>
                                    <div class='p-2 d-flex justify-content-center align-items-center'>";

                          if($result["profile_pic"] != NULL && $result["status"] == "Active"){
                            echo "<img src='./assets/img/profile/".$result["profile_pic"]."' alt='' class='profile-pic-rate'>";
                          }
                          else{
                            echo "<img src='./assets/img/SVG/profile-icon.svg' alt='' class='profile-pic-rate'>";
                          }
                          echo "</div>
                                    <div class='p-2' style='width:62%;'>
                                        <p class='dashboard-item' style='font-size: small;'><span class='text-muted'></span><b>".$result["first_name"]." ".$result["last_name"]."</b></p>
                                        <p class='dashboard-item' style='font-size: small;'><span class='text-muted'></span>".$result["expertise"]."</p>
                                        <p class='dashboard-item' style='font-size: small;'><span class='text-muted'></span>".$result["location"]."</p>
                                    </div>
                                    <div class='m-auto p-2 float-right'>
                                        <a href='page-client-request.php?artistID=".$result["artist_id"]."' class='btn btn-success'>Hire</a>
                                        <a href='page-view-artist.php?artistID=".$result["artist_id"]."' class='btn btn-secondary'>Visit</a>
                                    </div>
                                </div>";
                        }
                      }
                      else{
                        echo "<div class='text-center'>Oops! Nothing to see here.</div>";
                      }
                    }
                ?>
              </div>
            </section>
            <section class="col-lg-5">
                <div class="card" style="height: 103vh;">
                    <div class="card-header bg-white">
                        <a><i class=""></i><b><i class="bi bi-brush-fill"></i> Comunidad Artists</b></a>
                    </div>
                    <div class="card-body overflow-auto px-0">
                        <div>
                            <h6 class="mx-4"><b><em>Top New Artists</em></b></h6>
                        </div>
                        <div class="d-flex align-content-start flex-wrap p-2 pb-4 align-items-center justify-content-center" style="border-bottom:1px solid rgb(221, 221, 221);">
                            <?php
                                $sql = "SELECT * FROM profile_score_t A INNER JOIN artist_profile_t B ON A.artist_id = B.artist_id INNER JOIN user_profile_t C ON B.user_id = C.user_id WHERE A.cluster = 0";
                                $query = mysqli_query($conn, $sql);
                                $nums = mysqli_num_rows($query);

                                if($nums > 0){
                                    while($result = mysqli_fetch_array($query)){
                                        echo "
                                            <div class=' d-flex justify-content-center align-items-center'>
                                            <a href='page-view-artist.php?artistID=".$result["artist_id"]."'>";
                                            if($result["profile_pic"] != NULL){
                                                echo "<img src='./assets/img/profile/".$result["profile_pic"]."' alt='' class='profile-pic-small'>";
                                            }
                                            else{
                                                echo "<img src='./assets/img/SVG/profile-icon.svg' alt='' class='profile-pic-small'>";
                                            }
                                                echo "
                                                </a>
                                            </div>
                                        ";
                                    }
                                }
                                else{
                                    echo "Oops! Nothing to see here.";
                                }
                            ?>
                            
                            
                        </div>
                        <?php 
                            $sql = "SELECT * FROM profile_score_t A INNER JOIN artist_profile_t B ON A.artist_id = B.artist_id INNER JOIN user_profile_t C ON B.user_id = C.user_id ORDER BY A.n, A.cluster";
                            $query = mysqli_query($conn, $sql);
                            $nums = mysqli_num_rows($query);

                            if($nums > 0){
                                while($result = mysqli_fetch_array($query)){
                                    $cluster = "";

                                    if($result["cluster"] == 0){
                                        $cluster = "Expert Artist";
                                    }
                                    elseif($result["cluster"] == 1){
                                        $cluster = "Top Artist";
                                    }
                                    else{
                                        $cluster = "Rising Artist";
                                    }


                                    echo "
                                        <div class='d-flex align-content-start flex-wrap px-2' style='border-bottom:1px solid rgb(221, 221, 221);'>
                                            <div class='p-2 d-flex justify-content-center align-items-center'>";

                                            if($result["profile_pic"] != NULL){
                                                echo "<img src='./assets/img/profile/".$result["profile_pic"]."' alt='' class='profile-pic-rate'>";
                                            }
                                            else{
                                                echo "<img src='./assets/img/SVG/profile-icon.svg' alt='' class='profile-pic-rate'>";
                                            }
                                                
                                            echo "
                                            </div>
                                            <div class='p-2' style='width:62%;'>
                                                <p class='dashboard-item' style='font-size: small;'><span class='text-muted'></span><b>".$result["first_name"]." ".$result["last_name"]."</b></p>
                                                <p class='dashboard-item' style='font-size: small;'><span class='text-muted'></span>".$result["expertise"]."</p>
                                                <p class='dashboard-item' style='font-size: small;'><span class='text-muted'></span><b><em>".$cluster."</em></b></p>
                                            </div>
                                            <div class='m-auto p-2 float-right'>
                                                <a href='page-client-request.php?artistID=".$result["artist_id"]."' class='btn btn-success'>Hire</a>
                                                <a href='page-view-artist.php?artistID=".$result["artist_id"]."' class='btn btn-secondary'>Visit</a>
                                            </div>
                                        </div>
                                    ";
                                }
                            }

                            
                        ?>
                    </div>
                </div>
            </section>
        </div>
    </div>
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
                <p>Privacy Policy</p>
            </div>
            <div class="col-2 footer-link-float">
                <p>Community Standards</p>
            </div>
        </div>
    </div>
  </footer>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script> 
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.8/slick.min.js'></script>
  <script src='dist/js/script.js'></script>
</body>