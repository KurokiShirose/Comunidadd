<?php
    include("connection.php");
    require("plugins/PHPMailer/src/PHPMailer.php");
    require("plugins/PHPMailer/src/SMTP.php");
    session_start();

    $username = $_SESSION['username_s'];
    $id = $_SESSION["commission_id_s"];

    $sql = "SELECT * FROM user_profile_t A INNER JOIN commission_t B ON A.user_id = B.client_id WHERE B.req_id = '$id'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_array($query);

    $mailTo = $result["email"];

    if($result["status"] == "Reject"){
        $body = "<p>Good day, ".$result["first_name"].",<br><br>We regret to inform you that your commission request #".$id." is rejected by the artist. This update has been reflected in your dashboard.<br><br><i>This is an auto-generated email. If you did not request for a commission, or believe that you received this in error, please ignore this email.</i><br><br>Regards,<br>Comunidad</p>";
    }
    elseif($result["status"] == "In Progress"){
        $body = "<p>Good day, ".$result["first_name"].",<br><br>We are happy to inform you that your commission request #".$id." is accepted by the artist. This update has been reflected in your dashboard.<br><br><i>This is an auto-generated email. If you did not request for a commission, or believe that you received this in error, please ignore this email.</i><br><br>Regards,<br>Comunidad</p>";
    }
    
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

    $mail->addAddress($mailTo, $result["first_name"]." ".$result["last_name"]);

    $mail->isHTML(true);
    $mail->Subject = "Comunidad - Commission Request Update";
    $mail->Body = $body;

    if(!$mail->send()){
        echo "Mailer Error:" .$mail->ErrorInfo;
    }
    else{
        header("Location: page-dashboard-artist.php"); 
        exit;
    }

?>