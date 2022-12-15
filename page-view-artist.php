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

  $artistID = $_GET["artistID"];

  $username = $_SESSION['username_s'];

  $sql = "SELECT * FROM user_profile_t WHERE username = '$username'";
  $query = mysqli_query($conn, $sql);
  $result = mysqli_fetch_array($query);

  $sql_a = "SELECT * FROM user_profile_t A INNER JOIN artist_profile_t B ON A.user_id = B.user_id WHERE B.artist_id = '$artistID'";
  $query_a = mysqli_query($conn, $sql_a);
  $result_a = mysqli_fetch_array($query_a);

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
<body style="background-color: #f9f9f9">
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
          if($result_a["profile_pic"] != NULL){
            $pfp = $result_a["profile_pic"];
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
                    z-index: 1;'>
                </div>";
          }

          
          ?>
        
    </div>
  </section>  
  <!-- ======= Profile Section ======= -->
  <div class="container-fluid ">
    <div class="row">
    <div class="col-md-4 d-flex justify-content-center">
        <!-- Profile Image -->
        <div class="card card-outline" style="margin-top:-50px; width:90%;">
          <div class="card-body box-profile" style="padding:0px;">
            <div class="text-center">
              <img class="profile-pic" style="margin-top: -80px;" src="
                  <?php 
                    if($result_a["profile_pic"] != NULL){
                      $pfp = $result_a["profile_pic"];
                      echo "./assets/img/profile/". $pfp;
                    }
                    else{
                      echo "./assets/img/SVG/profile-icon.svg";
                    }
                    
                    
                  ?>"
                   alt="User profile picture"><br><br>
              <div class="text-center">
                <?php
                  if($_SESSION["user_type_s"] == "client" && $result_a["verified"] == 1){
                    echo "
                      <a href='page-client-request.php?artistID=". $result_a['artist_id'] ."' class='btn-custom-prof'><b>REQUEST COMMISSION</b></a>
                    ";
                  }
                  else{
                    echo "
                      <a href='mailto:".$result_a["email"]."' class='btn-custom-prof'><b>EMAIL</b></a>
                    ";
                  }
                ?>
              </div><br>
            </div>
            <h1 class="profile-name-format"><?php echo $result_a["first_name"]. " ". $result_a["last_name"] ?></h1>

            <p class="card-text-custom-1"><?php echo $result_a["expertise"] ?></p>
            <p class="card-text-custom-1">Artist ID: #<?php echo $result_a["artist_id"] ?></p>
            <p class="card-text-custom-1"><img src="assets/img/SVG/location.svg" class="icon-size">
              <b><?php echo $result["location"] ?></b></p>
            <h5 class="card-text-custom-1 margb-5"> <b>Joined <?php echo date("F Y", strtotime($result_a["date_joined"])) ?></b></h5>
            <h1 class="profile-name-format bolder">₱ <?php echo $result_a["min_charge"] ? $result_a["min_charge"]:"0.00" ?>-<?php echo $result_a["max_charge"] ? $result_a["max_charge"]:"0.00" ?></h1>
            <p class="card-text card-text-custom">Charge</p>
            <?php
              $sql2 = "SELECT AVG(A.client_rating) FROM feedback_t A INNER JOIN commission_t B ON A.req_id = B.req_id WHERE B.artist_id = '$artistID'";
              $query2 = mysqli_query($conn, $sql2);
              $result2 = mysqli_fetch_array($query2);

              
            ?>
            <?php
              $sql3 = "SELECT COUNT(*) AS counts FROM commission_t WHERE (status = 'Complete') AND (artist_id = '$artistID')";
              $query3 = mysqli_query($conn, $sql3);
              $result3 = mysqli_fetch_assoc($query3);

              
            ?>
            <h1 class="profile-name-format bolder"><?php echo $result3["counts"] ?></h1>
            <p class="card-text card-text-custom">Commissions</p>
            
            <div class="text-center">
              <?php 
                $stars = 5;
                for($i = 0; $i < (int) $result2[0]; $i++){
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
            <p class="card-text-custom-2"><?php echo $result_a["about"] ?></p>
            <h5 class="card-text-custom-2"> <b>CONTACT & LINKS</b></h5>
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
                          <b>Email Address:</b> <a class='float-right'>". $result_a['email'] ."</a>
                        </li>
                        <li class='list-group-item'>
                          <b>Contact Number:</b> <a class='float-right'>". $result_a['contact'] ."</a>
                        </li>
                      </ul>";
            }
            ?>
          </div>
        </div>
      </div>

      <div class="col-md-8">
        <div class="card" style="border-width: 0px; background-color: rgba(255, 255, 255, 0); margin-top:10px;">
          <div class="card-header card-header-bg-custom p-2" style="border-width: 0px; padding: 15px;"> 
            <ul class="nav nav-pills nav-pills-nbg">
              <li class="nav-item"><a class="nav-link active" href="#portfolio" data-toggle="tab">Portfolio</a></li>
            </ul>
          </div><!-- /.card-header -->
          <div class="card-body" style="border-width: 0px;">
            <div class="tab-content">
              <div class="active tab-pane" id="portfolio">
                <div class="row">
                  <?php
                    $user_id = $result_a['artist_id'];
                    $sql4 = "SELECT * FROM works_t WHERE status = 'Live' AND artist_id = '$user_id'";
                    $query4 = mysqli_query($conn, $sql4);
                    $count = mysqli_num_rows($query4);

                    if($count > 0){
                      while($row_data = mysqli_fetch_array($query4)){
                        echo "
                        <div class='col-sm-4'>
                          <div class='profile-card-3'>";

                              if((strtolower($row_data["type"]) == 'jpg') || (strtolower($row_data["type"]) == 'jpeg') || (strtolower($row_data["type"]) == 'png') || (strtolower($row_data["type"]) == 'jfif') || (strtolower($row_data["type"]) == 'gif')){
                                echo "<a href='page-view-work.php?show=".$row_data["id"]."'><img src='./assets/img/works/". $row_data['work'] ."' class='img-fluid portfolio' alt='Portfolio output'/></a>
                              <a href='page-view-work.php?show=".$row_data["id"]."'><h5 class='card-title card-title-custom-2 pt-3'>".$row_data['title']."</h5></a>";
                              }
                              else{
                                echo "<a href='page-view-work.php?show=".$row_data["id"]."'>
                                    <video width='300' height='225'>
                                      <source src='./assets/img/works/". $row_data['work'] ."' type='video/mp4'>
                                    </video>
                                    </a>
                                    <a href='page-view-work.php?show=".$row_data["id"]."'><h5 class='card-title card-title-custom-2'>".$row_data['title']."</h5></a>";
                              }
                              echo "
                              <div class='card-body card-body-custom'>
                                <div class='profile-icons-3'>
                                  <button type='button' class='btn-none' data-toggle='modal' data-target='#flagFile-".$row_data["id"]."'>
                                    <a><i class='bi-flag-fill'></i></a>
                                  </button>
                              </div>
                              <div class='card-footer-custom'>
                                <small class='text-muted'>".date('M d, Y H:i', strtotime($row_data['upload_date']))."</small>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class='modal fade' id='flagFile-".$row_data["id"]."'>
                          <div class='modal-dialog'>
                            <div class='modal-content'>
                              <form action='report.php' method='post'>
                                <div class='modal-body'>
                                  <div class='row' style='padding: 10px 10px 20px 10px;'>
                                    <h3 class='mb-2' style='margin: auto; text-align:center;'><b>Report</b></h3>
                                    <p style='margin: auto;'><b>Please Select a problem</b></p>
                                    <p style='font-size:15px;' class='text-muted'>Help us understand why</p>
                                    <div class='form-check' style='margin:0px 0px 10px 30px; '>
                                      <input class='form-check-input' type='radio' id='cat1' name='flagType' value='Nudity' required>
                                      <label class='form-check-label' for='cat1'>Nudity</label>
                                    </div>
                                    <div class='form-check' style='margin:0px 0px 10px 30px; '>
                                      <input class='form-check-input' type='radio' id='cat2' name='flagType' value='Self-Harm'>
                                      <label class='form-check-label' for='cat2'>Self-Harm</label>
                                    </div>
                                    <div class='form-check' style='margin:0px 0px 10px 30px; '>
                                      <input class='form-check-input' type='radio' id='cat3' name='flagType' value='Violence'>
                                      <label class='form-check-label' for='cat3'>Violence</label>
                                    </div>
                                    <div class='form-check' style='margin:0px 0px 10px 30px; '>
                                      <input class='form-check-input' type='radio' id='cat4' name='flagType' value='Harassment'>
                                      <label class='form-check-label' for='cat4'>Harassment</label>
                                    </div>
                                    <div class='form-check' style='margin:0px 0px 10px 30px; '>
                                      <input class='form-check-input' type='radio' id='cat5' name='flagType' value='Hate Speech'>
                                      <label class='form-check-label' for='cat5'>Hate Speech</label>
                                    </div>
                                    <div class='form-check' style='margin:0px 0px 10px 30px; '>
                                      <input class='form-check-input' type='radio' id='cat6' name='flagType' value='Plagiarism / Intellectual Property'>
                                      <label class='form-check-label' for='cat6'>Plagiarism / Intellectual Property</label>
                                    </div>
                                    <div class='form-check' style='margin:0px 0px 10px 30px; '>
                                      <input class='form-check-input' type='radio' id='cat7' name='flagType' value='Terrorism'>
                                      <label class='form-check-label' for='cat7'>Terrorism</label>
                                    </div>
                                    <div class='form-group form-group-custom' style='margin-bottom: 10px;'>
                                      <label for='description' class='lbl'>Description</label>
                                      <textarea type='text' style='height:180px; resize: none ' name='flagDesc' class='form-control' placeholder='Describe why?' value=''></textarea>
                                    </div>
                                  </div>
                                </div>
                                <!-- Modal footer -->
                                <div class='modal-footer'>
                                  <input type='text' name='flagWorkID' value='".$row_data["id"]."' hidden>
                                  <input type='text' name='artistID' value='".$row_data["artist_id"]."' hidden>
                                  <button type='button' class='btn btn-danger' data-dismiss='modal'>Cancel</button>
                                  <button type='submit' name='submitFlag2' class='btn btn-custom-reg'>Save</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                        ";
                      }
                    }
                  ?>
                </div>
              </div>
            </div>
            <!-- /.tab-content -->
            <section class="pt-1 pb-1 " style="padding: 0px;">
                    <div class="container">
                        <div class="row">

                            <h3 class="mb-3 mx-0 p-0">Past Commissions</h3>

                            <div class="card" style="border-radius: 7px; border-width: 0ch; background-color: #ffffff;" >
                  <div class="card-body">
                    <?php             
                            $sql = "SELECT * FROM commission_t A INNER JOIN feedback_t B ON A.req_id = B.req_id INNER JOIN user_profile_t C ON A.client_id = C.user_id  WHERE A.artist_id = '$artistID'";
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
                                            for($i = 0; $i < $result["client_rating"]; $i++){
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
                                          <div class='row'><p>".$result["client_comment"]."</p></div>
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
          </div><!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>

  <!-- ======= Footer ======= -->
  <footer class="footer-custom d-none">
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