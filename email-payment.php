<?php
    include("connection.php");
    require("plugins/PHPMailer/src/PHPMailer.php");
    require("plugins/PHPMailer/src/SMTP.php");
    session_start();

    $username = $_SESSION['username_s'];
    $id = $_SESSION["commission_id_s"];

    $sql = "SELECT * FROM payment_t A INNER JOIN commission_t B ON A.req_id = B.req_id INNER JOIN user_profile_t C ON B.client_id = C.user_id WHERE B.req_id = '$id'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_array($query);

    $mailTo = $result["email"];

    $subj = "Comunidad - Payment Notice";

    if($result["payment_option"] == "Online Payment"){
        $body = "<p>Good day, ".$result["first_name"].",<br><br>The artist from your commission request #".$id." has completed the commission. To receive the final commission work, kindly pay the agreed payment with the following details:<br><br>Payment Option: ".$result["payment_option"]."<br>Payment Method: ".$result["payment_method"]."<br>Service Provider: ".$result["service_provider"]."<br>Account Number: ".$result["acc_number"]."<br>Account Name: ".$result["acc_name"]."<br>Other Details: ".$result["online_details"]."<br><br>Once payment is settled, you will receive the final output in your email and a completion feedback and rating form will be available to view in your dashboard.<br><br><i>Disclaimer: Your choice of proceeding on finishing the commission between you and the artist, with knowledge that the payment for the commission work will be fully paid through a third party service or external transaction, is your sole decision and does not hold Comunidad responsible for any scams.</i><br><br>Regards,<br>Comunidad</p>";
    }
    else{
        $body = "<p>Good day, ".$result["first_name"].",<br><br>The artist from your commission request #".$id." has completed the commission. To receive the final commission work, kindly pay the agreed payment with the following details:<br><br>Payment Option: ".$result["payment_option"]."<br>Meet-up Location: ".$result["meet_location"]."<br>Meet-up Date: ".$result["meet_date"]."<br>Other Details: ".$result["meet_details"]."<br><br>Once payment is settled, you will receive the final output in your email and a completion feedback and rating form will be available to view in your dashboard.<br><br><i>Disclaimer: Your choice of proceeding on finishing the commission between you and the artist, with knowledge that the payment for the commission work will be fully paid through a third party service or external transaction, is your sole decision and does not hold Comunidad responsible for any scams.</i><br><br>Regards,<br>Comunidad</p>";
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

    $mail->isHTML(true);
    $mail->Subject = $subj;
    $mail->Body = $body;


    if(!$mail->send()){
        echo "Mailer Error:" .$mail->ErrorInfo;
    }
    else{
        $sql = "UPDATE commission_t SET status = 'Pending Payment' WHERE req_id = '$id'";
        $query = mysqli_query($conn, $sql);

        $sql = "INSERT INTO activity_t(user_id, activity) VALUES (".$_SESSION['user_id_s'].", 'has sent a commission completion report in commission #".$id."')";  
        mysqli_query($conn, $sql);

        echo "<script>window.location.assign('page-dashboard-artist.php')</script>";
        exit;
    }

?>