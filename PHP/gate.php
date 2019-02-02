<?php
include_once 'dbconnect.php';
$file = fopen('gate.log', 'w');
if(isset($_GET['a']))
{
  $a = trim($_GET['a']);
  $len = strlen($a);
  if($len > 5 && $len <12) //for now, to enhance soon en shaaAllah
  {
    $c = $a;
    //insert a record in v for a purchase hopefully
    $sql = "INSERT INTO `v` (`c`, `paid`) values('" . $c . "', '0')";  
    if ($conn->query($sql) === TRUE)
    {
      $order_id = $conn->insert_id;      //en shaaAllah

      $tmpcallbackurl = 'http://www.jamlak.ir/gatever.php?c=' . $c;
      $parameters = array( 
  	  'webgate_id' 	=> '40725809', 	
          'amount' 		=> '10000',
	  'CallbackURL' 	=> $tmpcallbackurl, 
	  'plugin' 		=> 'other',
	  'order_id' 		=> $order_id, 
	  'phone' 		=> '',	
	  'email'		=> '',	
 	  'Description' 	=> 'افزایش اعتبار' 
      );

      try
      {  
        $client = new SoapClient('http://startpay.ir/webservice/?wsdl' , array('soap_version'=>'SOAP_1_1','cache_wsdl'=>WSDL_CACHE_NONE  ,'encoding'=>'UTF-8'));
        $result = $client->Payment($parameters);
      } 
      catch (Exception $e)
      {
        fwrite($file, "errroooooooor:" . $e->getMessage() . "\n");
        sendtxt($c, 'ضمن عذرخواهی، خطا پیش آمد.');     
      }

      if( isset($result) && $result > 0 )
      {
  	Header('Location: http://startpay.ir/?tid='.$result);
      }
      else 
      {
        fwrite($file, "error ocured, with Error Code: " . $result . "\n");
        sendtxt($c, 'ضمن عذرخواهی، خطا پیش آمد.');     
      }
    }
    else
    {
      fwrite($file, "error inserting a new transation for customer with c= " . $c . " the sql is: " . $sql . "\n");
      sendtxt($c, 'ضمن عذرخواهی، خطا پیش آمد.');     
    }
  }
}

fclose($file);


//----------------------------------------------------------

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



