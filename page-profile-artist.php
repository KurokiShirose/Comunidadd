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

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>

</head>

<?php
  include("connection.php");
  session_start();

  error_reporting(0);

  if(!$_SESSION["username_s"]) {
    header("Location: index.php");
    exit;
  }

  $username = $_SESSION['username_s'];
  $userID = $_SESSION["user_id_s"];
  $artistID = $_SESSION["artist_id_s"];

  $sql = "SELECT * FROM user_profile_t A INNER JOIN artist_profile_t B ON A.user_id = B.user_id WHERE A.username = '$username'";
  $query = mysqli_query($conn, $sql);
  $result = mysqli_fetch_array($query);

  if(isset($_POST["post"])){

    if($_SESSION["verified_s"] != 1){
      $id = $_SESSION["artist_id_s"];
      
      $sql_check = "SELECT COUNT(*) FROM works_t WHERE artist_id = '$id'";
      $query_check = mysqli_query($conn, $sql_check);
      $result_check = mysqli_fetch_array($query_check);

      if($result_check[0] == 3){
        header("Location: page-verified-only.php");
        exit;
      }
      
    }

    $title = $_POST["title"];
    $description = $_POST["description"];
    $tags = $_POST["tags"];

    $filename = $username."-".$title;
    $tempname = $_FILES["image"]["tmp_name"];
    $folder = "./assets/img/works/".$filename;

    $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
    $ext = strtolower($ext);
    $flag = 0;
    
    if(($ext == 'jpg') || ($ext == 'jpeg') || ($ext == 'png') || ($ext == 'jfif') || ($ext == 'gif')){
      if(filesize($tempname) > 5242880) {  // 5mb
        echo "<script type='text/javascript'>
                  $(document).ready(function(){
                      $('#uploadErrorModal').modal('toggle');
                  });
                  </script>";
      }
      else{
        $flag = 1;
      }
    }
    else{
      $mediaDuration = $_COOKIE["mediaDuration"];

      if(($mediaDuration > 31) || (filesize($tempname) > 5242880)){
        echo "<script type='text/javascript'>
                $(document).ready(function(){
                    $('#uploadErrorModal').modal('toggle');
                });
                </script>";
      }
      else{
        $flag = 1;
      }
    }

    if($flag == 1){
      $id = $_SESSION["artist_id_s"];

      $sql = "SELECT * FROM works_t WHERE title = '$title' AND artist_id = '$id' and status = 'Live'";
      $query = mysqli_query($conn, $sql);
      $count = mysqli_num_rows($query);

      if($count > 0){
        echo "<script type='text/javascript'>
                $(document).ready(function(){
                    $('#sameTitleModal').modal('toggle');
                });
                </script>";
      }
      else{
        $sql = "INSERT INTO works_t(artist_id, work, type, title, description, tags) VALUES ('$id', '$filename', '$ext', '$title', '$description', '$tags')";
        $query = mysqli_query($conn, $sql);

        move_uploaded_file($tempname, $folder);  // move the uploaded image into the folder

        if($ext == "jpg" || $ext == "jpeg" || $ext == "png"){
          $image_info = getimagesize($folder);

          $watermark_raw = imagecreatefrompng("./assets/img/watermark.png");

          $watermark = imagecreatetruecolor($image_info[0] * 0.25, $image_info[1] * 0.07);
          imagealphablending($watermark, false);
          imagesavealpha($watermark, true);
          imagecopyresampled($watermark, $watermark_raw, 0, 0, 0, 0, $image_info[0] * 0.25, $image_info[1] * 0.07, imagesx($watermark_raw),imagesy($watermark_raw));


          if($ext == "jpg" || $ext == "jpeg"){
            $img = imagecreatefromjpeg($folder);
          }
          elseif($ext == "png"){
            $img = imagecreatefrompng($folder);
          }

          $marginRight = 20;
          $marginBottom = 20;
          $sx = imagesx($watermark);
          $sy = imagesy($watermark);

          imagecopy($img, $watermark, imagesx($img) - $sx - $marginRight, imagesy($img) - $sy - $marginBottom, 0, 0, $sx, $sy);

          $font = "./assets/font/BLMelody-Regular.otf";
          $name = "© ".$result["first_name"]." ".$result["last_name"];

          $width = imagesx($img);
          $height = imagesy($img);

          $textSize = imagettfbbox(20, 0, $font, $name);
          $textWidth = abs($textSize[2]) - abs($textSize[0]);
          $textHeight = abs($textSize[5]) - abs($textSize[3]);

          $centerX = ($width - $textWidth) / 2;
          $centerY = ($height - $textHeight) / 2;

          imagettftext($img, 20, 0, $centerX, $centerY, imagecolorallocate($img, 255, 255, 255), $font, $name);
          
          if($ext == "jpg" || $ext == "jpeg"){
            imagejpeg($img, $folder);
          }
          elseif($ext == "png"){
            imagepng($img, $folder);
          }
        }
        
        $flag = 0;

        $sql = "INSERT INTO activity_t(user_id, activity) VALUES (".$_SESSION['user_id_s'].", 'has posted ".$title."')";  
        mysqli_query($conn, $sql);

        header("Refresh:0");
      }
    }
  }

  if(isset($_POST["editBtn"])){
    $editID = $_POST["editWorkID"];
    $editTitle = $_POST["editTitle"];
    $editDesc = $_POST["editDesc"];
    $editTags = $_POST["editTags"];

    $sql = "UPDATE works_t SET title = '$editTitle', description = '$editDesc', tags = '$editTags' WHERE id = '$editID'";
    $query = mysqli_query($conn, $sql);

    $sql = "INSERT INTO activity_t(user_id, activity) VALUES (".$_SESSION['user_id_s'].", 'has edited ".$editTitle."')";  
    mysqli_query($conn, $sql);

    header("Refresh:0");
  }
  if(isset($_POST["deleteBtn"])){
    $deleteID = $_POST["deleteWorkID"];

    $sql = "SELECT * FROM works_t WHERE id = '$deleteID'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_array($query);

    $sql = "INSERT INTO removed_works_t(work_id, artist_id, remarks) VALUES ('$result[0]', '$result[1]', 'Deleted')";
    mysqli_query($conn, $sql);

    $sql = "UPDATE works_t SET status = 'Removed' WHERE id = '$deleteID'";
    mysqli_query($conn, $sql);

    $sql = "INSERT INTO activity_t(user_id, activity) VALUES (".$_SESSION['user_id_s'].", 'has removed ".$result[4]."')";  
    mysqli_query($conn, $sql);

    header("Refresh:0");
  }

  
