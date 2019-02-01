<?php
//ma shaa Allah la ghovvata ella beAllah 

exit;


  require_once 'dbconnect.php';
  //include_once 'gregorian_to_jalali.php';
  date_default_timezone_set('Asia/Tehran');
  $file=fopen("zsendtelall2.log","w");
  fwrite($file, "salaam" . "\n\n");
  fwrite($file, "started on " . date("d-M-Y") . " at " . date("H:i:s") . "\n\n");
  
  $MOD = 60;

  $sql_tmpturn2_select= "select `tmpturn2` from zsch"; 
  $res_tmpturn2_select = $conn->query($sql_tmpturn2_select);
  $row_tmpturn2_select= $res_tmpturn2_select->fetch_assoc();   
  $tmpturn2 = $row_tmpturn2_select["tmpturn2"];   
  fwrite($file, "tmpturn2 is:" . $tmpturn2 . "\n");
  
  $newtmpturn2 = ($tmpturn2+1)%$MOD;
  $sql_tmpturn2_update = "UPDATE `zsch` SET `tmpturn2`=" . $newtmpturn2 ;
  if ($conn->query($sql_tmpturn2_update) === TRUE)
  {
    fwrite($file, "updated tmpturn2 to " . $newtmpturn2 . " successfully, shokr" . "\n");
  }
  else 
  {
    fwrite($file, "error updaing tmpturn2" . "\n");         
  }    

  //sheduled



  $sql_amlaki = "select * from `amlakin` where  `c` = '174034313'  OR  ( (`unsub`<>1) AND (`c` <> '') AND (`c`%" . $MOD . "=" . $tmpturn2 . ") ) order by x";
  fwrite($file, "sql_amlaki is:" . $sql_amlaki . "\n");
  fwrite($file, "----------------------------------------------------------------------------------------------------------------" . "\n");         
  
  $res_amlaki = $conn->query($sql_amlaki);
  while($row_amlaki = $res_amlaki ->fetch_assoc())
  {  
    $amlakiID = $row_amlaki["ID"];
    $amlakic = $row_amlaki["c"];
    $amlakiname = $row_amlaki["name"];

        $tmpName = $amlakiname;
        $i = strrpos($tmpName, "املاک و مستغلات"); 
        if($i !== FALSE)
        {
          $tmpName = trim(substr($tmpName, $i+29));
          $j = strpos($tmpName, " ");
          if( ($j !== FALSE) && ($j < 2) )
            $tmpName = trim(substr($tmpName, $j+1));
        }
        else
        {
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
            else
            {
              $i = strrpos($tmpName, "بنگاه معاملاتی");                                       
              if($i !== FALSE)
              {
                $tmpName = trim(substr($tmpName, $i+28));
                $j = strpos($tmpName, " ");
                if( ($j !== FALSE) && ($j < 2) )
                  $tmpName = trim(substr($tmpName, $j+1));
              }
              else
              {
                $i = strrpos($tmpName, "بنگاه معاملات ملکی");                                       
                if($i !== FALSE)
                {
                  $tmpName = trim(substr($tmpName, $i+35));
                  $j = strpos($tmpName, " ");
                  if( ($j !== FALSE) && ($j < 2) )
                    $tmpName = trim(substr($tmpName, $j+1));
                }
              }
            }
          }
        }



    //en shaaAllah:
    $link = "http://jamlak.ir?id=" . $row_amlaki["ID"] ;
    $ms3 = "سلام" . "\n" . "املاک محترم " . $tmpName . "\n";
        $ms3 .= "به اطلاع میرساند با توجه به تماسهای متعدد املاکین محترم، هزینه درج آگهی به یک هزار تومان در هفته برای نمایش در شهر مربوطه کاهش یافت. هزینه نمایش آگهی در کل کشور به مدت یک هفته نیز ده هزار تومان تعیین شد. " .  "\n";
        $ms3 .= $link . "\n\n";
    $ms3 .=  "با آرزوی توفیق" . "\n";
    $ms3 .=  "\n\xF0\x9F\x8C\xBA خدا نگهدار\xF0\x9F\x8C\xBA" ;
    
    fwrite($file, "sending text: \n" . $ms3 . "\nto c:" . $amlakic . "\n");     
//    sendtxt($ms3, $amlakic);
//    sendtxt($ms3, "174034313");
}//while

fwrite($file, "shokr" . "\n");     
fclose($file);

//------------------------------------------------------------------------------

function sendtxt($ms, $user_id)
{
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
}


?>