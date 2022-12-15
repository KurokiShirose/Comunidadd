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

   <!-- Style CSS -->
   <link rel="stylesheet" href="css/style-profile.css">

   <!-- jQuery -->
      <script src="plugins/jquery/jquery.min.js"></script>

  <style>
    .content p, h3{
      margin-left: 0;
    }

    .dashboard-item {
      margin-bottom: 0.25rem;
    }
    .sidebar {
      margin-top: 70px;
      padding: 0;
      width: 450px;
      background-color: #e9e9e9;
      position: fixed;
      height: 100%;
      overflow: auto;
    }

    div.content {
      margin-left: 450px;
      padding: 1px 16px;
    }

    @media screen and (max-width: 700px) {
      .sidebar {
        width: 100%;
        height: auto;
        position: relative;
      }
      .sidebar a {float: left;}
      div.content {margin-left: 0;}
    }

    @media screen and (max-width: 400px) {
      .sidebar a {
        text-align: center;
        float: none;
      }
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
  if($_SESSION["verified_s"] != 1){
    header("Location: page-verified-only.php");
    exit;
  }

  unset($_SESSION["filename_s"]);
  unset($_SESSION["complete_s"]);

  $artistID = $_SESSION["artist_id_s"];
  $username = $_SESSION['username_s'];

  $sql = "SELECT * FROM user_profile_t A INNER JOIN artist_profile_t B ON A.user_id = B.user_id WHERE A.username = '$username'";
  $query = mysqli_query($conn, $sql);
  $result = mysqli_fetch_array($query);

  if(isset($_GET["accept"]) || isset($_GET["reject"])){

    if(isset($_GET["accept"])){
      $id = $_GET["accept"];

      $sql = "UPDATE commission_t SET status = 'In Progress' WHERE req_id = '$id'";
      $query = mysqli_query($conn, $sql);

      $sql = "INSERT INTO activity_t(user_id, activity) VALUES (".$_SESSION['user_id_s'].", 'has accepted commission #".$id."')";  
      mysqli_query($conn, $sql);
    }
  
    if(isset($_GET["reject"])){
      $id = $_GET["reject"];

      $sql = "UPDATE commission_t SET status = 'Reject' WHERE req_id = '$id'";
      $query = mysqli_query($conn, $sql);

      $sql = "INSERT INTO activity_t(user_id, activity) VALUES (".$_SESSION['user_id_s'].", 'has rejected commission #".$id."')";  
      mysqli_query($conn, $sql);
    }

    $_SESSION["commission_id_s"] = $id;

    header("Location: email-client.php");
    exit;
  }
  elseif(isset($_GET["wip"])){
    $id = $_GET["wip"];

    $_SESSION["commission_id_s"] = $id;

    header("Location: page-commission-progress.php");
    exit;
  }
  elseif(isset($_GET["complete"])){
    $id = $_GET["complete"];

    $_SESSION["commission_id_s"] = $id;

    header("Location: page-commission-completion.php");
    exit;
  }
  elseif(isset($_GET["receivedPayment"])){
    $id = $_GET["receivedPayment"];

    $sql = "UPDATE commission_t SET status = 'Complete' WHERE req_id = '$id'";
    $query = mysqli_query($conn, $sql);

    $sql = "SELECT * FROM completed_works_t WHERE req_id = '$id'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_array($query);
    
    $_SESSION["filename_s"] = $result["work"];
    $_SESSION["description_s"] = $result["description"];
    $_SESSION["commission_id_s"] = $id;
    $_SESSION["complete_s"] = 1;

    $sql = "INSERT INTO activity_t(user_id, activity) VALUES (".$_SESSION['user_id_s'].", 'has received payment in commission #".$id."')";  
    mysqli_query($conn, $sql);

    header("Location: email-client-progress.php");
    exit;
  }
  elseif(isset($_GET["resendWork"])){
    $id = $_GET["resendWork"];

    $sql = "SELECT * FROM completed_works_t WHERE req_id = '$id'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_array($query);

    $_SESSION["filename_s"] = $result["work"];
    $_SESSION["description_s"] = $result["description"];
    $_SESSION["commission_id_s"] = $id;
    $_SESSION["complete_s"] = 2;

    $sql = "INSERT INTO activity_t(user_id, activity) VALUES (".$_SESSION['user_id_s'].", 'has resended work in commission #".$id."')";  
    mysqli_query($conn, $sql);

    header("Location: email-client-progress.php");
    exit;
  }
  elseif(isset($_GET["resendPaymentNotice"])){
    $id = $_GET["resendPaymentNotice"];

    $_SESSION["commission_id_s"] = $id;

    $sql = "INSERT INTO activity_t(user_id, activity) VALUES (".$_SESSION['user_id_s'].", 'has sent payment notice in commission #".$id."')";  
    mysqli_query($conn, $sql);

    header("Location: email-payment.php");
    exit;
  }
  
?>
<body style="background-image: linear-gradient(to bottom right,#113b3a , #447271, #447271 , rgb(13, 55, 54)); overflow:hidden;">
  <div class="sidebar mt-0" style="background-color:white;">
    <div class="card" style="border:none;">
      <div class="card-body p-4">
        <div class="text-center">
          <img alt="" class="profile-pic-small" src="
              <?php 
                if($result["profile_pic"] != NULL){
                  $pfp = $result["profile_pic"];
                  echo "./assets/img/profile/". $pfp;
                }
                else{
                  echo "./assets/img/SVG/profile-icon.svg";
                }
              ?>
          ">
          <h3 style="margin: 10px 0px 0px 0px;"><b><?php echo $_SESSION["fname_s"]. " ". $_SESSION["lname_s"] ?></b></h3>
          <p style="color: #2b2a2a; margin-bottom:2px;"><?php echo $result["expertise"] ?></p>
          <p class="card-text-custom-1"><i class="fa fa-map-marker" aria-hidden="true"></i> <?php echo $result["location"] ?></p>
          <div class="row d-flex text-center px-3 py-2">
              <div class="col-6 px-1">
                <a class="btn btn-admin " href="page-profile-artist.php"><i class="bi bi-person-square"></i><b> Profile</b></a>
              </div>
              <div class="col-6 px-1">
              <a class="btn btn-out" href='logout.php'><b><i class="bi bi-power"></i> Log Out</b></a>
              </div>
          </div>
          <?php
            $sql = "SELECT SUM(status != 'Reject') total, SUM(status = 'Pending' OR status = 'Pending Rating' OR status = 'Pending Payment') pending, SUM(status = 'Complete') complete, SUM(status = 'In Progress') inProgress FROM commission_t WHERE artist_id = '$artistID'";
            $query = mysqli_query($conn, $sql);
            $counts = mysqli_fetch_array($query);
          ?>
          <h5><b><?php echo $counts[0] ? $counts[0] : 0 ?></b> Commissions</h5>
          <div class=" d-flex justify-content-center">
              <div class="px-3">
                <h5 class="mb-0"><b><?php echo $counts[3] ? $counts[3] : 0 ?></b></h5>
                <p class="text-muted" style="font-size:small; margin-top: 0px;">In progress</p>
              </div>
              <div class="px-3">
                <h5 class="mb-0"><b><?php echo $counts[1] ? $counts[2] : 0 ?></b></h5>
                <p class="text-muted" style="font-size:small; margin-top: 0px;">Pending</p>
              </div>
              <div class="px-3">
                <h5 class="mb-0"><b><?php echo $counts[2] ? $counts[2] : 0 ?></b></h5>
                <p class="text-muted" style="font-size:small; margin-top: 0px;">Completed</p>
              </div>
          </div>
        </div>
        <div class="card overflow-auto" style="height:40vh;">
          <div class="card-header">
            <p class="mb-0"><b>Activity Log</b></p>
          </div>
          <div class="card-body">
            <div class="row">
              <?php
                $sql = "SELECT * FROM activity_t A INNER JOIN user_profile_t B ON A.user_id = B.user_id WHERE B.user_id = ".$_SESSION["user_id_s"]." ORDER BY A.date DESC LIMIT 20";
                $query = mysqli_query($conn, $sql);
                $count = mysqli_num_rows($query);

                if($count > 0){
                  while($result = mysqli_fetch_array($query)){
                    if($result["profile_pic"] != NULL && $result["status"] == "Active"){
                      echo "
                        <li class='list-group-item px-3 mb-2'>
                          <div class='d-flex'>
                            <div>
                              <img src='./assets/img/profile/".$result["profile_pic"]."' width='40' class='mx-2' style='border-radius: 20px'>
                            </div>
                            <div>
                              ".$result["first_name"]." ".$result["last_name"]." ". $result["activity"].". <br>
                            <span style='font-size: 0.8rem'>".date('M d, Y H:i', strtotime($result["date"]))."</span>
                            </div>
                          </div>
                        </li>
                      
                      ";
                    }
                    else{
                      echo "
                        <li class='list-group-item px-3 mb-2'>
                          <div class='d-flex'>
                            <div>
                              <img src='assets/img/SVG/profile-icon.svg' width='40' class='mx-2'>
                            </div>
                            <div>
                              ".$result["first_name"]." ".$result["last_name"]." ". $result["activity"].". <br>
                            <span style='font-size: 0.8rem'>".date('M d, Y H:i', strtotime($result["date"]))."</span>
                            </div>
                          </div>
                        </li>
                      
                      ";
                    }
                    
                  }
                }
                else{
                  echo "
                        <li class='list-group-item px-3' style='background-color:#203c3b59;'>
                          <div class='d-flex justify-content-center'>
                            <div class='text-white'>
                              Activity log is empty
                            
                            </div>
                          </div>
                        </li>
                      
                      ";
                }
                
                
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="content p-4">
    <div class="card overflow-auto" style="height:90vh;">
      <div class="card-header">
        <h5><b>Dashboard</b></h5>
      </div>
      <div class="card-body">
      <?php
            $sql = "SELECT *, A.status AS user_status FROM user_profile_t A INNER JOIN commission_t B ON A.user_id = B.client_id WHERE B.artist_id = '$artistID' ORDER BY B.req_date;";
            $query = mysqli_query($conn, $sql);
            $count = mysqli_num_rows($query);
            
            if($count > 0){
              while($result = mysqli_fetch_array($query)){
                $completion = $result["completion_date"] ? date('M d, Y H:i', strtotime($result["completion_date"])) : "In Progress";
                echo "
                  <div class='d-flex justify-content-between flex-wrap'>
                    <div class='d-flex'>
                      <div class='p-2'>";

                      if($result["user_status"] == 'Removed'){
                        echo "
                        <img src='./assets/img/SVG/profile-icon.svg' alt='' class='profile-pic-small'></a>
                        ";
                      }
                      elseif($result["profile_pic"] == NULL){
                        echo "
                        <a href='page-view-client.php?clientID=".$result["client_id"]."'><img src='./assets/img/SVG/profile-icon.svg' alt='' class='profile-pic-small'></a>
                        ";
                      }
                      else{
                        echo "
                          <a href='page-view-client.php?clientID=".$result["client_id"]."'><img src='./assets/img/profile/".$result["profile_pic"]."' alt='' class='profile-pic-small'></a>
                        ";
                      }
                        echo "
                      </div>
                      <div class='p-2'>
                        <p class='dashboard-item' style='margin-left:5%;'><span class='text-muted'>Client: </span><b>".$result["first_name"]." ".$result["last_name"]."</b></p>
                        <p class='dashboard-item' style='margin-left:5%;'><span class='text-muted'>Date Requested: </span>". date('M d, Y H:i', strtotime($result["req_date"]))."</p>
                        <p class='dashboard-item' style='margin-left:5%;'><span class='text-muted'>Date Completed: </span>". $completion."</p>
                        <p class='dashboard-item' style='margin-left:5%;'><span class='text-muted'>Status:</span> ".$result["status"]."</p>
                      </div>
                    </div>
                    <div class='my-auto p-2'>";

                      if($result["status"] == "Pending"){
                        echo "
                          <a href='page-dashboard-artist.php?accept=".$result["req_id"]."' class='btn btn-custom-reg' onclick='if (! confirm(`Are you sure?`)) { return false; }');'><i class='fa fa-thumbs-up'  style='margin-right:5px;'></i>Accept</a>
                          <a href='page-dashboard-artist.php?reject=".$result["req_id"]."' class='btn btn-custom-reg' style='background-color: maroon;' onclick='if (! confirm(`Are you sure?`)) { return false; }');'>
                          <i class='fa fa-times'></i> Reject</a> |
                        ";
                      }
                      elseif($result["status"] == "In Progress"){
                        echo "
                          <a class='btn btn-custom-reg' href='page-dashboard-artist.php?wip=".$result["req_id"]."'><i class='fa fa-paper-plane' style='margin-right:5px;'></i>Send Progress</a>
                          <a class='btn btn-custom-reg' style='background-color: #F69312;' href='page-dashboard-artist.php?complete=".$result["req_id"]."' >
                          <i class='fa fa-check'></i> Complete</a> |
                          
                        ";
                      }
                      elseif($result["status"] == "Reject"){
                        echo "
                          <i class='fa fa-times-circle' aria-hidden='true'></i><span style='color: maroon'  class='d-none d-sm-block'> Rejected</span> |
                        ";
                      }
                      elseif($result["status"] == "Pending Rating"){
                        echo "
                          <span style='color: #203C3B'><i class='fa fa-spinner loading' aria-hidden='true'></i> Waiting for Feedback</span> |
                        ";
                      }
                      elseif($result["status"] == "Pending Payment"){
                        echo "
                          <a class='btn btn-custom-reg' style='background-color: #F69312;' data-toggle='modal' data-target='#complete-".$result["req_id"]."'><i class='fa fa-check'></i> Received Payment</a> |

                          <div class='modal fade' id='complete-".$result["req_id"]."'>
                            <div class='modal-dialog'>
                              <div class='modal-content'>
                                  <div class='modal-body text-start'>
                                    <h4 class='modal-title'>Release the commission?</h4>
                                    <br>
                                    <p class=''>By confirming, it is assumed that you received the payment and the commission work will be release to the client. </p>
                                    <i>Disclaimer: Your choice of proceeding on finishing the commission between you and the client, with knowledge that the payment for the commission work will be fully paid through a third party service or external transaction, is your sole decision and does not hold Comunidad responsible for any scams.</i>
                                  </div>
                                  <div class='modal-footer'>
                                    <button type='button' class='btn btn-danger' data-dismiss='modal'>No</button>
                                    <a href='page-dashboard-artist.php?receivedPayment=".$result["req_id"]."' name='deleteBtn' class='btn btn-custom-reg'>Yes</a>
                                  </div>
                              </div>
                            </div>
                          </div>
                        ";
                      }
                      elseif($result["status"] == "Cancelled"){
                        echo "
                          <i class='bi bi-x-circle-fill' ></i><span style='color: maroon'  class='d-none d-sm-block'> Cancelled</span> |
                        ";
                      }
                      elseif($result["status"] == "Complete"){
                        echo "
                        <i class='fa fa-check-circle' aria-hidden='true'></i><span class='d-none d-sm-block'> Completed</span> |
                        ";
                      }
                        echo "
                        
                        <a type='button' class='btn btn-custom-reg' style='background-color: rgb(225, 225, 225); color:black' 
                          data-toggle='modal' data-target='#modal-".$result["req_id"]."' onclick='$('#modal-".$result["req_id"]."').modal('toggle');'>
                            <i class='fa fa-file'></i> Details
                          </a>
                    </div>
                  </div>
                ";

                echo "
                  <div id='modal-".$result["req_id"]."' class='modal fade' tabindex='-1'>
                    <div class='modal-dialog'>
                      <div class='modal-content'>
                        <div class='modal-header'>
                          <h5 class='modal-title'><b>Commission ID #".$result["req_id"]." Details</b></h5>
                          <button type='button' class='btn-close' data-dismiss='modal' aria-label='Close'></button>
                        </div>
                        <div class='modal-body'>
                          <div class='row'>
                            
                            <p class='dashboard-item'><b>". $result['first_name']." ". $result['last_name']."</b></p>
                            <p class='dashboard-item'>". $result['contact'].", <a class='dashboard-item' href='mailto:".$result["email"]."'><i>".$result["email"]."</i></a></p>
                            <p  class='dashboard-item'><span class='text-muted'>Date Requested:</span> ".date('M d, Y H:i', strtotime($result["req_date"]))."</p>
                            <p  class='dashboard-item'><span class='text-muted'>Status:</span> ".$result["status"]."</p>
                            <p  class='dashboard-item'><span class='text-muted'>Request Type: </span> ".$result["req_type"]."</p>
                            <p  class='dashboard-item'><span class='text-muted'>Request Description: </span></p><br>
                            <p class='dashboard-item'>
                              ".$result["req_description"]."
                            </p>";

                            if($result["status"] == "Complete"){
                              $id2 = $result["req_id"];
                              $sql2 = "SELECT * FROM feedback_t WHERE req_id = '$id2'";
                              $query2 = mysqli_query($conn, $sql2);
                              $result2 = mysqli_fetch_array($query2);

                              echo "<hr>
                                <p  class='dashboard-item'><span class='text-muted'>Feedback Date:</span> ".date('M d, Y H:i', strtotime($result2["client_feedback_date"]))."</p>
                                <p  class='dashboard-item'><span class='text-muted'>Client Rating:</span> ".$result2["client_rating"]."</p>
                                <p  class='dashboard-item'><span class='text-muted'>Feedback: </span> ".$result2["client_comment"]."</p>
                                <br>
                                <hr>
                                <a href='page-dashboard-artist.php?resendWork=".$result["req_id"]."'><i class='bi bi-envelope'></i> Resend Work</a>
                                ";
                            }
                            elseif($result["status"] == "Pending Payment"){
                              echo "
                                <hr>
                                <a href='page-dashboard-artist.php?resendPaymentNotice=".$result["req_id"]."'><i class='bi bi-envelope'></i> Resend Payment Notice</a>
                              ";
                            }
                            echo "
                          </div>
                        </div>
                        <div class='modal-footer justify-content-between'>
                          <button class='btn btn-custom-reg' data-dismiss='modal' data-toggle='modal' data-target='#modalReport-".$result["req_id"]."' onclick='$('#modalReport-".$result["req_id"]."').modal('toggle');'><i class='bi-flag-fill'></i> Report</button>
                          <button type='button' class='btn btn-custom-reg' data-dismiss='modal' onclick='$('#modal-".$result["req_id"]."').modal('toggle');'>Close</button>
                        </div>
                      </div>
                    </div>
                  </div>
                ";

                echo "
                  <div id='modalReport-".$result["req_id"]."' class='modal fade' tabindex='-1'>
                    <div class='modal-dialog'>
                      <div class='modal-content'>
                        <form action='report-commission.php' method='post'>
                          <div class='modal-header'>
                            <h5 class='modal-title'><b>Report Commission?</b></h5>
                            <button type='button' class='btn-close' data-dismiss='modal' aria-label='Close'></button>
                          </div>
                          <div class='modal-body'>
                            <div class='row'>
                              <input type='text' name='reqID' value='".$result["req_id"]."' hidden>
                              
                                    <p style='margin: auto;'><b>Please Select a problem</b></p>
                                    <p style='font-size:15px;' class='text-muted'>Help us understand why</p>
                                    <div class='form-check' style='margin:0px 0px 10px 30px; '>
                                      <input class='form-check-input' type='radio' id='cat1' name='reportType' value='Nudity' required>
                                      <label class='form-check-label' for='cat1'>User is not responsive</label>
                                    </div>
                                    <div class='form-check' style='margin:0px 0px 10px 30px; '>
                                      <input class='form-check-input' type='radio' id='cat2' name='reportType' value='Self-Harm'>
                                      <label class='form-check-label' for='cat2'>Self-Harm</label>
                                    </div>
                                    <div class='form-check' style='margin:0px 0px 10px 30px; '>
                                      <input class='form-check-input' type='radio' id='cat3' name='reportType' value='Violence'>
                                      <label class='form-check-label' for='cat3'>Violence</label>
                                    </div>
                                    <div class='form-check' style='margin:0px 0px 10px 30px; '>
                                      <input class='form-check-input' type='radio' id='cat4' name='reportType' value='Harassment'>
                                      <label class='form-check-label' for='cat4'>Harassment</label>
                                    </div>
                                    <div class='form-check' style='margin:0px 0px 10px 30px; '>
                                      <input class='form-check-input' type='radio' id='cat5' name='reportType' value='Hate Speech'>
                                      <label class='form-check-label' for='cat5'>Hate Speech</label>
                                    </div>
                                    <div class='form-check' style='margin:0px 0px 10px 30px; '>
                                      <input class='form-check-input' type='radio' id='cat6' name='reportType' value='Plagiarism / Intellectual Property'>
                                      <label class='form-check-label' for='cat6'>Plagiarism / Intellectual Property</label>
                                    </div>
                                    <div class='form-check' style='margin:0px 0px 10px 30px; '>
                                      <input class='form-check-input' type='radio' id='cat7' name='reportType' value='Terrorism'>
                                      <label class='form-check-label' for='cat7'>Terrorism</label>
                                    </div>
                                    <div class='form-group form-group-custom' style='margin-bottom: 10px;'>
                                      <label for='description' class='lbl'>Description</label>
                                      <textarea type='text' style='height:180px; resize: none ' name='reportDesc' class='form-control' placeholder='Describe why?' value=''></textarea>
                                    </div>
                            </div>
                          </div>
                          <div class='modal-footer justify-content-end'>
                            <div>
                              <button data-dismiss='modal' class='btn btn-custom-reg' >Cancel</button>
                              <button type='submit' name='reportCommission' style='background-color: maroon' class='btn btn-custom-reg'>Report</button>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                ";

                echo "
                  <div id='modalCancel-".$result["req_id"]."' class='modal fade' tabindex='-1'>
                    <div class='modal-dialog'>
                      <div class='modal-content'>
                        <div class='modal-header'>
                          <h5 class='modal-title'><b>Cancel Commission?</b></h5>
                          <button type='button' class='btn-close' data-dismiss='modal' aria-label='Close'></button>
                        </div>
                        <div class='modal-body'>
                          <div class='row'>
                            <p class='dashboard-item'>To complete the cancelling process, the client should also confirm the cancellation in his/her part. Do you want to continue?</p>
                          </div>
                        </div>
                        <div class='modal-footer justify-content-end'>
                          <div>
                            <a href='' type='button' class='btn btn-custom-reg' style='background-color: maroon'>No</a>
                            <a href='cancel-commission.php?reqID=".$result["req_id"]."' type='button' class='btn btn-custom-reg'>Yes</a>
                          </div>
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

            $sql = "SELECT * FROM commission_t A INNER JOIN user_profile_t B ON A.client_id = B.user_id WHERE A.status = 'To Cancel by Client' AND A.artist_id = '$artistID'";
            $query = mysqli_query($conn, $sql);
            $count = mysqli_num_rows($query);

            if($count > 0){
              while($result = mysqli_fetch_array($query)){

                echo "<script type='text/javascript'>
                $(document).ready(function(){
                    $('#modalCancelConfirm-".$result["req_id"]."').modal('toggle');
                });
                </script>";
                

                echo "
                  <div id='modalCancelConfirm-".$result["req_id"]."' class='modal fade' tabindex='-1'>
                    <div class='modal-dialog'>
                      <div class='modal-content'>
                        <div class='modal-header'>
                          <h5 class='modal-title'><b>Cancel Commission?</b></h5>
                          <button type='button' class='btn-close' data-dismiss='modal' aria-label='Close'></button>
                        </div>
                        <div class='modal-body'>
                          <div class='row'>
                            <p class='dashboard-item'>Client ".$result["first_name"]." ".$result["last_name"]." has requested to cancel the commission. To complete the cancelling process, you should also confirm the cancellation in your part. Do you want to continue?</p>
                          </div>
                        </div>
                        <div class='modal-footer justify-content-end'>
                          <div>
                            <a href='' type='button' class='btn btn-custom-reg' style='background-color: maroon'>No</a>
                            <a href='cancel-commission-confirm.php?reqID=".$result["req_id"]."' type='button' class='btn btn-custom-reg'>Yes</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                ";

                
              }
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
  
  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


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

      function setEventId(event_id){
        createCookie("reqID", event_id, "1");
      }

    </script>
  
</body>
</html>