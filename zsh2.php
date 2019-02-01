<?php
  require_once 'dbconnect.php';
  //require_once 'myfunctions.php';
  date_default_timezone_set('Asia/Tehran');
  $file = fopen("zsh2.log","w");
  fwrite($file, "salaam" . "\n\n");
  fwrite($file, "started on " . date("d-M-Y") . " at " . date("H:i:s") . "\n\n");
  
  $TMP_MAX_CNT = 10000;//for avoiding indefinte loops only; otherwise dangerous to use because subsequent ones can only be read afterward
  $toal_num_curl= 0;//to log and revise the algorithm en sha Allah

  $states = array("آذربایجان شرقی"=>"آذربایجان-شرقی", 
                  "آذربایجان غربی"=>"آذربایجان-غربی",
                  "اردبیل"=>"اردبیل",
                  "اصفهان"=>"اصفهان",
                  "البرز"=>"البرز",
                  "ایلام"=>"ایلام",
                  "بوشهر"=>"بوشهر",
                  "تهران"=>"تهران",
                  "چهار محال و بختیاری"=>"چهارمحال-و-بختیاری",
                  "خراسان جنوبی"=>"خراسان-جنوبی",
                  "خراسان رضوی"=>"خراسان-رضوی",
                  "خراسان شمالی"=>"خراسان-شمالی",
                  "خوزستان"=>"خوزستان",
                  "زنجان"=>"زنجان",
                  "سمنان"=>"سمنان",
                  "سیستان و بلوچستان"=>"سیستان-و-بلوچستان",
                  "فارس"=>"فارس",
                  "قزوین"=>"قزوین",
                  "قم"=>"قم",
                  "کردستان"=>"کردستان",
                  "کرمان"=>"کرمان",
                  "کرمانشاه"=>"کرمانشاه",
                  "کهگیلویه و بویراحمد"=>"کهگیلویه-و-بویراحمد",
                  "گلستان"=>"گلستان",
                  "گیلان"=>"گیلان",
                  "لرستان"=>"لرستان",
                  "مازندران"=>"مازندران",
                  "مرکزی"=>"مرکزی",
                  "هرمزگان"=>"هرمزگان",
                  "همدان"=>"همدان",
                  "یزد"=>"یزد");        

  //----------------------------------------
 $tmpCountPosts = 0; 
//----------------------   just to present----------
 $states=array("البرز"=>"البرز");
