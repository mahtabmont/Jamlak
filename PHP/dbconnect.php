<?php

  $servername = "81.94.205.194";
  $username = "jamlakir_user";
  $password = "ras13500";
  $dbname = "jamlakir_besm";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  } 

  $conn->set_charset("utf8");
  header("content-type:text/html;charset=UTF-8");

?>
