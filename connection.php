<?php
    $server = "localhost";
    $user = "root";
    $password = "";
    $db = "comunidad_db";

    $conn = mysqli_connect($server, $user, $password, $db);

    if(!$conn){
        die("ERROR: Connection failed. " . mysqli_connect_error());
    }
?>

