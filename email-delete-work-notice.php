<?php
    include("connection.php");
    require("plugins/PHPMailer/src/PHPMailer.php");
    require("plugins/PHPMailer/src/SMTP.php");
    session_start();

    if(isset($_GET["fname"])){
        $artistFname = $_GET["fname"];
        $artistEmail = $_GET["email"];
        $workTitle = $_GET["title"];
        $reportType = $_GET["type"];

        $mailTo = $artistEmail;

        $body = "<p>Good day, ".$artistFname.",<br><br>We received information about your multimedia work and upon reviewing, your post '".$workTitle."' violated our community standards and tagged as ".$reportType." material. Because of this, the administrator has decided to remove the post. Comunidad does not tolerate users going against the community standards.<br><br>Regards,<br>Comunidad</p>";
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
        $mail->Subject = "Post Deleted - Comunidad";
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