<?php
 ob_start();
 session_start();
 require_once 'dbconnect.php';
 
 // it will never let you open index(login) page if session is set
 /*if ( isset($_SESSION['user'])!="" ) {
  header("Location: home.php");
  exit;
 }*/
   $file=fopen("change_pass_log.txt","w");
 fwrite($file,"salaam");
  $uname = $_POST['username'];
  $passold = $_POST['passold'];
  $pass1 = $_POST['passid1'];
  $pass2 = $_POST['passid2'];
  fwrite($file,$uname.$passold.$pass1.$pass2);
    
  $uname = strip_tags(trim($uname));
  $passold = strip_tags(trim($passold));
  $pass1 = strip_tags(trim($pass1));
  $pass2 = strip_tags(trim($pass2));

  if (($uname!="") && ($pass1==$pass2)){
    //escape, just in case
    $pass1 = mysqli_real_escape_string($conn, $pass1);          
    
    $query = "UPDATE `amlakin` set `passname`= '" . $pass1 ."' WHERE `senfno`='".$uname."' AND `passname`='".$passold."'"; 
    fwrite($file,$query);
    $res=$conn->query($query);
    $count = mysqli_affected_rows($conn);
    fwrite($file,"count=".$count);   
    if ($count>0) {
	    echo "true";
   //header("Location: home.php");
  } else {
	    echo "false";
  }
    $conn->close();
} else {
        echo "false";
}

?>