<?php

  include("connection.php");
  session_start();

  if(!$_SESSION["username_s"]) {
    header("Location: index.php");
    exit;
  }
  if($_SESSION["verified_s"] != 1){
    header("Location: page-verified-only.php");
    exit;
  }
  

  $username = $_SESSION['username_s'];

  if(!empty($_GET["rate"])){
    $id = $_GET["rate"];
    $_SESSION["commission_id_s"] = $id;
  }
  else{
    $id = $_SESSION["commission_id_s"];
  }

  if(isset($_POST["submit"])){
    // $rating = $_POST["rating"];
    $rating = $_COOKIE['star_rating'];
    echo "<script>alert($star_rating)</script>";

    $comment = $_POST["comment"];

    if($_SESSION["user_type_s"] == "artist"){

      $sql = "INSERT INTO feedback_t(req_id, artist_comment, artist_rating, artist_feedback_date) VALUES ('$id', '$comment', '$rating', NOW())";
      $query = mysqli_query($conn, $sql);

      header("Location: page-dashboard-artist.php");

    }else{
      $sql = "UPDATE feedback_t SET client_comment = '$comment', client_rating = '$rating', client_feedback_date = NOW() WHERE req_id = '$id'";
      $query = mysqli_query($conn, $sql);

      $sql = "UPDATE commission_t SET status = 'Complete', completion_date = NOW() WHERE req_id = '$id'";
      $query = mysqli_query($conn, $sql);

      $sql = "INSERT INTO activity_t(user_id, activity) VALUES (".$_SESSION['user_id_s'].", 'has completed commission #".$id."')";  
      mysqli_query($conn, $sql);

      header("Location: page-dashboard-client.php");
    }

    exit;
  }

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>COMUNIDAD | Connecting Filipino Talents GLobally</title>
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

      <!--Star rating CSS-->
      <link rel="stylesheet" href="assets/css/style-rating.css">

      <style>
        .star {
          font-size: 3rem;
          color: #ff9800;
          background-color: unset;
          border: none;
        }

        .star_rating {
          user-select: none;
        }

        .star:hover {
          cursor: pointer;
        }
      </style>
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
  <div class="row" style="padding: 50px 125px 100px 125px;">
    <div class="card" >
        <div class="card-body" style="margin: 50px 75px 50px 75px;">
            <div class="row">
                <div class="col-md-12">
                    <?php
                      if($_SESSION["user_type_s"] == "client"){
                        $title = "Thank you for choosing one of our artists!";
                        $type = "artist";

                        $sql = "SELECT * FROM user_profile_t A INNER JOIN artist_profile_t B ON A.user_id = B.user_id INNER JOIN commission_t C ON B.artist_id = C.artist_id WHERE C.req_id = '$id'";
                      }
                      else{
                        $title = "Thank you for serving one of our clients!";
                        $type = "client";

                        $sql = "SELECT * FROM user_profile_t A INNER JOIN commission_t B ON A.user_id = B.client_id WHERE B.req_id = '$id'";
                      }
                      
                      $query = mysqli_query($conn, $sql);
                      $result = mysqli_fetch_array($query);

                    ?>
                    <img src="assets/img/SVG/comunidad-black.svg" style="width: 500px; height:auto;">
                    <hr class="style1">
                    <h2><b><?php echo $title ?></b></h2>
                    As part of our commitment to ensure a reliable and trustworthy community platform for Filipino Artists and clients, we would like to hear from you about <b><?php echo $result["first_name"]." ".$result["last_name"] ?></b> based from your past transaction.</p>
                    <p> Provided below is a comment box and stars to rate our <?php echo $type ?>.</p>
                    <h6><b>Thank you and Mabuhay!</b></h6>
                </div>
            </div>
            <hr class="style1">
            <div class="row">
                <div class="col-md-6">
                    <div class="card" style="border-width: 0px; background-color: #ffffff00;" >
                        <div class="card-body p-4">
                          <div class="d-flex text-black">
                            <div class="flex-shrink-0">
                              <?php 
                                if($result["profile_pic"] != NULL){
                                  echo "<img src='./assets/img/profile/".$result["profile_pic"]."' class='profile-pic-small' style='object-fit:cover;'>";
                                }
                                else{
                                  echo "<img src='./assets/img/SVG/profile-icon.svg' class='profile-pic-small' style='object-fit:cover;'>";
                                }

                              ?>
                              
                            </div>
                            <div class="flex-grow-1 ms-3 ">
                              <h4 class="mb-1"><b><?php echo $result["first_name"]." ".$result["last_name"] ?></b></h4>
                              <p style="color: #2b2a2a; margin-bottom:2px;"><?php echo $result["location"] ?></p>
                            </div>
                          </div>
                          </div>
                      </div>
                </div>
                <div class="col-md-6">
                    
                        <br>
                        <div class="d-flex align-items-center">
                          <div class="star_rating">
                            <button class="star">&#9734;</button>
                            <button class="star">&#9734;</button>
                            <button class="star">&#9734;</button>
                            <button class="star">&#9734;</button>
                            <button class="star">&#9734;</button>
                          </div>
                          <!-- <input type="number" name="rating" min=1 max=5 required> -->
                        </div>
                        <script>
                          const stars = document.querySelectorAll(".star");

                          stars.forEach((star, i) => {
                            star.onclick = function(){
                              let current_star_lvl = i+1;
                              // console.log(current_star_lvl);
                              document.cookie="star_rating=" + current_star_lvl;

                              stars.forEach((star, j) => {
                                if(current_star_lvl >= j+1){
                                  star.innerHTML = "&#9733";
                                }
                                else{
                                  star.innerHTML = "&#9734";
                                }
                              })
                            }
                          })
                        </script>
                      <form action="page-rating.php" method="post">
                        <div class="form-outline mb-4">
                            <label class="form-label" for="comment">Comment</label>
                            <textarea name="comment" class="form-control" id="comment" style="background-color: rgb(244, 246, 246); height:250px; resize: none ;" maxlength="300"></textarea>
                        </div>
                          <button name="submit" onclick='return confirm(`Are you sure?`);' class="btn btn-custom-reg">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
  </div>
  <!-- ======= Footer ======= -->
