<?php
  include("connection.php");
  session_start();

  if(!$_SESSION["username_s"]) {
    header("Location: index.php");
    exit;
  }

  $username = $_SESSION['username_s'];

  $work_id = $_GET["show"];
  $sql = "SELECT * FROM works_t A INNER JOIN artist_profile_t B ON A.artist_id = B.artist_id INNER JOIN user_profile_t C ON B.user_id = C.user_id WHERE A.id = '$work_id'";
  $query = mysqli_query($conn, $sql);
  $result = mysqli_fetch_array($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?php echo $result["title"]." by ". $result["first_name"]." ".$result["last_name"] ?></title>
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

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

   <!-- Style CSS -->
   <link rel="stylesheet" href="css/style-profile.css">

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.11.1/baguetteBox.min.css">
   <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>



</head>
<body style="background-color: #ffffff;">
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
                  $sql = "SELECT * FROM user_profile_t WHERE username = '$username'";
                  $query = mysqli_query($conn, $sql);
                  $result = mysqli_fetch_array($query);

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
      </div>
    </div>
</section>

  <?php
    $work_id = $_GET["show"];
    $sql = "SELECT * FROM works_t A INNER JOIN artist_profile_t B ON A.artist_id = B.artist_id INNER JOIN user_profile_t C ON B.user_id = C.user_id WHERE A.id = '$work_id'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_array($query);
  ?>

  <!-- ======= Profile Section ======= -->
  <div class="sidebar-2 pt-5 m-0">
    <div class="p-4 d-none d-sm-block">
      <!-- Profile Image -->
      <div class="card" style="border-radius: 10px; border-width: 0ch; background-color: #ffffff00;" >
        <div class="card-body p-4">
          <div class="d-flex text-black">
            <div class="flex-shrink-0">
              <a href="page-view-artist.php?artistID=<?php echo $result["artist_id"] ?>"> <!-- or page-artist-profile.php-->
                <?php if($result["profile_pic"] != NULL){
                echo "<img src='./assets/img/profile/".$result["profile_pic"]."' class='profile-pic-small'>";
              }
              else{
                echo "<img src='./assets/img/SVG/profile-icon.svg' class='profile-pic-small'>";
              }
              ?>
              </a>
            </div>
            <div class="flex-grow-1 ms-3 ">
              <h4 class="mb-1"><b><?php echo $result["first_name"]. " ". $result["last_name"] ?></b></h4>
              <p style="color: #2b2a2a; margin-bottom:2px;"><?php echo $result["expertise"] ?></p>
              <div class="profile-icons-2">
                  <a href="mailto:<?php echo $result["email"] ?>"><i class="bi bi-envelope"></i></a>
              </div>
            </div>
          </div>
          </div>
      </div>
      <!-- Description-->
      <div class="card" style=" margin-top: 10px; color: black;" >
          <div class="card-body p-4">
              <h3 style="margin: 0px 10px 10px 0px"><b><?php echo $result["title"] ?></b></h3>
              <hr class="style1">
              <h6 style="margin-bottom:5px; "><small>Description</small></h6>
              <p style="line-height: 18px;"><?php echo $result["description"]?></p>
        </div>
      </div>
      <!-- Tool-->
      <div class="card" style=" margin-top: 10px; padding-left: 8px;" >
          <div class="card-body">
              <h6 style="margin: 0px 0px 2px 0px"><b>Tags</b></h6>
              <p style="line-height: 18px; margin: 0px;"><?php echo $result["tags"]?> </p>
        </div>
      </div>
      <!-- Publish date-->
    </div>
    <div class="p-1 d-block d-sm-none">
      <!-- Profile Image -->
      <div class="card py-2" style="border-radius: 10px; border-width: 0ch; background-color: #ffffff00;" >
        <div class="card-body">
          <div class="d-flex text-black">
            <div class="flex-shrink-0">
              <a href="page-view-artist.php?artistID=<?php echo $result["artist_id"] ?>"> <!-- or page-artist-profile.php-->
                <?php if($result["profile_pic"] != NULL){
                echo "<img src='./assets/img/profile/".$result["profile_pic"]."' class='profile-pic-small-2'>";
              }
              else{
                echo "<img src='./assets/img/SVG/profile-icon.svg' class='profile-pic-small-2'>";
              }
              ?>
              </a>
            </div>
            <div class="flex-grow-1 ms-2 ">
              <h6 class="mb-0"><b><?php echo $result["title"] ?></b></h6>
              <h6 class='mb-0'><?php echo $result["first_name"]. " ". $result["last_name"] ?></h6>
            </div>
          </div>
          </div>
      </div>
    </div>
  </div>
  <div class="content-2 py-5 d-none d-sm-block" style=" background-color: #2b2a2a; color: #ffffff;">
    <div class="card pt-3" style="background-color: #2b2a2a; border-width: 0ch; padding-left: 8px;" >
      <div class="card-body">
          <h6 style="margin:0px 0px 2px 0px"><b>Publish Date</b></h6>
          <p style="line-height: 18px; margin: 0px;"><?php echo  date('M d, Y H:i', strtotime($result["upload_date"]))?></p>
    </div>
  </div>
    <div class="card" style="border-width: 0px; background-color: #2b2a2a;">
      <div class="gallery-columns">
        <?php
          if((strtolower($result["type"]) == 'jpg') || (strtolower($result["type"]) == 'jpeg') || (strtolower($result["type"]) == 'png') || (strtolower($result["type"]) == 'jfif') || (strtolower($result["type"]) == 'gif')){
            echo "
              <a href='./assets/img/works/".$result["work"]."' class='thumbnail'>
              <img src='./assets/img/works/".$result["work"]."' class='img-fluid' alt='Gallery Image'>
          </a>
            ";
          }
          else{
            echo "
                    <video controls>
                    <source src='./assets/img/works/".$result["work"]."' type='video/mp4'>
                  </video>";
          }
        ?>
      </div>
  </div>
  </div>
  <div class="content px-0 d-block d-sm-none" style=" background-color: #2b2a2a; color: #ffffff;">
    <div class="card pt-3" style="background-color: #2b2a2a; border-width: 0ch; padding-left: 8px;" >
      <div class="card-body p-2">
          <h6 style="margin-bottom:5px; "><b><small>Description</small></b></h6>
          <p class="m-0"style="line-height: 18px;"><?php echo $result["description"]?></p>
          <h6 style="margin:0px 0px 2px 0px"><b><small>Publish Date</small></b></h6>
          <p style="line-height: 18px; margin: 0px;"><?php echo  date('M d, Y H:i', strtotime($result["upload_date"]))?></p>
    </div>
  </div>
    <div class="card py-3" style="border-width: 0px; background-color: #2b2a2a;">
      <div class="gallery-columns">
        <?php
          if(($result["type"] == 'jpg') || ($result["type"] == 'jpeg') || ($result["type"] == 'png') || ($result["type"] == 'jfif')){
            echo "
              <a href='./assets/img/works/".$result["work"]."' class='thumbnail'>
              <img src='./assets/img/works/".$result["work"]."' class='img-fluid' alt='Gallery Image'>
          </a>
            ";
          }
          else{
            echo "
                    <video controls>
                    <source src='./assets/img/works/".$result["work"]."' type='video/mp4'>
                  </video>";
          }
        ?>
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
  


  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

      <!-- jQuery -->
      <script src="plugins/jquery/jquery.min.js"></script>
      <!-- Bootstrap 4 -->
      <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
      <!-- AdminLTE App -->
      <script src="dist/js/adminlte.min.js"></script>   

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

      <!-- Baguettebox JS for image popup -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.11.1/baguetteBox.min.js"></script>
    <script>
    // baguetteBox.run is predefined in Baguette Box JS
        baguetteBox.run('.gallery-columns');
    </script>
    <script>
      baguetteBox.run('.gallery', {
        captions: true,
        buttons: 'auto',
        fullScreen: false,
        noScrollbars: false,
        titleTag: true,
        async: false,
        preload: 2,
        animation: 'fadeIn',
        onChange: null,
        overlayBackgroundColor: 'rgba(113,117,115,0.8)'
    });
    </script>
</body>
</html>