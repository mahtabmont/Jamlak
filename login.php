<?php
  ob_start();
  session_start();
  require_once 'dbconnect.php';
 
  $file=fopen("login.log","w");
  $uname = $_POST['username'];
  $upass = $_POST['password'];
  fwrite($file,$upass."  ".$uname);
  
  $uname = strip_tags(trim($uname));
  $password = strip_tags(trim($upass));
  
  //$password = hash('sha256', $upass); // password hashing using SHA256
  
  $query="SELECT `ID`,`name` FROM `amlakin` WHERE `senfno`=" . $uname . " AND `passname`='".$password."'";
  fwrite($file,$query);
  $res = $conn->query($query);
  //$count = mysqli_num_rows($res); // if email not found then proceed

  if ( $res) {
    $row= $res->fetch_assoc(); //mysqli_fetch_array($result); 
  
    $_SESSION['id'] = $row["ID"];

    fwrite($file,$row["name"]);
    $errTyp="alert-success";
    $errMSG="ورود به حساب کاربری به موفقیت انجام شد";
    $user=$row["name"];
    $id=$row["ID"];
  } else {
	$errTyp="alert-danger";  
        $errMSG = "نام کاربری یا رمز عبور اشتباه است، مجددا سعی کنید";
        $user="";
        $id="";
  }
  
  $conn->close();
  echo json_encode(
  array(
    "errTyp" => $errTyp,
    "errMSG" => $errMSG,
    "user"=>$user,
    "id"=>$id
    )
);

?>