<?php
// to get the ncf, nsf, n values of a data:
// SELECT (cf1+cf2+cf3)/3 AS ncf, (sf1+sf3+sf5+sf7+sf9)/5 AS nsf, (((cf1+cf2+cf3)/3)*.60)+(((sf1+sf3+sf5+sf7+sf9)/5)*.40) AS n, ((((cf1+cf2+cf3)/3)*.60)+(((sf1+sf3+sf5+sf7+sf9)/5)*.40))*100 AS total FROM profile_score_t

  include("connection.php");
  session_start();

  $artistID = $_SESSION["idToCluster_s"];

  $sql = "SELECT * FROM profile_score_t WHERE artist_id = '$artistID'";
  $query = mysqli_query($conn, $sql);
  $row_data = mysqli_fetch_array($query);

  $final = $row_data[16]*100;
    
  $command = escapeshellcmd("py python.py $row_data[14] $row_data[15] $row_data[16] $final $artistID");
  $output = shell_exec($command);

  // echo $output;

  $sql = "UPDATE profile_score_t SET cluster = '$output' WHERE artist_id = '$artistID'";
  mysqli_query($conn, $sql);

  header("Location: page-browse.php");
?>