<?php
 
 include_once 'dbconnect.php';
 //require_once 'myfunctions.php';
 $file=fopen("addmahal.log", "w");
 ob_start();
 session_start();
 if (isset($_SESSION['state']))
    $ostan = trim($_SESSION['state']);
 else
    $ostan = trim($_REQUEST['state']);

 $city = trim($_REQUEST['city']);
 $mahal = cleanedArabicHalfSpaceAndNumbers($_REQUEST['mahal']);
 if (  !empty($ostan) && isset($_REQUEST['mahal']) && !empty($mahal) && isset($_REQUEST['city']) && !empty($city)){

 $ostan   = mysqli_real_escape_string($conn, $ostan);          
 $city   = mysqli_real_escape_string($conn, $city);          
 $mahal   = mysqli_real_escape_string($conn, $mahal);          
 
 $sql = "select * from `omm` where state= '" . $ostan . "' AND `mahal` = '" . $mahal ."' AND `mantaghe` = '".$city."'";
 $result = $conn->query($sql);
 fwrite($file,$sql."avalie"); 
 $count = mysqli_num_rows($result);
 fwrite($file,$count); 
   if( $count == 0) {
      $sql = "INSERT INTO `omm` (`state`, `mantaghe`,`mahal`,`sayer`)";
      $sql .= " values ('" . $ostan . "', '" . $city . "','" . $mahal . "','1')";  
      fwrite($file,$sql."dovomi"); 
   
      if ($conn->query($sql) === TRUE)
        echo "true";
      else 
        echo "false"; 
   } else
        echo "false";
 }
 else echo "false";

//-------------------------------

function cleanedArabicHalfSpaceAndNumbers($s)
{
  $s = str_replace('ك', 'ک', $s );
  $s = str_replace('ئ', 'ی', $s );     
  $s = str_replace('ي', 'ی', $s );     
  $s = str_replace('‌', ' ', $s );     
  $s = str_replace('.', '۰', $s );     
  $s = str_replace('0', '۰', $s );     
  $s = str_replace('1', '۱', $s );     
  $s = str_replace('2', '۲', $s );     
  $s = str_replace('3', '۳', $s );     
  $s = str_replace('4', '۴', $s );     
  $s = str_replace('5', '۵', $s );     
  $s = str_replace('6', '۶', $s );     
  $s = str_replace('7', '۷', $s );     
  $s = str_replace('8', '۸', $s );     
  $s = str_replace('9', '۹', $s );  

  return $s;
}

//----------------------------------------------------------------

?>