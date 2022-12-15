

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
      
        <title>Commission Request Update Form</title>
        <meta content="" name="description">
        <meta content="" name="keywords">
      
        <!-- Favicons -->
        <link href="assets/img/SVG/logo-black.svg" rel="icon">
      
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
      

        <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
        <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
      
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

  if(isset($_POST["post"])){
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
        $_SESSION["filename_s"] = $filename.".".$ext;

        $_SESSION["description_s"] = $_POST["description"];
        $_SESSION["complete_s"] = 0;

        $flag = 0;

        header("Location: email-client-progress.php");
        exit;
      }
    }
    else{
      $_SESSION["description_s"] = $_POST["description"];
      $_SESSION["complete_s"] = 0;

      header("Location: email-client-progress.php");
      exit;
    }
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
  
  <div class="container bg-white">
    <div class="py-5">
        <div class="row text-center">
          <i class="fa fa-file mb-3" style="font-size:xxx-large; color: orange;"></i>
          <h2><b>Commission Progress Report</b></h2>
        </div>
        <div class="row py-3">
        <p> Please upload your work snapshots here to update your client on the progress of their commission request.</p>
        </div>
        <hr class="style1 m-0">
    </div>
    <form action="page-commission-progress.php" enctype="multipart/form-data" method="post">
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
          <div>
            <input type="submit" name="post" class="btn save-btn text-center"  style="float: right;"
            onclick='return confirm(`Are you sure? All files and information will be sent to the client.`);'value="Send"/>
          </div>
        </div>
      </div>
    </form>
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


  <div id="preloader"></div>

   <!-- Template Main JS File -->
   <script src="assets/js/main.js"></script>

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