<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Account Verification</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/SVG/logo-black.svg" rel="icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <link rel="stylesheet" href="dist/css/adminlte.min.css">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  
  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>

</head>

<?php
    include("connection.php");
    session_start();

    if(!$_SESSION["username_s"]) {
    header("Location: index.html");
    exit;
  }

    $username = $_SESSION["username_s"];

    $status = $statusMsg = ''; 

    if(isset($_POST["submit"])){
      if(!empty($_FILES["image"]["name"])) { 
        // Get file info 
        $fileName = basename($_FILES["image"]["name"]); 
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION); 
         
        // Allow certain file formats 
        $allowTypes = array('jpg','png','jpeg','gif'); 
        if(in_array($fileType, $allowTypes)){ 
            $image = $_FILES['image']['tmp_name']; 
            $imgContent = addslashes(file_get_contents($image)); 
         
            // Insert image content into database 
            $sql = "UPDATE user_profile_t SET verified = NULL, photo_verification = '$imgContent' WHERE username = '$username'"; 
            $result = mysqli_query($conn, $sql);
             
            if($result){ 
                $status = 'success'; 

                $sql = "INSERT INTO activity_t(user_id, activity) VALUES (".$_SESSION['user_id_s'].", 'has submitted verification request')";  
                mysqli_query($conn, $sql);

                $statusMsg = "File uploaded successfully."; 
                echo "<script type='text/javascript'>
                  $(document).ready(function(){
                      $('#verificationModal').modal('toggle');
                  });
                  </script>";
            }else{ 
                $statusMsg = "File upload failed, please try again."; 
            }  
        }else{ 
            $statusMsg = 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.'; 
        } 
    }else{ 
        $statusMsg = 'Please select an image file to upload.'; 
    } 
    }
    // echo $statusMsg; 

?>

<body class="login-bg" >
  <div class="modal fade" id="verificationModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">File uploaded successfully</h4>
                        <button type="button" class="close" data-dismiss="modal" onclick="$('#verificationModal').modal('toggle');">&times;</button>
                    </div>
                    <div class="modal-body">
                        An administrator will check your uploaded photo. This might take a while.
                    </div>
                    <div class="modal-footer">
                        <a href="page-browse.php"><button type="button" class="btn text-center">Go Back</button></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container d-flex justify-content-center align-items-center vh-100 overflow-hidden">
          <div class="row bg-white py-5" style="border-radius:10px;">
            <div class="col-lg-12">
              <div class="card p-3" style="box-shadow:none; border:none;">
                <div class="card-body">
                  <form action="page-signup-verify.php" enctype="multipart/form-data" method="post">
                    <div class="text-center">
                      <i class="bi bi-file-person" style="font-size:xxx-large;"></i>
                      <h2><b>Account Verification</b></h2>
                      <h5 class="mb-3">Mabuhay<span class="span-dot"> <?php echo $_SESSION['fname_s']. ' '. $_SESSION['lname_s']; ?></span></h5>
                    </div>
                    <p> To become our Ka-Comunidad,<br> please follow the account verification process below.</p>
                    <p class="text-muted" style="text-align:left;"> You may upload a photo of your any valid ID <br> as a proof that you are a legitimate individual to protect <br> and secure everyone in the community.</p>
                    <a href="index.php#FAQs" class="text-muted">See Valid IDs</a>
                    <div class="file-drop-area" style="width:100%;">
                      <span class="choose-file-button">Choose Photo</span>
                      <span class="file-message">Or drag and drop files here<br><small class="text-muted">File should be below 5mb in size.</small></span>
                      <input type="file" name="image" class="file-input" accept=".jfif,.jpg,.jpeg,.png,.gif,.mp4" multiple>
                    </div>
                    <div id="divImageMediaPreview">
                    </div>
                    <div class="pt-3">
                    <button type="submit" name="submit" class="cbtn-mob">Upload</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>


  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  <!-- bs-custom-file-input -->
  <script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
  <!-- AdminLTE App -->

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