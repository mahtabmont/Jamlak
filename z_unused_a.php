<?php
$servername = "78.129.241.86";
$username = "jamlakir_user";
$password = "ras13500";
$dbname = "jamlakir_besm";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
} 
$conn->set_charset("utf8");
header("content-type:text/html;charset=UTF-8");

$file=fopen("a.log","w");
fwrite($file, "سلام" . "\n");

//-----------------------------------

$c = $_GET['c'];

  $date = date('Y-m-d H:i:s');
  
  $MerchantID = '73ee5684-6b5b-11e6-9af4-005056a205be';  //Required
  $Amount = 1000; //Amount will be based on Toman  - Required
  $Description = 'افزایش اعتبار';  // Required
  $Email = ''; // Optional
  $Mobile = ''; // Optional
  $CallbackURL = 'http://www.jamlak.ir/v.php?c=' . $c;  // Required

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
    $sql = "INSERT INTO `v` (`c`, `paid`, `date`, `authority`) values('" . $c . "', '0', '" . $date . "', '" . $result->Authority . "')";  
    fwrite($file, "the sql is:" . $sql . "\n");

    if ($conn->query($sql) === TRUE)
    {
      header('Location: https://www.zarinpal.com/pg/StartPay/'.$result->Authority.'/ZarinGate');      
      fwrite($file, "inserting a new transation successfully shokr, for customer with c= " . $c . "\n");
    } 
    else
    {
      fwrite($file, "error inserting a new transation for customer with c= " . $c . "\n");
    }
 }
 else
 {
   fwrite($file, "errroooooooor:" . $result->Status . "\n");
 }

?>
