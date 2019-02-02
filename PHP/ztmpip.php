<?php
  $file = fopen("ztmpip.log","a");
  fwrite($file, "salaam" . "\n\n");
  fwrite($file, "started on " . date("d-M-Y") . " at " . date("H:i:s") . "\n\n");
  
  $url = 'https://whatismyipaddress.com/';

  $crl= curl_init($url);
  curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
  $str = curl_exec_follow($crl);
  curl_close($crl);

  $i= strpos($str, '<a href="//whatismyipaddress.com/ip/');
  if($i===FALSE) 
  {
    fwrite($file, "NOT FOUND! str is:" . $str . "\n");   
  }
  else
  {
    $str = substr($str, $i + 36); 
    $j= strpos($str, '"');
    $myip= trim(substr($str, 0, $j));
    fwrite($file, "myip is:" . $myip . "\n");        
  }
fclose($file);

//--------------------------


function curl_exec_follow($ch, &$maxredirect = null) {
  
  // we emulate a browser here since some websites detect
  // us as a bot and don't let us do our job
  $user_agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5)".
                " Gecko/20041107 Firefox/1.0";
  curl_setopt($ch, CURLOPT_USERAGENT, $user_agent );

  $mr = $maxredirect === null ? 5 : intval($maxredirect);

  if (filter_var(ini_get('open_basedir'), FILTER_VALIDATE_BOOLEAN) === false 
      && filter_var(ini_get('safe_mode'), FILTER_VALIDATE_BOOLEAN) === false
  ) {

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $mr > 0);
    curl_setopt($ch, CURLOPT_MAXREDIRS, $mr);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

  } else {
    
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

    if ($mr > 0)
    {
      $original_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
      $newurl = $original_url;
      
      $rch = curl_copy_handle($ch);
      
      curl_setopt($rch, CURLOPT_HEADER, true);
      curl_setopt($rch, CURLOPT_NOBODY, true);
      curl_setopt($rch, CURLOPT_FORBID_REUSE, false);
      do
      {
        curl_setopt($rch, CURLOPT_URL, $newurl);
        $header = curl_exec($rch);
        if (curl_errno($rch)) {
          $code = 0;
        } else {
          $code = curl_getinfo($rch, CURLINFO_HTTP_CODE);
          if ($code == 301 || $code == 302) {
            preg_match('/Location:(.*?)\n/i', $header, $matches);
            $newurl = trim(array_pop($matches));
            
            // if no scheme is present then the new url is a
            // relative path and thus needs some extra care
            if(!preg_match("/^https?:/i", $newurl)){
              $newurl = $original_url . $newurl;
            }   
          } else {
            $code = 0;
          }
        }
      } while ($code && --$mr);
      
      curl_close($rch);
      
      if (!$mr)
      {
        if ($maxredirect === null)
        trigger_error('Too many redirects.', E_USER_WARNING);
        else
        $maxredirect = 0;
        
        return false;
      }
      curl_setopt($ch, CURLOPT_URL, $newurl);
    }
  }
  return curl_exec($ch);
}//curl_exec_follow
//------------------------------------

function calc_sec_ago($howlongago)
{
  $Eng_howlongago= $howlongago;
  $Eng_howlongago= str_replace('۰','0',$Eng_howlongago);
  $Eng_howlongago= str_replace('۱','1',$Eng_howlongago);
  $Eng_howlongago= str_replace('۲','2',$Eng_howlongago);
  $Eng_howlongago= str_replace('۳','3',$Eng_howlongago);
  $Eng_howlongago= str_replace('۴','4',$Eng_howlongago);
  $Eng_howlongago= str_replace('۵','5',$Eng_howlongago);
  $Eng_howlongago= str_replace('۶','6',$Eng_howlongago);
  $Eng_howlongago= str_replace('۷','7',$Eng_howlongago);
  $Eng_howlongago= str_replace('۸','8',$Eng_howlongago);
  $Eng_howlongago= str_replace('۹','9',$Eng_howlongago);
        
  $j= mb_strpos($Eng_howlongago, 'لحظاتی پیش');
  if($j !== FALSE)
    return 0;

  $j= mb_strpos($Eng_howlongago, 'ثانیه پیش');
  if($j !== FALSE)
    return intval(trim(mb_substr($Eng_howlongago, 0, $j)));

  $j= mb_strpos($Eng_howlongago, 'دقایقی پیش');
  if($j !== FALSE)
    return 60;

  $j= mb_strpos($Eng_howlongago, 'دقیقه پیش');
  if($j !== FALSE)
    return  60*intval(trim(mb_substr($Eng_howlongago, 0, $j)));

  $j= mb_strpos($Eng_howlongago, 'یک ربع پیش');
  if($j !== FALSE)
    return 900;

  $j= mb_strpos($Eng_howlongago, 'نیم ساعت پیش');
  if($j !== FALSE)
    return 1800;

  $j= mb_strpos($Eng_howlongago, 'ساعاتی پیش');
  if($j !== FALSE)
    return 3600;

  $j= mb_strpos($Eng_howlongago, 'ساعت پیش');
  if($j !== FALSE)
    return  3600*intval(trim(mb_substr($Eng_howlongago, 0, $j)));
    
  $j= mb_strpos($Eng_howlongago, 'دیروز');//NOTE: esA to CHECK WhAT THEY MEAN BY THAT, may be 24 hours ago (at least) or just even a second ago (if around midnight)
  if($j !== FALSE)
    return  86400;

  $j= mb_strpos($Eng_howlongago, 'پریروز');//NOTE: esA to CHECK WhAT THEY MEAN BY THAT, may be 24 hours ago (at least) or just even a second ago (if around midnight)
  if($j !== FALSE)
    return  86400* 2;
    
  $j= mb_strpos($Eng_howlongago, 'روز پیش');
  if($j !== FALSE)
    return  86400*intval(trim(mb_substr($Eng_howlongago, 0, $j)));

  $j= mb_strpos($Eng_howlongago, 'هفته پیش');
  if($j !== FALSE)
  {
    $tmp = trim(mb_substr($Eng_howlongago, 0, $j));
    if(strlen($tmp) > 0)
      return  7*86400*intval($tmp);
    else
      return 7*86400;
  }

  $j= mb_strpos($Eng_howlongago, 'هفتهٔ پیش');
  if($j !== FALSE)
  {
    $tmp = trim(mb_substr($Eng_howlongago, 0, $j));
    if(strlen($tmp) > 0)
      return  7*86400*intval($tmp);
    else
      return 7*86400;
  }


  $j= mb_strpos($Eng_howlongago, 'ماه پیش');
  if($j !== FALSE)
  {
    $tmp = trim(mb_substr($Eng_howlongago, 0, $j));
    if(strlen($tmp) > 0)
      return  30*7*86400*intval($tmp);
    else
      return 30*7*86400;
  }
  //assume not before last year
}//calc_sec_ago
//--------------------------

?>