//---------------------------------------------------
 $cats= array("فروش مسکونی", "رهن و اجاره مسکونی", "فروش اداری تجاری", "رهن و اجاره اداری تجاری");//zamin va bagh an sayer later en shaa Allah
 for($i_cat=0  ;$i_cat <=    3   ; $i_cat++)
 {
  $cat = $cats[$i_cat];

/*  if($i_cat==0)
  {
    $c = "43604";//فروش مسکونی
    $types = array("آپارتمان"=>"440470", "خانه"=>"440471", "ویلا"=>"440472");
  }
  else//NOTE : assume i_cat is either 0 or 1
  {
    $c = "43606";//رهن و اجاره مسکونی
    $types = array("آپارتمان"=>"440477", "خانه"=>"440478", "ویلا"=>"440479", "اتاق و خوابگاه"=>"440481");//zamin va edari va gheireh later en shaa Allah
  }

  $rooms= array("0"=>"439837", "1"=>"439414", "2"=>"439415", "3"=>"439416", "4"=>"439417", "5 یا بیشتر"=>"439418");
*/
  foreach($states as $state => $stateurl)
  {
    if($tmpCountPosts >= $TMP_MAX_CNT) 
      break;
    
    $sql_mantaghe = "select distinct `mantaghe` from `omm` where `state`='" . $state . "' ";
    $res_mantaghe = $conn->query($sql_mantaghe);
    if($res_mantaghe)
    {
      $n_mantaghe = $res_mantaghe->num_rows;
      $i=0;
      while($row_mantaghe = $res_mantaghe->fetch_assoc())
      {
        $mantaghe[$i] = $row_mantaghe["mantaghe"];

        $tmpmantaghe = $mantaghe[$i];
        $tmpmantaghe = str_replace('ك', 'ک', $tmpmantaghe); //note that because of unicde, the first and second arguments seem to be swapped!!!
        $tmpmantaghe = str_replace('و', 'و', $tmpmantaghe); //note that because of unicde, the first and second arguments seem to be swapped!!!
        $tmpmantaghe = str_replace('ي', 'ی', $tmpmantaghe); //note that because of unicde, the first and second arguments seem to be swapped!!!
        $mantaghe[$i] = $tmpmantaghe ;

        $i++;
      }
    }
    else
    {
      fwrite($file, "ERROR: sql: " . $sql_mantaghe . "\n");
      exit;
    }

$type= "un";
$room= "un";

/*    foreach($types as $type => $typeurl)
    {
      if($tmpCountPosts >= $TMP_MAX_CNT) 
        break;
        
      foreach($rooms as $room => $roomurl)
      {
        if($tmpCountPosts >= $TMP_MAX_CNT) 
          break;
*/

        //the following is to be run once en shaaAllah to initialize the otherwise empty table zsh2:
        /*$state = mysqli_real_escape_string($conn, $state);                   
        $sql = "INSERT INTO `zsh2`(`i_cat`, `state`, `cnt`, `P`) values(" . $i_cat . ", '" . $state . "', 12, 12)";
        fwrite($file, "sql is:" . $sql . "\n");        
          
        if ($conn->query($sql) === TRUE)
        {
          fwrite($file, "inserted into the zsh2 table successfully, shooooookrr" . "\n");
        } 
        else 
        {
          fwrite($file, "errroror inserting into the table. sql is: " . $sql . "\n");
          exit("errroror inserting into the table");
        }       
        continue;*/


        //ma shaa Allah la ghavvata ella beAllah
        //first see if turn:

        $tmp_zsh2_where =  " where `i_cat`=" . $i_cat . " and `state`='" . $state . "'";
        $sql_zsh2_select= "select `cnt`, `P` from zsh2 " . $tmp_zsh2_where;
        $res_zsh2_select = $conn->query($sql_zsh2_select);
        $row_zsh2_select= $res_zsh2_select->fetch_assoc();   
        $cnt = $row_zsh2_select["cnt"];      
        $P = $row_zsh2_select["P"];      
        fwrite($file, "tmp_zsh2_where : " . $tmp_zsh2_where  . ", cnt=" . $cnt . ", P=" . $P . "\n");      

        if($cnt > 0)
        {
          //fwrite($file, "cnt=" . $cnt . ", so no turn yet. " );
          //decrement it
          $cnt--;
          //------------------  just for present--------------
          $cnt=0;
          //--------------------------------------------------
          $sql_zsh2_update = "UPDATE `zsh2` SET `cnt`=" . $cnt  . $tmp_zsh2_where;
          //fwrite($file, "sql_zsh2_update: " . $sql_zsh2_update . "\n");
          if ($conn->query($sql_zsh2_update) === TRUE)
          {
            fwrite($file, "updated cnt successfully, shokr" . "\n");
          }
          else 
          {
            fwrite($file, "error updaing cnt" . "\n");         
          }

          continue;//to keep an eye on this en  shaa Allah
        }
        //NOTE NOTE: else is omitted because of the 'continue' instruction above otherwise would certainly be required, to improve soon en shaa Allah

        $cnt_new_posts=0;//to decide on new P based on this                      
        $allowed_num_old_posts = 7;//number of old posts that can be seen withour breaking the while loop; 0 initially and 2 (for now) for each new page (to safe guard against the related issue of new post coming just at the transition to a new page- NOTE that it should be increased if sleep is used between pages


        if($i_cat==0) $url = 'http://www.sheypoor.com/' . $stateurl . '/املاک/فروش-مسکونی' ;//NOTE www.  
        if($i_cat==1) $url = 'http://www.sheypoor.com/' . $stateurl . '/املاک/رهن-و-اجاره-مسکونی' ;//NOTE www.  
        if($i_cat==2) $url = 'http://www.sheypoor.com/' . $stateurl . '/املاک/فروش-اداری-و-تجاری' ;//NOTE www.  
        if($i_cat==3) $url = 'http://www.sheypoor.com/' . $stateurl . '/املاک/اجاره-اداری-و-تجاری' ;//NOTE www.  
//zamin va bagh an sayer later en shaa Allah
        fwrite($file, "cat, state, type and room are : " . $cat . ", " . $state . ", " . $type . ", " . $room . "\n");      
        fwrite($file, "about to do the first curl with url: " . $url . "\n");      

/*        $crl = curl_init();
        $timeout = 50;
        curl_setopt ($crl, CURLOPT_URL,$url);
        curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($crl, CURLOPT_ENCODING ,"");
        $str = curl_exec($crl);
        curl_close($crl);
*/



        $crl= curl_init($url);
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
        $str = curl_exec_follow($crl);
        curl_close($crl);
        $toal_num_curl++;
//mysleep();
  $conn->close();
  sleep(rand(4, 7));//just in case for avoinding possible annoyance              
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) 
  {
    //try once again after one second
    sleep(1);
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) 
    {  
     die("Connection failed: " . $conn->connect_error);
    } 
  }
  $conn->set_charset("utf8");
  //header("content-type:text/html;charset=UTF-8");  

        
        $flgFinished = 0;
        $i= strpos($str, "<article class=");
        if($i===FALSE) 
        {
          fwrite($file, "NO POST FOUND, so not entering the while loop." . "\n");// here is the received string: " . $str . "\n");   
          $flgFinished =1;     //NOTE NOTE: HERE DONT USE BREAK OR CONTINUE (to go after the while loop and update cnt and P) ; to revise soon en shaa Allah
        }
        else
          $str = substr($str, $i + 15); 
  
        //fwrite($file, "str is:" . $str . "\n");        
        fwrite($file, "about to enter (or skip) the while loop" . "\n");        
        while( $flgFinished === 0 )  
        {
          if($tmpCountPosts >= $TMP_MAX_CNT) 
            break;
          
          $tmpCountPosts ++;
          
          //extract the article
          $end_article_pos = strpos($str, "</article>");//en shaa Allah : always exists
          $str_article = substr($str, 0, $end_article_pos); //should include both openning and closing article tags
          //fwrite($file, "str_article is:" . $str_article . "\n");

          //post id
          $post_id= "";// in sheypoor that's called listing id
          $i = strpos($str_article, 'id="listing-'); 
          if($i !== false)
          {
            $str_article = substr($str_article, $i + 12); 
            $j= strpos($str_article, '"'); 
            $post_id= trim(substr($str_article, 0, $j));
          }
          fwrite($file, "post_id is:" . $post_id . "\n");


          //see if already read within the past 24 hours to revise soon en shaa Allah)
          $tmp_is_new = 1;
          $sql_check_already = "select count(*) as N from `cnotes` where source like '%sheypoor.com%' AND post_id='" . $post_id . "'";   //NOTE: ASSUMUD UNIQUE, TO REVISE SOON esA
          $res_check_already = $conn->query($sql_check_already);
          if($res_check_already)
          {
            $row_check_already = $res_check_already ->fetch_assoc();         
            if($row_check_already["N"] > 0)
              $tmp_is_new = 0;
          }
          if($tmp_is_new == 0)
          {
            if ($allowed_num_old_posts > 0)
            {
              $allowed_num_old_posts--;
              fwrite($file, "post_id " . $post_id . " has already been read, but ignoring because of allowed_num_old_posts." . "\n");
            }
            else
            {
              fwrite($file, "post_id " . $post_id . " has already been read, so breaking." . "\n");
              fwrite($file, "---------------------------------------------\n\n");
              break;//to keep an eye on this en  shaa Allah
            }
          }
          else//i.e. a new post
          {
            $allowed_num_old_posts = 7;

            //source
            $source= "";
            $i = strpos($str_article, 'data-href="'); 
            if($i !== false)
            {
              $str_article = substr($str_article, $i + 11); 
              $j= strpos($str_article, '"'); 
              $source= trim(substr($str_article, 0, $j));
            }
            fwrite($file, "source is:" . $source . "\n");
  
            //image
            $imagelink = $origimagename= "";
            $newimagename= "sh_no_image.jpg";//copied manually for the first time        
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
            fwrite($file, "imagelink is:" . $imagelink . "\n");
                $k1= strrpos($imagelink , "/");
                $origimagename= trim(substr($imagelink , $k1 +1)); 
            fwrite($file, "origimagename is:" . $origimagename . "\n");
              
                //avoid unnecessary copies of the default and previously-copied images:
                if( strcmp($origimagename, "real-estate.jpg")!=0 ) 
                {
                  $newimagename = 'sh_' . microtime();              
                  $tmpPath = 'cimg/' . $newimagename;
                  //copy($imagelink , $tmpPath);     
                }              
              }
            }
            fwrite($file, "newimagename is: " . $newimagename. "\n");
          


            //title, city, price, and howlongago
            $title = ""; $city = ""; $price = "";  $rahn = "";  $ejare= ""; $howlongago = "";
   
            $i= strpos($str_article, '<div class="content"');
            if($i !== FALSE)
            { //ASSUMED, the content div includes the title and at least the second <p> tag - for both price (with strong tag) and howlongago (for time tag)
              //and the only missing parts may be the price and the howlongago
              $str_article= substr($str_article, $i);
            

              //title
              $i = strpos($str_article, '<h2>'); 
              if($i !== false)
              {
                $str_article = substr($str_article, $i + 4);
                $j = strpos($str_article, '</h2>'); 
                $title = trim(substr($str_article, 0, $j));
                $i = strpos($title, '">'); 
                if($i !== false)
                {
                  $title = substr($title, $i + 2);
                  $j = strpos($title , '</a>'); 
                  $title = trim(substr($title, 0, $j));
                }            
              }
              fwrite($file, "title is:" . $title . "\n");
            


              //looking for mahal----------------------------------------------------------
              $i= strpos($str_article, '<p>');
              $str_article = substr($str_article, $i+3);
              $j= strpos($str_article, '</p>');
              $tmpmahaletc= trim(substr($str_article, 0, $j));
              //also replace half spaces with space:
              $tmpmahaletc= str_replace('‌', ' ', $tmpmahaletc);


              //previuous method to guess city:
              //$k= strrpos($tmpmahaletc, "،");  //NOTE virgol (farsi) not comma
              //if($k == FALSE)//=== ?  also this logic seems to retrieve mahal not city; to revise soon en shaa Allah
             //   $city = trim($tmpmahaletc) ;
              //else
              //  $city= trim(substr($tmpmahaletc, $k+2)); 
              //be careful city is most likely wrong (is mahal I think) to amend soon en shaa Allah
              //fwrite($file, "city is: " . $city. "\n");//NOTE NOTE, because of unicode, $k+1 would remove half of the virgoul , resulting in a strange character �

              //new method; to enhance soon en shaa Allah                     
              $tmpFinalMantaghe = "";          
              $tmpFinalMahal    = "";          
              extract_mantaghe_and_mahal($tmpmahaletc, $file, $conn, $state, $mantaghe, $n_mantaghe, $tmpFinalMantaghe, $tmpFinalMahal);  
              //find_mantaghe_and_mahal($tmpmahaletc, $file, $conn, $state, $mantaghe, $n_mantaghe, $tmpFinalMantaghe, $tmpFinalMahal);  
              
              //if mantaghe is the state, by user's mistake, then fix it:
              if( (strcmp(cleanup($tmpFinalMantaghe), cleanup($state))==0) and  (ismarkaz($tmpFinalMantaghe)==0) )
              {
                $tmpFinalMantaghe = $tmpFinalMahal;
                $tmpFinalMahala = '';
              }
              fwrite($file, "final mantaghe and mahal are: " . $tmpFinalMantaghe . ", " . $tmpFinalMahal . "\n");            

              //-------------------------------------------------------------------------------


              if($i_cat==0)
              {
                $i= strpos($str_article, 'item-price">');
                if($i !== FALSE)//== ?
                {
                  $str_article= substr($str_article, $i+12);
                  $j= strpos($str_article, '</');
                  $price= trim(substr($str_article, 0, $j)); 
                }
                fwrite($file, "price is:" . $price . "\n");
              }
              else//NOTE : assume i_cat is either 0 or 1
              {
                $iRahn= strpos($str_article, '>رهن<');
                if($iRahn!== FALSE)
                {
                  //extract part of the string associated with the rahn only
                  $tmpRahnStr = substr($str_article, $iRahn);
                  $iEjare= strpos($tmpRahnStr , '>اجاره<');//BE VERY CAUTIOUS
                  if($iEjare!==FALSE)
                    $tmpRahnStr = substr($tmpRahnStr , 0, $iEjare); //BE VERY CAUTIOUS
               
                  $iRahnPrice= strpos($tmpRahnStr, '"item-price">');
                  if($iRahnPrice!==FALSE)
                  {
                    $tmpRahnStr = substr($tmpRahnStr , $iRahnPrice + 13);
                    $endRahnPrice= strpos($tmpRahnStr, '</strong');
                    $rahn = trim(substr($tmpRahnStr , 0, $endRahnPrice));
                  }
                }
                $iEjare= strpos($str_article, '>اجاره<');
                if($iEjare!== FALSE)
                {
                  $tmpEjareStr = substr($str_article, $iEjare);
                  $iEjarePrice= strpos($tmpEjareStr , '"item-price">');
                  if($iEjarePrice!==FALSE)
                  {
                    $tmpEjareStr = substr($tmpEjareStr , $iEjarePrice+ 13);
                    $endEjarePrice= strpos($tmpEjareStr , '</strong');
                    $ejare= trim(substr($tmpEjareStr , 0, $endEjarePrice));
                  }
                }
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
              fwrite($file, "howlongago is:" . $howlongago. "\n");
            }
  

            //This is to avoid too much bothering that site only assuming we do at least one per day, to revise soon en shaa Allah          
            if( (mb_strpos($howlongago, 'ماه') !== FALSE)|| (mb_strpos($howlongago, 'هقته') !== FALSE) || (mb_strpos($howlongago, 'روز') !== FALSE) )
            {
              fwrite($file, "post too old, so breaking". "\n");
              fwrite($file, "---------------------------------------------\n\n");
              break;//keep an eye on it
            }

            //time offset back from now, in sec.
            $howmanysecago      = 0; //NOTE to fix soon en shaa Allah: if the ad is فوری or does not have in the first page howlongago, then howsecago remains 0
            if(strlen(trim($howlongago)) > 0)
              $howmanysecago = calc_sec_ago($howlongago);
            //fwrite($file, "howlongago is:" . $howlongago . ", and howmanysecago is:" . $howmanysecago . "\n");

            //now the internal page:
            $meter=""; $matn =""; $m="";

           /* fwrite($file, "about to do the internal curl with source: " . $source . "\n");
            $crl = curl_init();
            $timeout = 50;
            curl_setopt ($crl, CURLOPT_URL,       $source);
            curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($crl, CURLOPT_ENCODING ,"");
            $str_2 = curl_exec($crl);
            curl_close($crl);
            $toal_num_curl++;


            //fwrite($file, "second page: shokr:" . "\n" . $str_2 . "\n");      

            $i= strpos($str_2, "<table");
            $str_2= substr($str_2, $i); 
            $i= strpos($str_2, "متراژ</th>");//NOTE: such patterns may exist in the matn  <<<<<<
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
            $str_2= substr($str_2, $i1 + 8); 
            $i1 = strpos($str_2, "</table>");//NOTE: there are 2 tables
            $str_2= substr($str_2, $i1); 
            $i2 = strpos($str_2, "<p>");                 
            if( ($i2 - $i1) < 40)
            {
              $str_2= substr($str_2, $i2 + 3);
              $j= strpos($str_2, "</p>");
              $matn = trim(substr($str_2, 0, $j));  
              str_replace("<br>", "", $matn); str_replace("</br>", "", $matn); str_replace("  ", " ", $matn); $matn = trim($matn);
            }
            //fwrite($file, "matn is:" . $matn . "\n");

            $i= strpos($str_2, 'reveal="');
            if($i !== FALSE)
            {
              $str_2= substr($str_2, $i+8);
              $j= strpos($str_2, '"');
              $m= trim(substr($str_2, 0, $j));  
            }
            //fwrite($file, "m is:" . $m . "\n"); //en shaaAllah : email; very important (cost-effective)
         */ 

            //shokr , now insert the record in to the database
            //remove half spaces with space:
            $tmpFinalMantaghe = cleanedArabicHalfSpaceAndNumbers($tmpFinalMantaghe);
            $tmpFinalMahal = cleanedArabicHalfSpaceAndNumbers($tmpFinalMahal);
            $city = $tmpFinalMantaghe;// for noe, to revise soon en shaa Allah
                       
            $title = strip_tags($title );         
            $meter = strip_tags($meter );          
            $room = strip_tags($room );          
            $type = strip_tags($type );          
            $newimagename= strip_tags($newimagename);          
            $source = strip_tags($source );          
            $matn = strip_tags($matn );          
            $m = strip_tags($m );          
            $howlongago= strip_tags($howlongago);          
            $howmanysecago = strip_tags($howmanysecago);          
            $post_id = strip_tags($post_id);          
                              
            
            $cat = mysqli_real_escape_string($conn, $cat);          
            $state = mysqli_real_escape_string($conn, $state );          
            $city = mysqli_real_escape_string($conn, $city );          
            $tmpFinalMantaghe = mysqli_real_escape_string($conn, $tmpFinalMantaghe );          
            $tmpFinalMahal = mysqli_real_escape_string($conn, $tmpFinalMahal ); 
            $title = mysqli_real_escape_string($conn, $title );          
            $meter = mysqli_real_escape_string($conn, $meter );          
            $room = mysqli_real_escape_string($conn, $room );          
            $type = mysqli_real_escape_string($conn, $type );                      
            $imagelink= mysqli_real_escape_string($conn, $imagelink);          
            $newimagename= mysqli_real_escape_string($conn, $newimagename);          
            $source = mysqli_real_escape_string($conn, $source );          
            $matn = mysqli_real_escape_string($conn, $matn );          
            $m = mysqli_real_escape_string($conn, $m );          
            $howlongago= mysqli_real_escape_string($conn, $howlongago);          
            $howmanysecago = mysqli_real_escape_string($conn, $howmanysecago);          
            $post_id = mysqli_real_escape_string($conn, $post_id);          


            //price or rahn va ejare, esA
            if($i_cat==0)
            {
              $price = strip_tags($price );          
              $price = mysqli_real_escape_string($conn, $price);          
              $sql = "INSERT INTO `cnotes`(`ymd`, `time`, `cat`, `state`, `city`, `mantaghe2`, `mahal2`, `title`, `meter`, `room`, `tp_rahn`, `type`, `image`, `imagelink`, `source`, `matn`, `m`, `howlongago`, `howmanysecago`,`post_id`) values('" . date('Y-M-d') . "', " . time() . ", '" . $cat . "', '" . $state . "', '" . $city . "', '" . $tmpFinalMantaghe . "', '" . $tmpFinalMahal . "', '" . $title . "', '" . $meter . "', '" . $room . "', '" . $price . "', '" . $type . "', '" . $newimagename . "', '" . $imagelink. "', '" . $source . "', '" . $matn . "', '" . $m . "', '" . $howlongago . "', " . $howmanysecago . ", '" . $post_id . "')";
            }
            else//NOTE: assumes that i_cat is either 0 or 1
            {
              $rahn = strip_tags($rahn );          
              $ejare = strip_tags($ejare );          
              $rahn = mysqli_real_escape_string($conn, $rahn );          
              $ejare = mysqli_real_escape_string($conn, $ejare );          
              $sql = "INSERT INTO `cnotes`(`ymd`, `time`, `cat`, `state`, `city`, `mantaghe2`, `mahal2`, `title`, `meter`, `room`, `tp_rahn`, `ppm_ejare`, `type`, `image`, `imagelink`, `source`, `matn`, `m`, `howlongago`, `howmanysecago`, `post_id`) values('" . date('Y-M-d') . "', " . time() . ", '" . $cat . "', '" . $state . "', '" . $city . "', '" . $tmpFinalMantaghe . "', '" . $tmpFinalMahal . "', '" . $title . "', '" . $meter . "', '" . $room . "', '" . $rahn . "', '" . $ejare . "', '" . $type . "', '" . $newimagename. "', '" . $imagelink. "', '" . $source . "', '" . $matn . "', '" . $m . "', '" . $howlongago . "', " . $howmanysecago . ", '" . $post_id . "')";
            }


            //fwrite($file, "the sql is:" . $sql . "\n");
            
            if ($conn->query($sql) === TRUE)
            {
              fwrite($file, "inserted into the sh table successfully, shooooookrr" . "\n");
              $cnt_new_posts++;
            } 
            else 
            {
              fwrite($file, "errroror inserting into the table. sql is: " . $sql . "\n");
              exit("errroror inserting into the table");
            }
          }//else of if old (i.e. new post)
            
          fwrite($file, "---------------------------------------------\n\n");

       
          $i= strpos($str, "<article class=");
          if( $i===FALSE ) //just in case to prevent infinite loop due to potential bugs
          {
            //see if next page:
            $k1 = strpos($str, '"pagination"');
            $str= substr($str, $k1);
              
            $k2 = strpos($str, '<footer');
            $str= substr($str, 0, $k2);     
            $k2 = strpos($str, "بعدی");

            if( $k2 === FALSE) 
            {
              fwrite($file, "---------------------------------------------\n\n");
              $flgFinished = 1;
            }
            else
            {
              fwrite($file, "---------------------next page----------------\n\n");
              $str= substr($str, 0, $k2);
              $k1 = strrpos($str, 'href="');//NOTE: rpos not pos
              $str= substr($str, $k1+6);
              $k2 = strpos($str, '"');
              $url= trim(substr($str, 0, $k2)); 
              //$url = "http://www.sheypoor.com" . $url; here was a bug, take case

              fwrite($file, "about to do the first curl with url: " . $url . "\n");      
 
/*                $crl = curl_init();
                $timeout = 50;
                curl_setopt ($crl, CURLOPT_URL,$url);
                curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
                curl_setopt($crl, CURLOPT_ENCODING ,"");
                $str = curl_exec($crl);
                curl_close($crl);
*/
              $crl= curl_init($url);
              curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
              $str = curl_exec_follow($crl);
              curl_close($crl);
//mysleep();
  $conn->close();
  sleep(rand(4, 7));//just in case for avoinding possible annoyance              
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) 
  {
    //try once again after one second
    sleep(1);
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) 
    {  
     die("Connection failed: " . $conn->connect_error);
    } 
  }
  $conn->set_charset("utf8");
  //header("content-type:text/html;charset=UTF-8");  

              $toal_num_curl++;
                

              $i= strpos($str, "<article class=");
              if($i===FALSE)
              {
                fwrite($file, "------------------no more post was found in the while so finishing the while loop--------\n\n");
                $flgFinished = 1;
              }
              else
              {
                $str = substr($str, $i + 15); 
              }
            }
          }
          else
          {
            $str = substr($str, $i + 15); 
          }                    
        }//while
        
        fwrite($file, "after the while loop for the where_zsh2_select:" . $where_zsh2_select . " and cnt_new_posts=" . $cnt_new_posts . "\n");

        //update P
        if($cnt_new_posts != 20)
        {
          if($cnt_new_posts > 20)
          {
            if($P>1) //no less than 1 of course
              $P--;  
          }
          else// i.e. <20
          {
            $P++; 
          }
             
          $sql_zsh2_update = "UPDATE `zsh2` SET `cnt`=" . $P . ", `P`=" . $P . $tmp_zsh2_where;
          fwrite($file, "sql_zsh2_update: " . $sql_zsh2_update . "\n");
          if ($conn->query($sql_zsh2_update) === TRUE)
            fwrite($file, "updated cnt and P to: " . $P . "\n");
          else 
            fwrite($file, "error updaing cnt and P" . "\n");         

          fwrite($file, "-----------------------------------------------------------------------" . "\n");      
        }
        
