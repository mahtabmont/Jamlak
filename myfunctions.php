<?php

function mypagination($url, $page, $total, $per_page)
{
  $logfile = fopen("mypagination.log","w");
  fwrite($logfile, "Salaam");

    $lastpage = ceil($total/$per_page);   fwrite($logfile, "lastpage :" . $lastpage . " \n");
    $pagination = "<ul class='pagination'>";  
    $tmp_start_page = floor($page/10)*10 + 1;   fwrite($logfile, "tmp_start_page :" . $tmp_start_page. " \n");
    if($tmp_start_page != 1)
    {
      $pagination.= "<li><a href='{$url}page=1' class='bg-info'><span class='bg-primary'> &laquo; </span></a></li>";               
      $tmp_for_previous = $tmp_start_page-10;
      $pagination.= "<li><a href='{$url}page={$tmp_for_previous}' class='bg-info'><span class='bg-primary'> &raquo; </span></a></li>";               
    }
    if($lastpage >= 10+ $tmp_start_page)
      $tmp_max = $tmp_start_page + 9;
    else
      $tmp_max = $lastpage;
    
    for ($counter = $tmp_start_page; $counter <= $tmp_max; $counter++)
      if($counter == $page)
        $pagination.= "<li><a class='bg-success'><span class='bg-info'>{$counter}</span></a></li>";     
      else
        $pagination.= "<li><a href='{$url}page={$counter}'>{$counter} </a></li>";     

    if($lastpage >= 10+ $tmp_start_page)
    {
      $pagination.= "<li><a href='{$url}page={$counter}'><span class='bg-primary'>&laquo; </span></a></li>";               
      $pagination.= "<li><a href='{$url}page={$lastpage}' class='bg-info'><span class='bg-primary'> &raquo; </span></a></li>";               
    }
  
    $pagination.= "</ul>";   fwrite($logfile, "pagination :" . $pagination . " \n");
    
fclose($logfile);
    
return $pagination;  
}
//--------------------------------------

//NOTE: this function assumes ID in the session, if exists
function inc_visits($page)
{
  $logfile = fopen("function_inc_visits.log" , "w");
  $dmy = date("d-M-Y"); 
  $his = date("H:i:s"); 
  if (isset($_SESSION['id']))
    $visitor_ID = $_SESSION['id'];
  else
    $visitor_ID = "no_ID";

  $IP = $_SERVER['REMOTE_ADDR'];
  
  $sql1 = "select n from `visits` where `dmy`='" . $dmy . "' AND `visitor_ID`='" . $visitor_ID . "' AND `page`='" . $page . "'";
  fwrite($logfile, "sql1 : " . $sql1 . "\n");
  $result = $GLOBALS['conn']->query($sql1);
  if($result->num_rows > 0)
  {
    $row = $result->fetch_assoc();
    $n = $row["n"] + 1; 
    $sql2 = "update `visits` set `n`=" . $n . ", `his`='" . $his . "' where `dmy`='" . $dmy . "' AND `visitor_ID`='" . $visitor_ID . "' AND `page`='" . $page . "'";
    fwrite($logfile, "sql2 : " . $sql2 . "\n");
    if ($GLOBALS['conn']->query($sql2) === TRUE)
      fwrite($logfile, "updated the visits tablee successfully, shoooooooookrr" . "\n");
    else
      fwrite($logfile, "error updating the visits table!!!" . "\n");
  }
  else
  {
    $sql2 = "insert into `visits` (`his`, `dmy`, `visitor_ID`, `page`, `n`) values('" . $his . "', '" . $dmy . "', '" . $visitor_ID . "', '" . $page . "', 1)" ;
    fwrite($logfile, "sql2 : " . $sql2 . "\n");
    if ($GLOBALS['conn']->query($sql2) === TRUE)
      fwrite($logfile, "inserted into the visits tablee successfully, shoooooooookrr" . "\n");
    else
      fwrite($logfile, "error inserting into the visits table!!!" . "\n");
  }
  
  //also reset cnt and P, if page is cnote
  if( (strcmp($visitor_ID,"no_ID")!=0) && (strcmp($page,"cnote")==0) )
  {
    $sql_update = "UPDATE `amlakin` SET `cnt`=0, `P`=1 where ID='" . $visitor_ID . "'";
    //fwrite($file, "sql_update: " . $sql_update . "\n");
    if ($GLOBALS['conn']->query($sql_update) === TRUE)
    {
      fwrite($logfile, "updated cnt and P successfully, shokr" . "\n");
    }
    else 
    {
      fwrite($logfile, "error updaing cnt and P" . "\n");         
    }
  }
  
  
   
  fclose($logfile);
}
//---------------------------------------------