<footer id="footer">

  <div class="footer-top">
    <div class="container">
      <div class="row">

        <div class="col-lg-3 col-md-6 footer-contact">
          <h3>Comunidad<span>.</span></h3>
          <p>
            123 Kalye Anluwage <br>
            Manila MN0021<br>
            Philippines <br><br>
            <strong>Phone:</strong> +63 9432 334 323<br>
            <strong>Email:</strong> communidadPH@gmail.com<br>
          </p>
        </div>

        <div class="col-lg-3 col-md-6 footer-links">
          <h4>Useful Links</h4>
          <ul>
            <li><i class="bx bx-chevron-right"></i> <a href="#">Privacy Policy</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="#">Terms of Service</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="#">Intellectual Property Claims</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="#">Community Standards</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="#">Help & Support</a></li>
          </ul>
        </div>

        <div class="col-lg-3 col-md-6 footer-links">
          <h4>Our Categories</h4>
          <ul>
            <li><i class="bx bx-chevron-right"></i> <a href="#">Photography</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="#">Video and Animation</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="#">Graphics and Design</a></li>
          </ul>
        </div>

        <div class="col-lg-3 col-md-6 footer-links">
          <h4>Our Social Networks</h4>
          <p>You can follow us on the following:</p>
          <div class="social-links mt-3">
            <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
            <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
            <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
            <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
          </div>
        </div>

      </div>
    </div>
  </div>

  <div class="container py-4">
    <div class="copyright">
      &copy; Copyright <strong><span>Comunidad</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      Inspired from <a href="https://bootstrapmade.com/">BootstrapMade</a>
    </div>
  </div>
</footer><!-- End Footer -->

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
  <!-- Templat Star Rating File -->
  <script  src="assets/js/script-rating.js"></script>

</body>

</html>