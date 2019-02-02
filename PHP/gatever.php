<?php
include_once 'dbconnect.php';
$file = fopen('gatever.log', 'a');
if(isset($_GET['c']))
{
  $c = trim($_GET['c']);
  if(isset($_POST['status']))
  {
    $order_id 	= $_POST['order_id'];
    $tran_id 	= $_POST['tran_id'];
    $amount 	= $_POST['amount'];
    $status 	= $_POST['status'];
    $refcode	= $_POST['refcode'];
	
    if($status == 'paid')	
    {
      $parameters = array(
	'webgate_id'	=> '40725809',	// Required
	'tran_id' 	=> $tran_id,	// Required
	'amount' 	=> $amount		// Required
	);
      try
      {
	$client = new SoapClient('http://startpay.ir/webservice/?wsdl' , array('soap_version'=>'SOAP_1_1','cache_wsdl'=>WSDL_CACHE_NONE ,'encoding'=>'UTF-8'));
	$result = $client->PaymentVerification($parameters);
      }
      catch (Exception $e)
      { 
        //echo 'Error'. $e->getMessage(); 
        fwrite($file, "errroooooooor:" . $e->getMessage() . "\n");
        sendtxt($c, 'ضمن عذرخواهی، خطا پیش آمد.');     
      }

      if ($result == 1)
      {
        //echo "transaction paid. refcode :" .$refcode;
        sendtxt("112423114","پرداخت.");
        sendtxt("174034313","شکر.");

        $sql = "UPDATE `v` SET `paid` = '1', `paydate` ='" . date('d-M-Y  h:i:s') . "' WHERE ID=" . $order_id ;
        fwrite($file, "the sql is:" . $sql . "\n");
        if ($conn->query($sql) === TRUE)
        {
          fwrite($file, "updated v table successfully" . "\n");
          $msg= "تراکنش با موفقیت انجام شد.";
          $message= "<h3 class='alert alert-success'>تراکنش با موفقیت انجام شد.</h3>";
        
          //also, increase the credit; NOTE here, susceptible to secutity issue, to deal with soon en shaa Allah
          $sql = "UPDATE `akkasbashi` SET `credit` = `credit` + 10 , lastcreditdate='" . date('d-M-Y  h:i:s') . "' WHERE c=" . $c ;
          if ($conn->query($sql) === TRUE)
          {
            fwrite($file, "updated credit in akkasbash table successfully at " . date('h:i:s on d-M-Y') . "\n");
          }          
          else
          {
            $msg = 'تراکنش با موفقیت صورت گرفته اما مشکلی  جزیی در ثبت در پایگاه داده پیش آمده که به زودی بررسی خواهد شد. مسلماً اعتبار شما محفوظ است';  
            fwrite($file, "Error updating credit in akkasbashi table, sql is: " . $sql . "\n");      
          }
        }
        else
        {
          $msg = 'تراکنش با موفقیت صورت گرفته اما مشکلی  جزیی در ثبت در پایگاه داده پیش آمده که به زودی بررسی خواهد شد. مسلماً اعتبار شما محفوظ است';  
          fwrite($file, "error updating v table!!! Authoity:" . $order_id . "    sql is: " . $sql . "\n");    
        }   
      }
      else
      {
        $msg = 'ضمن عذرخواهی، خطا صورت گرفت. مسلماً اعتبار شما کماکان محفوظ است.';  
        fwrite($file, "erroorr, with Error Code:" . $result . "\n");
      }
    }
  }
  else
  {
    fwrite($file, "Transaction canceled by user" . "\n");  
    $msg = 'تراکنش توسط کاربر لغو شد';  
  }

}
else
{
  fwrite($file, "No c or Transaction canceled by user" . "\n");  
  $msg = 'تراکنش صورت نگرفت.';  
}

//ma shaa Allah la ghovvata ella beAllah
header('Location: https://telegram.me/akkasbashibot');      
fwrite($file, "sending " . $msg . " to user on telegram." . "\n");
sendtxt($c, $msg);
sendtxt("112423114", $msg);
sendtxt("174034313", $msg);


$conn->close();       
fwrite($file, "---------------------------------------------------------------" . "\n");
fclose($file); 
//-----------------------------------

function sendtxt($user_id,$ms){

    //$url = "https://api.telegram.org/bot254204272:AAGw4J_0T2j4x4iQQvcezVJps-0E0i0veqU/sendMessage";
    $url = "https://api.telegram.org/bot263233217:AAGwKWJc2wZKtrBQpKSQxA0rcrUDOl9Kihg/sendMessage";
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


<!----DOCTYPE html>
<html lang="en">
<head>
  <title>تاییدیه تراکنش</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap-3.3.7-dist/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
</head>
<body>

<div class="jumbotron text-center">
  <?php echo $message; ?> 
  <?php echo 'لطفاً این صفحه را ببندید و به تلگرام برگردید.'; ?> 
  <input type="button" class="btn btn-primary" onclick="location.href='https://telegram.me/akkasbashibot'" value="بازگشت"/>

</div>

</body>
</html------>
