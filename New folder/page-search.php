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

  <title>Search Results</title>
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
<body style="background-color: #f4f4f4;">

<?php
    if($_SESSION["verified_s"] != 1){
      echo "<div id='myModal' class='modal fade' tabindex='-1'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <h5 class='modal-title'><b>Verify your Account!</b></h5>
              <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body'>
              <p>To fully navigate and access our community, please proceed to your account verification. Thank you!</p>
            </div>
            <div class='modal-footer'>
              <button type='button' class='btn' style='background-color:#203C3B; color:white;' data-bs-dismiss='modal'>Not Now</button>
            </div>
          </div>
        </div>
      </div>";
    }
/*<div id='myModal' class='modal fade' tabindex='-1'>
<div class='modal-dialog'>
  <div class='modal-content'>
    <div class='modal-header'>
      <h5 class='modal-title'>Message</h5>
      <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
    </div>
    <div class='modal-body'>
      <p>Your Account Verification is on process, please bear with us</p>
    </div>
    <div class='modal-footer'>
      <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Not Now</button>
    </div>
  </div>
</div>
</div>*/ 
    
  ?>

<header id="header" class="d-flex align-items-center">
    <div class="container d-flex align-items-center justify-content-between">

      <a href="index.php" class="text-logo"><h2><b>comunidad<span style="color:orange;">. </span></b></h2></a>

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
          echo "
              <nav id='navbar' class='navbar' style='color:black;'>
          <ul>";

            echo "<li><a class='browse-link' style='color:#363636;' href='page-browse.php'>Browse</a></li>";
            
            if($_SESSION["verified_s"] != 1){
              echo "<li>
                      <a href='page-verify.php' class='nbtn'>Verify</a>
                    </li>";
            }
            
            echo "
            <li class='nav-item dropdown'>
              <a class='nav-link dropdown-toggle' id='navbarDropdownMenuLink' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
              ";

            $username = $_SESSION["username_s"];
            $sql = "SELECT profile_pic FROM user_profile_t WHERE username = '$username'";
            $query = mysqli_query($conn, $sql);
            $result = mysqli_fetch_array($query);

            if($result[0] != NULL){
              echo "<img src='./assets/img/profile/".$result[0]."' width='40' height='40' class='rounded-circle' style='object-fit:cover;'>";
            }
            else{
              echo "<img src='assets/img/SVG/profile-icon.svg' width='40' height='40' class='rounded-circle' style='object-fit:cover;'>";
            }
              
              echo "</a>
                    <div class='dropdown-menu dropdown-menu-style' aria-labelledby='navbarDropdownMenuLink'>
                      <a class='dropdown-item' href='".$profileView."'>".$_SESSION["fname_s"]." ".$_SESSION["lname_s"]."</a>
                      <a class='dropdown-item' href='".$profileEdit."'>Edit Profile</a>
                      <a class='dropdown-item' href='logout.php'>Log Out</a>
                    </div>
                  </li>   
                </ul>
                <i class='bi bi-list mobile-nav-toggle'></i>
              </nav>
          ";}
        }
        else{  // if visitor only
          echo "
            <nav id='navbar' class='navbar'>
          <ul>
            <li><a class='browse-link' href='page-browse.php'>Browse</a></li>
            <li class='dropdown'><a href='#'><span>Services</span> <i class='bi bi-chevron-down'></i></a>
              <ul>
                <li><a href='#'>Graphics and Design</a></li>
                <li><a href='#'>Photography</a></li>
                <li><a href='#'>Video and Animation</a></li>
              </ul>
            </li>
            <li class='dropdown'><a href='#'><span>Explore</span> <i class='bi bi-chevron-down'></i></a>
              <ul>
                <li><a href='#'>Featured Artists</a></li>
                <li><a href='#'>The Community</a></li>
                <li><a href='#'>The Team</a></li>
                <li><a href='#'>FAQs</a></li>
              </ul>
            </li>
            <li><a href='page-login.php'>Log-in</a></li>
            <li>
              <form action='page-signup.php'>
                  <button type='submit' class='nbtn'>Join</button>
              </form>
            </li>
          </ul>
          <i class='bi bi-list mobile-nav-toggle'></i>
        </nav><!-- .navbar -->
          ";
        }
      ?>

    </div>
  </header><!-- End Header -->

  <header id="browse-header" class="p">
    <div class="">
      <div class="card" style="border-width:0px;">
        <div class="card-body" style="border-width:0px; padding: 16px 16px 3px 16px;">
          <div class="row d-flex align-items-center justify-content-center">
            <div class="col-md-2" style="padding:4px 0px;">
              <h2 class=" d-flex align-items-center justify-content-center"><b>Explore!</b></h2>
            </div>
            <div class="col-md-6" style="padding:4px 0px;">
              <form action='page-search.php' method='get'>
                <div class='input-group ' style='margin-bottom: 0'>
                  <input name='search' class='form-control' placeholder='Try Logo Design' aria-label='Search' required>
                  <button class='btn btn-searchbar' type='submit'>Search</button>
                </div> 
              </form>
            </div>
            <div class="col-md-4 d-flex align-items-center justify-content-center" style="padding:4px 0px;">
              <div class="btn-group" role="group" aria-label="Categories">
                <a type="button" href="page-search.php?search=Photography" class="btn btn-category">Photography</a>
                <a type="button" href="page-search.php?search=Graphics & Design" class="btn btn-category">Graphics & Design</a>
                <a type="button" href="page-search.php?search=Video & Animation" class="btn btn-category">Video & Animation</a>
              </div>
            </div>
          </div>
          <hr class="style1">
        </div>
      </div>
    </div>
  </header>

  <div id="main" style=""> 
        <div class="" style="margin:50px 30px 30px 30px;">  <!-- ni-remove ko muna yung class d-md-flex h-md-100 -->
          <div class="row justify-content-start" >
            <?php
              $search = validate($_GET["search"], $conn);
              $searchFlag = 0;
              
              echo "<h3 class='d-flex align-items-center justify-content-center' style='margin:0px; font-weight: 700'>Search results for '".$search."'</h3>";

              

                      $sql = "SELECT A.profile_pic, B.artist_id, A.first_name, A.last_name FROM user_profile_t A INNER JOIN artist_profile_t B ON A.user_id = B.user_id WHERE (A.user_type = 'artist') AND (A.status = 'Active') AND ((A.username LIKE '".$search."%') OR (A.first_name LIKE '".$search."%') OR (A.last_name LIKE '".$search."%') OR (A.location LIKE '%".$search."%')) ORDER BY A.date_joined LIMIT 5";
                      $query = mysqli_query($conn, $sql);
                      $count = mysqli_num_rows($query);

                      if($count > 0){
                        echo "<br><br><b class='d-flex align-items-center justify-content-center text-muted'>Artist Profiles</b>
                                <div class='row '>";

                        while($row_data = mysqli_fetch_array($query)){
                          echo "<div class='col mb-1 d-flex align-items-start' style='padding:10px 120px;'>";
                          if(isset($row_data[0])){
                            echo "<a href='page-view-artist.php?artistID=".$row_data[1]."'><img src='assets/img/profile/".$row_data[0]."' class='display-size profile-pic-small'><br>";
                          }
                          else{
                            echo "<a href='page-view-artist.php?artistID=".$row_data[1]."'><img src='assets/img/SVG/profile-icon.svg' class='display-size profile-pic-small'><br>";
                          }
                          echo $row_data[2]." ".$row_data[3]."</a></div>";
                        }

                        echo "</div>";
                      }
                      else{
                        $searchFlag += 1;
                      }

                      $sql = "SELECT profile_pic, user_id, first_name, last_name FROM user_profile_t WHERE (user_type = 'client') AND (status = 'Active')  AND ((username LIKE '".$search."%') OR (first_name LIKE '".$search."%') OR (last_name LIKE '".$search."%') OR (location LIKE '%".$search."%')) ORDER BY date_joined LIMIT 5";
                      $query = mysqli_query($conn, $sql);
                      $count = mysqli_num_rows($query);

                      if($count > 0){
                        echo "
                          <b class='my-3 text-muted d-flex align-items-center justify-content-center'>Client Profiles</b>
                          <div class='row'>";

                        while($row_data = mysqli_fetch_array($query)){
                          echo "<div class='col mb-1 d-flex align-items-start' style='padding:10px 120px;'>";
                          if(isset($row_data[0])){
                            echo "<a href='page-view-client.php?clientID=".$row_data[1]."'><img src='assets/img/profile/".$row_data[0]."' class='display-size profile-pic-small'><br>";
                          }
                          else{
                            echo "<a class='' href='page-view-client.php?clientID=".$row_data[1]."'><img src='assets/img/SVG/profile-icon.svg' class='display-size profile-pic-small'><br>";
                          }
                          echo $row_data[2]." ".$row_data[3]."</a></div>";
                        }

                        echo "</div>";
                      }
                      else{
                        $searchFlag += 1;
                      }

                    $sql = "SELECT works_t.id, works_t.work, works_t.title, works_t.tags, user_profile_t.first_name, user_profile_t.last_name, works_t.type FROM works_t INNER JOIN artist_profile_t ON works_t.artist_id=artist_profile_t.artist_id INNER JOIN user_profile_t ON artist_profile_t.user_id=user_profile_t.user_id WHERE ((works_t.title LIKE '%".$search."%') OR (works_t.tags LIKE '%".$search."%')) AND user_profile_t.user_type = 'artist' AND works_t.status = 'Live' ORDER BY RAND() LIMIT 18";
                    $query = mysqli_query($conn, $sql);
                    $count = mysqli_num_rows($query);

                    if($count > 0){
                      echo "
                        <b class='my-3 text-muted d-flex align-items-center justify-content-center'>Artist Works</b>";

                      while($row_data = mysqli_fetch_array($query)){
                        echo "
                          <div class='col-md-3' >
                            <div class='browse-card browse-size'>";

                            if(($row_data[6] == 'jpg') || ($row_data[6] == 'jpeg') || ($row_data[6] == 'png') || ($row_data[6] == 'jfif') || ($row_data[6] == 'gif')){
                              echo "<a href='page-view-work.php?show=".$row_data[0]."'><img src='./assets/img/works/". $row_data['work'] ."' class='img-fluid mb-2 portfolio' alt='Portfolio output'/>";
                            }
                            else{
                              echo "<a href='page-view-work.php?show=".$row_data[0]."'>
                                    <video width='300' height='325'>
                                      <source src='./assets/img/works/". $row_data['work'] ."' type='video/mp4'>
                                    </video>";
                            }

                            echo "
                            <div class='card-body card-body-custom'>
                              <h5 class='card-title card-title-custom'>".$row_data[2]."</h5>
                              <p class='card-text card-text-custom' style='text-align: left;'>".$row_data[4]." ".$row_data[5]."</p>
                              <small class='text-muted'>".$row_data[3]."</small>
                            </div>
                            </a>
                            </div>
                          </div>
                        ";
                      }
                    }
                    else{
                      $searchFlag += 1;
                    }
                  ?>
                </div>
                <?php
                  if($searchFlag == 3){
                      echo "
                        <div class='row justify-content-start m-4 text-center text-secondary'>
                          <h4>Whoops! Your search doesn't exist.</h4>
                        </div>
                        ";
                    }
                ?>
              </div>
            </div>

  <!-- ======= Footer ======= -->


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
  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script> 
  <script>
	  $(document).ready(function(){
		  $("#myModal").modal('show');
	  }); 
  </script>   
</body>
</html>