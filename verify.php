<?php
$file = fopen("verify.log", "a");
fwrite($file, "Salaam on date: " . date('d-M-Y--h:i:s') . "\n");
include_once 'dbconnect.php';

$MerchantID = '73ee5684-6b5b-11e6-9af4-005056a205be'; 
$Authority = $_GET['Authority'];

if ($_GET['Status'] == 'OK')
{
  $query ="SELECT `paytype` from advertis where authority='" . $Authority . "'"; 
  fwrite($file, "query is : " . $query . "\n");
  $res = $conn->query($query);
  if ($res)
  { 
    $row= $res->fetch_assoc(); 
    $paytype = $row["paytype"];

    if($paytype=='1')
      $Amount = 1000; 
    else//i.e. 2, for now
      $Amount = 10000; 
  

    // URL also can be ir.zarinpal.com or de.zarinpal.com
    $client = new SoapClient('https://www.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

    $result = $client->PaymentVerification([
      'MerchantID'     => $MerchantID,
      'Authority'      => $Authority,
      'Amount'         => $Amount,
    ]);

    if ($result->Status == 100)
    {
      sendtxt("112423114","فوری: یک آگهی جدید پرداخت شد.");
      sendtxt("174034313","فوری: یک آگهی جدید پرداخت شد.");

      $sql = "UPDATE `advertis` SET `paid` = '1' WHERE authority=" . $Authority ;
      fwrite($file, "the sql is:" . $sql . "\n");
      if ($conn->query($sql) === TRUE)
      {
        if(mysqli_affected_rows($conn)==1)
        {
          fwrite($file, "updated advertis table successfully" . "\n");
          $message= "<h3 class='alert alert-success'>تراکنش با موفقیت انجام شد و آگهی شما در ظرف (حداکثر) 24 ساعت در سایت قرار میگیرد.</h3>";
        }
        else
        {
          fwrite($file, "Error updated advertis table: number of effected rows (i.e. with the same authority as given) is NOT 1 !!! Authoity:" . $Authority . "\n");      
          $message= "<h3 class='alert alert-danger'>تراکنش با موفقیت صورت گرفته اما مشکلی  جزیی در یافتن اطلاعات authority در پایگاه داده پیش آمده که به زودی بررسی خواهد شد. </h3>";
        }
      }
      else
      {
        fwrite($file, "error updating advertis table!!! Authoity:" . $Authority . "\n");    
        $message= "<h3 class='alert alert-danger'>تراکنش با موفقیت صورت گرفته اما مشکلی  جزیی در ثبت در پایگاه داده پیش آمده که به زودی بررسی خواهد شد. </h3>";
      }   
    } 
    else
    {
      fwrite($file, "Transation failed. Status:" . $result->Status . " for Authoity:" . $Authority . "\n");    
      $message= "<h3 class='alert alert-danger'>خطا در تراکنش، به زودی با شما تماس گرفته میشود</h3>";
    }
  }
  else
  {
    fwrite($file, "error reading paytype from advertis table!!! Authoity:" . $Authority . "\n");    
    $message= "<h3 class='alert alert-danger'>ضمن عذرخواهی، مشکلی  جزیی در پایگاه داده پیش آمده که به زودی بررسی شده و با شما تماس گرفته خواهد شد.. </h3>";

    sendtxt("112423114","فوری: مشکل در خواندن از جدول آگهی ها.");
    sendtxt("174034313","فوری: مشکل در خواندن از جدول آگهی ها.");    
  }
}
else
{ 
  fwrite($file, "Transaction canceled by user" . " for Authoity:" . $Authority . "\n");    
  $message="<h3 class='alert alert-danger'>تراکنش به درخواست شما لغو شد</h3>";
}

$conn->close();       
fwrite($file, "---------------------------------------------------------------" . "\n");
fclose($file); 

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

<!DOCTYPE html>
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
  <input type="button" class="btn btn-primary" onclick="window.history.go(-4);" value="بازگشت"/>
</div>


</body>
</html>
