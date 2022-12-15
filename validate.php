<?php
    function validate($data, $conn){
        $data = trim($data);
        $data = stripcslashes($data);
        $data = mysqli_real_escape_string($conn, $data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>