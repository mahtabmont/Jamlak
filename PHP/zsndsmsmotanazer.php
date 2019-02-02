<?php

//exit(0);


  $file = fopen("zsndsmsmotanazer_" . date("d-M-Y") . ".log", "a");
  fwrite($file, " بسم الله الرحمن الرحیم " . "\n\n");

  require_once 'dbconnect.php';
  
  $username ="seyedrm";
  $password = "123456";
  $number   = "50002832589"; //"50002210003000";//"50005959645";
  $action='sendmessage';
  $type='1';//for motanazer
  $dodate   = ''; //keep blank ''

  $client = new soapclient('http://sms.trez.ir/XmlForSMS.asmx?WSDL'); //should most likely move up outside the loop, later esA




 for($k=0; $k<10; $k++)
 { 
  $usergroupid = "25_" . $k ; //NOTE NOTE: MUST BE UNIQUE  
  $xfrom = 78000 +  $k   *100;
  $xto   = 78000 + ($k+1)*100;





  $xmlreq='<?xml version="1.0" encoding="UTF-8"?>
  <xmlrequest>
   <username>'.$username.'</username>
   <password>'.$password.'</password>
   <number>'.$number.'</number>
   <action>'.$action.'</action>
   <type>'.$type.'</type>
   <dodate>'.$dodate.'</dodate>
   <message></message>
   <usergroupid>'.$usergroupid.'</usergroupid>
   <body>
     ' ;


  $sql = "select * from `amlakin` where `m` like '0__________'  and `m` not like '______00000'  and `m` not like '______11111'  and `m` not like '______22222'  and `m` not like '______33333' and `c`='' and `x`>=" . $xfrom . " and `x` <" . $xto . " ORDER BY `x` ASC" ;

    fwrite($file, "sql :" . $sql . "\n\n");

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


    $message     = "سلام" . "\n" . "املاک محترم " . $tmpName  . "\n" . "لطفا لینک جامعه مجازی مشاورین املاک استان را start نمایید:" . "\n" . "https://telegram.me/jamlakbot?start=" . $row["ID"] ;


    $potentialmsg = "سلام" . "\n" . "املاک محترم " . $tmpName  . "\n" . "لطفا لینک تلگرام جامعه مجازی مشاورین املاک استان را start نمایید:" . "\n" . "https://telegram.me/jamlakbot?start=" . $row["ID"] ;
    if( mb_strlen($potentialmsg )<=132)
      $message = $potentialmsg;

    $potentialmsg = "سلام" . "\n" . "املاک محترم " . $tmpName  . "\n" . "لطفاً لینک تلگرام جامعه مجازی مشاورین املاک استان را start فرمایید:" . "\n" . "https://telegram.me/jamlakbot?start=" . $row["ID"] ;
    if( mb_strlen($potentialmsg )<=132)
      $message = $potentialmsg;



    $potentialmsg = "سلام" . "\n" . "مشاور محترم املاک " . $tmpName  . "\n" . "لطفاً جهت حضور در جامعه مجازی مشاورین املاک استان، لینک زیر را start فرمایید:" . "\n" . "https://telegram.me/jamlakbot?start=" . $row["ID"] ;
    if( mb_strlen($potentialmsg )<=132)
      $message = $potentialmsg;


//with the web url
/*    $message     = "سلام" . "\n" . "املاک محترم " . $tmpName  . "\n" . "لطفاٌ لینک جامعه مشاورین املاک استان را استارت فرمایید:" . "\n" . "http://jamlak.ir/?id=" . $row["ID"] ;*/


    //fwrite($file, "message:" . $message . "\n" . ", len:" . mb_strlen($message) . " to mobile:" . $row["m"] . "\n");


    $m = trim($row["m"]); 
    $x = $row["x"];
    $xmlreq .=    '<recipient mobile="' . $m . '" doreid="' . $x . '"> ' . $message . ' </recipient>';

  }


  $xmlreq .= '
   </body>
  </xmlrequest>';

  fwrite($file, 'xmlreq is:' . $xmlreq . "\n");

  //send
  $xmlres = $client->getxml(array('xmlString'=>$xmlreq));
  
  //get results to log etc.
  fwrite($file, "xmlres->getxmlresult is: " . $xmlres->getxmlResult . "\n");

  //$xml = simplexml_load_string($xmlres->getxmlResult);
  //$sendmsg = $xml->body->recipient; 
  //fwrite($file, "xml is: " . $sendmsg . "\n");

sleep(1);  
 }//k
  
  fwrite($file, "الحمد لله" . "\n");
  fclose($file);

?>

