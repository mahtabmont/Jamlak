<?php
$parameters = array( 
	'webgate_id' 	=> '40725809', 										// Required (20 = Test Mode)
	'amount' 	=> '10000', 												// Required - (Rial)
	'CallbackURL' 	=> 'http://jamlak.ir', 	// Required
	'plugin' 	=> 'other',												// Required
	'order_id' 	=> '2564', 												// Optional
	'phone' 	=> '',													// Optional
	'email'		=> '',													// Optional
	'Description' 	=> ''													// Optional
);

try {
	$client = new SoapClient('http://startpay.ir/webservice/?wsdl' , array('soap_version'=>'SOAP_1_1','cache_wsdl'=>WSDL_CACHE_NONE  ,'encoding'=>'UTF-8'));
	$result = $client->Payment($parameters);
	//print_r($result);
}catch (Exception $e) { echo 'Error'. $e->getMessage();  }

if( isset($result) && $result > 0 ){
        //sendtxt("174034313",'http://startpay.ir/?tid='.$result);

	Header('Location: http://startpay.ir/?tid='.$result);
}else {
	echo "Error Code: ".$result;
	echo "<br/><a href='http://dargahbank.ir/api-guide/' target='_blank' >Help</a>";
}


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
