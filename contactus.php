<?php
$mobile = $_POST['mobile'];
$name = $_POST['name'];
$msg = $_POST['message'];
$file = fopen("contact.log","w");

require_once 'dbconnect.php';
$date = date('Y-m-d H:i:s');

//escape, just in case
$name = mysqli_real_escape_string($conn, $name);          
$mobile = mysqli_real_escape_string($conn, $mobile);          
$msg = mysqli_real_escape_string($conn, $msg);          
$date = mysqli_real_escape_string($conn, $date);          
if (!empty(trim($msg))){ 
  $sql = "INSERT INTO `contact` (`name`, `mobile`, `msg`, `date`) values('" . $name . "', '" . $mobile . "', '" . $msg . "', '" . $date ."')";
  fwrite($file, "the sql is:" . $sql . "\n");

  if ($conn->query($sql) === TRUE)
  {
    fwrite($file, "inserted into the table successfully, shoooooooookrr" . "\n");
    echo "true" ;
  } 
  else 
  {
    fwrite($file, "errroooooooor inserting into the table" . "\n");
    echo "false" ;
  }
} 
fclose($file);
$conn->close();
?>