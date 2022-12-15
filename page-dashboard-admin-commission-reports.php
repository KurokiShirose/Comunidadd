<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Dashboard</title>
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

  <style>
    .content p, h3{
      margin-left: 0;
    }

    .dashboard-item {
      margin-bottom: 0.25rem;
    }
  </style>


</head>

<?php 
  include("connection.php");
  session_start();

  if(!$_SESSION["username_s"]) {
    header("Location: index.php");
    exit;
  }

  $username = $_SESSION['username_s'];
  
    if(isset($_GET["approve"])){
        $id = $_GET["approve"];

        $sql = "UPDATE user_profile_t SET verified = 1, photo_verification = NULL WHERE user_id = '$id'"; 
        mysqli_query($conn, $sql);
    }

    if(isset($_GET["reject"])){
        $id = $_GET["reject"];

        $sql = "UPDATE user_profile_t SET verified = 2, photo_verification = NULL WHERE user_id = '$id'"; 
        mysqli_query($conn, $sql);
    }

    $message = "";
    if(isset($_POST["post"])){
      $oldPass = $_POST["oldPass"];
      $newPass = $_POST["newPass"];
      $retypePass = $_POST["retypePass"];

      $sql = "SELECT * FROM account_t WHERE username = '$username'";
      $query = mysqli_query($conn, $sql);
      $result = mysqli_fetch_array($query);

      if(strlen($newPass) < 6){
        $message = "Passwords must be 6 characters or more. Try again.";
        echo "<script type='text/javascript'>
                $(document).ready(function(){
                $('#passwordModal').modal('toggle');
                });
              </script>";
      }
      else{
        if(($newPass == $retypePass) && password_verify($oldPass, $result["password"])){
          $newPassHashed = password_hash($newPass, PASSWORD_BCRYPT);
          $sql = "UPDATE account_t SET password = '$newPassHashed' WHERE username = '$username'";
          mysqli_query($conn, $sql);

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

    if(isset($_GET["done"])){
      $reportID = $_GET["done"];

      $sql = "UPDATE reported_commission_t SET action = 'Done' WHERE id = '$reportID'";
      mysqli_query($conn, $sql);
    }

    // 0 or NULL - not verified
    // 1 - verified
    // 2 - attempted to verify but denied, try again
  
?>

<body style="background-image: linear-gradient(to bottom right,#113b3a , #447271, #447271 , rgb(13, 55, 54)); overflow:hidden;">

  <?php
    $sql_u = "SELECT SUM(user_type = 'artist' OR user_type = 'client') , SUM(user_type = 'artist') ,  SUM(user_type = 'client') FROM user_profile_t WHERE status = 'Active'";
    $query_u = mysqli_query($conn, $sql_u);
    $counts_u = mysqli_fetch_array($query_u);

    $sql_c = "SELECT COUNT(*), SUM(status = 'Complete') FROM commission_t; ";
    $query_c = mysqli_query($conn, $sql_c);
    $counts_c = mysqli_fetch_array($query_c);

    $sql_w = "SELECT COUNT(*) FROM works_t WHERE status = 'Live'; ";
    $query_w = mysqli_query($conn, $sql_w);
    $counts_w = mysqli_fetch_array($query_w);
  ?>

  <div class="sidebar-2">
    <div class="container-fluid p-3">
      <div class="card px-1 pt-3" style="border:none;">
        <div class="card-body">
          <div class="row d-flex justify-content-center">
            <img alt="" class="" style="width: 70px; height:auto;" src="assets/img/icon4x.png">
          </div>
          <div class="row text-center">
            <h4 class="pt-3 mb-0"><b>Administrator</b></h4>
            <p class="card-text-custom-1"><i class="fa fa-map-marker" aria-hidden="true"></i><small> Comunidad</small></p>
          </div>
          <div class="row d-flex text-center px-3 py-2">
            <div class="col-6 px-1">
              <a class="btn btn-admin " data-toggle='modal' data-target="#accountModal"><i class="bi bi-key-fill"></i><b> Account</b></a>
            </div>
            <div class="col-6 px-1">
            <a class="btn btn-out" href='logout.php'><b><i class="bi bi-power"></i> Log Out</b></a>
            </div>
          </div>
        </div>
      </div>
      <?php
        $sql = "SELECT COUNT(*) FROM user_profile_t WHERE photo_verification IS NOT NULL";
        $query = mysqli_query($conn, $sql);
        $result = mysqli_fetch_array($query);
        $counts_verify = $result[0];

        $sql = "SELECT COUNT(*) FROM flagged_users_t";
        $query = mysqli_query($conn, $sql);
        $result = mysqli_fetch_array($query);
        $counts_fusers = $result[0];

        $sql = "SELECT COUNT(*) FROM flagged_works_t WHERE action = 'Pending'";
        $query = mysqli_query($conn, $sql);
        $result = mysqli_fetch_array($query);
        $counts_fworks = $result[0];

        $sql = "SELECT COUNT(*) FROM reported_commission_t";
        $query = mysqli_query($conn, $sql);
        $result = mysqli_fetch_array($query);
        $counts_commrep = $result[0];

      ?>
      <div class="px-3 d-none d-sm-block">
        <div class="card ">
          <div class="card-body ">
            <div class="row px-3">
              <a class="btn btn-menu" href='page-dashboard-admin.php'><b><i class="bi bi-list"></i> Dashboard</b></a>
            </div>
            <div class="row pt-1 px-3">
              <a class="btn btn-menu" href='page-dashboard-admin-verification-request.php'><i class="bi bi-person-badge"></i> Verification Request<span class="badge text-black"><?php echo $counts_verify ?></span></a>
            </div>
            <div class="row pt-1 px-3">
              <a class="btn btn-menu" href='page-dashboard-admin-flagged-users.php'><i class="bi bi-flag"></i> Flagged Users<span class="badge text-black"><?php echo $counts_fusers ?></span></a>
            </div>
            <div class="row pt-1 px-3">
            <a class="btn btn-menu" href='page-dashboard-admin-flagged-works.php'><i class="bi bi-flag-fill"></i> Flagged Works<span class="badge text-black"><?php echo $counts_fworks ?></span> </a>
            </div>
            <div class="row pt-1 px-3">
            <a class="btn btn-menu-active" href='page-dashboard-admin-commission-reports.php'><i class="bi bi-exclamation-circle-fill"></i> Commission Reports<span class="badge text-white"><?php echo $counts_commrep ?></span></a>
            </div>
          </div>
        </div>
      </div>
      <div class="px-4 text-center d-block d-sm-none ">
        <div class="card" style="border:none;">
          <div class="card-body p-1">
            <div class="row px-2">
              <a class="btn btn-menu" href='page-dashboard-admin.php'><b><i class="bi bi-arrow-return-left"></i> Back to Dashboard</b></a>
            </div>
            <div class="text-left text-muted px-4 d-block d-sm-none">
              <p class="mb-1 mt-2">Dashboard Menu</p>
            </div>
            <div class="row px-2">
              <div class="col-3 p-1">
                <a class="btn btn-menu-mob stats-mob text-center" href='page-dashboard-admin-verification-request.php'><i class="bi bi-person-badge"></i><span class="badge text-white sm-font">4</span></a>
              </div>
              <div class="col-3 p-1">
                <a class="btn btn-menu-mob stats-mob text-center" href='page-dashboard-admin-flagged-users.php'><i class="bi bi-flag"></i><span class="badge text-white sm-font">1</span></a>
              </div>
              <div class="col-3 p-1">
                <a class="btn btn-menu-mob stats-mob text-center " href='page-dashboard-admin-flagged-works.php'><i class="bi bi-flag-fill"></i><span class="badge text-white sm-font">1</span> </a>
              </div>
              <div class="col-3 p-1">
                <a class="btn btn-menu-mob-active stats-mob text-center text-secondary" href='page-dashboard-admin-commission-reports.php'><i class="bi bi-exclamation-circle-fill"></i><span class="badge text-secondary sm-font">1</span></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="content-2" style="height:100vh;">
    <div class="container-fluid p-3 " >
      <div class="card overflow-auto" style="border-radius: 10px; border-width: 0ch; background-color: #ffffff;" >
              <div class="card-body">
                <?php
                  $sql = "SELECT *, A.id AS report_id FROM reported_commission_t A INNER JOIN commission_t B ON A.req_id = B.req_id INNER JOIN artist_profile_t C ON B.artist_id = C.artist_id INNER JOIN user_profile_t D ON C.user_id = D.user_id";
                  $query = mysqli_query($conn, $sql);
                  $count = mysqli_num_rows($query);

                  $sql2 = "SELECT *, C.user_id AS client_user_id FROM reported_commission_t A INNER JOIN commission_t B ON A.req_id = B.req_id INNER JOIN user_profile_t C ON B.client_id = C.user_id LEFT JOIN flagged_users_t D ON C.user_id = D.user_id";
                  $query2 = mysqli_query($conn, $sql2);
                  $result2 = mysqli_fetch_array($query2);

                  if($count > 0){
                    while($result = mysqli_fetch_array($query)){
                      echo "
                        <div class='row '>
                          <div class='col-lg-8 p-2'>
                          <div class='d-flex flex-wrap'>
                          <div class='py-2 px-3'>";

                          if($result["profile_pic"] != NULL && $result["status"] == "Active"){
                            echo "<img style='height: 70px; width: 70px; '  src='./assets/img/profile/".$result["profile_pic"]."'
                              alt='' class='profile-pic-small'>";
                          }
                          else{
                            echo "<img src='./assets/img/SVG/profile-icon.svg' alt='' class='profile-pic-small' style='height: 70px; width: 70px;' >";
                          }
                          if($result2["profile_pic"] != NULL && $result2["status"] == "Active"){
                            echo "<img src='./assets/img/profile/".$result2["profile_pic"]."'  style='height: 70px; width: 70px;position:absolute; left:60px;'
                              alt='' class='profile-pic-small'>";
                          }   
                          else{
                            echo "<img src='./assets/img/SVG/profile-icon.svg' alt='' class='profile-pic-small' style='height: 70px; width: 70px; left:60px;' >";
                          }
                            echo "
                            </div>
                          <div class='py-2 ps-2'>
                            <p class='dashboard-item'><span class='text-muted'></span><b>".$result["req_type"]."</b></p>
                            <p class='dashboard-item'><span class='text-muted'>Artist: </span>".$result["first_name"]." ".$result["last_name"]."</p>
                            <p class='dashboard-item'><span class='text-muted'>Client: </span>".$result2["first_name"]." ".$result2["last_name"]."</p>
                            <p class='dashboard-item'><span class='text-muted'></span>Date: ".date('M d, Y H:i', strtotime($result["req_date"]))."</p>
                          </div></div></div>
                          <div class='col-lg-4 d-flex align-items-center justify-content-center'>";

                            if($result["action"] != "Done"){
                              echo "<a href='page-dashboard-admin.php?done={$result['report_id']}' class='btn btn-success' onclick='return confirm(`Are you sure?`);'><i class='fa fa-check-circle'></i> Resolve</a>|";
                            }
                            else{
                              echo "<span style='color: green'><i class='fa fa-check-circle'></i> Done </span>|";
                            }

                            
                              echo "
                              
                              <a type='button' class='btn btn-custom-reg' style='background-color: rgb(225, 225, 225); color:black' 
                                data-toggle='modal' data-target='#modalReport-".$result["report_id"]."' onclick='$('#modalReport-".$result["report_id"]."').modal('toggle');'>
                                  <i class='fa fa-file'></i> See Actions
                                </a>
                          </div>
                        </div>
                      ";

                      echo "
                        <div id='modalReport-".$result["report_id"]."' class='modal fade' tabindex='-1'>
                          <div class='modal-dialog'>
                            <div class='modal-content'>
                              <div class='modal-header'>
                                <h5 class='modal-title'><b>Flag Details</b></h5>
                                <button type='button' class='btn-close' data-dismiss='modal' aria-label='Close'></button>
                              </div>
                              <div class='modal-body'>
                                <div class='row'>
                                  <p>Report Type: ".$result['report_type']."</p>
                                  <p class='dashboard-item'><span class='text-muted'>Artist: </span><b>". $result['first_name']." ". $result['last_name']."</b> (<b>".$result["flags"]." </b> flag/s )</p>
                                  <p class='dashboard-item'><span class='text-muted'>Client: </span><b>". $result2['first_name']." ". $result2['last_name']."</b> (<b>".($result2["flags"] == NULL ? 0 : $result2["flags"])." </b> flag/s )</p>

                                  <p class='dashboard-item'><span class='text-muted'>Commission Details: </span>". $result['req_description']."</p>

                                  <p class='dashboard-item'><span class='text-muted'>Description:</span> ".$result["description"]."</p>";

                                  if($result["action"] != "Done"){
                                    echo "
                                      <hr>
                                      <a href='email-flag-commission.php?type=artist&reportID=".$result['report_id']."&reportType=".$result['report_type']."&userID=".$result['user_id']."&email=".$result['email']."'><i class='bi bi-flag-fill'></i> Flag Artist</a>
                                      <a href='email-flag-commission.php?type=client&reportID=".$result['report_id']."&reportType=".$result['report_type']."&userID=".$result2['client_user_id']."&email=".$result2['email']."'><i class='bi bi-flag'></i> Flag Client</a>
                                      <a href='email-flag-commission.php?type=close&reportID=".$result['report_id']."&reportType=".$result['report_type']."&userID=".$result2['client_user_id']."' ><i class='bi bi-x-circle-fill'></i> Close Commission</a>
                                    ";
                                  }

                                  echo "
                                </div>
                              </div>
                              <div class='modal-footer'>
                              
                                <button type='button' class='btn btn-custom-reg' data-dismiss='modal' onclick='$('#modalReport-".$result["report_id"]."').modal('toggle');'>Close</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      ";
                    }
                  }
                  else{
                    echo "<div class='row text-center text-secondary '>
                            <h3>Uh oh! Nothing to see here.</h3>
                          </div>";
                  }
                ?>
                </div>
            </div>
  </div>



  <!-- ======= modal ======= -->
  <div class="modal fade" id="accountModal" role="dialog">
    <div class="modal-dialog" style="width:fit-content">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Admin Account</h4>
        </div>
        <div class="modal-body" style="width:fit-content">
          <form action="page-dashboard-admin.php" method="post">
            <div class="row" style="padding: 10px 10px 20px 10px;">
              <h3 class="mb-2" style="margin: auto;">Login Information</h3>
              <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                <label for="uname" class="lbl">Username</label>
                <input type="text" name="uname" class="form-control" placeholder="" value=<?php echo $username ?> readonly>
              </div>
              <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                <label for="oldPass" class="lbl">Old Password</label>
                <input type="password" name="oldPass" class="form-control" placeholder="*******" required>
              </div>
              <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                <label for="newPass" class="lbl">New Password</label>
                <input type="password" name="newPass" class="form-control" placeholder="*******" required>
              </div>
              <div class="form-group form-group-custom" style="margin-bottom: 10px;">
                <label for="retypePass" class="lbl">Retype Password</label>
                <input type="password" name="retypePass" class="form-control" placeholder="*******" required>
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
                  <p>Privacy Policy</p>
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

      function setEventId(event_id){
        createCookie("reqID", event_id, "1");
      }

    </script>
  
</body>
</html>