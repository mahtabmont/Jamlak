<?php
 ob_start();
 session_start();
 include_once 'dbconnect.php';
 $file=fopen("save_adver.log","w");
 fwrite($file, "Salaam on date: " . date('Y-M-d H:i:s') . "\n");

 
 $id= "";
 if(isset($_POST['id']))
   $id = trim($_POST['id']);
 else
   if(isset($_SESSION['id']))
     $id = $_SESSION['id'];
 $paytype=trim($_POST['paytype']);  fwrite($file, "paytype: " . $paytype . "\n");
 $city = trim($_POST['city']);
 $mahal = trim($_POST['mahal']);
 $title = trim($_POST['adtitle']);
 $adtel = trim($_POST['adtel']);
 $mobile = trim($_POST['admobile']);
 $tozihat = trim($_POST['adtozihat']);
 $state = trim($_POST['state']);
 $state = strip_tags($state);
 $title = strip_tags($title);
 $adtel = strip_tags($adtel);
 $mobile = strip_tags($mobile);
 $tozihat = strip_tags($tozihat);
 $date = date('Y-m-d H:i:s');
 $city = strip_tags($city);
 $mahal = strip_tags($mahal);
 
 fwrite($file, "info: " . $title . " , " . $adtel . " , " . $mobile. " , " .  $tozihat . "," . $uploaddir . ", paytype=" . $paytype);



 if(trim($_FILES['image']['name'])!=="")
 {
  //fwrite($file,print_r($_FILES["image"],1));
  $uploaddir = $_SERVER['DOCUMENT_ROOT'].'/adimg/';
  
  //$uploadfile = $uploaddir . basename($_FILES['image']['name']);
  $imgname = time();//to fix en shaa Allah for the case two files are uploaded at the same second
  $initialimgname=basename($_FILES['image']['name']);//question for Mahtab: is this name or tmp_name as below???
  //find ext, if any
  $k = strrpos($initialimgname, '.');
  if($k !== FALSE)
  {
    $ext= substr($initialimgname , 1+$k, strlen($initialimgname));
    $imgname .= "." . $ext;
  }
  $uploadfile = $uploaddir . $imgname;
  fwrite($file, "imgname is:" . $imgname . "\n");
  $image = file_get_contents($_FILES['image']['tmp_name']);
  $f=file_put_contents($uploadfile,$image);
 }
 if ($mobile==false || $title==false || $tozihat==false || $state==false || $city==false || $paytype==''){
     $message= "<h3 class='alert alert-danger'>لطفا اطلاعات خواسته شده را تکمیل بفرمایید</h3>";   
 }	 
 else {
  $MerchantID = '73ee5684-6b5b-11e6-9af4-005056a205be';  //Required
  if($paytype=='1')
    $Amount = 1000; //Amount will be based on Toman  - Required
  else//i.e. 2, for now
    $Amount = 10000; //Amount will be based on Toman  - Required

  $Description = 'هزینه درج آگهی به مدت یک هفته';  // Required
  $Email = ''; // Optional
  $Mobile = $mobile; // Optional
  $CallbackURL = 'http://www.jamlak.ir/verify.php';  // Required
  
  // URL also can be ir.zarinpal.com or de.zarinpal.com
  $client = new SoapClient('https://www.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);
  
  $result = $client->PaymentRequest([
    'MerchantID'     => $MerchantID,
    'Amount'         => $Amount,
    'Description'    => $Description,
    'Email'          => $Email,
    'Mobile'         => $Mobile,
    'CallbackURL'    => $CallbackURL,
  ]);
  //Redirect to URL You can do it also by creating a form
  if ($result->Status == 100)
  {
    //escape, just in case
    $id= mysqli_real_escape_string($conn, $id);          
    $title= mysqli_real_escape_string($conn, $title);          
    $adtel = mysqli_real_escape_string($conn, $adtel );          
    $mobile = mysqli_real_escape_string($conn, $mobile );          
    $tozihat= mysqli_real_escape_string($conn, $tozihat);          
    $date= mysqli_real_escape_string($conn, $date);          
    $state= mysqli_real_escape_string($conn, $state);          
    $city= mysqli_real_escape_string($conn, $city);          
    $mahal= mysqli_real_escape_string($conn, $mahal);          
    $paytype= mysqli_real_escape_string($conn, $paytype);          
    
    //our added code to record the purchase and authority:
    $sql = "INSERT INTO `advertis` (`title`, `adtel`, `mobile`, `tozihat` , `date`,`state`, `mantaghe`, `mahal`, `paytype`";
    if(isset($_FILES["image"]))
      $sql .= ", `image`";
    $sql .= ", `authority`, `paid`, `cust_ID`) values('" . $title . "', '" . $adtel . "', '" . $mobile . "', '" . $tozihat. "', '" . $date. "', '" . $state. "', '" . $city. "', '" . $mahal. "', '" . $paytype;  
    if(isset($_FILES["image"]))
      $sql .= "','" . $imgname;
    $sql .= "', '" . $result->Authority . "', '0', '" . $id . "')";
    fwrite($file, "the sql is:" . $sql . "\n");

    if ($conn->query($sql) === TRUE){
      $errTyp = "alert-success";
      $errMSG = "ثبت آگهی با موفقیت انجام شد";
      //header('Location: https://www.zarinpal.com/pg/StartPay/'.$result->Authority);
      //برای استفاده از زرین گیت باید ادرس به صورت زیر تغییر کند:
      header('Location: https://www.zarinpal.com/pg/StartPay/'.$result->Authority.'/ZarinGate');      
    } else {
       $errTyp = "alert-danger";
       $errMSG = "خطا در ثبت آگهی رخ داد، لطفا مجددا سعی کنید"; 
       
       sendtxt("112423114","خطا در ثبت آگهی در save_adver.");
       sendtxt("174034313","خطا در ثبت آگهی در save_adver.");    
       }
 }
 else
 {
   $errMSG='ERR: '.$result->Status;
   $message= "<div class='alert alert-danger'>".$errMSG."</div>";
   fwrite($file, "errroooooooor:" . $result->Status . "\n");

   sendtxt("112423114","خطا در ثبت آگهی در save_adver - قبل از پرداخت.");
   sendtxt("174034313","خطا در ثبت آگهی در save_adver - قبل از پرداخت.");    
 }
}

$conn->close();
fwrite($file, "---------------------------------------------------------------" . "\n");
fclose($file); 

/* echo json_encode(
 array(
    "errTyp" => $errTyp,
    "errMSG" => $errMSG
    )
);*/


//-----------------------------------------------------------------
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

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec ($ch);

}


?>

