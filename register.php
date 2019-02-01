<?php
 ob_start();
 session_start();
 include_once 'dbconnect.php';
 $uname = trim($_POST['usernamesignup']);
 //$email = trim($_POST['email']);
 $upass1 = trim($_POST['passwordsignup1']);
 $upass2 = trim($_POST['passwordsignup2']);
 $uname = strip_tags($uname);
 $upass1 = strip_tags($upass1);
 $upass2 = strip_tags($upass2);
 // password encrypt using SHA256();
 if (strcmp($upass1,$upass2)!=0){
	$errTyp = "alert-danger";
    $errMSG="دو رمز عبور را یکسان وارد نمایید"; 
 }	 
  else {
    // $password = hash('sha256', $upass1); 	
 // check email exist or not
     $file=fopen("mytest.txt","w");
     $password=$upass1;
     $query = "SELECT `senfno`,`passname` FROM `amlakin` WHERE `senfno`='".$uname."'";
     fwrite($file,$query);

     $result = $conn->query($query); //mysqli_query($query);     
     $row= $result->fetch_assoc(); //mysqli_fetch_array($result); 
     fwrite($file,"row==".$row["passname"].$row["senfno"]."param=".$uname);

     $count = mysqli_num_rows($result); // if email not found then proceed

     if ($count>0) {
        fwrite($file,"injast".$count.$row['passname']);
  
        if ($row['passname']>''){
                $errTyp = "alert-info";
                $errMSG = "لطفا برای تغییر پسورد گزینه تغییر پسورد را انتخاب کنید";
        //change password
        }
        else {
             //escape, just in case
             $password = mysqli_real_escape_string($conn, $password);          

             $query = "UPDATE `amlakin` SET `passname`=".$password." WHERE `senfno`='".$uname."'";
             $res = $conn->query($query);
             if ($res) {
                $errTyp = "alert-success";
                $errMSG = "عضویت با موفقیت انجام شد";
             } else {
                $errTyp = "alert-danger";
                $errMSG = "خطا در عضویت رخ داد، لطفا مجددا سعی کنید"; 
             } 
        }
   
     } else {
        $errTyp = "alert-danger";
        $errMSG = "اطلاعات شما وجود ندارد، لطفا گزینه افزودن را انتخاب کنید";
    }	
 }
$conn->close();
 echo json_encode(
 array(
    "errTyp" => $errTyp,
    "errMSG" => $errMSG
    )
);
?>