function fill_mantaghe2()
{
  $DEBUG_MOD = 0;//1;
  require_once 'dbconnect.php';
  $file = fopen("func_fill_mantaghe2.log","w");
  fwrite($file, "salaam" . "\n\n");
  fwrite($file, "started on " . date("d-M-Y") . " at " . date("H:i:s") . "\n\n");
  
  $sql_amlaki = "select * from `amlakin` where  x=4581 and     trim(`state`)<>'' AND trim(`city`)<> '' AND trim(`addr`)<> '' " ;
  if($DEBUG_MOD==1) fwrite($file, "sql_amlaki is:" . $sql_amlaki . "\n");
  if($DEBUG_MOD==1) fwrite($file, "------------------------------------------------------------------------------" . "\n");         
 
  $res_amlaki = $GLOBALS['conn']->query($sql_amlaki);
  while($row_amlaki = $res_amlaki ->fetch_assoc())
  {  
    $ID       = trim($row_amlaki["ID"]);
    $state    = trim($row_amlaki["state"]);    $cstate= str_replace(' ','',$state);
    $city     = trim($row_amlaki["city"]);     $ccity= str_replace(' ','',$city);
    $addr     = trim($row_amlaki["addr"]);     $caddr= str_replace(' ','',$addr);
    
    $sql_mantaghe = "select mantaghe from omm 
                     where replace(state,' ','') = '" . $cstate . "' AND trim(mantaghe)<>''
                     AND POSITION(REPLACE(mantaghe,' ','') IN '" . $caddr . "') >=1 
                     AND POSITION(REPLACE(mantaghe,' ','') IN '" . $caddr . "') <=length(mantaghe)/2 
                     GROUP BY mantaghe" ;
    
    if($DEBUG_MOD==1) fwrite($file, "sql_mantaghe =" . $sql_mantaghe . "\n");   
    $res_mantaghe = $GLOBALS['conn']->query($sql_mantaghe);
    if($DEBUG_MOD==1) fwrite($file, "number of matching mantaghe is =" . mysqli_num_rows($res_mantaghe) . "\n");   
    if(mysqli_num_rows($res_mantaghe) == 1)
    {
      $row_mantaghe = $res_mantaghe ->fetch_assoc() ; 
      $foundmantaghe = trim($row_mantaghe['mantaghe']);
      $sql_update= "update amlakin set mantaghe2='" . $foundmantaghe . "', mahal2='' where ID=" . $ID ;
      if($DEBUG_MOD==1) fwrite($file, "sql_update :" . $sql_update . "\n");
      $GLOBALS['conn']->query($sql_update);  
      if( strcmp($row_amlaki['mantaghe2'], $foundmantaghe) != 0 )
        if($DEBUG_MOD==1) fwrite($file, "updated record ID=" . $ID . " setting mantaghe from " . $row_amlaki['mantaghe2'] . " to " . $foundmantaghe . "\n\n");   
        
      //now, look for mahal
      $caddr = str_replace(' ','', (str_replace($foundmantaghe, '', $addr))); //NOTE that the parameter is addr not caddr
      $sql_mahal = "select mahal from omm 
                    where replace(state,' ','') = '" . $cstate . "' AND trim(mantaghe)='" . $foundmantaghe . "'
                    AND POSITION(REPLACE(mahal,' ','') IN '" . $caddr . "') >=1 
                    AND POSITION(REPLACE(mahal,' ','') IN '" . $caddr . "') <=length(mahal)/2 
                    GROUP BY mahal" ;
      
      if($DEBUG_MOD==1) fwrite($file, "sql_mahal =" . $sql_mahal . "\n");   
      $res_mahal= $GLOBALS['conn']->query($sql_mahal);
      if($DEBUG_MOD==1) fwrite($file, "number of matching mahal is =" . mysqli_num_rows($res_mahal) . "\n");   
      if(mysqli_num_rows($res_mahal) == 1)
      {
        $row_mahal= $res_mahal->fetch_assoc() ; 
        $foundmahal = trim($row_mahal['mahal']);
        $sql_update= "update amlakin set mahal2='" . $foundmahal . "' where ID=" . $ID ;
        if($DEBUG_MOD==1) fwrite($file, "sql_update :" . $sql_update . "\n");
        $GLOBALS['conn']->query($sql_update);  
        if( strcmp($row_amlaki['mahal2'], $foundmahal) != 0 )
          if($DEBUG_MOD==1) fwrite($file, "updated record ID=" . $ID . " setting mahal from " . $row_amlaki['mahal2'] . " to " . $foundmahal . "\n\n");   
      }
      else
      {
          if($DEBUG_MOD==1) fwrite($file, "no mahal found for ID=" . $ID . "\n\n");   
      }      
    } 
    else//i.e. no mantaghe was found
    {
      //look for mahal (and mantaghe) then
      $sql_mantaghemahal = "select mantaghe,mahal from omm 
                            where replace(state,' ','') = '" . $cstate . "' AND trim(mantaghe)<>'' AND trim(mahal)<>'' 
                            AND POSITION(REPLACE(mahal,' ','') IN '" . $caddr . "') >=1 
                            AND POSITION(REPLACE(mahal,' ','') IN '" . $caddr . "') <=length(mahal)/2
                            GROUP BY mantaghe,mahal" ;
      
      if($DEBUG_MOD==1) fwrite($file, "sql_mantaghemahal =" . $sql_mantaghemahal . "\n");   
      $res_mantaghemahal = $GLOBALS['conn']->query($sql_mantaghemahal );
      if($DEBUG_MOD==1) fwrite($file, "number of matching mantaghe,mahal is =" . mysqli_num_rows($res_mantaghemahal) . "\n");   
      if(mysqli_num_rows($res_mantaghemahal ) == 1)
      {
        $row_mantaghemahal = $res_mantaghemahal ->fetch_assoc() ;   
        $sql_update= "update amlakin set mantaghe2='" . $row_mantaghemahal['mantaghe'] . "', mahal2='" . $row_mantaghemahal['mahal'] . "' where ID=" . $ID ;
        if($DEBUG_MOD==1) fwrite($file, "sql_update :" . $sql_update . "\n");
        $GLOBALS['conn']->query($sql_update);  
        if( strcmp($row_amlaki['mantaghe2'], $row_mantaghemahal['mantaghe']) != 0 )
          if($DEBUG_MOD==1) fwrite($file, "updated record ID=" . $ID . " setting mantaghe from " . $row_amlaki['mantaghe2'] . " to " . $row_mantaghemahal['mantaghe'] . "\n");   
        if( strcmp($row_amlaki['mahal2'], $row_mantaghemahal['mahal']) != 0 )
          if($DEBUG_MOD==1) fwrite($file, "updated record ID=" . $ID . " setting mantaghe from " . $row_amlaki['mahal2'] . " to " . $row_mantaghemahal['mahal'] . "\n");   
      }
      else
      {
        //then assume (by chance) that mantaghe is the same as city and try again:
        $guessedmantaghe = trim($row_amlaki['city']);
        $caddr = str_replace(' ','', (str_replace($guessedmantaghe, '', $addr))); //NOTE that the parameter is addr not caddr
        $sql_mahal = "select mahal from omm 
                      where replace(state,' ','') = '" . $cstate . "' AND trim(mantaghe)='" . $guessedmantaghe . "'
                      AND POSITION(REPLACE(mahal,' ','') IN '" . $caddr . "') >=1 
                      AND POSITION(REPLACE(mahal,' ','') IN '" . $caddr . "') <=length(mahal)/2 
                      GROUP BY mahal" ;
      
        if($DEBUG_MOD==1) fwrite($file, "sql_mahal =" . $sql_mahal . "\n");   
        $res_mahal= $GLOBALS['conn']->query($sql_mahal);
        if($DEBUG_MOD==1) fwrite($file, "number of matching mahal is =" . mysqli_num_rows($res_mahal) . "\n");   
        if(mysqli_num_rows($res_mahal) == 1)
        {
          $row_mahal= $res_mahal->fetch_assoc() ; 
          $guessedmahal = trim($row_mahal['mahal']);
          $sql_update= "update amlakin set mantaghe2='" . $guessedmantaghe . "', mahal2='" . $guessedmahal . "' where ID=" . $ID ;
          if($DEBUG_MOD==1) fwrite($file, "sql_update :" . $sql_update . "\n");
          $GLOBALS['conn']->query($sql_update);  
          if( strcmp($row_amlaki['mahal2'], $guessedmahal) != 0 )
            if($DEBUG_MOD==1) fwrite($file, "updated record ID=" . $ID . " setting mahal from " . $row_amlaki['mahal2'] . " to " . $guessedmahal . "\n\n");   
        }
        else
        {
          if($DEBUG_MOD==1) fwrite($file, "no mantaghe, mahal found for ID=" . $ID . "\n\n");   
        }           
      }
    }
  }
  fwrite($file, "al hamdo leAllah" . "\n\n");
}//fill_mantaghe2
//---------------------------------------------

