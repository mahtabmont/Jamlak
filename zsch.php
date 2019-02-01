<?php

//exit(); 



  $DEBUG_MOD = 0;//1;
  require_once 'dbconnect.php';
  //include_once 'gregorian_to_jalali.php';
  //date_default_timezone_set('Asia/Tehran');
  $file = fopen("zsch.log","w");
  fwrite($file, "salaam" . "\n\n");
  fwrite($file, "started on " . date("d-M-Y") . " at " . date("H:i:s") . "\n\n");
  
  $MOD = 60;

  $sql_turn_select= "select `turn` from zsch"; 
  $res_turn_select = $conn->query($sql_turn_select);
  $row_turn_select= $res_turn_select->fetch_assoc();   
  $turn = $row_turn_select["turn"];   
  fwrite($file, "turn is:" . $turn . "\n");
  
  $newturn = ($turn+1)%$MOD;
  $sql_turn_update = "UPDATE `zsch` SET `turn`=" . $newturn ;
  if ($conn->query($sql_turn_update) === TRUE)
  {
    fwrite($file, "updated turn to " . $newturn . " successfully, shokr" . "\n");
  }
  else 
  {
    fwrite($file, "error updaing turn" . "\n");         
  }    

  //sheduled
  $sql_amlaki = "select * from `amlakin` where  ( (mahal<>'همه' or automm=0) and (`unsub`<>1) AND (trim(`mantaghe`)<>'') AND  (trim(`mahal`)<>'') and (`c` <>'') AND (`c`%" . $MOD . "=" . $turn . ") ) order by `x` " ;
  if($DEBUG_MOD==1) fwrite($file, "sql_amlaki is:" . $sql_amlaki . "\n");
  if($DEBUG_MOD==1) fwrite($file, "---------------------------------------------------------------------------------------------" . "\n");         
  
  try
  {
    $res_amlaki = $conn->query($sql_amlaki);
  } 
  catch (customException $e) 
  {
    fwrite($file, "error: " . $e->errorMessage() . ", so exiting " . "\n");
    fflush($file);  
    exit;
  }

  $num_all= 0;  $num_sent = 0;
  while($row_amlaki = $res_amlaki ->fetch_assoc())
  {
    $num_all++;      
    $amlakiID = $row_amlaki["ID"];
    
    $cnt = $row_amlaki["cnt"];
    $P = $row_amlaki["P"];//indeed, using current mechanism, P is not necessary and can be eliminated, if we use cnt=1 as its minimum and double it here and reset it to 1 in inc_visits
                          //to enhance soon en shaa Allah
    if($cnt > 0)
    {
        $cnt--;
        $sql_update = "UPDATE `amlakin` SET `cnt`=" . $cnt  . " where ID='" . $amlakiID . "'";
        //fwrite($file, "sql_update: " . $sql_update . "\n");
        if ($conn->query($sql_update) === TRUE)
        {
          fwrite($file, "updated cnt successfully, shokr" . "\n");
        }
        else 
        {
          fwrite($file, "error updaing cnt" . "\n");         
        }
        continue;
    }
    //NOTE NOTE: else is omitted because of the 'continue' instruction above otherwise would certainly be required, to improve soon en shaa Allah
    
    $amlakic = $row_amlaki["c"];
    $tmpName = prepareamlakiname($row_amlaki["name"]);

    //to further optimize soon en shaaAllah:
    $state     = cleanup($row_amlaki["state"]);
    $city      = cleanup($row_amlaki["city"]);
    $mantaghe  = cleanup($row_amlaki["mantaghe"]);
    $mantaghe2 = cleanup($row_amlaki["mantaghe2"]);
    $mahal     = str_replace('_', '-', cleanup($row_amlaki["mahal"]));//for now; to tidy up soon en shaa Allah 
    $mahal2    = cleanup($row_amlaki["mahal2"]);

    if(strlen($mantaghe) > 0)
    {
      $tmpSinceSec = time() - 86400; //floor(time()/86400)*86400 - 86400;

      $tmpmantaghe = "";
      //if(strlen($mantaghe) > 0)
        $tmpmantaghe = $mantaghe;
      //else
        //$tmpmantaghe = $mantaghe2;

      $sql_scheduled = "select count(*) as N from `cnotes` where ";
      


      $where_clause = " (" . sqlcleanup("`state`") . "='" . cleanup($state) . "') AND (`mantaghe2`<>'') AND (`time`-`howmanysecago` >= " . 
                       $tmpSinceSec . ") ";

      $tmpp =$state . '  ' . $city . '  ' . $mantaghe . '  ';// increase to 3 when city in cnotes used later en shaa Allah
      $tmpt = "CONCAT('%',  " . sqlcleanup("`mantaghe2`") ; //note the blanks, shokr
      $tmpt2 = $tmpt;

      if(strcmp($mahal,'همه')!=0)
      {
        $tmpp .=$state . '  ' . $city . '  ' . $mantaghe . '  ';
        $tmpt .= ", '%-%', " . sqlcleanup("`mahal2`") . " , '%-%')" ;//note the blanks, shokr
        $tmpt2.= ", '%-', " . sqlcleanup("`mahal2`") . " , '-%')" ;//ma shaa Allah


        $tmpp .= '-' . $mahal . '-' ;
        if( strcmp($tmpmantaghe, "تهران")==0 ) 
        {
          if( (strpos("dummy" . $mahal, "منطقه")>0) && (strlen($mahal)<strlen("منطقه ۱۱۱")) ) //keep an eye in this for now, to improve soon en shaa Allah
          {
            $sql_itsmahal = "select `mahal` from `t22mm` where replace(`mantaghe`, ' ','') = '" . $mahal . "'";
            fwrite($file, "sql_itsmahal is:" . $sql_itsmahal);
            $result_itsmahal = $conn->query($sql_itsmahal);
            while($row_itsmahal = $result_itsmahal -> fetch_assoc())
            {
              $itsmahal = cleanup($row_itsmahal["mahal"]);
              $tmpp .= '-' .$itsmahal . '-' ;
            } 
          }
        }

         $where_clause .= " AND ( ('" . $tmpp . "' LIKE " . $tmpt . " and length(`mahal2`)>4) or ('" . $tmpp . "' LIKE " . $tmpt2 . ") ) ";
       }
       else
       {
         $tmpt .= ", '%')";

         $where_clause .= " AND ( " . sqlcleanup("mantaghe2") . " = '" . cleanup($mantaghe) . "' OR " . sqlcleanup("mahal2") . " = '" . cleanup($mantaghe) . "')  AND '" . $tmpp . "' LIKE " . $tmpt ;
       }


      $sql_scheduled .= $where_clause ;
      if($DEBUG_MOD==1) fwrite($file, "sql_scheduled is:" . $sql_scheduled . "\n");
 
      try
      {
        $res_scheduled = $conn->query($sql_scheduled);
      } 
      catch (customException $e) 
      {
        fwrite($file, "error: " . $e->errorMessage() . ", so exiting 22222" . "\n"); 
        fflush($file);  
        sendtxt("174034313", "the error occured in zsch");
        exit;
      }

      $row_scheduled = $res_scheduled ->fetch_assoc();
      $N = $row_scheduled["N"];//for now only number of recent posts, to include a summary as well soon en shaa Allah

      if($N > 0)
      {     
        //send
        $link = "https://jamlak.ir/cnote.php?id=" . $amlakiID ;

        $txt = "سلام" . "\n" . "املاک محترم " . $tmpName  . "\n" . "برخی آگهی های 24 ساعت اخیر :" . "\n" . $link . "\n\nلطفاً منطقه های کاری خود را در بخش ویرایش بررسی بفرمایید" . "\n\xF0\x9F\x8C\xBA خدا نگهدار\xF0\x9F\x8C\xBA" ;
    
        fwrite($file, "sending text: \n" . $txt . "\nto c:" . $amlakic . "with tmpmantaghe :" . $tmpmantaghe . " and addr:" . $row_amlaki["addr"] . "\n");
        sendtxt($amlakic, $txt);
        //also double P
        $P *= 2;
        $cnt = $P;
        $sql_update = "UPDATE `amlakin` SET `cnt`=" . $cnt . ", `P`=" . $P  . " where ID='" . $amlakiID . "'";
        //fwrite($file, "sql_update: " . $sql_update . "\n");
        if ($conn->query($sql_update) === TRUE)
        {
          fwrite($file, "updated cnt and P successfully, shokr" . "\n");
        }
        else 
        {
          fwrite($file, "error updaing cnt and P" . "\n");         
        }
        
//sendtxt("174034313", $txt);
$num_sent++;
        
        //also log it
        if ($conn->query("insert into zschsent(dmy, recipient_ID, n)values('" . date('d-M-Y') . "', $amlakiID," .  $N . ");") === TRUE)
        {
          if($DEBUG_MOD==1) fwrite($file, "inserted into the zschsent table successfully, shokr" . "\n");
        }
        else 
        {
          fwrite($file, "error inserting into the zschsent table" . "\n");         
        }
      }	  
      else
      {
       //bigger arear, whole managhe except for maraakez for now; to fix soon en shaa Allah
       if( ismarkaz($row_amlaki["mantaghe"])==0 )
       {
        $sql_scheduled = "select count(*) as N from `cnotes` where ";
      
        $where_clause = " (" . sqlcleanup("`state`") . "='" . cleanup($state) . "') AND (`mantaghe2`<>'') AND (`time`-`howmanysecago` >= " . 
                       $tmpSinceSec . ") ";

        $tmpp =$state . '  ' . $city . '  ' . $mantaghe . '  ';// increase to 3 when city in cnotes used later en shaa Allah
        $tmpt = "CONCAT('%',  " . sqlcleanup("`mantaghe2`") ; //note the blanks, shokr

        $tmpt .= ", '%')";
        $where_clause .= " AND ( " . sqlcleanup("mantaghe2") . " = '" . cleanup($mantaghe) . "' OR " . sqlcleanup("mahal2") . " = '" . cleanup($mantaghe) . "')  AND '" . $tmpp . "' LIKE " . $tmpt ;


        $sql_scheduled .= $where_clause ;
        if($DEBUG_MOD==1) fwrite($file, "sql_scheduled is:" . $sql_scheduled . "\n");
 
        $res_scheduled = $conn->query($sql_scheduled);
        $row_scheduled = $res_scheduled ->fetch_assoc();
        $N = $row_scheduled["N"];//for now only number of recent posts, to include a summary as well soon en shaa Allah

        if($N > 0)
        {     
          //send
          $link = "https://jamlak.ir/cnote.php?id=" . $amlakiID . "&m=همه";

          $txt = "سلام" . "\n" . "املاک محترم " . $tmpName  . "\n" . "برخی آگهی های 24 ساعت اخیر در " . $row_amlaki["mantaghe"] . ":" . "\n" . $link . "\n\nلطفاً منطقه های کاری خود را در بخش ویرایش بررسی بفرمایید" . "\n\xF0\x9F\x8C\xBA خدا نگهدار\xF0\x9F\x8C\xBA" ;

          fwrite($file, "sending text: \n" . $txt . "\nto c:" . $amlakic . "with tmpmantaghe :" . $tmpmantaghe . " and addr:" . $row_amlaki["addr"] . "\n");
          sendtxt($amlakic, $txt);
          //also double P and set cnt
          $P *= 2;
          $cnt = $P;
          $sql_update = "UPDATE `amlakin` SET `cnt`=" . $cnt . ", `P`=" . $P  . " where ID='" . $amlakiID . "'";
          //fwrite($file, "sql_update: " . $sql_update . "\n");
          if ($conn->query($sql_update) === TRUE)
          {
            fwrite($file, "updated cnt and P successfully, shokr" . "\n");
          }
          else 
          {
            fwrite($file, "error updaing cnt and P" . "\n");         
          }

//  sendtxt("174034313", $txt);
  $num_sent++;
        
          //also log it
          if ($conn->query("insert into zschsent(dmy, recipient_ID, n)values('" . date('d-M-Y') . "', $amlakiID," .  $N . ");") === TRUE)
          {
            if($DEBUG_MOD==1) fwrite($file, "inserted into the zschsent table successfully, shokr" . "\n");
          }
          else 
          {
            fwrite($file, "error inserting into the zschsent table" . "\n");         
          }
        }	   
        else
        {
          //log it to see why soon en shaa Allah
          if($DEBUG_MOD==1) fwrite($file, "\n" . "no posts found for c:" . $amlakic . " with tmpmantaghe :" . $tmpmantaghe . " and addr:" . $row_amlaki["addr"] . " link: " . "https://jamlak.ir/cnote.php?id=" . $amlakiID  . "\n\n");        
        
          $link = "https://jamlak.ir/cnote.php?id=" . $amlakiID ;

          $txt = "سلام" . "\n" . "املاک محترم " . $tmpName  . "\n" . "لطفاً در لینک زیر منطقه های کاری خود (حد اکثر سه منطقه) را مشخص بفرمایید (تا بتوانیم آگهی های مربوطه را به شما ارجاع دهیم). با تشکر.:" . "\n" . $link . "\n\xF0\x9F\x8C\xBA خدا نگهدار\xF0\x9F\x8C\xBA" ;

    
          //fwrite($file, "sending text: \n" . $txt . "\nto c:" . $amlakic . "with tmpmantaghe :" . $tmpmantaghe . " and addr:" . $row_amlaki["addr"] . "\n");
          //sendtxt($amlakic, $txt);
        }
       }    
      }
      if($DEBUG_MOD==1) fwrite($file, "----------------------------------------------------------------------------------------------------------------" . "\n");         
    }//if amlaki mantaghe or mahal known
    else
    {
      $link = "https://jamlak.ir/cnote.php?id=" . $amlakiID ;

      $txt = "سلام" . "\n" . "املاک محترم " . $tmpName  . "\n" . "لطفاً در لینک زیر منطقه های کاری خود (حد اکثر سه منطقه) را مشخص بفرمایید (تا بتوانیم آگهی های مربوطه را به شما ارجاع دهیم). با تشکر.:" . "\n" . $link . "\n\xF0\x9F\x8C\xBA خدا نگهدار\xF0\x9F\x8C\xBA" ;

    
      //fwrite($file, "sending text: \n" . $txt . "\nto c:" . $amlakic . "with tmpmantaghe :" . $tmpmantaghe . " and addr:" . $row_amlaki["addr"] . "\n");
      //sendtxt($amlakic, $txt);
    }
  }//while


 fwrite($file, "al hamdo leAllah , finished on " . date("d-M-Y") . " at " . date("H:i:s") . " with num_sent, num_all= " . $num_sent . ", " . $num_all . "\n\n");
 fclose($file);
 sendtxt("174034313", "finished zsch with num_sent = " . $num_sent . ", and num_all = " . $num_all);

