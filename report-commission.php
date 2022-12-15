<?php
  include("connection.php");
  session_start();

  if(isset($_POST["reportCommission"])){
    $username = $_SESSION['username_s'];
    $user_id = $_SESSION['user_id_s'];
    $type = $_SESSION['user_type_s'];
    
    $req_id = $_POST["reqID"];
    $report_type = $_POST["reportType"];
    $report_desc = $_POST["reportDesc"];

    $sql = "INSERT INTO reported_commission_t (req_id, reported_by, report_type, description, action) VALUES ('$req_id', '$user_id', '$report_type', '$report_desc', 'Pending')";
    mysqli_query($conn, $sql);

    $sql = "INSERT INTO activity_t(user_id, activity) VALUES (".$_SESSION['user_id_s'].", 'has reported commission #".$req_id."')";  
    mysqli_query($conn, $sql);

    if($type == "artist"){
      header("Location: page-dashboard-artist.php");
      exit;
    }
    else{
      header("Location: page-dashboard-client.php");
      exit;
    }
  }
?>