/*      }//for each room 
    }//for each type
    */
  }//for each state
 }//for each cat

 fwrite($file, "toal_num_curl= " . $toal_num_curl . "\n\n");
 fwrite($file, "al hamdo leAllah , finished on " . date("d-M-Y") . " at " . date("H:i:s") . "\n\n");
 fclose($file);

//------------------------------------------------------------------

function mysleep()
{
  $conn->close();
  sleep(rand(4, 7));//just in case for avoinding possible annoyance              
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) 
  {
    //try once again after one second
    sleep(1);
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) 
    {  
     die("Connection failed: " . $conn->connect_error);
    } 
  }
  $conn->set_charset("utf8");
  //header("content-type:text/html;charset=UTF-8");  
}//mysleep

//------------------------------------------------------------------


function extract_mantaghe_and_mahal($tmpmahaletc, $file, $conn, $state, $mantaghe, $n_mantaghe, &$tmpFinalMantaghe, &$tmpFinalMahal)
{
  $tmpFinalMantaghe ="";
  $tmpFinalMahal    ="";

  $tmpmahaletc= str_replace('ك', 'ک', $tmpmahaletc); //note that because of unicde, the first and second arguments seem to be swapped!!!
  $tmpmahaletc= str_replace('و', 'و', $tmpmahaletc); //note that because of unicde, the first and second arguments seem to be swapped!!!
  $tmpmahaletc= str_replace('ي', 'ی', $tmpmahaletc); //note that because of unicde, the first and second arguments seem to be swapped!!!
         
  //for now so simple, to enhance soon en shaa Allah
  $i = strpos($tmpmahaletc, "،"); 
  if($i !== false)
  {
//    $tmpFinalMantaghe = trim(mb_substr($tmpmahaletc , 0, $i));
//    $tmpFinalMahal    = trim(mb_substr($tmpmahaletc , $i+2));

   $tmparray =explode("،" , trim($tmpmahaletc ));
   $tmpFinalMantaghe = trim($tmparray[0]);
   $tmpFinalMahal    = trim($tmparray[1]);

  }
  
  
}