?>

<body style="background-color: #f9f9f9;">

<div class="modal fade" id="uploadFile" role="dialog">
    <div class="modal-dialog" style="width:fit-content">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Upload File</h4>
        </div>
        <div class="modal-body" style="width:fit-content">
          <form action="page-profile-artist.php" enctype="multipart/form-data" method="post">
            <div class="row text-center">
              <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                <div class="file-drop-area" style="width: 100%;">
                  <span class="choose-file-button"><i class="bi bi-files" style="font-size:50px;"></i><br>Choose File</span>
                  <span class="file-message">Or drag and drop files here<br>Note: Images should be below 5mb in size. <br>Max. media length is 30 seconds.</span>
                  <input type="file" name="image" class="file-input" accept=".gif,.jfif,.jpg,.jpeg,.png,.mp4" multiple required>
                </div>
                <div id="divImageMediaPreview">
                </div>
              </div>
            </div>
            <div class="row" style="padding: 10px 10px 20px 10px;">
              <h3 class="mb-2" style="margin: auto;">Details</h3>
              <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                <label for="title" class="lbl">Title</label>
                <input type="text" name="title" class="form-control" placeholder="" value="" maxlength="100" required>
              </div>
              <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                <label for="description" class="lbl">Description</label>
                <textarea type="text" name="description" class="form-control" maxlength="300" placeholder="What is it all about? What tools did you use?" value="" required></textarea>
              </div>
              <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                <label for="tool" class="lbl">Tags</label>
                <select name="tags" class="form-control" required>
                  <option class="hidden"  selected disabled>Select</option>
                  <option value="Photography">Photography</option>
                  <option value="Graphics & Design">Graphics & Design</option>
                  <option value="Video & Animation">Video & Animation</option>
                </select>
              </div>
            </div>
            <br>
            <div>
                <input type="submit" name="post" class="btn save-btn text-center" 
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

  <div class="modal fade" id="uploadErrorModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Upload Failed</h4>
          <button type="button" class="close" data-dismiss="modal" onclick="$('#uploadErrorModal').modal('toggle');">&times;</button>
        </div>
        <div class="modal-body">
          File too big. Use files below 5mb in size. For videos, maximum of 30 seconds media duration is allowed.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="$('#uploadErrorModal').modal('toggle');">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="sameTitleModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Upload Failed</h4>
          <button type="button" class="close" data-dismiss="modal" onclick="$('#sameTitleModal').modal('toggle');">&times;</button>
        </div>
        <div class="modal-body">
          You have a previous work with the same title.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="$('#sameTitleModal').modal('toggle');">Close</button>
        </div>
      </div>
    </div>
  </div>

  
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
            echo "<div class='img' style='
                  background-image: linear-gradient(to bottom, rgba(245, 246, 252, 0.255), rgba(6, 6, 6, 0.73)),url(./assets/img/profile/". $pfp .");
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
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-4 d-flex justify-content-center">
        <!-- Profile Image -->
        <div class="card card-outline" style="margin-top:-50px; width:90%;">
          <div class="card-body box-profile" style="padding:0px;">
            <div class="text-center">
              <img class="profile-pic" style="margin-top: -80px;" src="
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
              <div class="text-center">
                <button type="button" class="btn btn-modal" data-toggle="modal" data-target="#uploadFile">
                  <i class="fa fa-circle-plus"></i>
                </button>
                <a href="page-profile-artist-edit.php"><img src="assets/img/SVG/edit-profile.svg" class="prof-btn"></a>
                
              </div>
            </div>
            <h1 class="profile-name-format"><?php echo $_SESSION["fname_s"]. " ". $_SESSION["lname_s"] ?></h1>
            <p class="card-text-custom-1">Artist ID: #<?php echo $result["artist_id"] ?></p>
            <p class="card-text-custom-1"><?php echo $result["expertise"] ?></p>
            <p class="card-text-custom-1"><img src="assets/img/SVG/location.svg" class="icon-size">
              <b><?php echo $result["location"] ?></b></p>
            <h5 class="card-text-custom-1 margb-5"> <b>Joined <?php echo date("F Y", strtotime($result["date_joined"])) ?></b></h5>
            <h1 class="profile-name-format bolder">₱ <?php echo $result["min_charge"] ? $result["min_charge"]:"0.00" ?>-<?php echo $result["max_charge"] ? $result["max_charge"]:"0.00" ?></h1>
            <p class="card-text card-text-custom">Charge</p>
            <?php
              $artistID = $result["artist_id"];
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
            <p class="card-text-custom-2"><?php echo $result["about"] ?></p>
            <h5 class="card-text-custom-2"> <b>CONTACT & LINKS</b></h5>
            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item">
                <b>Email Address:</b> <a class="float-right"><?php echo $result["email"] ?></a>
              </li>
              <li class="list-group-item">
                <b>Contact Number:</b> <a class="float-right"><?php echo $result["contact"] ?></a>
              </li>
            </ul>
            <!--<div class="text-center">
              <span><img src="assets/img/SVG/new-email.svg" class="icon-size-2"></span>
              <span><img src="assets/img/SVG/mobile.svg" class="icon-size-2"></span>
              <span><img src="assets/img/SVG/facebook.svg" class="icon-size-2"></span>
              <span><img src="assets/img/SVG/twitter.svg" class="icon-size-2"></span>
              <span><img src="assets/img/SVG/instagram.svg" class="icon-size-2"></span>
            </div>
            <br>-->
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <div class="card" style="border-width: 0px; background-color: rgba(255, 255, 255, 0); margin-top:10px;">
          <div class="card-header card-header-bg-custom p-2" style="border-width: 0px; padding: 15px;"> 
            <ul class="nav nav-pills nav-pills-nbg">
              <li class="nav-item"><a class="nav-link active" href="#portfolio" data-toggle="tab">Portfolio</a></li>
              <li class="nav-item"><a class="nav-link" href="page-dashboard-artist.php">Dashboard</a></li>
            </ul>
          </div><!-- /.card-header -->
          <div class="card-body" style="border-width: 0px;">
            <div class="tab-content">
              <div class="active tab-pane" id="portfolio">
              <div class="row justify-content-start">
            <?php
                    $id = $result['artist_id'];
                    $sql = "SELECT * FROM works_t WHERE status = 'Live' AND artist_id = '$id'";
                    $query = mysqli_query($conn, $sql);
                    $count = mysqli_num_rows($query);

                    if($count > 0){
                      while($row_data = mysqli_fetch_array($query)){
                        echo "
                        <div class='col-sm-4 '>
                          <div class='profile-card-3'>";
                          
                          if((strtolower($row_data["type"]) == 'jpg') || (strtolower($row_data["type"]) == 'jpeg') || (strtolower($row_data["type"]) == 'png') || (strtolower($row_data["type"]) == 'jfif') || (strtolower($row_data["type"]) == 'gif')){
                            echo "
                              <a href='page-view-work.php?show=".$row_data[0]."'><img src='./assets/img/works/". $row_data['work'] ."' class='img-fluid portfolio' alt='Portfolio output'/>
                            ";

                          }
                          else{
                            echo "<a href='page-view-work.php?show=".$row_data[0]."'>
                                    <video class='video-2'>
                                      <source src='./assets/img/works/". $row_data['work'] ."' type='video/mp4'>
                                    </video>";
                          }
                            echo "
                            <div class='card-body card-body-custom'>
                              <h5 class='card-title card-title-custom'>".$row_data["title"]."</h5>
                              <div class='profile-icons-3'>
                                <button type='button' class='btn-none' data-toggle='modal' data-target='#editFile-".$row_data["id"]."'>
                                  <a><i class='bi-pencil-square'></i></a>
                                </button>
                                <button type='button' class='btn-none' data-toggle='modal' data-target='#deleteFile-".$row_data["id"]."'>
                                  <a><i class='bi-trash3-fill'></i></a>
                                </button>
                              </div>
                              <div class='card-footer-custom'>
                                <small class='text-muted'>".date('M d, Y H:i', strtotime($row_data['upload_date']))."</small>
                              </div>
                            </div>
                          </div>
                        </div>
                        ";

                        echo "
                          <div class='modal fade' id='editFile-".$row_data["id"]."'>
                            <div class='modal-dialog'>
                              <div class='modal-content'>
                                <form action='page-profile-artist.php' method='post'>
                                  <div class='modal-body'>
                                    <div class='row' style='padding: 10px 10px 20px 10px;'>
                                      <h3 class='mb-2' style='margin: auto;'>Details</h3>
                                      <div class='form-group form-group-custom' style='margin-bottom: 10px;'>
                                        <input name='editWorkID' value='".$row_data["id"]."' hidden>
                                        <label for='title' class='lbl'>Title</label>
                                        <input type='text' name='editTitle' class='form-control' maxlength='100' placeholder='' value='".$row_data["title"]."' >
                                      </div>
                                      <div class='form-group form-group-custom' style='margin-bottom: 10px;'>
                                        <label for='description' class='lbl'>Description</label>
                                        <textarea type='text' name='editDesc' class='form-control' maxlength='300' placeholder='What is it all about? What tools did you use?'>".$row_data["description"]."</textarea>
                                      </div>
                                      <div class='form-group form-group-custom' style='margin-bottom: 10px;'>
                                        <label for='tool' class='lbl'>Tags</label>
                                        <select name='editTags' class='form-control' required>
                                          <option value='Photography'". ($row_data["tags"] == "Photography" ? "selected" : "").">Photography</option>
                                          <option value='Graphics & Design' ". ($row_data["tags"] == "Graphics & Design" ? "selected" : "").">Graphics & Design</option>
                                          <option value='Video & Animation' ". ($row_data["tags"] == "Video & Animation" ? "selected" : "").">Video & Animation</option>
                                        </select>
                                      </div>
                                    </div>
                                  </div>
                                  <div class='modal-footer'>
                                    <button type='button' class='btn btn-danger' data-dismiss='modal'>Cancel</button>
                                    <button type='submit' name='editBtn' class='btn btn-custom-reg' onclick='return confirm(`Are you sure?`);'>Save</button>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>


                          <div class='modal fade' id='deleteFile-".$row_data["id"]."'>
                            <div class='modal-dialog'>
                              <div class='modal-content'>
                                <form action='page-profile-artist.php' method='post'>
                                  <div class='modal-body'>
                                    <p class='text-center'>Are you sure you want to delete ".$row_data["title"]."?</p>
                                    <input name='deleteWorkID' value='".$row_data["id"]."' hidden>
                                  </div>
                                  <div class='modal-footer'>
                                    <button type='button' class='btn btn-danger' data-dismiss='modal'>No</button>
                                    <button type='submit' name='deleteBtn' class='btn btn-custom-reg'>Yes</button>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>
                        ";
                      }
                    }
                    else{
                      echo "
                        <div class='col-sm-12 m-4 text-center text-secondary'>
                          <h4>Uh oh! Nothing to see here.</h4>
                        </div>
                        ";
                    }

                    echo "
                       </div>
                       </div>
                    ";

                  ?>
                  
                  <h4>Past Commissions</h4>
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
            <!-- /.tab-content -->
          </div><!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>


  


  <!-- ======= modal ======= -->
  

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

  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>   

  </script>
  <script>
    window.onscroll = function() {scrollFunction()};
  
    function scrollFunction() {
      if (document.body.scrollTop > 40 || document.documentElement.scrollTop > 40) {
        
        document.getElementById("first-nav-2").style.background = "#203c3b";
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
                  img.attr("style", "width: 75px; height:auto;padding: 10px");
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