<?php
    include("connection.php");
    require("plugins/PHPMailer/src/PHPMailer.php");
    require("plugins/PHPMailer/src/SMTP.php");
    session_start();

    $username = $_SESSION['username_s'];
    $id = $_SESSION["commission_id_s"];
    $description = $_SESSION["description_s"];

    $sql = "SELECT * FROM user_profile_t A INNER JOIN commission_t B ON A.user_id = B.client_id WHERE B.req_id = '$id'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_array($query);

    $mailTo = $result["email"];

    if($_SESSION["complete_s"] > 0){
        $subj = "Comunidad - Commission Completed";
        $body = "<p>Good day, ".$result["first_name"].",<br><br>The artist from your commission request #".$id." has sent you the commission completion report:<br><br>".$description."<br><br>The completion feedback and rating form is now available to view in your dashboard.<br><br><i>This is an auto-generated email. If you did not request for a commission, or believe that you received this in error, please ignore this email.</i><br><br>Regards,<br>Comunidad</p>";
    }
    else{
        $subj = "Comunidad - Commission Request Update";
        $body = "<p>Good day, ".$result["first_name"].",<br><br>The artist from your commission request #".$id." has sent you a progress report:<br><br>".$description."<br><br><i>This is an auto-generated email. If you did not request for a commission, or believe that you received this in error, please ignore this email.</i><br><br>Regards,<br>Comunidad</p>";
    }

    $mail = new PHPMailer\PHPMailer\PHPMailer();

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

    if(!empty($_SESSION["filename_s"])){
        $filename = $_SESSION["filename_s"];
        $mail->AddAttachment("./assets/img/works/".$filename, $filename);
    }

    $mail->isHTML(true);
    $mail->Subject = $subj;
    $mail->Body = $body;


    if(!$mail->send()){
        echo "Mailer Error:" .$mail->ErrorInfo;
    }
    else{
        $sql = "INSERT INTO activity_t(user_id, activity) VALUES (".$_SESSION['user_id_s'].", 'has sent commission progress in commission #".$id."')";  
        mysqli_query($conn, $sql);

        if($_SESSION["complete_s"] == 1){
            $sql = "UPDATE commission_t SET status = 'Pending Rating' WHERE req_id = '$id'";
            $query = mysqli_query($conn, $sql);

            echo "<script>window.location.assign('page-rating.php')</script>";
            exit;
        }
        else{
            echo "<script>window.location.assign('page-dashboard-artist.php')</script>";
            exit;
        }
    }

?>