//------------------------------------------------------------------

function find_mantaghe_and_mahal($tmpmahaletc, $file, $conn, $state, $mantaghe, $n_mantaghe, &$tmpFinalMantaghe, &$tmpFinalMahal)
{
         $tmpmahaletc= str_replace('ك', 'ک', $tmpmahaletc); //note that because of unicde, the first and second arguments seem to be swapped!!!
         $tmpmahaletc= str_replace('و', 'و', $tmpmahaletc); //note that because of unicde, the first and second arguments seem to be swapped!!!
         $tmpmahaletc= str_replace('ي', 'ی', $tmpmahaletc); //note that because of unicde, the first and second arguments seem to be swapped!!!


     
        //remove state, if exists
        //fwrite($file, "looking for the state: " . $state . " in tmpmahaletc:" . $tmpmahaletc . " \n");
        $i = strpos($tmpmahaletc, $state); 
        if($i !== false)
        {
          //fwrite($file, "state was found at i: " . $i . "\n");
          if($i > 0)
            fwrite($file, "!!!!!WARNING: tmpmahaletc: " . $tmpmahaletc. " and the state " . $state . " does not start exactly at the beginning!!!" . "\n");
          else
          {
            $tmpmahaletc = trim(mb_substr($tmpmahaletc , $i+mb_strlen($state)));                               
            //fwrite($file, "after removing state, tmpmahaletc: " . $tmpmahaletc. "\n");

            //clean
            $tmpstr = mb_substr($tmpmahaletc, 0, 2);
            $k = strpos($tmpstr, '،'); 
            if($k !== false)
            {
              $tmpmahaletc= trim(mb_substr($tmpmahaletc, $k+mb_strlen('،')));                                              
            }

            $tmpstr = mb_substr($tmpmahaletc, 0, 2);
            $k = strpos($tmpstr, '-'); 
            if($k!== false)
            {
              $tmpmahaletc= trim(mb_substr($tmpmahaletc, $k+mb_strlen('-')));                                              
            }
            //fwrite($file, "after cleaning, if any, tmpmahaletc: " . $tmpmahaletc. "\n");
          }
        }
        fwrite($file, "the resulting addr after looking/removing the state is: " . $tmpmahaletc . " \n");
        
        //remove shahrestan, if exists
        /*fwrite($file, "looking for the shahrestan: " . $tmpcity . " in addr:" . $tmpaddr . " \n");
        $i = strpos($tmpaddr, $tmpcity); 
        if($i !== false)
        {
          if($i > 0)
            fwrite($file, "!!!!!!!!!!WARNING: addr: " . $tmpaddr . " and the shahrestan " . $tmpcity . " does not start exactly at the beginning!!!" . "\n");
          else
          {
            fwrite($file, "shahrestan was found at i: " . $i . "\n");
            $tmpaddr = trim(mb_substr($tmpaddr, $i+mb_strlen($tmpcity)));                               
            //clean
            $tmpstr = mb_substr($tmpaddr, 0, 2);
            $k = strpos($tmpstr, '،'); 
            if($k !== false)
            {
              $tmpaddr = trim(mb_substr($tmpaddr, $k+mb_strlen('،')));                                              
            }

            $tmpstr = mb_substr($tmpaddr, 0, 2);
            $k = strpos($tmpstr, '-'); 
            if($k !== false)
            {
              $tmpaddr = trim(mb_substr($tmpaddr, $k+mb_strlen('-')));                                              
            }
          }
        }
        fwrite($file, "the resulting addr after looking/removing the shahrestan is: " . $tmpaddr . " \n");
        */
        
        
        //now that shahrestan is removed (if any), first look for the exact mahal- except those that are identical to mantaghe- at almost the beginning 
        //fwrite($file, "looking for an exact mach of mahal within the state" . " \n"); //need to improve soon enshaa Allah by looking for those in the shahrestan only
        
        $sql_allmahal = "select distinct `mahal` from `omm` where `state`='" . $state . "' and `mahal`<>`mantaghe`";
        $res_allmahal = $conn->query($sql_allmahal );
        $found=0;
        while( ($row_allmahal = $res_allmahal ->fetch_assoc()) && !$found)//again first match for now, to reise soon en shaa Allah
        {
          $tmpmahal = $row_allmahal ['mahal'];
          $tmpmahal = str_replace('ك', 'ک', $tmpmahal); //note that because of unicde, the first and second arguments seem to be swapped!!!
          $tmpmahal = str_replace('و', 'و', $tmpmahal ); //note that because of unicde, the first and second arguments seem to be swapped!!!
          $tmpmahal = str_replace('ي', 'ی', $tmpmahal ); //note that because of unicde, the first and second arguments seem to be swapped!!!
          
          $tmpstr = mb_substr($tmpmahaletc, 0, 7 + mb_strlen($tmpmahal));
          //fwrite($file, "looking for the mahal " . $tmpmahal . " in tmpstr:" . $tmpstr . " \n");
          $i = strpos($tmpstr, $tmpmahal); 
          if($i !== false)
          {
            if($i > 0)
              //fwrite($file, "ٌٌ!!!!!!!!!!WARNING: tmpmahaletc: " . $tmpmahaletc. " and the mahal " . $tmpmahal . " does not start exactly at the beginning!!!" . "\n");
            //else
            {
              $found = 1;
              //fwrite($file, "the mahal " . $tmpmahal . " was found at i: " . $i . "\n");
              $tmpmahaletc = trim(mb_substr($tmpmahaletc, $i+mb_strlen($tmpmahal)));                               
              
              //now find corresponding mantaghe and update if unique
              $sql_itsmantaghe = "select distinct `mantaghe` from `omm` where `state`='" . $state . "' AND `mahal`='" . $tmpmahal . "'";
              $res_itsmantaghe = $conn->query($sql_itsmantaghe );
              if($res_itsmantaghe->num_rows == 1)
              {
                $row_itsmantaghe= $res_itsmantaghe->fetch_assoc();
                $tmpmantaghe = $row_itsmantaghe["mantaghe"];
                //fwrite($file, "exact match was found for the mahal " . $tmpmahal . " whose mantaghe is " . $tmpmantaghe . " \n");

                $tmpFinalMantaghe = $tmpmantaghe;          
                $tmpFinalMahal    = $tmpmahal;          
              }
            }
          }                                                             
        }
        
       if(!$found)
       {
        //look for a mantaghe and record then remove it, if found
        //fwrite($file, "looking for a mantaghe in addr:" . $tmpmahaletc . " \n");
        $found = 0;
        for($m=0 ; ($m < $n_mantaghe) && !$found ; $m++) //for now the first match, may revise soon en shaaAllah
        {
          $tmpmantaghe = $mantaghe[$m];

          $tmpstr = mb_substr($tmpmahaletc, 0, 7 + mb_strlen($tmpmantaghe));
          //fwrite($file, "looking for mantaghe " . $tmpmantaghe . " in tmpstr:" . $tmpstr . " \n");
          $i = strpos($tmpstr, $tmpmantaghe); 
          if($i !== false)
          {
            if($i > 0)
              //fwrite($file, "!!!!!WARNING: addr: " . $tmpmahaletc . " and the mantaghe " . $tmpmantaghe . " does not start exactly at the beginning!!!" . "\n");
            //else
            {
              //fwrite($file, "the mantaghe " . $tmpmantaghe . " was found at i: " . $i . "\n");
              $tmpmahaletc = trim(mb_substr($tmpmahaletc, $i+mb_strlen($tmpmantaghe)));                               
              //fwrite($file, "after removing the mantaghe, tmpmahaletc is: " . $tmpmahaletc . "\n");
              //clean
              $tmpstr = mb_substr($tmpmahaletc, 0, 2);
              $k = strpos($tmpstr, '،'); 
              if($k !== false)
              {
                //fwrite($file, "comma was found at k=" . $k . " in tmpmahaletc: " . $tmpmahaletc. "\n");
                $tmpmahaletc= trim(mb_substr($tmpmahaletc, $k+mb_strlen('،')));                                              
                //fwrite($file, "after removing the comma, tmpmahaletc is: " . $tmpmahaletc . "\n");
              }

              $tmpstr = mb_substr($tmpmahaletc, 0, 2);
              $k = strpos($tmpstr, '-'); 
              if($k !== false)
              {
                //fwrite($file, "dash was found at k=" . $k . " in tmpmahaletc : " . $tmpmahaletc . "\n");
                $tmpmahaletc = trim(mb_substr($tmpmahaletc, $k+mb_strlen('-')));                                              
                //fwrite($file, "after removing the dash, tmpmahaletc is: " . $tmpmahaletc . "\n");
              }
              
              //then, also look for mahal
              $tmpFinalMantaghe = $tmpmantaghe;
              $guessed_mantaghe = $tmpmantaghe;              
              $found = 1;
            }
          }                                                    
        }//for m
        if(!$found)
        {                
          //mantaghe is not found, then look for mahal for those whose mantaghe is shahrestan only (e.g. karaj)- it might have been removed as shahrestan.
          $guessed_mantaghe = $state; //$tmpcity;
        }
        //fwrite($file, "the resulting (guessed) mantaghe is : " . $guessed_mantaghe . " \n");
        
        //now, look for mahals of the mantaghe specified by tmpmantaghe
        //fwrite($file, "looking for a mahal of the guessed mantaghe in tmpmahaletc  :" . $tmpmahaletc . " \n");
        $sql_mahal = "select distinct `mahal` from `omm` where `state`='" . $state . "' AND mantaghe='" . $guessed_mantaghe . "'";
        $res_mahal = $conn->query($sql_mahal);
        $found=0;
        while( ($row_mahal = $res_mahal->fetch_assoc()) && !$found)//again first match for now, to reise soon en shaa Allah
        {
          $tmpmahal = $row_mahal['mahal'];
          $tmpmahal = str_replace('ك', 'ک', $tmpmahal); //note that because of unicde, the first and second arguments seem to be swapped!!!
          $tmpmahal = str_replace('و', 'و', $tmpmahal ); //note that because of unicde, the first and second arguments seem to be swapped!!!
          $tmpmahal = str_replace('ي', 'ی', $tmpmahal ); //note that because of unicde, the first and second arguments seem to be swapped!!!
          
          $tmpstr = mb_substr($tmpmahaletc , 0, 7 + mb_strlen($tmpmahal));
//          fwrite($file, "looking for the mahal " . $tmpmahal . " in tmpstr:" . $tmpstr . " \n");
          $i = strpos($tmpstr, $tmpmahal); 
          if($i !== false)
          {
            if($i > 0)
              //fwrite($file, "ٌٌ!!!!!!!!!!WARNING: addr: " . $tmpmahaletc . " and the mahal " . $tmpmahal . " does not start exactly at the beginning!!!" . "\n");
            //else
            {
              //fwrite($file, "the mahal " . $tmpmahal . " was found at i: " . $i . "\n");
              $tmpmahaletc  = trim(mb_substr($tmpmahaletc , $i+mb_strlen($tmpmahal)));                               
              //clean
              $tmpstr = mb_substr($tmpmahaletc , 0, 2);
              $k = strpos($tmpstr, '،'); 
              if($k !== false)
              {
                $tmpmahaletc = trim(mb_substr($tmpmahaletc , $k+mb_strlen('،')));                                              
              }

              $tmpstr = mb_substr($tmpmahaletc , 0, 2);
              $k = strpos($tmpstr, '-'); 
              if($k !== false)
              {
                $tmpmahaletc = trim(mb_substr($tmpmahaletc , $k+mb_strlen('-')));                                              
              }              
              $found = 1;
              
              //now update it:
              $tmpFinalMantaghe = $guessed_mantaghe;          
              $tmpFinalMahal    = $tmpmahal;          
            }
          }                                                             
        }
        if(!$found)
          fwrite($file, "no mahal was found" . " \n");
        else
          fwrite($file, "finally, the mahal " . $tmpFinalMahal . " was found" . " \n");
       }      
  
}//find_mantaghe_and_mahal

