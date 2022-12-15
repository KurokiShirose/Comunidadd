<?php
  include("connection.php");
  session_start();

  if(!$_SESSION["username_s"]) {
    header("Location: index.php");
    exit;
  }

  if(isset($_POST["submitFlag"])){
    $userID = $_SESSION["user_id_s"];

    $flagWorkID = $_POST["flagWorkID"];
    $flagType = $_POST["flagType"];
    $flagDesc = $_POST["flagDesc"];

    $sql = "INSERT INTO flagged_works_t(work_id, user_id, type, description, date, action) VALUES ('$flagWorkID', '$userID', '$flagType', '$flagDesc', NOW(), 'Pending')";
    mysqli_query($conn, $sql);

    $sql = "INSERT INTO activity_t(user_id, activity) VALUES (".$_SESSION['user_id_s'].", 'has flagged work #".$flagWorkID."')";  
    mysqli_query($conn, $sql);

    header("Location: page-browse.php");
    exit;

  }

  if(isset($_POST["submitFlag2"])){
    $userID = $_SESSION["user_id_s"];

    $artistID = $_POST["artistID"];
    $flagWorkID = $_POST["flagWorkID"];
    $flagType = $_POST["flagType"];
    $flagDesc = $_POST["flagDesc"];

    $sql = "INSERT INTO flagged_works_t(work_id, user_id, type, description, date, action) VALUES ('$flagWorkID', '$userID', '$flagType', '$flagDesc', NOW(), 'Pending')";
    mysqli_query($conn, $sql);

    $sql = "INSERT INTO activity_t(user_id, activity) VALUES (".$_SESSION['user_id_s'].", 'has flagged work #".$flagWorkID."')";  
    mysqli_query($conn, $sql);

    header("Location: page-view-artist.php?artistID=$artistID");
    exit;

  }

  if(isset($_GET["pass"])){
    $flagWorkID = $_GET["pass"];

    $sql = "UPDATE flagged_works_t SET action = 'Unflagged' WHERE id = '$flagWorkID'";
    mysqli_query($conn, $sql);

    header("Location: page-dashboard-admin.php");
    exit;
  }

  if(isset($_GET["remove"])){
    $flagWorkID = $_GET["remove"];

    // update status in flagged_works_t
    $sql = "UPDATE flagged_works_t SET action = 'Removed' WHERE id = '$flagWorkID'";
    mysqli_query($conn, $sql);

    $sql = "SELECT *, C.user_id AS artist_user_id, A.type AS report_type FROM flagged_works_t A INNER JOIN works_t B ON A.work_id = B.id INNER JOIN artist_profile_t C ON C.artist_id = B.artist_id WHERE A.id = '$flagWorkID'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_array($query);

    $artistID = $result["artist_id"];
    $id = $result["work_id"];

    $artistUserID = $result["artist_user_id"];
    $reportType = $result["report_type"];
    $workTitle = $result["title"];

    // insert to removed_works_t
    $sql = "SELECT * FROM works_t WHERE id = '$id'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_array($query);

    $sql = "INSERT INTO removed_works_t(work_id, artist_id, remarks) VALUES ('$result[0]', '$result[1]', 'Archived')";
    mysqli_query($conn, $sql);

    // update status in works_t
    $sql = "UPDATE works_t SET status = 'Removed' WHERE id = '$id'";
    mysqli_query($conn, $sql);

    // update flags count
    $sql = "SELECT * FROM user_profile_t WHERE user_id = '$artistUserID'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_array($query);

    $artistEmail = $result["email"];
    $artistFname = $result["first_name"];

    $sql = "UPDATE artist_profile_t SET flags = flags + 1 WHERE artist_id = '$artistID'";
    mysqli_query($conn, $sql);

    $sql = "INSERT INTO activity_t(user_id, activity) VALUES (0, 'has removed work user #".$flagWorkID." due to ".$reportType."')";  
    mysqli_query($conn, $sql);

    header("Location: email-delete-work-notice.php?fname=$artistFname&email=$artistEmail&title=$workTitle&type=$reportType");
    exit;
  }

  if(isset($_POST["deleteAcc"])){
    $deleteAcc = $_POST["deleteAcc"];
    $flagWorkID = $_POST["flagWorkID"];
    $artistUserID = $_POST["artistUserID"];
    $artistUsername = $_POST["artistUsername"];
    $artistFname = $_POST["artistFname"];
    $artistEmail = $_POST["artistEmail"];

    $sql = "UPDATE flagged_works_t SET action = 'Removed' WHERE id = '$flagWorkID'";
    mysqli_query($conn, $sql);

    $sql = "SELECT * FROM flagged_works_t A INNER JOIN works_t B ON A.work_id = B.id INNER JOIN artist_profile_t C ON C.artist_id = B.artist_id WHERE A.id = '$flagWorkID'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_array($query);

    $artistID = $result["artist_id"];
    $id = $result["work_id"];

    // update status to all works
    $sql = "UPDATE works_t SET status = 'Removed' WHERE artist_id = '$artistID'";
    mysqli_query($conn, $sql);

    $sql = "INSERT INTO removed_works_t(work_id, artist_id, remarks) SELECT id, artist_id, 'Archived' FROM works_t WHERE artist_id = '$artistID'";
    mysqli_query($conn, $sql);

    // add 1 to flag
    $sql = "UPDATE artist_profile_t SET flags = flags + 1 WHERE artist_id = '$artistID'";
    mysqli_query($conn, $sql);

    // delete login info
    $sql = "UPDATE account_t SET type = 'removed' WHERE username = '$artistUsername'";
    mysqli_query($conn, $sql);

    // copy user info to removed_users_t
    $sql = "SELECT * FROM user_profile_t WHERE username = '$artistUsername'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_array($query);
    $userID = $result["user_id"];

    // copy id and remarks to removed_users_t
    $sql = "INSERT INTO removed_users_t(user_id, remarks) VALUES ('$userID', 'Banned')";
    mysqli_query($conn, $sql);

    // set user_profile_t status to removed
    $sql = "UPDATE user_profile_t SET status = 'Removed' WHERE username = '$artistUsername'";
    mysqli_query($conn, $sql);

    header("Location: email-delete-notice.php?username=$artistUsername&fname=$artistFname&email=$artistEmail");
    exit;
  }

  if(isset($_POST["deleteAccUser"])){
    $userID = $_POST["deleteAccUser"];
    $username = $_POST["username"];
    $firstName = $_POST["firstName"];
    $email = $_POST["email"];

    // add 1 to flag
    $sql = "UPDATE flagged_users_t SET flags = flags + 1 WHERE user_id = '$userID'";
    mysqli_query($conn, $sql);

    // delete login info
    $sql = "UPDATE account_t SET type = 'removed' WHERE username = '$username'";
    mysqli_query($conn, $sql);

    // copy user info to removed_users_t
    $sql = "SELECT * FROM user_profile_t WHERE username = '$username'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_array($query);
    $userID = $result["user_id"];

    // copy id and remarks to removed_users_t
    $sql = "INSERT INTO removed_users_t(user_id, remarks) VALUES ('$userID', 'Banned')";
    mysqli_query($conn, $sql);

    // set user_profile_t status to removed
    $sql = "UPDATE user_profile_t SET status = 'Removed' WHERE username = '$username'";
    mysqli_query($conn, $sql);

    $sql = "INSERT INTO activity_t(user_id, activity) VALUES (0, 'has banned user #".$userID."')";  
    mysqli_query($conn, $sql);

    header("Location: email-delete-notice.php?username=$username&fname=$firstName&email=$email");
    exit;

      
  }
    
  

?>