function fillin_mahals()
{
  $DEBUG_MODE = 0;//1;
  
  $logfile = fopen("func_fillin_mahals.log","w");
  fwrite($logfile, "salaam, started on " . date('d-M-Y') . " at " . date('h:i:s') . "\n\n");
  
//similarly for other states:
set_time_limit(3600);

  $sql_s = "select distinct `state` from `omm`";
  $res_s = $GLOBALS['conn']->query($sql_s);
  $n_s = $res_s->num_rows;
  $i_s=0;
  while($row_s = $res_s->fetch_assoc())
  {
    //store in array
    $states[$i_s] = $row_s["state"];
    $i_s++;  
  }  


  //main loop
 

  for($i_s = 0 ; $i_s < $n_s ; $i_s++)
  {
    $orgstate = $states[$i_s];
    $state = $orgstate;
    if($DEBUG_MODE==1) fwrite($logfile, "--------------------processing state: " . $state . "\n");

    $state = str_replace('ك', 'ک', $state ); //note that because of unicde, the first and second arguments seem to be swapped!!!
    $state = str_replace('و', 'و', $state ); //note that because of unicde, the first and second arguments seem to be swapped!!!
    $state = str_replace('ي', 'ی', $state ); //note that because of unicde, the first and second arguments seem to be swapped!!!

      $sql_mantaghe = "select distinct `mantaghe` from `omm` where `state` = '" . $state . "' ";
      if($DEBUG_MODE==1) fwrite($logfile, "sql_mantaghe : " . $sql_mantaghe . "\n");
      $res_mantaghe = $GLOBALS['conn']->query($sql_mantaghe);
      $n_mantaghe = $res_mantaghe->num_rows;
      if($DEBUG_MODE==1) fwrite($logfile, "n_mantaghe : " . $n_mantaghe . "\n");
      $i=0;
      while($row_mantaghe = $res_mantaghe->fetch_assoc())
      {
        $mantaghe[$i] = $row_mantaghe["mantaghe"];
        if($DEBUG_MODE==1) fwrite($logfile, "next mantaghe before replace is :" . $mantaghe[$i] . "\n"); 
               
        $tmp = $mantaghe[$i]; //for some reason this was necessary othewise replace would out nothing in the array cell
        $tmp = str_replace('ك', 'ک', $tmp ); //note that because of unicde, the first and second arguments seem to be swapped!!!
        $tmp = str_replace('و', 'و', $tmp ); //note that because of unicde, the first and second arguments seem to be swapped!!!
        $tmp = str_replace('ي', 'ی', $tmp ); //note that because of unicde, the first and second arguments seem to be swapped!!!
        $mantaghe[$i] = $tmp;

        if($DEBUG_MODE==1) fwrite($logfile, "next mantaghe after replace is :" . $mantaghe[$i] . "\n");
        $i++;
      }

     
      //go through each amlaki 
      $sql_amlakin = "select * from `amlakin` where      `state` = '" . $orgstate . "' AND length(trim(`addr`))>0";
      if($DEBUG_MODE==1) fwrite($logfile, "sql_amlakin : " . $sql_amlakin . "\n");
      $res_amlakin = $GLOBALS['conn']->query($sql_amlakin);
      if($DEBUG_MODE==1) fwrite($logfile, "number of amlakin is : " . $res_amlakin->num_rows . "\n");
      while($row_amlakin = $res_amlakin->fetch_assoc())
      {
        $tmpx = $row_amlakin['x'];
        $tmpcity= trim($row_amlakin['city']);
        $orgamlakinmantaghe= $row_amlakin['mantaghe'];

        $tmpaddr= trim($row_amlakin['addr']);
        $tmpcity = str_replace('ك', 'ک', $tmpcity ); //note that because of unicde, the first and second arguments seem to be swapped!!!
        $tmpcity = str_replace('و', 'و', $tmpcity ); //note that because of unicde, the first and second arguments seem to be swapped!!!
        $tmpcity = str_replace('ي', 'ی', $tmpcity ); //note that because of unicde, the first and second arguments seem to be swapped!!!
        $tmpaddr = str_replace('ك', 'ک', $tmpaddr); //note that because of unicde, the first and second arguments seem to be swapped!!!
        $tmpaddr = str_replace('و', 'و', $tmpaddr); //note that because of unicde, the first and second arguments seem to be swapped!!!
        $tmpaddr = str_replace('ي', 'ی', $tmpaddr); //note that because of unicde, the first and second arguments seem to be swapped!!!
        $tmpaddr = str_replace('،', '-', $tmpaddr); //note that because of unicde, the first and second arguments seem to be swapped!!!

        $tmpaddr = str_replace('آ', 'ا', $tmpaddr); //note that because of unicde, the first and second arguments seem to be swapped!!!

//also replace half spaces to space
        //$tmpaddr = str_replace('‌', ' ', $tmpaddr);//for some reason did not work

 if(strlen($tmpaddr)===0)
   continue;
 //note that because of unicde, the first and second arguments seem to be swapped!!!
        
        //preprocess, for now only one but complete soon en shaa Allah
        if($DEBUG_MODE==1) fwrite($logfile, "processing amlakin with x: " . $tmpx . ", city: " . $tmpcity . ", and addr: " . $tmpaddr . "\n");
                
       
        //remove state, if exists
        if($DEBUG_MODE==1) fwrite($logfile, "looking for the state: " . $state . " in addr:" . $tmpaddr . " \n");
        $i = strpos($tmpaddr, $state); 
        if($i !== false && $i<10)
        {
          if($DEBUG_MODE==1) fwrite($logfile, "state " . $state . " was found at i: " . $i . "\n");
          if($i > 0)
            if($DEBUG_MODE==1) fwrite($logfile, "!!!!!!!!!!WARNING: addr: " . $tmpaddr . " and the state " . $state . " does not start exactly at the beginning!!!" . "\n");

          $tmpaddr = trim(mb_substr($tmpaddr, $i+mb_strlen($state)));                               
          if($DEBUG_MODE==1) fwrite($logfile, "after removing state, tmpaddr: " . $tmpaddr . "\n");

          //clean
          $tmpstr = mb_substr($tmpaddr, 0, 2);
          $k = strpos($tmpstr, '،'); 
          if($k !== false)
            $tmpaddr = trim(mb_substr($tmpaddr, $k+mb_strlen('،')));                                              
        }
        if($DEBUG_MODE==1) fwrite($logfile, "the resulting addr after looking/removing the state and clean up is: " . $tmpaddr . " \n");
  
  
  if(strlen($tmpaddr)===0)
   continue;
      
        //remove shahrestan, if is worth it!
        if(strlen($tmpcity)> strlen('ری'))//note here
        {
          if($DEBUG_MODE==1) fwrite($logfile, "looking for the shahrestan: " . $tmpcity . " in addr:" . $tmpaddr . " \n");
          $i = strpos($tmpaddr, $tmpcity); 
          if($i !== false && $i<10)
          {
            $tmpaddr = trim(mb_substr($tmpaddr, $i+mb_strlen($tmpcity)));                               
   if(strlen($tmpaddr)===0)
     continue;

            //clean
            $tmpstr = mb_substr($tmpaddr, 0, 2);
            $k = strpos($tmpstr, '،'); 
            if($k !== false)
              $tmpaddr = trim(mb_substr($tmpaddr, $k+mb_strlen('،')));                                              
          }
          else
          {
            //try compact veersion 
            $i = strpos($tmpaddr, str_replace(' ','',$tmpcity)); 
            if($i !== false && $i<10)
            {
              $tmpaddr = trim(mb_substr($tmpaddr, $i+mb_strlen(str_replace(' ','',$tmpcity))));                               
     if(strlen($tmpaddr)===0)
     continue;

              //clean
              $tmpstr = mb_substr($tmpaddr, 0, 2);
              $k = strpos($tmpstr, '،'); 
              if($k !== false)
                $tmpaddr = trim(mb_substr($tmpaddr, $k+mb_strlen('،')));                                              
            }
          }
          
          if($DEBUG_MODE==1) fwrite($logfile, "the resulting addr after looking/removing the shahrestan and clean up is: " . $tmpaddr . " \n");
   if(strlen($tmpaddr)===0)
     continue;
        }
        
        
        
        //now that shahrestan is removed (if any), look for the exact mahal at almost the beginning 
//       if($DEBUG_MODE==1) fwrite($logfile, "looking for an exact mach of mahal within the state" . " \n"); //need to improve soon enshaa Allah by looking for those in the shahrestan only
        
        $sql_allmahal = "select distinct `mahal` from `omm` where `state` = '" . $orgstate . "'";
        if(strlen($orgamlakinmantaghe)>0)
          $sql_allmahal .= " and `mantaghe` = '" . $orgamlakinmantaghe . "'";   
        
 //to keep an eye on this:
     $sql_allmahal .= " ORDER BY LENGTH(mahal) DESC";    
        
        $res_allmahal = $GLOBALS['conn']->query($sql_allmahal );
        $found=0;
        while( ($row_allmahal = $res_allmahal ->fetch_assoc()) && !$found)//again first match for now, to reise soon en shaa Allah
        {
          $orgmahal= trim($row_allmahal ['mahal']);
          $tmpmahal = $orgmahal;
          $tmpmahal = str_replace('ك', 'ک', $tmpmahal); //note that because of unicde, the first and second arguments seem to be swapped!!!
          $tmpmahal = str_replace('و', 'و', $tmpmahal ); //note that because of unicde, the first and second arguments seem to be swapped!!!
          $tmpmahal = str_replace('ي', 'ی', $tmpmahal ); //note that because of unicde, the first and second arguments seem to be swapped!!!

          $tmpmahal = str_replace('آ', 'ا', $tmpmahal ); //note that because of unicde, the first and second arguments seem to be swapped!!!


$s = "d" . $tmpmahal; //dummy for avoid current lack of knowledge about strpos! resolve soon en shaa Allah
//NOTE that the following only can address half the cases where mahal has blank but the address not; the unsolved case is the reverse
//۱۲۳۴۵۶۷۸۹۰
if( (strpos($s, " ")> 0) || (strpos($s, "۰")>0) || (strpos($s, ".")>0) || (strpos($s, "۱")>0) || (strpos($s, "۲")>0) || (strpos($s, "۳")>0) || (strpos($s, "۴")>0) || (strpos($s, "۵")>0) || (strpos($s, "۶")>0) || (strpos($s, "۷")>0) || (strpos($s, "۸")>0) || (strpos($s, "۹")>0) || (strpos($s, "0")>0) || (strpos($s, "1")>0) || (strpos($s, "2")>0) || (strpos($s, "3")>0) || (strpos($s, "4")>0) || (strpos($s, "5")>0) || (strpos($s, "6")>0) || (strpos($s, "7")>0) || (strpos($s, "8")>0) || (strpos($s, "9")>0) || (strpos($s, "شهر")>0) || (strpos($s, "آباد")>0) || (strpos($s, "اباد")>0) || (strlen($s)>strlen('ببببببب')) )
{
  $tmpmahal_ns = str_replace(" ", "", $tmpmahal);
  $tmpaddr_ns = str_replace(" ", "", $tmpaddr);
}
else
{
  $tmpmahal_ns= $tmpmahal;
  $tmpaddr_ns = $tmpaddr;
}

          $tmpstr = mb_substr($tmpaddr_ns, 0, 10 + mb_strlen($tmpmahal_ns));
          if($DEBUG_MODE==1) fwrite($logfile, "looking for the mahal " . $tmpmahal_ns . " in tmpstr:" . $tmpstr . " \n");
 if(strlen($tmpstr)===0)
   continue;
          $i = strpos($tmpstr, $tmpmahal_ns); 
          if($i !== false)
          {
            if($DEBUG_MODE==1) fwrite($logfile, "a mahal was found: " . $tmpmahal . " \n");
            if($i > 0)
              if($DEBUG_MODE==1) fwrite($logfile, "ٌٌ!!!!!!!!!!WARNING: addr: " . $tmpaddr_ns . " and the mahal " . $tmpmahal_ns . " does not start exactly at the beginning!!!" . "\n");
              
            if(strlen($orgamlakinmantaghe)>0)
            {
              $found = 1;
              $tmpmantaghe = $orgamlakinmantaghe;
            }
            else
            {
              //now find corresponding mantaghe and update if unique
              $sql_itsmantaghe = "select distinct `mantaghe` from `omm` where `state` = '" . $orgstate . "' AND `mahal` = '" . $orgmahal . "'";
              if($DEBUG_MODE==1) fwrite($logfile, "sql_itsmantaghe is: " . $sql_itsmantaghe . " \n");
              $res_itsmantaghe = $GLOBALS['conn']->query($sql_itsmantaghe );
              if($res_itsmantaghe->num_rows == 1)
              {
                $found = 1;
                $row_itsmantaghe= $res_itsmantaghe->fetch_assoc();
                $tmpmantaghe = $row_itsmantaghe["mantaghe"];
              }
              else
                if($DEBUG_MODE==1) fwrite($logfile, "no match found!" . " \n");              
            }              
            if($found)
            {
              if($DEBUG_MODE==1) fwrite($logfile, "exact match was found for the mahal " . $orgmahal . " whose mantaghe is " . $tmpmantaghe . " \n");
              //escape, just in case, and update
              $tmpmantaghe = mysqli_real_escape_string($GLOBALS['conn'], $tmpmantaghe);          
              $tmpmahal    = mysqli_real_escape_string($GLOBALS['conn'], $tmpmahal);          
              $sql_update = "update `amlakin` set `mantaghe2`='" . $tmpmantaghe . "', `mahal2`='" . $orgmahal . "' where `x`=" . $tmpx ; 
              //if($DEBUG_MODE==1) fwrite($logfile, $sql_update . "\n");
              $GLOBALS['conn']->query($sql_update);   
            }
          }                                                             
        }
      }      
  }//for i_s

  fclose($logfile);    
}

