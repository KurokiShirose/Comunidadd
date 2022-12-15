<?php
  include("connection.php");
  session_start();

  $username = $_SESSION['username_s'];
  $type = $_SESSION['user_type_s'];
  
  $req_id = $_GET["reqID"];

  

  if($type == "artist"){
    $sql = "UPDATE commission_t SET status = 'To Cancel by Artist' WHERE req_id = '$req_id'";
    mysqli_query($conn, $sql);

    header("Location: page-dashboard-artist.php");
    exit;
  }
  else{
    $sql = "UPDATE commission_t SET status = 'To Cancel by Client' WHERE req_id = '$req_id'";
    mysqli_query($conn, $sql);

    header("Location: page-dashboard-client.php");
    exit;
  }

?>