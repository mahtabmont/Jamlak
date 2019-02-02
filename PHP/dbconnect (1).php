<?php
  $servername = "89.187.86.7";
  $username = "jamlakco_aaa";
  $password = "abc1234567";
  $dbname = "jamlakco_jamlakir_besm";
  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error)
  {
    //give it a try in a sec.
    sleep(1);
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error)
    {
      //last try in two sec.
      sleep(2);
      $conn = new mysqli($servername, $username, $password, $dbname);
      if ($conn->connect_error)
      {
        die("Connection failed: " . $conn->connect_error);
      }
    }
  } 

  $conn->set_charset("utf8");
  header("content-type:text/html;charset=UTF-8");


/*
$conn = db_connect();

function db_connect() 
{
  static $conn;

    // Try and connect to the database, if a connection has not been established yet
  if(!isset($conn)) 
  {
    $config = parse_ini_file('../param.ini'); 
    $servername = $config['servername'];  //"78.129.241.86";
    $username   = $config['username'];    //"jamlakir_user";
    $password   = $config['password'];    //"ras13500";
    $dbname     = $config['dbname'];      //"jamlakir_besm";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) 
    {
      die("Connection failed: " . $conn->connect_error);
    } 
    $conn->set_charset("utf8");    
    header("content-type:text/html;charset=UTF-8");
  }
  return $conn;
}
*/

?>