//---------------------------------------------

function rename_images()
{
   //temporary, to remove redundant records
  $logfile = fopen("func_rename_images.log","w");
  fwrite($logfile, "salaam" . "\n\n");
  $sql1 = "select * from `main` ORDER BY `ID` ASC";
  $res = $GLOBALS['conn']->query($sql1);
  while( $row = $res->fetch_assoc() )
  {
    $image = trim($row["image"]);
    $k= strrpos($image , ".");
    $dottext= substr($image , $k);
    $ID = trim($row["ID"]);
    $new_name = $ID . $dottext;
    //rename ("img/" . $image , "img/" . $new_name);
    $sql2 = "update `main` set `image`= '" . $new_name . "' where ID=" . $ID;
    fwrite($logfile, "sql2 is:" . $sql2 . "\n\n");
    $GLOBALS['conn']->query($sql2);
  }
  fclose($logfile);
}          
//---------------------------------------------

function rand_pass()
{
   //temporary, to generate first-time simple random pass
  $logfile = fopen("func_rand_pass.log","w");
  fwrite($logfile, "salaam" . "\n\n");
  
  $sql1 = "select * from `amlakin`";
  $res = $GLOBALS['conn']->query($sql1);
  while( $row = $res->fetch_assoc() )
  {
    if( strcmp($row["passname"], "")==0 )
    {
      //generate and insert
      $tmppass  = rand(1000, 9999);
      $sql2 = "update `amlakin` set `passname`= '" . $tmppass . "' where ID=" . $row["ID"];
      fwrite($logfile, "sql2 is:" . $sql2 . "\n\n");
      $GLOBALS['conn']->query($sql2);
    }
  }
  fclose($logfile);
}          
//---------------------------------------------