//--------------------------



function curl_exec_follow($ch, &$maxredirect = null)
{
  
  // we emulate a browser here since some websites detect
  // us as a bot and don't let us do our job
  $UAs = array("Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0", 
                "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36 Edge/12.246",
                "Mozilla/5.0 (X11; CrOS x86_64 8172.45.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.64 Safari/537.36",
                "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_2) AppleWebKit/601.3.9 (KHTML, like Gecko) Version/9.0.2 Safari/601.3.9",
                "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36",
                "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:15.0) Gecko/20100101 Firefox/15.0.1",
                "Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)",
                "Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)",
                "Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)",
                "Mozilla/5.0 (Linux; U; en-US) AppleWebKit/528.5+ (KHTML, like Gecko, Safari/528.5+) Version/4.0 Kindle/3.0 (screen 600x800; rotate)",
                "Mozilla/5.0 (X11; U; Linux armv7l like Android; en-us) AppleWebKit/531.2+ (KHTML, like Gecko) Version/5.0 Safari/533.2+ Kindle/3.0+",
                "Mozilla/5.0 (Nintendo 3DS; U; ; en) Version/1.7412.EU",
                "Mozilla/5.0 (PlayStation Vita 3.61) AppleWebKit/537.73 (KHTML, like Gecko) Silk/3.2",
                "Mozilla/5.0 (PlayStation 4 3.11) AppleWebKit/537.73 (KHTML, like Gecko)",
                "Mozilla/5.0 (Windows Phone 10.0; Android 4.2.1; Xbox; Xbox One) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2486.0 Mobile Safari/537.36 Edge/13.10586",
                "Mozilla/5.0 (Nintendo WiiU) AppleWebKit/536.30 (KHTML, like Gecko) NX/3.0.4.2.12 NintendoBrowser/4.3.1.11264.US",
                "AppleTV5,3/9.1.1",
                "Dalvik/2.1.0 (Linux; U; Android 6.0.1; Nexus Player Build/MMB29T)",
                "Mozilla/5.0 (Linux; Android 4.2.2; AFTB Build/JDQ39) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.173 Mobile Safari/537.22",
                "Mozilla/5.0 (Linux; U; Android 4.2.2; he-il; NEO-X5-116A Build/JDQ39) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Safari/534.30",
                "Mozilla/5.0 (CrKey armv7l 1.5.16041) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.0 Safari/537.36",
                "Mozilla/5.0 (Linux; Android 5.0.2; LG-V410/V41020c Build/LRX22G) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/34.0.1847.118 Safari/537.36",
                "Mozilla/5.0 (Linux; Android 4.4.3; KFTHWI Build/KTU84M) AppleWebKit/537.36 (KHTML, like Gecko) Silk/47.1.79 like Chrome/47.0.2526.80 Safari/537.36",
                "Mozilla/5.0 (Linux; Android 5.0.2; SAMSUNG SM-T550 Build/LRX22G) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/3.3 Chrome/38.0.2125.102 Safari/537.36",
                "Mozilla/5.0 (Linux; Android 6.0.1; SGP771 Build/32.2.A.0.253; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/52.0.2743.98 Safari/537.36",
                "Mozilla/5.0 (Linux; Android 7.0; Pixel C Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/52.0.2743.98 Safari/537.36",
                "Mozilla/5.0 (Linux; Android 6.0; HTC One M9 Build/MRA58K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.98 Mobile Safari/537.36",
                "Mozilla/5.0 (Linux; Android 6.0.1; E6653 Build/32.2.A.0.253) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.98 Mobile Safari/537.36",
                "Mozilla/5.0 (Linux; Android 6.0.1; Nexus 6P Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.83 Mobile Safari/537.36",
                "Mozilla/5.0 (Windows Phone 10.0; Android 4.2.1; Microsoft; Lumia 950) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2486.0 Mobile Safari/537.36 Edge/13.10586",
                "Mozilla/5.0 (Linux; Android 5.1.1; SM-G928X Build/LMY47X) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.83 Mobile Safari/537.36",
                "Mozilla/5.0 (Linux; Android 6.0.1; SM-G920V Build/MMB29K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.98 Mobile Safari/537.36",
                "Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53 BingPreview/1.0b",
                );
                
                
  $user_agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0";

  
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



//--------------------------

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


function cleanedArabicHalfSpaceAndNumbers($s)
{
  $s = str_replace('ك', 'ک', $s );
  $s = str_replace('ئ', 'ی', $s );     
  $s = str_replace('ي', 'ی', $s );     
  $s = str_replace('‌', ' ', $s );     
  $s = str_replace('.', '۰', $s );     
  $s = str_replace('0', '۰', $s );     
  $s = str_replace('1', '۱', $s );     
  $s = str_replace('2', '۲', $s );     
  $s = str_replace('3', '۳', $s );     
  $s = str_replace('4', '۴', $s );     
  $s = str_replace('5', '۵', $s );     
  $s = str_replace('6', '۶', $s );     
  $s = str_replace('7', '۷', $s );     
  $s = str_replace('8', '۸', $s );     
  $s = str_replace('9', '۹', $s );  

  return $s;
}

//----------------------------------------------------------------

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



?>
