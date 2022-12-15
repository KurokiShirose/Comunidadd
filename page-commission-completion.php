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

  <div class="container pb-5 bg-white">
    <div class="p-5">
        <div class="row text-center">
          <i class="bi bi-envelope-paper-fill mb-3" style="font-size:xxx-large; color: orange;"></i>
          <h2><b>Commission Completion Form</b></h2>
        </div>
        <div class="row py-3">
          <p>Upload your completed work here to update your client on the completion of their commission request.</p>
          <p>Note: It's advisable to use storage services like <a href="https://www.google.com/drive/"><b>Google Drive</b></a> and include the link here if file exceeds max. size/duration limit.</p>
        </div>
        <hr class="style1 m-0">
    </div>
    <div class="">
      <div class="bs-stepper">
        <div class="bs-stepper-header" role="tablist">
          <div class="step" data-target="#commission-part">
            <button type="button" class="step-trigger" role="tab" aria-controls="commission-part" id="commission-part-trigger">
              <span class="bs-stepper-circle" style="background-color:orange;">1</span>
              <span class="bs-stepper-label">Comission Ouput</span>
            </button>
          </div>
          <div class="line"></div>
          <div class="step" data-target="#payment-part">
            <button type="button" class="step-trigger" role="tab" aria-controls="payment-part" id="payment-part-trigger">
              <span class="bs-stepper-circle" style="background-color:orange;">2</span>
              <span class="bs-stepper-label">Payment Method</span>
            </button>
          </div>
        </div>
        <div class="bs-stepper-content">
          <form action="page-commission-completion.php" enctype="multipart/form-data" method="post">
            <div id="commission-part" class="content" role="tabpanel" aria-labelledby="commission-part-trigger" style="margin:auto;">
              <div class="row">
                <div class="col-md-5 order-md-1 d-flex align-items-center justify-content-center">
                  <div class="mb-3 ">
                    <div class="file-drop-area" style="width:100%;">
                      <span class="choose-file-button"><i class="bi bi-files" style="font-size:50px;"></i><br>Choose Files</span>
                      <span class="file-message">Or drag and drop files here<br>Note: File should be below 5mb in size.</span>
                      <input type="file" name="image" class="file-input" accept=".jfif,.jpg,.jpeg,.png,.gif,.mp4" multiple>
                    </div>
                    <div id="divImageMediaPreview">
                    </div>
                  </div>
                </div>
                <div class="col-md-7 order-md-2 mb-4">
                  <div class="mb-3">
                    <label for="description" class="lbl">Description</label>
                    <textarea name="description" class="form-control" id="comment" style="background-color: rgba(244, 246, 246, 0.37); height:250px; resize: none ;"></textarea>
                  </div>
                </div>
              </div>
              <a class="btn" onclick="stepper.next()" style="background-color:#447271; float:right; color:white;">Next  <span class="bi bi-arrow-right-circle"></span></a>
            </div>
          <div id="payment-part" class="content" role="tabpanel" aria-labelledby="payment-part-trigger" style="margin:auto;">
            <div class="form-group">

              <input class="cstm-label" id="tab1" type="radio" name="tabs" value="Online Payment">
              <label for="tab1" style="padding:5px 15px; "><i class="bi bi-credit-card"></i> Online Payment</label>

              <input class="cstm-label" id="tab2" type="radio" name="tabs" value="Meet-Up">
              <label for="tab2" style="padding:5px 15px; "><i class="bi bi-person-fill"></i> Meet-Up</label>

              <section id="content1">
                <div class="form-group form-group-custom">
                  <label for="paymentMethod" class="lbl"></label>
                  <select name="paymentMethod" class="form-control form-select">
                    <option class="hidden"  selected disabled>Payment Method</option>
                    <option value="Digital Wallet">Digital Wallet</option>
                    <option value="Online Banking">Online Banking</option>
                  </select>
                </div>
                <div class="form-group form-group-custom">
                  <label for="sp" class="lbl">Service Provider</label>
                  <input type="text" name="serviceProvider" class="form-control" placeholder="" value="" />
                </div>
                <div class="form-group form-group-custom">
                  <label for="accnum" class="lbl">Account Number</label>
                  <input type="number" name="accNumber" class="form-control" placeholder="xxxxxxxxxxxx" value="" />
                </div>
                <div class="form-group form-group-custom">
                  <label for="accname" class="lbl">Account Name</label>
                  <input type="text" name="accName" class="form-control" placeholder="" value="" />
                </div>
                <div class="form-group form-group-custom">
                  <label class="form-label" for="comment">More Details</label>
                  <textarea name="onlineDetails" class="form-control" id="comment" style="background-color: rgb(244, 246, 246); height:150px; resize: none ;"></textarea>
                </div>
              </section>
              <section id="content2">
                <div class="form-group form-group-custom">
                  <label for="loc" class="lbl">Location</label>
                  <input type="text" name="meetLocation" class="form-control" placeholder="" value="" />
                </div>
                <div class="form-group form-group-custom">
                  <label for="meedtdate" class="lbl">Date</label>
                  <input type="datetime-local" name="meetDate" class="form-control">
                </div>
                <div class="form-group form-group-custom">
                  <label class="form-label" for="comment">More Details</label>
                  <textarea name="meetDetails" class="form-control" id="comment" style="background-color: rgb(244, 246, 246); height:150px; resize: none ;"></textarea>
                </div>
              </section>

            </div><br>
            <button class="btn" style="background-color: #447271; color:white;" onclick="stepper.previous()"><span class="bi bi-arrow-left-circle"></span> Previous</button>
            <button type="submit" name="submit" class="btn" style="background-color: #203C3B; color:white; float:right;">Submit</button>
          </div>
          </form>
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
          File too big. For images, use files below 5mb in size. For videos, maximum of 30 seconds media duration is allowed.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="$('#uploadErrorModal').modal('toggle');">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="uploadBigModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Notice for big files</h4>
          <button type="button" class="close" data-dismiss="modal" onclick="$('#uploadBigModal').modal('toggle');">&times;</button>
        </div>
        <div class="modal-body">
          <p>It is advisable to use storage services like <a href="https://www.google.com/drive/"><b>Google Drive</b></a> and include the link here if file exceeds maximum size/duration limit.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="$('#uploadBigModal').modal('toggle');">Close</button>
        </div>
      </div>
    </div>
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