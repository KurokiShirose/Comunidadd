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

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>

  <!-- Style CSS -->
  <link rel="stylesheet" href="css/style-profile.css">

</head>

<?php
  include("connection.php");
  session_start();

  if(!$_SESSION["username_s"]) {
    header("Location: index.php");
    exit;
  }

  function validate($data, $conn){
    $data = trim($data);
    $data = stripcslashes($data);
    $data = mysqli_real_escape_string($conn, $data);
    $data = htmlspecialchars($data);
    return $data;
  }

  $username = $_SESSION['username_s'];
  $artistID = $_SESSION["artist_id_s"];

  $sql = "SELECT * FROM user_profile_t A INNER JOIN artist_profile_t B ON A.user_id = B.user_id WHERE A.username = '$username'";
  $query = mysqli_query($conn, $sql);
  $result = mysqli_fetch_array($query);

  $userID = $result['user_id'];

  if(isset($_POST["edit"])){
    $fname = validate($_POST["fname"], $conn);
    $lname = validate($_POST["lname"], $conn);
    $city_v = validate($_POST["city"], $conn);
    $province_v = validate($_POST["province"], $conn);
    $location = $city_v . ', ' . $province_v;
    $email = validate($_POST["email"], $conn);
    $contact = validate($_POST["mobile"], $conn);
    $about = validate($_POST["about"], $conn);

    $expertise = validate($_POST["expertise"], $conn);
    $minCharge = $_POST["minCharge"];
    $maxCharge = $_POST["maxCharge"];

    if(strlen($location) < 4){
      $location = $result["location"];
    }

    $sql = "UPDATE user_profile_t SET first_name = '$fname', last_name = '$lname', location = '$location', email = '$email', contact = '$contact', about = '$about' WHERE username = '$username'";
    $query = mysqli_query($conn, $sql);

    $_SESSION["fname_s"] = $fname;
    $_SESSION["lname_s"] = $lname;

    $sql = "UPDATE artist_profile_t SET expertise = '$expertise', min_charge = '$minCharge', max_charge = '$maxCharge' WHERE user_id = '$userID'";
    $query = mysqli_query($conn, $sql);

    $sql = "INSERT INTO activity_t(user_id, activity) VALUES (".$_SESSION['user_id_s'].", 'has edit profile info')";  
    mysqli_query($conn, $sql);

    header("Location: page-profile-artist.php");
    exit;
  }

  $message = "";
  if(isset($_POST["editLogin"])){
    $oldPass = validate($_POST["oldPassword"], $conn);
    $newPass = validate($_POST["newPassword"], $conn);
    $repeatPass = validate($_POST["repeatPassword"], $conn);

    $sql2 = "SELECT * FROM account_t WHERE username = '$username'";
    $query2 = mysqli_query($conn, $sql2);
    $result2 = mysqli_fetch_array($query2);

    if(strlen($newPass) < 6){
      $message = "Passwords must be 6 characters or more. Try again.";
      echo "<script type='text/javascript'>
              $(document).ready(function(){
              $('#passwordModal').modal('toggle');
              });
            </script>";
    }
    else{
      if(($newPass == $repeatPass) && password_verify($oldPass, $result2["password"])){
        $newPassHashed = password_hash($newPass, PASSWORD_BCRYPT);

        $sql = "UPDATE account_t SET password = '$newPassHashed' WHERE username = '$username'";
        $query = mysqli_query($conn, $sql);

        $sql = "INSERT INTO activity_t(user_id, activity) VALUES (".$_SESSION['user_id_s'].", 'has edit account info')";  
        mysqli_query($conn, $sql);

        header("Location: page-profile-artist.php");
        exit;
      }
      else{
        $message = "Passwords do not match. Try again.";
        echo "<script type='text/javascript'>
                  $(document).ready(function(){
                      $('#passwordModal').modal('toggle');
                  });
                  </script>";
      }
    }
    
  }

  if(isset($_POST["deleteAcc"])){
    $sql2 = "SELECT * FROM commission_t A INNER JOIN artist_profile_t B ON A.artist_id = B.artist_id WHERE A.artist_id = '$artistID' AND A.status != 'Complete'";
    $query2 = mysqli_query($conn, $sql2);
    $unfinishedCommission = mysqli_num_rows($query2);

    if($unfinishedCommission >= 1){
      echo "<script type='text/javascript'>
                $(document).ready(function(){
                    $('#cantDeleteModal').modal('toggle');
                });
                </script>";
    }
    else{
      // delete login info
      $sql2 = "UPDATE account_t SET type = 'removed' WHERE username = '$username'";
      mysqli_query($conn, $sql2);

      // update status to all works
      $sql = "UPDATE works_t SET status = 'Removed' WHERE artist_id = '$artistID'";
      mysqli_query($conn, $sql);

      $sql = "INSERT INTO removed_works_t(work_id, artist_id, remarks) SELECT id, artist_id, 'Archived' FROM works_t WHERE artist_id = '$artistID'";
      mysqli_query($conn, $sql);

      // copy id and remarks to removed_users_t
      $sql = "INSERT INTO removed_users_t(user_id, remarks) VALUES ('$userID', 'Deleted')";
      mysqli_query($conn, $sql);

      // set user_profile_t status to removed
      $sql2 = "UPDATE user_profile_t SET status = 'Removed' WHERE username = '$username'";
      mysqli_query($conn, $sql2);

      $sql = "INSERT INTO activity_t(user_id, activity) VALUES (".$_SESSION['user_id_s'].", 'has deleted his/her account')";  
      mysqli_query($conn, $sql);

      session_destroy();
      header("Location: page-login.php");
      exit;
    }
  }

  if(isset($_POST["submitProfilePic"])){
    $filename = $username;
    $tempname = $_FILES["image"]["tmp_name"];
    $folder = "./assets/img/profile/".$username;

    if(file_exists($folder)) {
      chmod($folder,0755); //Change the file permissions if allowed
      unlink($folder); //remove the file
    }

    $sql = "UPDATE user_profile_t SET profile_pic = '$filename' WHERE username = '$username'";
    $query = mysqli_query($conn, $sql);
 
    move_uploaded_file($tempname, $folder);  // move the uploaded image into the folder

    $sql = "INSERT INTO activity_t(user_id, activity) VALUES (".$_SESSION['user_id_s'].", 'has edit account info')";  
    mysqli_query($conn, $sql);

    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate( 'D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache'); 
    header("Location: page-profile-artist-edit.php");
    exit;
  }
