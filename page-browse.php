<!--

to get verified (aside sa id):
  (artist)
  - maka-post ng 3 photos
  - may profile pic

  (client)
  - may profile pic
  - complete details

perks of not being verified:
  (artist)
  - limited to 3 posts only
  - di makakalagay ng contact details?

  (client)
  - di makikita contact details ng artist
  - may min number of views ng profile (5?)


limit video length to 45secs, 25mb
limit image 5mb

-->




<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  
  <title>Browse Work Categories</title>
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

<?php
  include("connection.php");
  session_start();

  if(!$_SESSION["username_s"]) {
    header("Location: index.php");
    exit;
  }

  $username = $_SESSION['username_s'];

  $sql = "SELECT * FROM user_profile_t WHERE username = '$username'";
  $query = mysqli_query($conn, $sql);
  $result = mysqli_fetch_array($query);

  if(isset($_POST["post"])){

    if($_SESSION["verified_s"] != 1){
      $id = $_SESSION["artist_id_s"];
      
      $sql_check = "SELECT COUNT(*) FROM works_t WHERE artist_id = '$id' AND status = 'Live'";
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

  
?>


<body style="background-color: #fcfcfc">

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
              if($_SESSION["user_type_s"] == "artist"){
                echo "<li class='nav-item px-2'>
                <a href='#uploadFile' style='color: #F69312' data-toggle='modal' data-target='#uploadFile' class='nav-tem nav-link'><b><i style='font-size: 1rem;' class='fa fa-plus-circle pe-1'></i> POST</b></a>
                </li>";}
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
  <nav class="navbar navbar-expand-sm navbar-light sticky-top second-nav py-2 px-4">
    <div class="container-fluid p-0 d-flex align-items-center justify-content-center">
      <div class="navbar-nav d-flex align-items-center justify-content-between px-2">
        <div class="row">
        <div class="d-block d-sm-none py-2 dropdown show">
          <a class="btn btn-light dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class='fa fa-cogs'></i> Creative Fields</a>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="#">Events</a>
            <a class="dropdown-item" href="#">Commercial</a>
            <a class="dropdown-item" href="#">Sports and Travel</a>
            <a class="dropdown-item" href="#">Video Editing</a>
            <a class="dropdown-item" href="#">Animation</a>
            <a class="dropdown-item" href="#">visual Effects</a>
            <a class="dropdown-item" href="#">Logo and Branding</a>
            <a class="dropdown-item" href="#">Layouting</a>
            <a class="dropdown-item" href="#">Web Design</a>
          </div>
          <div class="btn-group" role="group" aria-label="Categories">
          <a type="button" href="page-search.php?search=Photography" class="btn btn-category"><i class='fa fa-camera'></i></a>
          <a type="button" href="page-search.php?search=Graphics & Design" class="btn btn-category"><i class="fa fa-paint-brush"></i></a>
          <a type="button" href="page-search.php?search=Video & Animation" class="btn btn-category"><i class='fa fa-video-camera'></i></a>
        </div>
        </div>
        </div>

        <a class="px-2 d-flex align-items-center justify-content-between d-none d-sm-block"><h5><strong>Creative Fields</strong></h5></a>
        <div class="d-none d-sm-block px-2 dropdown show">
          <a class="btn btn-light dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class='fa fa-camera'></i> Photography</a>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="page-search.php?search=Events">Events</a>
            <a class="dropdown-item" href="page-search.php?search=Commercial">Commercial</a>
            <a class="dropdown-item" href="page-search.php?search=Sports and Travel">Sports and Travel</a>
          </div>
        </div>
        <div class="d-none d-sm-block px-2 dropdown show">
          <a class="btn btn-light dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class='fa fa-video-camera'></i> Video and Animation</a>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="page-search.php?search=Video Editing">Video Editing</a>
            <a class="dropdown-item" href="page-search.php?search=Animation">Animation</a>
            <a class="dropdown-item" href="page-search.php?search=Visual Effects">Visual Effects</a>
          </div>
        </div>
        <div class="d-none d-sm-block px-2 dropdown show">
          <a class="btn btn-light dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-paint-brush"></i> Graphics and Design</a>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="page-search.php?search=Logo and Branding">Logo and Branding</a>
            <a class="dropdown-item" href="page-search.php?search=Layouting">Layouting</a>
            <a class="dropdown-item" href="page-search.php?search=Web Design">Web Design</a>
          </div>
        </div>
    </div>
    <div class="navbar-nav ms-auto d-flex align-items-center justify-content-between px-2">
      
    <a class="px-2 d-none d-sm-block"><h6><small>Filter</small></h6></a>
        <div class="d-none d-sm-block px-2 dropdown show">
          <a class="btn btn-light dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class='fa fa-video-camera'></i>  Budget</a>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="page-find-artist.php?max=1000">Below P1000</a>
            <a class="dropdown-item" href="page-find-artist.php?min=1001&max=5000">P1001 - P5000</a>
            <a class="dropdown-item" href="page-find-artist.php?min=5001&max=10000">P5001 - P10000</a>
            <a class="dropdown-item" href="page-find-artist.php?min=10001&max=15000">P10001 - P15000</a>
            <a class="dropdown-item" href="page-find-artist.php?min=15001&max=20000">P15001 - P20000</a>
            <a class="dropdown-item" href="page-find-artist.php?min=20000">Above P20000</a>
          </div>
        </div>
        <a class='d-none d-sm-block nav-item nav-link'><button class="btn btn-light" style="vertical-align:middle" data-toggle="modal" data-target="#findArtistLocModal" ><i class="bi bi-geo-alt-fill"></i> Location</button></a>
      </div>
    </div>
  </nav>

    <div class="container-fluid " id="browsing" >  <!-- ni-remove ko muna yung class d-md-flex h-md-100 -->
      <div class="row justify-content-start p-3">
            <?php
                    $sql = "SELECT * FROM works_t A INNER JOIN artist_profile_t B ON A.artist_id = B.artist_id INNER JOIN user_profile_t C ON B.user_id = C.user_id WHERE A.status = 'Live' AND C.user_type != 'deleted' ORDER BY RAND() LIMIT 40";
                    $query = mysqli_query($conn, $sql);
                    $count = mysqli_num_rows($query);

                    if($count > 0){
                      while($row_data = mysqli_fetch_array($query)){
                        echo "
                        <div class='col-sm-3 card-padding-hired'>
                          <div class='browse-card browse-size'>";
                          
                          if((strtolower($row_data["type"]) == 'jpg') || (strtolower($row_data["type"]) == 'jpeg') || (strtolower($row_data["type"]) == 'png') || (strtolower($row_data["type"]) == 'jfif') || (strtolower($row_data["type"]) == 'gif')){
                            echo "
                              <a href='page-view-work.php?show=".$row_data[0]."'><img src='./assets/img/works/". $row_data['work'] ."' class='img-fluid portfolio' alt='Portfolio output'/>
                            ";

                          }
                          else{
                            echo "<a href='page-view-work.php?show=".$row_data[0]."'>
                                    <video width='300' height='325'>
                                      <source src='./assets/img/works/". $row_data['work'] ."' type='video/mp4'>
                                    </video>";
                          }
                            echo "
                            <div class='card-body card-body-custom'>
                              <h5 class='card-title card-title-custom'>".$row_data["title"]."</h5>
                              <p class='card-text-custom' style='text-align: left; margin:0px;'>".$row_data["first_name"]." ".$row_data["last_name"]."</p>
                              <div class='browse-icon'>
                                <button type='button' class='btn-none' data-toggle='modal' data-target='#flagFile-".$row_data["id"]."'>
                                  <a><i class='bi-flag-fill'></i></a>
                                </button>
                              </div>
                              <div class='card-footer-custom'>
                              <small class='text-muted'>".$row_data["tags"]."</small>
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
                                  <button type='button' class='btn btn-danger' data-dismiss='modal'>Cancel</button>
                                  <button type='submit' name='submitFlag' class='btn btn-custom-reg'>Save</button>
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
                    

                  ?>
      </div>
        <!-- Modal popup warning for unverified accounts -->
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
  ?>
    </div> 



  <div class="modal fade" id="uploadFile" role="dialog">
    <div class="modal-dialog" style="width:fit-content">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Upload File</h4>
        </div>
        <div class="modal-body" style="width:fit-content">
          <form action="page-browse.php" enctype="multipart/form-data" method="post">
            <div class="row text-center">
              <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                <div class="file-drop-area" style="width: 100%;">
                  <span class="choose-file-button"><i class="bi bi-files" style="font-size:50px;"></i><br>Choose Files</span>
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
                <label for="tag" class="lbl">Tag</label>
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
                onclick='return confirm(`Are you sure?`);' value="Save"/>
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

  <div class="modal fade" id="findArtistLocModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="page-find-artist.php" action="get">
          <div class="modal-header">
            <h4 class="modal-title">Enter Location</h4>
            <button type="button" class="close" data-dismiss="modal" onclick="$('#findArtistLocModal').modal('toggle');">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <input type="text" class="form-control" name="location" placeholder="Ex. Quezon City" required>
            </div>
          </div>
          <div class="modal-footer">
            <input type="submit" class="btn save-btn text-center" value="Search"/>
          </div>
        </form>
      </div>
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


  <script src="assets/vendor/aos/aos.js"></script>
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
  <script>
	  $(document).ready(function(){
		  $("#myModal").modal('show');
	  }); 
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