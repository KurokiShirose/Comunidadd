<?php
    include("connection.php");
    require("plugins/PHPMailer/src/PHPMailer.php");
    require("plugins/PHPMailer/src/SMTP.php");
    session_start();

    if(isset($_GET["username"])){
        $artistUsername = $_GET["username"];
        $artistFname = $_GET["fname"];
        $artistEmail = $_GET["email"];

        $mailTo = $artistEmail;

        $body = "<p>Good day, ".$artistFname.",<br><br>We received information about your multimedia works and commissions and upon reviewing, your account has gone against our community standards. Because of this, the administrator has decided to terminate and remove your profile account. For artist accounts, uploaded works are also deleted. Comunidad does not tolerate users going against the community standards.<br><br>Regards,<br>Comunidad</p>";
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

        $mail->addAddress($mailTo, $artistFname);

        $mail->isHTML(true);
        $mail->Subject = "Account Termination - Comunidad";
        $mail->Body = $body;

        if(!$mail->send()){
            echo "Mailer Error:" .$mail->ErrorInfo;
        }
        else{
            header("Location: page-dashboard-admin.php"); 
            exit;
        }
    }

?>