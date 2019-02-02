<?php
 ob_start();
 session_start();
 require_once 'dbconnect.php';
 
  $uname = $_POST['username'];

  $uname = strip_tags(trim($uname));

  if ($uname!=="") {
  $query="SELECT `c`,`passname` FROM `amlakin` WHERE `senfno`='".$uname."' AND `c`>''";
  $res = $conn->query($query);
  $row= $res->fetch_assoc(); 
  $count = mysqli_num_rows($res); 

  if( $count == 1) {
	sendtxt($row["c"],"رمز  شما :".$row["passname"]);
    $errTyp="alert-success";
    $errMSG="رمز عبور روی تلگرام شما ارسال شد";
   
   //header("Location: home.php");
  } else {
	$errTyp="alert-danger";  
        $errMSG = "<a href='https://telegram.me/jamlakbot'>جهت فعالسازی بات تلگرام را start نمایید</a>";

  }
  $conn->close();
} else {
        $errTyp="alert-info";  
        $errMSG="لطفا ابتدا نام کاربری خود (شماره صنفی) را وارد کنید";
}
  echo json_encode(
  array(
    "errTyp" => $errTyp,
    "errMSG" => $errMSG
    )
);
function sendtxt($user_id,$ms){

    $url = "https://api.telegram.org/bot254204272:AAGw4J_0T2j4x4iQQvcezVJps-0E0i0veqU/sendMessage";
    $content = array(
        'chat_id' => $user_id,
        'text' => $ms
    );

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($content));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// receive server response ...
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec ($ch);

//</send>
}
?>
