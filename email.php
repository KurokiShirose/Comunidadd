<?php
    include("connection.php");
    require("plugins/PHPMailer/src/PHPMailer.php");
    require("plugins/PHPMailer/src/SMTP.php");
    session_start();

    $otp = $_SESSION['otp_s'];
    $mailTo = $_SESSION['email_s'];

    $body = "<p>Good day, ".$_SESSION['fname_s'].",<br><br>Your one-time password (OTP) is <strong>".$otp."</strong>. Use this code to verify your account information to complete the sign-up process.<br><br>Thank you for joining Comunidad.<br><br><strong>IMPORTANT: Do not share your OTP to anyone!</strong><br><br><i>This is an auto-generated email. If you did not sign-up for a Comunidad account, or believe that you received this in error, please ignore this email.</i><br><br>Regards,<br>Comunidad</p>";
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

    $mail->addAddress($mailTo, "New User");

    $mail->isHTML(true);
    $mail->Subject = "[OTP] Verify Account - Comunidad";
    $mail->Body = $body;

    if(!$mail->send()){
        echo "Mailer Error:" .$mail->ErrorInfo;
    }
    else{
        header("Location: page-signup-verify-email.php"); 
        exit;
    }

?>