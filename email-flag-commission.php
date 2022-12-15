<?php
  include("connection.php");
  require("plugins/PHPMailer/src/PHPMailer.php");
  require("plugins/PHPMailer/src/SMTP.php");
  session_start();

  $username = $_SESSION['username_s'];

  $reportID = $_GET["reportID"];
  $reportType = $_GET["reportType"];
  $userID = $_GET["userID"];
  $email = $_GET["email"];

  $mailTo = $email;

  $mail = new PHPMailer\PHPMailer\PHPMailer();
  $mail->SMTPDebug = 3;
  $mail->isSMTP();
  $mail->Host = "mail.smtp2go.com";
  $mail->SMTPAuth = true;
  $mail->Username = "comunidad";
  $mail->Password = "WvaxWmPkoo0x551s";
  $mail->SMTPSecure = "tls";
  $mail->Port = "2525";
  $mail->From = "ComunidadPhilippines@gmail.com";
  $mail->FromName = "Comunidad";

  $mail->addAddress($mailTo, $userID);

  $mail->isHTML(true);
  $mail->Subject = "Commission Notice - Comunidad";

  if($_GET["type"] == "client"){
    $body = "<p>Good day,<br><br>We received information about your commission that has been tagged as '".$reportType.".' Upon reviewing the commission activity, the administrator has decided to send you this notice and flag your account. All your data are not affected. <br><br>Regards,<br>Comunidad</p>";
    $mail->Body = $body;

    if(!$mail->send()){
      echo "Mailer Error:" .$mail->ErrorInfo;
    }
    else{
      $sql = "SELECT * FROM flagged_users_t WHERE user_id = '$userID'";
      $query = mysqli_query($conn, $sql);
      $count = mysqli_num_rows($query);

      if($count > 0){
        $sql = "UPDATE flagged_users_t SET flags = flags + 1 WHERE user_id = '$userID'";
        mysqli_query($conn, $sql);
      }
      else{
        $sql = "INSERT INTO flagged_users_t (user_id, flags) VALUES ('$userID', 1)";
        mysqli_query($conn, $sql);
      }

      $sql = "UPDATE reported_commission_t SET action = 'Noticed' WHERE id = '$reportID'";
      mysqli_query($conn, $sql);

      $sql = "INSERT INTO activity_t(user_id, activity) VALUES (0, 'has flagged user #".$userID." in commission #".$reportID." due to ".$reportType."')";  
      mysqli_query($conn, $sql);

      header("Location: page-dashboard-admin.php"); 
      exit;
    }
  }
  elseif($_GET["type"] == "artist"){
    $body = "<p>Good day,<br><br>We received information about your commission that has been tagged as '".$reportType.".' Upon reviewing the commission activity, the administrator has decided to send you this notice and flag your account. All your data are not affected. <br><br>Regards,<br>Comunidad</p>";
    $mail->Body = $body;

    if(!$mail->send()){
      echo "Mailer Error:" .$mail->ErrorInfo;
    }
    else{

      $sql = "UPDATE artist_profile_t SET flags = flags + 1 WHERE user_id = '$userID'";
      mysqli_query($conn, $sql);

      $sql = "UPDATE reported_commission_t SET action = 'Noticed' WHERE id = '$reportID'";
      mysqli_query($conn, $sql);

      $sql = "INSERT INTO activity_t(user_id, activity) VALUES (0, 'has flagged user #".$userID." in commission #".$reportID." due to ".$reportType."')";  
      mysqli_query($conn, $sql);

      header("Location: page-dashboard-admin.php"); 
      exit;
    }
  }
  else{
    $body = "<p>Good day,<br><br>We received information about your commission that has been tagged as '".$reportType.".' Upon reviewing the commission activity, the administrator has decided to terminate and close the commission. All your data are not affected. <br><br>Regards,<br>Comunidad</p>";

    $mail->Body = $body;

    if(!$mail->send()){
      echo "Mailer Error:" .$mail->ErrorInfo;
    }
    else{
      $sql = "UPDATE reported_commission_t SET status = 'Closed' WHERE id = '$reportID'";
      mysqli_query($conn, $sql);

      $sql = "SELECT * FROM reported_commission_t WHERE id = '$reportID'";
      $query = mysqli_query($conn, $sql);
      $result = mysqli_fetch_array($query);
      $req_id = $result["req_id"];

      $sql = "UPDATE commission_t SET status = 'Closed' WHERE req_id = '$req_id'";
      mysqli_query($conn, $sql);

      $sql = "INSERT INTO activity_t(user_id, activity) VALUES (0, 'has closed commission #".$reportID." due to ".$reportType."')";  
      mysqli_query($conn, $sql);

      header("Location: page-dashboard-admin.php"); 
      exit;
    }
  }

?>