function ids()
{
   //temporary, to fill in id column for exisinting records
  $logfile = fopen("func_exids.log","w");
  fwrite($logfile, "salaam" . "\n\n");
  
  $sql1 = "select * from `amlakin`";
  $res = $GLOBALS['conn']->query($sql1);
  while( $row = $res->fetch_assoc() )
  {
    //generate and fill-in
    $x = $row['x'];
    $id = rand(1,9) . rand(1,9) . $x . rand(1,9) . rand(1,9) ;
    $sql2 = "update `amlakin` set `id`='{$id}' where `x`={$x}";
    fwrite($logfile, "sql2 is:" . $sql2 . "\n\n");
    if ($GLOBALS['conn']->query($sql2) === TRUE)
      fwrite($logfile, "updated the exid field successfully, shoooooooookr" . "\n");
    else 
      fwrite($logfile, "errroooooooor filling in the exid field" . "\n");
  }
  fclose($logfile);
}          

//---------------------------------------------

function run_update_sql()
{
  $logfile = fopen("func_run_update_sql.log","w");
  fwrite($logfile, "salaam" . "\n\n");
  fwrite($logfile, "started on " . date("d-M-Y") . " at " . date("H:i:s") . "\n\n");
  
  $sql2 = "update amlakin set mantaghe=city
where x in
(select x from
(select * from amlakin where mantaghe='' and c<>'') a inner join marakez on a.state=marakez.ostan and replace(a.city,' ', '')<>replace(marakez.markaz,' ','')
where replace(concat(a.state,a.city),' ','') in (select replace(concat(state,mantaghe),' ','') from omm)
 )";

  fwrite($logfile, "sql2 is:" . $sql2 . "\n\n");
  if ($GLOBALS['conn']->query($sql2) === TRUE)
    fwrite($logfile, "ran the query successfully, shoooooooookr" . "\n");
  else 
    fwrite($logfile, "errroooooooor running the query!" . "\n");
  fclose($logfile);
}         
//---------------------------------------------------------------------
?>



