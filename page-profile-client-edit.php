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
  $userID = $_SESSION["user_id_s"];

  $sql = "SELECT * FROM user_profile_t WHERE username = '$username'";
  $query = mysqli_query($conn, $sql);
  $result = mysqli_fetch_array($query);

  if(isset($_POST["edit"])){
    $fname = validate($_POST["fname"], $conn);
    $lname = validate($_POST["lname"], $conn);
    $city_v = validate($_POST["city"], $conn);
    $province_v = validate($_POST["province"], $conn);
    $location = $city_v . ', ' . $province_v;
    $email = validate($_POST["email"], $conn);
    $contact = validate($_POST["mobile"], $conn);
    $about = validate($_POST["about"], $conn);

    if(strlen($location) < 4){
      $location = $result["location"];
    }

    $sql = "UPDATE user_profile_t SET first_name = '$fname', last_name = '$lname', location = '$location', email = '$email', contact = '$contact', about = '$about' WHERE username = '$username'";
    $query = mysqli_query($conn, $sql);

    $_SESSION["fname_s"] = $fname;
    $_SESSION["lname_s"] = $lname;

    $sql = "INSERT INTO activity_t(user_id, activity) VALUES (".$_SESSION['user_id_s'].", 'has edit profile info')";  
    mysqli_query($conn, $sql);

    header("Location: page-profile-client.php");
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

        $sql = "INSERT INTO activity_t(user_id, activity) VALUES (".$_SESSION['user_id_s'].", 'has edit profile info')";  
        mysqli_query($conn, $sql);

        header("Location: page-profile-client.php");
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
    $sql2 = "SELECT * FROM commission_t A INNER JOIN user_profile_t B ON A.client_id = B.user_id WHERE B.username = '$username' AND A.status != 'Complete'";
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

    $sql = "INSERT INTO activity_t(user_id, activity) VALUES (".$_SESSION['user_id_s'].", 'has edit profile info')";  
    mysqli_query($conn, $sql);

    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate( 'D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache'); 
    header("Location: page-profile-client-edit.php");
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

        <nav class="navbar navbar-expand-sm bg-faded first-nav navbar-light fixed-top px-4 py-2">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar1">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a href="index.php" class="logo-name"><h5 class="mb-0"><b>comunidad<span style="color:orange;">. </span></b></h5></a>
    
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
              <a href="page-browse.php" class="nav-item nav-link active">Browse</a>
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
        </div>
      </div>
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
          <form action="page-profile-client-edit.php" enctype="multipart/form-data" method="post">
            <div class="row text-center">
              <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                <div class="file-drop-area">
                  <span class="choose-file-button"><i class="bi bi-images" style="font-size:50px;"></i><br>Choose Photo</span>
                  <span class="file-message">or drag and drop files here</span>
                  <input type="file" name="image" class="file-input" accept=".jfif,.jpg,.jpeg,.png,.gif" multiple>
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
    <div class="row py-5">
      <div class="col-md-4 pt-5 px-3">
        <!-- Profile Image -->
        <div class="card card-outline">
          <div class="card-body box-profile">
            <div class="text-center">
            <div class="d-flex align-items-center justify-content-center">
              <form action="page-profile-client-edit.php" enctype="multipart/form-data" method="post">
                <div class="container-pic" style="margin: 0px 85px 0px 85px">
                <img class="image profile-pic" style="margin-top: 10px " src="
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
              </form>
            </div>
              <br>  
            </div>
            <h3 class="text-center" style="margin: 0px 0px 15px 0px;"><b>Edit Profile</b></h3>
            <form action="page-profile-client-edit.php" method="post">
                <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                    <label for="firstname" class="lbl">First Name</label>
                    <input type="text" name="fname" class="form-control" value="<?php echo $result["first_name"] ?>" maxlength="100" required/>
                </div>
                <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                    <label for="lastname" class="lbl">Last Name</label>
                    <input type="text" name="lname" class="form-control" value="<?php echo $result["last_name"] ?>" maxlength="100" required/>
                </div>
                <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                    <label for="location" class="lbl">Location</label>
                    <select id="province" name="province" class="form-control mb-2" required></select>
                    <select id="city" name="city" class="form-control" required></select>
                </div>
                <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                    <label for="links" class="lbl">About</label>
                    <textarea type="text" name="about" class="form-control" maxlength="300"><?php echo $result["about"] ?></textarea>
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
                    <a href="page-profile-client.php"><button type="button" class="btn btn-default" 
                        style="background-color: rgb(130, 38, 38); color: white;">Cancel</button></a>
                </div>
            </form>
          </div>
        </div>
      </div>

      <div class="col-md-8 pt-5 px-3">

        <div class="card" style="border-width: 0px; background-color: rgba(255, 255, 255, 0);">
          <div class="card-header card-header-bg-custom p-2" style="border-width: 0px; padding: 15px;"> 
            <ul class="nav nav-pills nav-pills-nbg">
              <li class="nav-item"><a class="nav-link disabled" href="#activity" data-toggle="tab">Activity</a></li>
              <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Account Settings</a></li>
            </ul>
            
          </div><!-- /.card-header -->
          <div class="card-body" style="border-width: 0px; padding: 5px 0px 0px 0px;">
            <div class="tab-content">
              <div class="tab-pane" id="activity">
                <section class="pt-1 pb-1 " style="padding: 0px;">
                    <div class="container">
                        <div class="row">
                            <div class="col-6">
                            <h3 class="mb-3" style="margin: 10px 0px 15px 5px;">Artists Hired</h3>
                            </div>
                            <div class="col-6" style="padding-left: 355px">
                                <a class="btn mb-3 mr-1" style="background-color: #447271; color: white;" href="#carouselExampleIndicators2" role="button" data-slide="prev">
                                    <i class="fa fa-arrow-left"></i></a>
                                <a class="btn mb-3 " style="background-color: #447271; color: white;" href="#carouselExampleIndicators2" role="button" data-slide="next">
                                <i class="fa fa-arrow-right"></i></a>
                            </div>
                            <div class="col-12">
                            <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <div class="row">
                                            <div class="col-sm-3 card-padding-hired">
                                                <div class="profile-card-2">
                                                    <img src="assets/img/taka.jpg" class="browse-size ">
                                                    <div class="profile-name">Takahiro Moriuchi</div>
                                                    <div class="profile-username">Rated 5 stars</div>
                                                    <div class="profile-icons">
                                                        <a href=""><i class="bi bi-twitter"></i></a>
                                                        <a href=""><i class="bi bi-facebook"></i></a>
                                                        <a href=""><i class="bi bi-instagram"></i></a>
                                                        <a href=""><i class="bi bi-envelope"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 card-padding-hired">
                                                <div class="profile-card-2">
                                                    <img src="assets/img/taka.jpg" class="browse-size ">
                                                    <div class="profile-name">Takahiro Moriuchi</div>
                                                    <div class="profile-username">Rated 5 stars</div>
                                                    <div class="profile-icons">
                                                        <a href=""><i class="bi bi-twitter"></i></a>
                                                        <a href=""><i class="bi bi-facebook"></i></a>
                                                        <a href=""><i class="bi bi-instagram"></i></a>
                                                        <a href=""><i class="bi bi-envelope"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 card-padding-hired">
                                                <div class="profile-card-2">
                                                    <img src="assets/img/taka.jpg" class="browse-size ">
                                                    <div class="profile-name">Takahiro Moriuchi</div>
                                                    <div class="profile-username">Rated 5 stars</div>
                                                    <div class="profile-icons">
                                                        <a href=""><i class="bi bi-twitter"></i></a>
                                                        <a href=""><i class="bi bi-facebook"></i></a>
                                                        <a href=""><i class="bi bi-instagram"></i></a>
                                                        <a href=""><i class="bi bi-envelope"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 card-padding-hired">
                                                <div class="profile-card-2">
                                                    <img src="assets/img/taka.jpg" class="browse-size ">
                                                    <div class="profile-name">Takahiro Moriuchi</div>
                                                    <div class="profile-username">Rated 5 stars</div>
                                                    <div class="profile-icons">
                                                        <a href=""><i class="bi bi-twitter"></i></a>
                                                        <a href=""><i class="bi bi-facebook"></i></a>
                                                        <a href=""><i class="bi bi-instagram"></i></a>
                                                        <a href=""><i class="bi bi-envelope"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <div class="row">
                                            <div class="col-sm-3 card-padding-hired">
                                                <div class="profile-card-2">
                                                    <img src="assets/img/taka.jpg" class="browse-size ">
                                                    <div class="profile-name">Takahiro Moriuchi</div>
                                                    <div class="profile-username">Rated 5 stars</div>
                                                    <div class="profile-icons">
                                                        <a href=""><i class="bi bi-twitter"></i></a>
                                                        <a href=""><i class="bi bi-facebook"></i></a>
                                                        <a href=""><i class="bi bi-instagram"></i></a>
                                                        <a href=""><i class="bi bi-envelope"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 card-padding-hired">
                                                <div class="profile-card-2">
                                                    <img src="assets/img/taka.jpg" class="browse-size ">
                                                    <div class="profile-name">Takahiro Moriuchi</div>
                                                    <div class="profile-username">Rated 5 stars</div>
                                                    <div class="profile-icons">
                                                        <a href=""><i class="bi bi-twitter"></i></a>
                                                        <a href=""><i class="bi bi-facebook"></i></a>
                                                        <a href=""><i class="bi bi-instagram"></i></a>
                                                        <a href=""><i class="bi bi-envelope"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 card-padding-hired">
                                                <div class="profile-card-2">
                                                    <img src="assets/img/taka.jpg" class="browse-size ">
                                                    <div class="profile-name">Takahiro Moriuchi</div>
                                                    <div class="profile-username">Rated 5 stars</div>
                                                    <div class="profile-icons">
                                                        <a href=""><i class="bi bi-twitter"></i></a>
                                                        <a href=""><i class="bi bi-facebook"></i></a>
                                                        <a href=""><i class="bi bi-instagram"></i></a>
                                                        <a href=""><i class="bi bi-envelope"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 card-padding-hired">
                                                <div class="profile-card-2">
                                                    <img src="assets/img/taka.jpg" class="browse-size ">
                                                    <div class="profile-name">Takahiro Moriuchi</div>
                                                    <div class="profile-username">Rated 5 stars</div>
                                                    <div class="profile-icons">
                                                        <a href=""><i class="bi bi-twitter"></i></a>
                                                        <a href=""><i class="bi bi-facebook"></i></a>
                                                        <a href=""><i class="bi bi-instagram"></i></a>
                                                        <a href=""><i class="bi bi-envelope"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div> <a href="" style="color: black;"><p>See Full List</p></a></div>
                            </div>
                            </div>
                        </div>
                    </div>
                </section>
                <div class="card">
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
              </div>
              <div class="active tab-pane" id="settings">
                <div class="card">
                    <div class="card-body">
                        <h3 style="margin: 0px 0px 15px 0px;"><b>Manage Log-in</b></h3>
                        <form action="page-profile-client-edit.php" method="post">
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
          
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- ======= Footer ======= -->
  <footer class="footer-custom fixed-bottom" style="height:35px; padding:0px 10px;">
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

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>   

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