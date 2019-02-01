<?php

  //exit();
  
  require_once 'dbconnect.php';
  //include_once 'gregorian_to_jalali.php';
  date_default_timezone_set('Asia/Tehran');
  $file = fopen("zschremind.log","w");
  fwrite($file, "salaam" . "\n\n");
  fwrite($file, "started on " . date("d-M-Y") . " at " . date("H:i:s") . "\n\n");
  
  $MOD = 28;

  $sql_remindturn_select= "select `remindturn` from zsch"; 
  $res_remindturn_select = $conn->query($sql_remindturn_select);
  $row_remindturn_select= $res_remindturn_select->fetch_assoc();   
  $remindturn = $row_remindturn_select["remindturn"];   
  fwrite($file, "remindturn is:" . $remindturn . "\n");
  
  $newremindturn = ($remindturn+1)%$MOD;
  $sql_remindturn_update = "UPDATE `zsch` SET `remindturn`=" . $newremindturn ;
  if ($conn->query($sql_remindturn_update) === TRUE)
  {
    fwrite($file, "updated remindturn to " . $newremindturn . " successfully, shokr" . "\n");
  }
  else 
  {
    fwrite($file, "error updaing remindturn" . "\n");         
  }    

  //sheduled
  $sql_amlaki = "select * from `amlakin` where `c` <> '' AND (`mantaghe` = '' OR `mahal` = '') AND (`c`%" . $MOD . "=" . $remindturn . " OR `c` = '174034313' OR `c` = '112423114') order by `x` " ;// en shaa Allah soon for all
  fwrite($file, "sql_amlaki is:" . $sql_amlaki . "\n");
  fwrite($file, "----------------------------------------------------------------------------------------------------------------" . "\n");         
  
  $res_amlaki = $conn->query($sql_amlaki);
  while($row_amlaki = $res_amlaki ->fetch_assoc())
  {
    $amlakiID = $row_amlaki["ID"];
    $amlakistate = $row_amlaki["state"];
    $amlakic = $row_amlaki["c"];
    $amlakiname = $row_amlaki["name"];
    //$amlakim = $row_amlaki["m"];

    $tmpmantaghe = $tmpmahal = "";
    if(strlen($row_amlaki["mantaghe"]) > 0)
      $tmpmantaghe = $row_amlaki["mantaghe"];
    else
      $tmpmantaghe = $row_amlaki["mantaghe2"];

    if(strlen($row_amlaki["mahal"]) > 0)
      $tmpmahal = $row_amlaki["mahal"];
    else
      $tmpmahal = $row_amlaki["mahal2"];


      //to further optimize soon en shaaAllah:
      //for now only look at mahal 2 of posts and see if matches mahal or even mantaghe of amlaki; a rule would help this heuristic: no mahal has identiacal name as a mantaghe within a state

      $tmpmantaghe = str_replace(' ', '', $tmpmantaghe);
      $tmpmahal = str_replace(' ', '', $tmpmahal);

      $tmpSinceSec = floor(time()/86400)*86400 - 86400;
      //to compare these without blanks soon en shaa Allah
      $sql_scheduled = "select count(*) as N from `cnotes` where `state`='" . $amlakistate . "' AND (replace(`mahal2`,' ','')='" . $tmpmahal . "') AND `mahal2`<>'' AND `mantaghe2`<>'' AND `time`-`howmanysecago` >= " . $tmpSinceSec;

      fwrite($file, "sql_scheduled is:" . $sql_scheduled . "\n");
      $res_scheduled = $conn->query($sql_scheduled);
      $row_scheduled = $res_scheduled ->fetch_assoc();
      $N = $row_scheduled["N"];//for now only number of recent posts, to include a summary as well soon en shaa Allah
      if($N > 0)
      {
        //send
        fwrite($file, "\n" . "skipped as at least one post was found for c:" . $amlakic . " with tmpmantaghe :" . $tmpmantaghe . ", tmpmahal:" . $tmpmahal . " and addr:" . $row_amlaki["addr"] . " link: " . "jamlak.ir/cnote.php?id=" . $amlakiID  . "\n\n");        
      else
      {
        //send en shaa Allah
        $link = "jamlak.ir/cnote.php?id=" . $amlakiID ;

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
        $txt = "سلام" . "\n" . "املاک محترم " . $tmpName  . "\n" . "لطفاً جهت اطلاع از درخواستهای مشتریان محدوده شما، منطقه کاری خود را در لینک زیر ویرایش فرمایید:" . "\n" . $link . "\n با تشکر و احترام" . "\n\xF0\x9F\x8C\xBA خدا نگهدار\xF0\x9F\x8C\xBA" ;

    
        fwrite($file, "sending text: \n" . $txt . "\nto c:" . $amlakic . "with tmpmantaghe :" . $tmpmantaghe . ", tmpmahal:" . $tmpmahal . " and addr:" . $row_amlaki["addr"] . "\n");
        sendtxt($amlakic, $txt, $file);
      }
      fwrite($file, "----------------------------------------------------------------------------------------------------------------" . "\n");         
  }//while

 $conn->close();//not sure whether or not needed/preferred

 fwrite($file, "al hamdo leAllah , finished on " . date("d-M-Y") . " at " . date("H:i:s") . "\n\n");
 fclose($file);

//--------------------------

function sendtxt($c, $ms, $file)
{
  fwrite($file, "I am here in the sendtxt function" . "\n");
  $url = "https://api.telegram.org/bot254204272:AAGw4J_0T2j4x4iQQvcezVJps-0E0i0veqU/sendMessage";
  $content = array(
    'chat_id' => $c,
    'text' => $ms
  );

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($content));
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

  //receive server response ...
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_exec ($ch);
}

?>