?>

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
                <a class='dropdown-item' href='page-profile-artist.php'><?php echo $_SESSION["fname_s"]. " ". $_SESSION["lname_s"] ?></a>
                <a class='dropdown-item' href='page-profile-artist-edit.php'>Edit Profile</a>
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
            echo "
                <div class='img' style='
                  background-image: linear-gradient(to bottom, rgba(245, 246, 252, 0.255), rgba(6, 6, 6, 0.73)),url(./assets/img/profile/". $pfp .");
                  height: 255px;background-size: contain;
                    top: 10px;
                    padding: 10px; 
                    z-index: 1;'></div>
                </div>";
          }
          else{
            
            echo "<div class='img d-none d-sm-block' style='  background-image: linear-gradient(to bottom right,#113b3a , #447271, #447271 , rgb(13, 55, 54));
                  height: 255px;background-size: cover;
                    top: 10px;
                    padding: 10px; 
                    z-index: 1;'></div>
                </div>";
          }

          
          ?>
    </div>
  </section>  

  <!-- ======= modal ======= -->
  <div class="modal fade" id="uploadFile" role="dialog">
    <div class="modal-dialog" style="width:fit-content">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Upload Profile Picture</h4>
        </div>
        <div class="modal-body" style="width:fit-content">
          <form action="page-profile-artist-edit.php" enctype="multipart/form-data" method="post">
            <div class="row text-center">
            <div class="form-group form-group-custom" style="margin-bottom: 10px;">
              <div class="file-drop-area">
                <span class="choose-file-button"><i class="bi bi-images" style="font-size:50px;"></i><br>Choose Photo</span>
                <span class="file-message">Or drag and drop files here<br><small class="text-muted">File should be below 5mb in size.</small></span>
                <input type="file" name="image" class="file-input" accept=".jfif,.jpg,.jpeg,.png,.gif,.mp4" multiple>
              </div>
              <div id="divImageMediaPreview">

              </div>
            </div>
            </div>
            <br>
            <div>
                <input type="submit" name="submitProfilePic" class="btn save-btn text-center" 
                onclick='return confirm(`Are you sure?`);'value="Save"/>
            </div>
        </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="passwordModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Failed</h4>
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

  <div class="modal fade" id="cantDeleteModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Delete Failed</h4>
          <button type="button" class="close" data-dismiss="modal" onclick="$('#cantDeleteModal').modal('toggle');">&times;</button>
        </div>
        <div class="modal-body">
          You cannot delete your account if you have existing commission(s).
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="$('#cantDeleteModal').modal('toggle');">Close</button>
        </div>
      </div>
    </div>
  </div>


  <!-- ======= Profile Section ======= -->
  <div class="container-fluid">
    <div class="row pb-5">
      <div class="col-md-4" style="padding: 0px 20px 20px 20px;">
        <!-- Profile Image -->
        <div class="card card-outline" style="margin-top:-50px;">
          <div class="card-body box-profile" style="padding:30px;">
            <div class="text-center">
              <div class="d-flex align-items-center justify-content-center">
              <form action="page-profile-artist-edit.php" enctype="multipart/form-data" method="post">
                <div class="container-pic" style="margin:0px 85px" >
                <img class="image profile-pic" style="margin-top: -125px; width:200px;" src="
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
                   <div class="overlay">
                    <a href="#uploadFile" data-toggle="modal" data-target="#uploadFile" title="Change Photo">
                      <i class="icon fa fa-pencil"></i>
                    </a>
                    </div>
                </div>
                <br>
              </form>
              </div>
            </div>
            <h3 class="text-center" style="margin: 0px 0px 15px 0px;"><b>Edit Profile</b></h3>
            <form action="page-profile-artist-edit.php" method="post">
                <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                    <label for="firstname" class="lbl">First Name</label>
                    <input type="text" name="fname" class="form-control" value="<?php echo $result["first_name"] ?>" maxlength="100" required/>
                </div>
                <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                    <label for="lastname" class="lbl">Last Name</label>
                    <input type="text" name="lname" class="form-control" value="<?php echo $result["last_name"] ?>" maxlength="100" required/>
                </div>
                <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                    <label for="expertise" class="lbl">Expertise</label>
                    <select name="expertise" id="" class="form-control form-select">
                      <option value="Graphics and Design">Graphics and Design</option>
                      <option value="Photography">Photography</option>
                      <option value="Video and Animation">Video and Animation</option>
                    </select>
                </div>
                <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                    <label for="location" class="lbl">Location</label>
                    <select id="province" name="province" class="form-control form-select mb-2" required></select>
                    <select id="city" name="city" class="form-control form-select" required></select>
                </div>
                <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                    <label for="links" class="lbl">About</label>
                    <textarea type="text" name="about" class="form-control" maxlength="300"><?php echo $result["about"] ?></textarea>
                </div>
                <!-- <label for="about" class="lbl">Contact & Links</label>
                <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                    <input type="text" class="form-control" placeholder="facebook.com/" value="" />
                </div>
                <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                    <input type="text" class="form-control" placeholder="twitter.com/" value="" />
                </div>
                <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                    <input type="text" class="form-control" placeholder="instagram.com/" value="" />
                </div>
                <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                    <input type="text" class="form-control" placeholder="Link 1" value="" />
                </div>
                <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                    <input type="text" class="form-control" placeholder="Link 2" value="" />
                </div> -->
                <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                    <label for="charge" class="lbl">Min charge in Pesos</label>
                    <input type="number" min="0" name="minCharge" class="form-control" value="<?php echo $result["min_charge"] ? $result["min_charge"]:"0.00" ?>" required/>
                    <label for="charge" class="lbl">Max charge in Pesos</label>
                    <input type="number" min="0" name="maxCharge" class="form-control" value="<?php echo $result["max_charge"] ? $result["max_charge"]:"0.00" ?>" required/>
                </div>
                <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                    <label for="about" class="lbl">Email Address</label>
                    <input type="text" name="email" class="form-control" value="<?php echo $result["email"] ?>" required/>
                </div>
                <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                    <label for="about" class="lbl">Mobile Number</label>
                    <input type="text" name="mobile" class="form-control" value="<?php echo $result["contact"] ?>" maxlength="20" required/>
                </div>
                <br>
                <div>
                    <input type="submit" name="edit" class="btn save-btn text-center" 
                    onclick='return confirm(`Are you sure?`);'value="Save"/>
                    <a href="page-profile-artist.php"><button type="button" class="btn btn-default" 
                        style="background-color: rgb(130, 38, 38); color: white;">Cancel</button></a>
                </div>
            </form>
          </div>
          
        </div>
      </div>


      <div class="col-md-8" style="padding: 0px 0px 20px 0px;">
        <div class="card" style="border-width: 0px; background-color: rgba(255, 255, 255, 0);">
          <div class="card-header card-header-bg-custom p-2" style="border-width: 0px; padding: 15px;"> 
            <ul class="nav nav-pills nav-pills-nbg">
              <li class="nav-item"><a class="nav-link disabled" href="#portfolio" data-toggle="tab">Portfolio</a></li>
              <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Account Settings</a></li>
            </ul>
          </div><!-- /.card-header -->
          <div class="card-body" style="border-width: 0px;">
            <div class="tab-content">
              <div class="tab-pane" id="portfolio">
                <div class="row">
                  <?php
                    $user_id = $result['user_id'];
                    $sql = "SELECT * FROM works_t WHERE artist_id = '$user_id'";
                    $query = mysqli_query($conn, $sql);
                    $count = mysqli_num_rows($query);

                    if($count > 0){
                      while($row_data = mysqli_fetch_array($query)){
                        echo "
                        <div class='col-sm-4 card-padding-portfolio'>
                          <div class='card card-profile portfolio-size'>
                            <div class='card-body card-body-custom-2'>
                              <a href=''><img src='./assets/img/works/". $row_data['work'] ."' class='img-fluid mb-2 portfolio' alt='Portfolio output'/></a>
                              <a href=''><h5 class='card-title card-title-custom-2'>".$row_data['title']."</h5></a></a>
                            </div>
                            <div class='card-footer card-footer-custom'>
                              <small class='text-muted'>".date('M d, Y H:i', strtotime($row_data['upload_date']))."</small>
                            </div>
                          </div>
                        </div>
                        ";
                      }
                    }
                    else{
                      echo "
                        <div class='col-sm-12 m-4 card-padding-portfolio text-center text-secondary'>
                          <h4>Looks like you have not posted anything yet.<br>Click the <strong>+</strong> to start!</h4>
                        </div>
                        ";
                    }
                  ?> 
                </div>
              </div>
              <div class="active tab-pane" id="settings">
                <div class="card">
                    <div class="card-body">
                        <h3 style="margin: 0px 0px 15px 0px;"><b>Manage Log-in</b></h3>
                        <form action="page-profile-artist-edit.php" method="post">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                                        <label for="username" class="lbl">Username</label>
                                        <input type="text" class="form-control" value="<?php echo $result['username'] ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                                        <label for="opassword" class="lbl">Old Password</label>
                                        <input type="password" name="oldPassword" class="form-control" placeholder="********" value="" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                                        <label for="npassword" class="lbl">New Password</label>
                                        <input type="password" name="newPassword" class="form-control" placeholder="********" value="" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                                        <label for="rpassword" class="lbl">Repeat New Password</label>
                                        <input type="password" name="repeatPassword" class="form-control" placeholder="********" value="" />
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div>
                              <input type="submit" name="editLogin" style="float: right" class="btn save-btn text-center" 
                                onclick='return confirm(`Are you sure?`);'value="Save"/>
                              <input type="submit" name="deleteAcc" style="background-color: #822626" class="btn save-btn text-center" 
                                onclick='return confirm(`This cannot be undone. Are you sure?`);' value="Delete Account">
                              
                                
                            </div>
                        </form>
                    </div>
                </div>
              </div>
            </div>
            <!-- /.tab-content -->
          </div><!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>

      <!-- /.col -->
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

  <script src="city.js"></script>
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

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  
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
    function createCookie(name, value, days) {
      var expires;
      if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
      }
      else {
        expires = "";
      }
      document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/";
    }


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
                  img.attr("style", "width: 125px; height:auto;padding: 10px");
                  img.attr("src", e.target.result);

                  var media = new Audio(reader.result);
                  media.onloadedmetadata = function(){
                       // this would give duration of the video/audio file
                      var mediaDuration = media.duration;
                      createCookie("mediaDuration", mediaDuration, "1");
                  };    

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