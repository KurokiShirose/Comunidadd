<?php
    include("connection.php");
    session_start();

    if($_SESSION["user_type_s"] == "artist" || $_SESSION["user_type_s"] == "client"){
        $sql = "INSERT INTO activity_t(user_id, activity) VALUES (".$_SESSION['user_id_s'].", 'has logged out')";  
        mysqli_query($conn, $sql);
    }
    
    // destroy all session variables
    session_unset();
    session_destroy();

    header("Location: page-login.php");
    exit;
?>