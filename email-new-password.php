<?php
    include("connection.php");
    require("plugins/PHPMailer/src/PHPMailer.php");
    require("plugins/PHPMailer/src/SMTP.php");
    session_start();

    if(isset($_GET["email"]) && isset($_GET["pass"])){
        $email = $_GET["email"];
        $pass = $_GET["pass"];
        $name = $_GET["name"];

        $mailTo = $email;

        $body = "<p>Good day, ".$name.",<br><br>Your new password is <strong>".$pass."</strong>.Do not share this with anyone!<br><br><i>If you do not have an account at Comunidad or did not request a new password, kindly ignore this email.</i><br><br>Regards,<br>Comunidad</p>";
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

        $mail->addAddress($mailTo, $name);

        $mail->isHTML(true);
        $mail->Subject = "Request for New Password - Comunidad";
        $mail->Body = $body;

        if(!$mail->send()){
            echo "Mailer Error:" .$mail->ErrorInfo;
        }
        else{
            header("Location: page-login.php"); 
            exit;
        }
    }

?>