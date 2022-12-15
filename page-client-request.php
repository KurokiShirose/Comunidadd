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

  $artistID = $_GET["artistID"];

  $username = $_SESSION['username_s'];

  $sql = "SELECT * FROM user_profile_t WHERE username = '$username'";
  $query = mysqli_query($conn, $sql);
  $result = mysqli_fetch_array($query);

  $sql_a = "SELECT * FROM user_profile_t A INNER JOIN artist_profile_t B ON A.user_id = B.user_id WHERE B.artist_id = '$artistID'";
  $query_a = mysqli_query($conn, $sql_a);
  $result_a = mysqli_fetch_array($query_a);

  if(isset($_POST["submitReq"])){
    $clientID = $result["user_id"];
    $artistID = $result_a["artist_id"];
    $reqType = $_POST["reqType"];
    $reqDesc = $_POST["reqDesc"];
    $status = "Pending";

    $_SESSION["request_name_s"] = $result_a["first_name"]." ".$result_a["last_name"];

    $sql_r = "INSERT INTO commission_t(client_id, artist_id, req_type, req_description, status) VALUES('$clientID', '$artistID', '$reqType', '$reqDesc', '$status')";
    $query = mysqli_query($conn, $sql_r);

    header("Location: page-thank-you.php");
    exit;
  }

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>COMUNIDAD | Client Request</title>
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

</head>

<body class="bg-light">

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

  <div class="container bg-white">
    <div class="py-5">
        <div class="row text-center">
          <i class="bi bi-clipboard2" style="font-size:xxx-large"></i>
          <h2><b> Commission Request Form</b></h2>
        </div>
        <div class="row py-3">
        <p> Please fill in your commission request details below, then click submit request and we'll notify our artist immediately. <br>We are looking forward to your transaction here in Comunidad.</p>
        </div>
        <hr class="style1 m-0">
    </div>
    
    <form action="page-client-request.php?artistID=<?php echo $artistID ?>" method="post">
        <div class="row">
          <div class="col-md-8 order-md-1">
            <div class="mb-3">
              <label for="firstname" class="lbl">First Name</label>
              <input type="text" name="fname" class="form-control" value="<?php echo $result_a["first_name"] ?>" readonly>
            </div>
            <div class="mb-3">
              <label for="lastname" class="lbl"> Last Name</label>
              <input type="text" name="lname" class="form-control" value="<?php echo $result_a["last_name"] ?>" readonly>            
            </div>
            <div class="mb-3">
              <label for="reqType" class="lbl">Request Type</label>
              <select name="reqType" class="form-control form-select" required>
                  <option class="hidden" value=""  selected disabled>Select</option>
                  <option value="Art & Illustration">Art & Illustration</option>
                  <option value="Logo & Brand Identity">Logo & Brand Identity</option>
                  <option value="Web & App Design">Web & App Design</option>
                  <option value="Video Editing">Video Editing</option>
                  <option value="Animation">Animation</option>
                  <option value="Visual Effects">Visual Effects</option>
                  <option value="Commercial">Commercial</option>
                  <option value="Events">Events</option>
                  <option value="Sports and Travel">Sports and Travel</option>
              </select>            
            </div>
          </div>
          <div class="col-md-4 order-md-2 mb-4">
            <div class="mb-3">
              <label class="form-label" for="request_description">Request Description<br><i>Includes technical details, proposed charge, due date, etc.</i></label>
              <textarea name="reqDesc" class="form-control" id="request_description" style="height:165px; resize: none ; margin-bottom: 20px;" maxlength="500" required></textarea>
              <button type="submit" name="submitReq" class="btn btn-custom-reg" style="float: right;">Submit Request</button>
            </div>
          </div>
        </div>
    </form>
  </div>



  <!-- ======= Footer ======= -->

  <div id="preloader"></div>

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

</body>

</html>