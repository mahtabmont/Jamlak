<?php
exit(0);
  $file = fopen("zsndsms2_" . date("dmy") . ".log", "w");
  fwrite($file, " بسم الله الرحمن الرحیم " . "\n\n");
  require_once 'dbconnect.php';




  $sql = "select * from `amlakin` where `state` = 'البرز' and `city` = 'کرج' and `m` like '0__________'  and `m` not like '______00000'  and `m` not like '______11111'  and `m` not like '______22222'  and `m` not like '______33333' and `chatid`='' and `x`>= 5500 and `x`= 124710 ORDER BY `x` ASC" ;
    fwrite($file, "sql :" . $sql . "\n");

  $res = $GLOBALS['conn']->query($sql);

  while($row = $res->fetch_assoc())
  {
    
    $tmpName = $row["name"];
    $i = strrpos($tmpName, "املاک"); 
    if($i !== FALSE)
    {
      $tmpName = trim(substr($tmpName, $i+10));
      $j = strpos($tmpName, " ");
      if( ($j !== FALSE) && ($j < 2) )
        $tmpName = trim(substr($tmpName, $j+1));
    }
    else
    {
      $i = strrpos($tmpName, "املاك");                                       
      if($i !== FALSE)
      {
        $tmpName = trim(substr($tmpName, $i+10));
        $j = strpos($tmpName, " ");
        if( ($j !== FALSE) && ($j < 2) )
          $tmpName = trim(substr($tmpName, $j+1));
      }
    }
    //fwrite($file, "for name:" . $row["name"] . ", tmpName is:" . $tmpName . "\n");



// with orininal bot link
//    $message     = "سلام" . "\n" . "املاک محترم " . $tmpName  . "\n" . "لطفا لینک تلگرام جامعه مجازی مشاورین املاک را start کنید:" . "\n" . "https://telegram.me/jamlakbot?start=" . $row["ID"] ;


    $message     = "سلام" . "\n" . "املاک محترم " . $tmpName  . "\n" . "لطفاٌ اطلاعات بنگاه خود در جامعه مجازی مشاورین را ویرایش فرمایید:" . "\n" . "http://jamlak.ir";

   

   fwrite($file, "message:" . $message . "\n" . ", len:" . mb_strlen($message) . " to mobile:" . $row["m"] . "\n");






    $mobile      = trim($row["m"]); 



    ini_set("soap.wsdl_cache_enabled", "0");
    $sms_client = new SoapClient('http://payamak-service.ir/SendService.svc?wsdl', array('encoding'=>'UTF-8'));

    try
    {
      $parameters['userName'] = "s.seyed_r_mousavi";
      $parameters['password'] = "89926";
      $parameters['fromNumber'] = "50005708615693";

      $parameters['toNumbers'] = array($mobile);
      $parameters['messageContent'] = $message;

      $parameters['isFlash'] = false;
      $recId = array();
      $status = array();
      $parameters['recId'] = &$recId ;
      $parameters['status'] = &$status ;

      fwrite($file, $sms_client->SendSMS($parameters)->SendSMSResult . "\n");
    } 
    catch (Exception $e) 
    {
      fwrite($file, 'Caught exception: ' . $e->getMessage() . "\n");
    }


    usleep(100000);//not sure whether/how needed

  }//while

  fwrite($file, "الحمد لله" . "\n");
  fclose($file);

?>