//--------------------------


function sendtxt($c, $ms)
{
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
//------------------------------------------------------

function prepareamlakiname($tmpName)
{
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
  return $tmpName;
}//prepareamlakiname
//------------------------------------------------------

function cleanup($s)
{
  //arabic and half space are supposed to be done before inserting the data into the DB, hence commented out here
  //also, numbers are assumed done before inserting the database and not included here
  /*$s = str_replace('ك', 'ک', $s );
  $s = str_replace('ئ', 'ی', $s );     
  $s = str_replace('ي', 'ی', $s );     
  $s = str_replace('‌', ' ', $s );*/  

  $s = str_replace('آ', 'ا', $s );     
  $s = str_replace('میدان', '', $s );     
  $s = str_replace('خیابان', '', $s );     
  $s = str_replace('فلکه', '', $s );     
  $s = str_replace('بلوار', '', $s );     
  $s = str_replace('چهارراه', '', $s );     
  $s = str_replace('چهار راه', '', $s );     
  $s = str_replace('شهید', '', $s );     
    
  $s = str_replace(' ', '', $s );//this is the last thing to do after the others
  return $s;
}
//-----------------------

function sqlcleanup($s)
{
  //arabic and half space are supposed to be done before inserting the data into the DB, hence commented out here
  /*return "replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(" . $s . ",'بلوار',''),'فلکه',''),'چهارراه',''),'چهار راه',''),'ي','ی'),'ك','ک'),'آ','ا'),'خیابان',''),'شهید',''),'بلوار',''),'میدان',''), ' ', '')" ;*/
  
  return "replace(replace(replace(replace(replace(replace(replace(replace(replace(" . $s . ",'فلکه',''),'چهار راه',''),'چهارراه',''),'آ','ا'),'خیابان',''),'شهید',''),'بلوار',''),'میدان',''), ' ', '')";
}
//-------------------------------------------------------

function ismarkaz($mantaghe)
{
  $tmp = str_replace(' ', '', $mantaghe);
  if( 
     (strcmp($tmp, 'تبریز')===0) || 
     (strcmp($tmp, 'ارومیه')===0) || 
     (strcmp($tmp, 'اردبیل')===0) || 
     (strcmp($tmp, 'اصفهان')===0) || 
     (strcmp($tmp, 'کرج')===0) || 
     (strcmp($tmp, 'ایلام')===0) || 
     (strcmp($tmp, 'بوشهر')===0) || 
     (strcmp($tmp, 'تهران')===0) || 
     (strcmp($tmp, 'شهرکرد')===0) || 
     (strcmp($tmp, 'بیرجند')===0) || 
     (strcmp($tmp, 'مشهد')===0) || 
     (strcmp($tmp, 'بجنورد')===0) || 
     (strcmp($tmp, 'اهواز')===0) || 
     (strcmp($tmp, 'زنجان')===0) || 
     (strcmp($tmp, 'سمنان')===0) || 
     (strcmp($tmp, 'زاهدان')===0) || 
     (strcmp($tmp, 'شیراز')===0) || 
     (strcmp($tmp, 'قزوین')===0) || 
     (strcmp($tmp, 'قم')===0) || 
     (strcmp($tmp, 'سنندج')===0) || 
     (strcmp($tmp, 'کرمان')===0) || 
     (strcmp($tmp, 'کرمانشاه')===0) || 
     (strcmp($tmp, 'یاسوج')===0) || 
     (strcmp($tmp, 'گرگان')===0) || 
     (strcmp($tmp, 'رشت')===0) || 
     (strcmp($tmp, 'خرمآباد')===0) || 
     (strcmp($tmp, 'ساری')===0) || 
     (strcmp($tmp, 'اراک')===0) || 
     (strcmp($tmp, 'بندرعباس')===0) || 
     (strcmp($tmp, 'همدان')===0) || 
     (strcmp($tmp, 'یزد')===0) 
    )
    return 1;
  else
    return 0;    
}//ismarkaz
//----------------------------------------------------------------



?>
