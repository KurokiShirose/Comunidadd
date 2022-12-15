<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
      
        <title>Commission Completion Form</title>
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
        <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
        <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
      
        <!-- Template Main CSS File -->
        <link href="assets/css/style.css" rel="stylesheet">
        <link href="assets/css/style-payment.css" rel="stylesheet">
      
        <!-- Style CSS -->
        <link rel="stylesheet" href="css/style-profile.css">
      
        <!-- jQuery -->
        <script src="plugins/jquery/jquery.min.js"></script>

        <!-- BS Stepper -->
        <link rel="stylesheet" href="plugins/bs-stepper/css/bs-stepper.min.css">
      
      </head>

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

  unset($_SESSION["filename_s"]);
  unset($_SESSION["complete_s"]);

  $username = $_SESSION['username_s'];
  $id = $_SESSION["commission_id_s"];

  $sql = "SELECT * FROM user_profile_t A INNER JOIN commission_t B ON A.user_id = B.client_id WHERE B.req_id = '$id'";
  $query = mysqli_query($conn, $sql);
  $result = mysqli_fetch_array($query);

  if(isset($_POST["submit"])){
    $description = $_POST["description"];

    if($_FILES['image']['size'] != 0){
      date_default_timezone_set("Asia/Manila");
      $filename = $username."-commission-#".$id."-".date("Y-m-d H-i");
      $tempname = $_FILES["image"]["tmp_name"];
      $folder = "./assets/img/works/".$filename;

      $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
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
        move_uploaded_file($tempname, $folder.".".$ext);  // move the uploaded image into the folder

        $filename = $filename.".".$ext;
        $sql = "INSERT INTO completed_works_t(req_id, work, description) VALUES('$id', '$filename', '$description')";
        $query = mysqli_query($conn, $sql);

        $flag = 0;
      }
    }
    else{
      $sql = "INSERT INTO completed_works_t(req_id, description) VALUES('$id', '$description')";
      $query = mysqli_query($conn, $sql);
    }


    $tabs = $_POST["tabs"];

    if($tabs == "Online Payment"){
      $paymentMethod = $_POST["paymentMethod"];
      $serviceProvider = $_POST["serviceProvider"];
      $accNumber = $_POST["accNumber"];
      $accName = $_POST["accName"];
      $onlineDetails = $_POST["onlineDetails"];

      $sql = "INSERT INTO payment_t(req_id, payment_option, payment_method, service_provider, acc_number, acc_name, online_details) VALUES('$id', '$tabs', '$paymentMethod', '$serviceProvider', '$accNumber', '$accName', '$onlineDetails')";
      $query = mysqli_query($conn, $sql);

    }else{
      $meetLocation = $_POST["meetLocation"];
      $meetDate = $_POST["meetDate"];
      $meetDetails = $_POST["meetDetails"];

      $sql = "INSERT INTO payment_t(req_id, payment_option, meet_location, meet_date, meet_details) VALUES('$id', '$tabs', '$meetLocation', '$meetDate', '$meetDetails')";
      $query = mysqli_query($conn, $sql);
    }
    
    header("Location: email-payment.php");
    exit;
  }
?>

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
  
  <div class="container p-0 overflow-hidden">
    <div class="card p-4" style="border-width: 0px; background-color: rgba(255, 255, 255, 0); margin-top:10px;">
    <form action="">
        <h4><b>Meet-Up</b></h4>
        <div class="form-group form-group-custom">
                <input type="text" name="meetLocation" class="form-control" placeholder="Location" value="" />
            </div>
            <div class="form-group form-group-custom">
                <p class="m-0 py-1">Date</p>
                <input type="datetime-local" name="meetDate" class="form-control">
            </div>
            <div class="form-group form-group-custom pb-3">
                <textarea name="meetDetails" placeholder="More Details" class="form-control" id="comment" style="background-color: rgb(244, 246, 246); height:150px; resize: none ;"></textarea>
            </div>
            <button type="submit" name="submit" class="btn" style="background-color: #203C3B; color:white; float:right;">Submit</button>
    </form>
  </div>
  

  <div id="preloader"></div>


   <!-- Template Main JS File -->
   <script src="assets/js/main.js"></script>

   <!-- Bootstrap 4 -->
   <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
   <!-- AdminLTE App -->
   <script src="dist/js/adminlte.min.js"></script>   
      <!-- BS-Stepper -->
  <script src="plugins/bs-stepper/js/bs-stepper.min.js"></script>
   <script>
    
      // BS-Stepper Init
  document.addEventListener('DOMContentLoaded', function () {
    window.stepper = new Stepper(document.querySelector('.bs-stepper'))
  })
</script>
   <script>
    $(document).ready(function(){
		  $("#uploadBigModal").modal('show');
	  }); 
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