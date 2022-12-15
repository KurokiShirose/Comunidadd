<?php
  include("connection.php");
  session_start();

  if(!$_SESSION["username_s"]) {
    header("Location: index.php");
    exit;
  }
  if($_SESSION["verified_s"] != 1){
    if($_SESSION["visits_s"] > 5){
      setcookie($_SESSION["username_s"], "prohibit", time()+3600);  // prohibits account visit for 1 hour
      header("Location: page-verified-only.php");
      exit;
    }
    elseif(!empty($_COOKIE[$_SESSION["username_s"]])){
      if($_COOKIE[$_SESSION["username_s"]] == "prohibit"){
        header("Location: page-verified-only.php");
        exit;
      }
    }
    else{
      $_SESSION["visits_s"]++;
    }
  }

  $clientID = $_GET["clientID"];

  $username = $_SESSION['username_s'];

  $sql = "SELECT * FROM user_profile_t WHERE username = '$username'";
  $query = mysqli_query($conn, $sql);
  $result = mysqli_fetch_array($query);

  $sql_c = "SELECT * FROM user_profile_t WHERE user_id = '$clientID'";
  $query_c = mysqli_query($conn, $sql_c);
  $result_c = mysqli_fetch_array($query_c);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Profile</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

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
              <ul class="navbar-nav ms-auto d-flex align-items-center">
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
                    <?php if($_SESSION["user_type_s"] == "artist"){
                      echo "<a class='dropdown-item' href='page-profile-artist.php'>". $_SESSION["fname_s"]. " ". $_SESSION["lname_s"] ."</a>
                      <a class='dropdown-item' href='page-profile-artist-edit.php'>Edit Profile</a>";
                    }
                    else{
                      echo "<a class='dropdown-item' href='page-profile-client.php'>". $_SESSION["fname_s"]. " ". $_SESSION["lname_s"] ."</a>
                      <a class='dropdown-item' href='page-profile-client-edit.php'>Edit Profile</a>";
                    }
                  ?>
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
          if($result_c["profile_pic"] != NULL){
            $pfp = $result_c["profile_pic"];
            echo "<div class='img' style='
                  background-image:  linear-gradient(to bottom, rgba(245, 246, 252, 0.255), rgba(6, 6, 6, 0.73)),url(./assets/img/profile/". $pfp .");
                  height: 255px;background-size: contain;
                    top: 10px;
                    padding: 10px; 
                    z-index: 1;'></div>
                </div>";
          }
          else{
            
            echo "<div class='img' style='  background-image: linear-gradient(to bottom right,#113b3a , #447271, #447271 , rgb(13, 55, 54));
                  height: 255px;background-size: cover;
                    top: 10px;
                    padding: 10px; 
                    z-index: 1;'></div>
                </div>";
          }

          
          ?>
        
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
                    if($result_c["profile_pic"] != NULL){
                      $pfp = $result_c["profile_pic"];
                      echo "./assets/img/profile/". $pfp;
                    }
                    else{
                      echo "./assets/img/SVG/profile-icon.svg";
                    }
                    
                    
                  ?>"
                   alt="User profile picture"><br><br>
              <div class="text-center">
                <a href="mailto:<?php echo $result_c["email"] ?>" class="btn-custom-prof"><b>EMAIL</b></a>
              </div><br>
            </div>
            <h1 class="profile-name-format"><?php echo $result_c["first_name"]. " ". $result_c["last_name"] ?></h1>

            <p class="card-text-custom-1">Client</p>
            <p class="card-text-custom-1"><img src="assets/img/SVG/location.svg" class="icon-size">
              <b><?php echo $result["location"] ?></b></p>
            <h5 class="card-text-custom-1 margb-5"> <b>Joined <?php echo date("F Y", strtotime($result_c["date_joined"])) ?></b></h5>
            <?php
              $sql2 = "SELECT COUNT(*) AS counts FROM commission_t WHERE (status = 'Complete') AND (client_id = '$clientID') UNION SELECT AVG(A.artist_rating) FROM feedback_t A INNER JOIN commission_t B ON A.req_id = B.req_id WHERE B.client_id = '$clientID'";
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
            <p class="card-text-custom-2"><?php echo $result_c["about"] ?></p>
            <h5 class='card-text-custom-2'> <b>CONTACT & LINKS</b></h5>
            <?php if($_SESSION["verified_s"] != 1){
              echo "  <ul class='list-group list-group-unbordered mb-3'>
                        <li class='list-group-item'>
                          <b>Email Address:</b> <a class='float-right'><i>Verify to see email</i></a>
                        </li>
                        <li class='list-group-item'>
                          <b>Contact Number:</b> <a class='float-right'><i>Verify to see contact</i></a>
                        </li>
                      </ul>";
            }
            else{
              echo "  <ul class='list-group list-group-unbordered mb-3'>
                        <li class='list-group-item'>
                          <b>Email Address:</b> <a class='float-right'>". $result_c['email'] ."</a>
                        </li>
                        <li class='list-group-item'>
                          <b>Contact Number:</b> <a class='float-right'>". $result_c['contact'] ."</a>
                        </li>
                      </ul>";
            }
            ?>
            <!-- <div class="text-center">
              <span><img src="assets/img/SVG/new-email.svg" class="icon-size-2"></span>
              <span><img src="assets/img/SVG/mobile.svg" class="icon-size-2"></span>
              <span><img src="assets/img/SVG/facebook.svg" class="icon-size-2"></span>
              <span><img src="assets/img/SVG/twitter.svg" class="icon-size-2"></span>
              <span><img src="assets/img/SVG/instagram.svg" class="icon-size-2"></span>
            </div>
            <br> -->
          </div>
        </div>
      </div>

      <div class="col-md-8" style="padding: 0px 20px 20px 20px;">
        
        <div class="card" style="border-width: 0px; background-color: rgba(255, 255, 255, 0);">
          
          <div class="card-body" style="border-width: 0px; padding: 5px 0px 0px 0px;">
            <div class="tab-content">
              <div class="active tab-pane" id="activity">
                <section class="pt-1 pb-1 " style="padding: 0px;">
                    <div class="container">
                        <div class="row">
                            <div class="col-6">
                            <h3 class="mb-3" style="margin: 10px 0px 15px 5px;">Artists Hired</h3>
                            </div>
                            <div class="card" style="border-radius: 7px; border-width: 0ch; background-color: #ffffff;" >
                  <div class="card-body">
                    <?php             
                            $sql = "SELECT * FROM commission_t A INNER JOIN feedback_t B ON A.req_id = B.req_id INNER JOIN artist_profile_t C ON A.artist_id = C.artist_id INNER JOIN user_profile_t D ON C.user_id = D.user_id  WHERE A.client_id = '$clientID'";
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
                <!-- <div class="card">
                    <div class="card-body">
                        <h5> Artists Visited</h5>
                        <div class="row">
                              <div class="col-sm-2 card-padding-visited">
                                <div class="card card-profile viewed-artist-size">
                                  <div class="card-body card-body-custom-2">
                                    <a href=""><img src="assets/img/works/mori.jfif" class="img-fluid mb-2 portfolio"
                                         alt="Artists Profile Picture"/></a>
                                    <a href=""><h6 class="card-title card-title-custom-2">Artist Name</h6></a></a>
                                  </div>
                                  <div class="card-footer card-footer-custom">
                                    <small class="text-muted">Date Viewed</small>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-2 card-padding-visited">
                                <div class="card card-profile viewed-artist-size">
                                  <div class="card-body card-body-custom-2">
                                    <a href=""><img src="assets/img/works/mori.jfif" class="img-fluid mb-2 portfolio"
                                         alt="Artists Profile Picture"/></a>
                                    <a href=""><h6 class="card-title card-title-custom-2">Artist Name</h6></a></a>
                                  </div>
                                  <div class="card-footer card-footer-custom">
                                    <small class="text-muted">Date Viewed</small>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-2 card-padding-visited">
                                <div class="card card-profile viewed-artist-size">
                                  <div class="card-body card-body-custom-2">
                                    <a href=""><img src="assets/img/works/mori.jfif" class="img-fluid mb-2 portfolio"
                                         alt="Artists Profile Picture"/></a>
                                    <a href=""><h6 class="card-title card-title-custom-2">Artist Name</h6></a></a>
                                  </div>
                                  <div class="card-footer card-footer-custom">
                                    <small class="text-muted">Date Viewed</small>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-2 card-padding-visited">
                                <div class="card card-profile viewed-artist-size">
                                  <div class="card-body card-body-custom-2">
                                    <a href=""><img src="assets/img/works/mori.jfif" class="img-fluid mb-2 portfolio"
                                         alt="Artists Profile Picture"/></a>
                                    <a href=""><h6 class="card-title card-title-custom-2">Artist Name</h6></a></a>
                                  </div>
                                  <div class="card-footer card-footer-custom">
                                    <small class="text-muted">Date Viewed</small>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-2 card-padding-visited">
                                <div class="card card-profile viewed-artist-size">
                                  <div class="card-body card-body-custom-2">
                                    <a href=""><img src="assets/img/works/mori.jfif" class="img-fluid mb-2 portfolio"
                                         alt="Artists Profile Picture"/></a>
                                    <a href=""><h6 class="card-title card-title-custom-2">Artist Name</h6></a></a>
                                  </div>
                                  <div class="card-footer card-footer-custom">
                                    <small class="text-muted">Date Viewed</small>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-2 card-padding-visited">
                                <div class="card card-profile viewed-artist-size">
                                  <div class="card-body card-body-custom-2">
                                    <a href=""><img src="assets/img/works/mori.jfif" class="img-fluid mb-2 portfolio"
                                         alt="Artists Profile Picture"/></a>
                                    <a href=""><h6 class="card-title card-title-custom-2">Artist Name</h6></a></a>
                                  </div>
                                  <div class="card-footer card-footer-custom">
                                    <small class="text-muted">Date Viewed</small>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-2 card-padding-visited">
                                <div class="card card-profile viewed-artist-size">
                                  <div class="card-body card-body-custom-2">
                                    <a href=""><img src="assets/img/works/mori.jfif" class="img-fluid mb-2 portfolio"
                                         alt="Artists Profile Picture"/></a>
                                    <a href=""><h6 class="card-title card-title-custom-2">Artist Name</h6></a></a>
                                  </div>
                                  <div class="card-footer card-footer-custom">
                                    <small class="text-muted">Date Viewed</small>
                                  </div>
                                </div>
                              </div>
                              <div class="col-sm-2 card-padding-visited">
                                <div class="card card-profile viewed-artist-size">
                                  <div class="card-body card-body-custom-2">
                                    <a href=""><img src="assets/img/works/mori.jfif" class="img-fluid mb-2 portfolio"
                                         alt="Artists Profile Picture"/></a>
                                    <a href=""><h6 class="card-title card-title-custom-2">Artist Name</h6></a></a>
                                  </div>
                                  <div class="card-footer card-footer-custom">
                                    <small class="text-muted">Date Viewed</small>
                                  </div>
                                </div>
                              </div>
                        </div>
                    </div>
                </div>
              </div> -->
              
                        
            </div>
            <!-- /.tab-content -->
          </div><!-- /.card-body -->
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    </div>
    <!-- /.row -->
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
              <a href="privacy-policy.html">Privacy Policy</a>
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
        
        document.getElementById("first-nav-2").style.background = "#203C3B";
      } else {
      
        document.getElementById("first-nav-2").style.background = "none";
      }
    }
  </script>
  <script>
    $(document).on('change', '.file-input', function() {


    var filesCount = $(this)[0].files.length;

    var textbox = $(this).prev();

    if (filesCount === 1) {
    var fileName = $(this).val().split('\\').pop();
    textbox.text(fileName);
    } else {
    textbox.text(filesCount + ' files selected');
    }



    if (typeof (FileReader) != "undefined") {
      var dvPreview = $("#divImageMediaPreview");
      dvPreview.html("");            
      $($(this)[0].files).each(function () {
          var file = $(this);                
              var reader = new FileReader();
              reader.onload = function (e) {
                  var img = $("<img />");
                  img.attr("style", "width: 75px; height:auto;padding: 10px");
                  img.attr("src", e.target.result);
                  dvPreview.append(img);
              }
              reader.readAsDataURL(file[0]);                
      });
    } else {
      alert("This browser does not support HTML5 FileReader.");
    }


    });
  </script>
</body>
</html>