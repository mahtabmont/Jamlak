<?php
// turn off the WSDL cache

$file = fopen("crl_sh_log.txt","w");
fwrite($file, "salaam" . "\n\n");

$servername = "localhost";
$username = "kodoomco_user";
$password = "ras1350";
$dbname = "kodoomco_besm";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
 if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$conn->set_charset("utf8");
header("content-type:text/html;charset=UTF-8");


$cat = "فروش مسکونی";
$c = "43604";//فروش مسکونی
//$types = array("آپارتمان"=>"440470", "خانه"=>"440470", "ویلا"=>"440470");
//$rooms= array("0"=>"439837", "1"=>"439414", "2"=>"439415", "3"=>"439416", "4"=>"439417", " 5 یا بیشتر"=>"439418" );

$types = array("آپارتمان"=>"440470");
$rooms= array("0"=>"439837");

$tmpCountPages = 0;


  for($state= 1; $state <=1 ; $state++)
  {
    foreach($types as $type => $a68094)
    {
      foreach($rooms as $room => $a68133)
      {
        $url = 'http://www.sheypoor.com/search?c=' . $c . '&r=' . $state . '&a68094=' . $a68094 . '&a68133=' . $a68133 ; //NOTE NOTE: www.  REQUIRED!
        fwrite($file, "url:" . $url . "\n");      

        //$str =file_get_contents("$url"); 
        $crl = curl_init();
        $timeout = 50;
        curl_setopt ($crl, CURLOPT_URL,$url);
        curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($crl, CURLOPT_ENCODING ,"");
        $str = curl_exec($crl);
        curl_close($crl);
        







        $flgFinished = 0;
        $i= strpos($str, "<article class=");
        if($i==FALSE) $flgFinished = 1;
       
        while($flgFinished == 0)  //had issue with TRUE / FALSE == === !== etc., fixed shokr using int  1 / 0
        {
          //extract the article
          $start_article = $i;
          $str_article = substr($str, $start_article); 
          $end_article = strpos($start_article, "</article");//en shaa Allah : always exists
          $str_article = substr($str_article, $i, $end_article + 10); //should include both openning and closing article tags

          //source
          $source= "";
          $i = strpos($str_article, 'data-href="'); 

          if($i == false)
          {
            $source= "";
          }
          else
          {
            $str_article = substr($str_article, $i + 11); 
            $j= strpos($str_article, '"'); 
            $source= trim(substr($str_article, 0, $j));
          }
          fwrite($file, "source is:" . $source . "\n");


          //image
          $origimagename= "";
          $i= strpos($str_article, "<img"); 
          if($i !== FALSE)
          {
            $str_article= substr($str_article, $i);
            $i= strpos($str_article, 'src="');
            if($i !== FALSE)
            {
              $str_article = substr($str_article, $i+5);
              $j= strpos($str_article, '"');
              $imagelink = trim(substr($str_article, 0, $j));
              $k1= strrpos($imagelink , "/");
              $origimagename= trim(substr($imagelink , $k1 +1));       
              copy($imagelink , 'shimg/' . $origimagename);
            }
          }
          fwrite($file, "origimagename is: " . $origimagename. "\n");
          
          //city, price, and howlongago
          $city = ""; $price = ""; $howlongago = "";

          $i= strpos($str_article, '<div class="content"');
          if($i !== FALSE)
          { //ASSUMED, the content div includes at least the second <p> tag - for both price (with strong tag) and howlongago (for time tag)
            //and the only missing parts may be the price and the howlongago
            $str_article= substr($str_article, $i);
            $i= strpos($str_article, '<p>');
            $str_article = substr($str_article, $i+3);
            $j= strpos($str_article, '</p>');
            $tmpStateCity = trim(substr($str_article, 0, $j));
            $k= strrpos($tmpStateCity , "،");  //NOTE virgol (farsi) not comma
            if($k == FALSE)
              $city = trim($tmpStateCity) ;
            else
              $city= trim(substr($tmpStateCity, $k+2)); 

          
            $i= strpos($str_article, 'item-price">');
            if($i !== FALSE)//== ?
            {
              $str_article= substr($str_article, $i+12);
              $j= strpos($str_article, '</');
              $price= trim(substr($str_article, 0, $j)); 
            }


            $i= strpos($str_article, '<time');
            if($i !== FALSE)//== ?
            {
              $str_article= substr($str_article, $i+5);
              $i= strpos($str_article, '">');
              $str_article= substr($str_article, $i+2);
              $j= strpos($str_article, '</time');
              $howlongago= trim(substr($str_article, 0, $j)); 
            }
          }
          fwrite($file, "city is: " . $city. "\n");//NOTE NOTE, because of unicode, $k+1 would remove half of the vairgoul , resulting in a strange character �
          fwrite($file, "price is:" . $price. "\n");
          fwrite($file, "howlongago is:" . $howlongago. "\n");



          //now the internal page:
          $meter=""; $matn =""; $m="";
          //$str_2 =file_get_contents("$source"); 
          $crl = curl_init();
          $timeout = 50;
          curl_setopt ($crl, CURLOPT_URL,       $source);
          curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
          curl_setopt($crl, CURLOPT_ENCODING ,"");
          $str_2 = curl_exec($crl);
          curl_close($crl);


          //fwrite($file, "second page: shokr:" . "\n" . $str_2 . "\n");      

          $i= strpos($str_2, "متراژ");
          if($i !== FALSE)
          {
            $str_2= substr($str_2, $i); 
            $i= strpos($str_2, "<td>"); 
            $str_2= substr($str_2, $i+4); 
            $j= strpos($str_2, '</td>'); 
            $meter= trim(substr($str_2, 0, $j)); 
          }
          fwrite($file, "meter is:" . $meter. "\n");


          $i1 = strpos($str_2, "</table>");
          $str_2= substr($str_2, $i); 
          $i2 = strpos($str_2, "<p>");                 
          if( ($i2 - $i1) < 40)
          {
            $str_2= substr($str_2, $i2 + 3);
            $j= strpos($str_2, "</p>");
            $matn = trim(substr($str_2, 0, $j));  
            str_replace("<br>", "", $matn); str_replace("</br>", "", $matn); str_replace("  ", " ", $matn); $matn = trim($matn);
          }
          fwrite($file, "matn is:" . $matn . "\n");

          $i= strpos($str_2, 'reveal="');
          if($i !== FALSE)
          {
            $str_2= substr($str_2, $i+8);
            $j= strpos($str_2, '"');
            $m= trim(substr($str_2, 0, $j));  
          }
          fwrite($file, "m is:" . $m . "\n"); //en shaaAllah : email; very important (cost-effective)
          


 
 
          //shokr , now insert the record in to the database

          $sql = "INSERT INTO `shmelk`(`curdate`, `cat`, `state`, `city`, `meter`, `room`, `price`, `type`, `image`, `source`, `matn`, `m`, `howlongago`) values('" . date('F Y') . "', '" . $cat . "', '" . $state . "', '" . $city . "', '" . $meter . "', '" . $room . "', '" . $price . "', '" . $type . "', '" . $origimagename. "', '" . $source . "', '" . $matn . "', '" . $m . "', '" . $howlongago. "')";

          fwrite($file, "the sql is:" . $sql . "\n");

          if ($conn->query($sql) === TRUE)
          {
            fwrite($file, "inserted into the table successfully, shooooookrr" . "\n");
          } 
          else 
          {
            fwrite($file, "errroror inserting into the table" . "\n");
          }

          //also need to handle possible errors, soon en shaaAllah

          fwrite($file, "---------------------------------------------\n\n");

       
          $i= strpos($str, "<article class=");
          if($i==FALSE) 
          {
            //see if next page:
            $k1 = strpos($str, "pagination");
            $str= substr($str, $k1);
            
            $k2 = strpos($str, "<footer");
            $str= substr($str, 0, strlen($str)-$k2);     
          fwrite($file, "last cut pagination is:" . $str . "\n\n");           
            $k2 = strpos($str, "بعدی");
            if( ($k2 == FALSE) || ($tmpCountPages > 2) )
            { 
              $flgFinished = 1;
            }
            else
            {
       $tmpCountPages ++; 
          fwrite($file, "-----------------------------------next page-----------------------------\n\n");
              $str= substr($str, 0, strlen($str)-$k2);
              $k1 = strrpos($str, 'href="');
              $str= substr($str, $k1+6);
              $k2 = strpos($str, '"');
              $nexturl= trim(substr($str, 0, $k2)); fwrite($file, "nexturl is:" . $nexturl. "\n");
              //$str =file_get_contents("$url"); 
              $crl = curl_init();
              $timeout = 50;
              curl_setopt ($crl, CURLOPT_URL,$url);
              curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
              curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
              curl_setopt($crl, CURLOPT_ENCODING ,"");
              $str = curl_exec($crl);
              curl_close($crl);
            }
          }                    

          sleep(1);
        }//while





      }//for each type 
    }//for each type
  }//for each state


$conn->close();
fclose($file);

?>
