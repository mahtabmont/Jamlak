<?php
  require_once 'dbconnect.php';
//  require_once 'myfunctions.php';
  date_default_timezone_set('Asia/Tehran');
  $file = fopen("zdv2.log","w");
  fwrite($file, "salaam" . "\n\n");
  fwrite($file, "started on " . date("d-M-Y") . " at " . date("H:i:s") . "\n\n");
  
  $TMP_MAX_CNT = 10000;//for avoiding indefinte loops only; otherwise dangerous to use because subsequent ones can only be read afterward
  $toal_num_curl= 0;//to log and revise the algorithm en sha Allah

  $cities = array(
                  "کرمانشاه" =>"kermanshah/کرمانشاه , کرمانشاه" ,
                  "مشهد" =>"mashhad/مشهد ,  خراسان رضوی" ,
                  "کرج" =>"karaj/كرج  ,البرز  " ,
                  "اراک" =>"arak/اراک , مرکزی" ,
                  "یزد" =>"yazd/یزد , یزد" ,
                  "اردبیل" =>"ardabil/اردبیل , اردبیل" ,
                  "بندرعباس" =>"bandar-abbas/بندرعباس , هرمزگان" ,
                  "قزوین" =>"qazvin/قزوین , قزوین" ,
                  "زنجان" =>"zanjan/زنجان , زنجان" ,
                  "گرگان" =>"gorgan/گرگان , گلستان" ,
                  "ساری" =>"sari/ساری , مازندران" ,
                  "دزفول" =>"dezful/دزفول , خوزستان" ,
                  "آبادان" =>"abadan/آبادان , خوزستان" ,
                  "بوشهر" =>"bushehr/بوشهر , بوشهر" ,
                  "بروجرد" =>"borujerd/بروجرد , لرستان" ,
                  "خرم‌آباد" =>"khorramabad/خرم آباد , لرستان" ,
                  "سنندج" =>"sanandaj/سنندج , کردستان" ,
                  "اسلام‌شهر" =>"eslamshahr/اسلام شهر , تهران" ,
                  "کاشان" =>"kashan/کاشان , اصفهان" ,
                  "نجف‌آباد" =>"najafabad/نجف آباد , اصفهان" ,
                  "ایلام" =>"ilam/ایلام , ایلام" ,
                  "کیش" =>"kish/کیش , هرمزگان" ,
                  "بیرجند" =>"birjand/بیرجند , خراسان جنوبی" ,
                  "سمنان" =>"semnan/سمنان , سمنان" ,
                  "شهرکرد" =>"shahrekord/شهرکرد , لرستان" ,
                  "بندر ماهشهر" =>"mahshahr/بندر ماهشهر , خوزستان" ,
                  "یاسوج" =>"yasuj/یاسوج , کهگیلویه و بویراحمد" ,
                  "بجنورد" =>"bojnurd/بجنورد , خراسان شمالی" ,
                  "بهبهان" =>"behbahan/بهبهان , خوزستان" ,
                  "سبزوار" =>"sabzevar/سبزوار , خراسان رضوی" ,
                  "مسجد سلیمان" =>"masjed-e-soleyman/مسجد سلیمان , خوزستان" ,
                  "نیشابور" =>"neyshabur/نیشابور , خراسان رضوی" ,
                  "شوشتر" =>"shushtar/شوشتر , خوزستان" ,
                  "قشم" =>"qeshm/قشم , هرمزگان" ,
                  "بانه" =>"baneh/بانه , کردستان" ,
                  "آمل" =>"amol/آمل , مازندران" ,
                  "بابل" =>"babol/بابل , مازندران" ,
                  "قائم‌شهر" =>"qaem-shahr/قائم شهر , مازندران" ,
                  "ساوه" =>"saveh/ساوه , مرکزی" ,
                  "تهران" => "tehran/تهران , تهران"  ,
                  "اصفهان" =>"isfahan/اصفهان , اصفهان" ,
                  "تبریز" =>"tabriz/تبریز, آذربایجان شرقی" ,
                  "شیراز" =>"shiraz/شیراز , فارس" ,
                  "اهواز" =>"ahvaz/اهواز , خوزستان" ,
                  "قم" =>"qom/قم,قم" ,
                  "ارومیه" =>"urmia/ارومیه , آذربایجان غربی" ,
                  "زاهدان" =>"zahedan/زاهدان , سیستان و بلوچستان" ,
                  "رشت" =>"rasht/رشت , گیلان" ,
                  "کرمان" =>"kerman/کرمان , کرمان" ,
                  "همدان" =>"hamedan/همدان , همدان" ,
                  "زابل" =>"zabol/زابل , سیستان و بلوچستان" );   

//--- just to presernt -------------------------------------

$cities = array("کرج" =>"karaj/كرج  ,البرز");
//--------------------------------------

$tmpCountPosts = 0; 

 $cats= array("فروش مسکونی", "رهن و اجاره مسکونی", "فروش اداری تجاری", "رهن و اجاره اداری تجاری");
 for($i_cat=0  ;$i_cat <=    3    ; $i_cat++)
 {
  $cat = $cats[$i_cat];

/*  if($i_cat==0)
    $types = array("فروش-آپارتمان", "فروش-خانه-ویلا");
  else//NOTE : assume i_cat is either 0 or 1
    $types = array("اجاره-آپارتمان", "اجاره-خانه-ویلا");
  
  $rooms= array("0"=>"1", "1"=>"2", "2"=>"4", "3"=>"8", "4"=>"16", "5 یا بیشتر"=>"32");
*/

  foreach($cities as $city => $cityurlandstate)
  {
    $city = str_replace('ك', 'ک', $city ); //note that because of unicde, the first and second arguments seem to be swapped!!!
    $city = str_replace('ی', 'ئ', $city ); //note that because of unicde, the first and second arguments seem to be swapped!!!
    $city = str_replace('ي', 'ی', $city ); //note that because of unicde, the first and second arguments seem to be swapped!!!

   $tmparray =explode("," , trim($cityurlandstate));
   $cityurl = trim($tmparray[0]);
   $state   = trim($tmparray[1]);

    if($tmpCountPosts >= $TMP_MAX_CNT) 
      break;
 
$type= "un";
$room= "un"; 
    
//    for($i_t=0  ;$i_t <= 1 ; $i_t++)
//    {
//      $type = $types[$i_t];
//      if($tmpCountPosts >= $TMP_MAX_CNT) 
//        break;
//        
//      foreach($rooms as $room => $v03)
//      {      

        if($tmpCountPosts >= $TMP_MAX_CNT) 
          break;

        //the following is to be run once en shaaAllah to initialize the otherwise empty table zdv2:
        /*$city = mysqli_real_escape_string($conn, $city);                   
        $sql = "INSERT INTO `zdv2`(`i_cat`, `city`, `cnt`, `P`) values(" . $i_cat . ", '" . $city . "', 12, 12)";
        fwrite($file, "sql is:" . $sql . "\n");        
          
        if ($conn->query($sql) === TRUE)
        {
          fwrite($file, "inserted into the zdv2 table successfully, shooooookrr" . "\n");
        } 
        else 
        {
          fwrite($file, "errroror inserting into the table. sql is: " . $sql . "\n");
          exit("errroror inserting into the table");
        }       
        continue;*/
        
        //ma shaa Allah la ghavvata ella beAllah
        //first see if turn:

        $tmp_zdv2_where =  " where `i_cat`=" . $i_cat . " and `city`='" . $city . "'";
        $sql_zdv2_select= "select `cnt`, `P` from zdv2 " . $tmp_zdv2_where;
        $res_zdv2_select = $conn->query($sql_zdv2_select);
        $row_zdv2_select= $res_zdv2_select->fetch_assoc();   
        $cnt = $row_zdv2_select["cnt"];      
        $P = $row_zdv2_select["P"];      
        fwrite($file, "tmp_zdv2_where : " . $tmp_zdv2_where  . ", cnt=" . $cnt . ", P=" . $P . "\n");      

        if($cnt > 0)
        {
          //fwrite($file, "cnt=" . $cnt . ", so no turn yet. " );
          //decrement it
          $cnt--;
          //------------ Again just for present--------------
          $cnt=0;
          //------------------------------------------------
          $sql_zdv2_update = "UPDATE `zdv2` SET `cnt`=" . $cnt  . $tmp_zdv2_where;
          if ($conn->query($sql_zdv2_update) === TRUE)
          {
            //fwrite($file, "updated cnt successfully, shokr" . "\n");
          }
          else 
          {
            fwrite($file, "error updaing cnt" . "\n");         
          }
          continue;//to keep an eye on this en  shaa Allah
        }
        //NOTE NOTE: else is omitted because of the 'continue' instruction above otherwise would certainly be required, to improve soon en shaa Allah

        $cnt_new_posts=0;//to decide on new P based on this                      
        $allowed_num_old_posts= 7;//number of old posts that can be seen withour breaking the while loop; 0 initially and 2 (for now) for each new page (to safe guard against the related issue of new post coming just at the transition to a new page- NOTE that it should be increased if sleep is used between pages
       
        if($i_cat==0) $tmp1 = urlencode("فروش-مسکونی-آپارتمان-خانه-زمین");//NOTE: do not change i_cat then because no elses were used
        if($i_cat==1) $tmp1 = urlencode("اجاره-مسکونی-آپارتمان-خانه-زمین");
        if($i_cat==2) $tmp1 = urlencode("فروش-اداری-تجاری-مغازه-دفتر-کار-صنعتی");
        if($i_cat==3) $tmp1 = urlencode("اجاره-اداری-تجاری-مغازه-دفتر-کار-صنعتی");

        $tmp2 = urlencode("املاک-مسکن");//NOTE NOTE: did not work without urlencode (whereas tmp3 below worked!)
        //$tmp3 = urlencode($type);
            
        $url = 'https://divar.ir/' . $cityurl . '/browse/' . $tmp1 . '/' .$tmp2 ; //NOTE NOTE NOTE: al ham do leAllah: eventually the right permutation was found
        
        
        fwrite($file, "cat and state are : " . $cat . ", " . $state . "\n");      
        fwrite($file, "about to do the first curl with url: " . $url . " at: " . date("H:i:s") . "\n");      

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

        $flgFinished = 0;
        $i= strpos($str, 'class="post-card-link"');
        if($i===FALSE) 
        {
          fwrite($file, "NO POST FOUND, so not entering the while loop" . "\n");//. here is the received string: " . $str . "\n");   
          $flgFinished =1;     //NOTE NOTE: HERE DONT USE BREAK OR CONTINUE (to go after the while loop and update cnt and P) ; to revise soon en shaa Allah
        }
        else
          $str = substr($str, $i + 22); 

        //fwrite($file, "str is:" . $str . "\n");        
        fwrite($file, "about to enter (or skip) the while loop" . "\n");        
        $page=1;
        while( $flgFinished === 0 )  
        {
          if($tmpCountPosts >= $TMP_MAX_CNT) 
            break;
          
          $tmpCountPosts ++;
          
          //extract the post
          $next_post_pos = strpos($str, 'class="post-card-link"');//en shaa Allah, works
          if($next_post_pos === FALSE)
          {
            $end_body_pos = strpos($str, '</body>');//en shaa Allah, works
            if($next_post_pos === FALSE)
              $str_post = $str;
            else
              $str_post = substr($str, 0, $end_body_pos); //keep an eye on the beginning and end of the post string
          }
          else
            $str_post = substr($str, 0, $next_post_pos); //keep an eye on the beginning and end of the post string: strange: it cuts at the end of html tag!!!
          //fwrite($file, "str_post is:" . $str_post. "\n");

          //source and post_id
          $source="";
          $i = strpos($str_post, 'href="');//en shaa Allah : always exists
          $str_post = substr($str_post, $i + 6); 
          $j= strpos($str_post, '"'); 
          $source = "https://www.divar.ir" . trim(substr($str_post, 0, $j));
          $post_id="";
          $k= strrpos($source , "/");
          if($k !== FALSE)
            $post_id= substr($source , 1+$k, strlen($source));
          fwrite($file, "post_id is: " . $post_id . "\n");
          $source = "https://divar.ir/v/" . $post_id;
          fwrite($file, "source is:" . $source. "\n");

         
          //see if already read within the past 24 hours to revise soon en shaa Allah
          $tmp_is_new = 1;
          $sql_check_already = "select count(*) as N from `cnotes` where source like '%divar.ir%' AND post_id='" . $post_id . "'";   //NOTE: ASSUMUD UNIQUE, TO REVISE SOON esA
 
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
            //title esA
            $title = "";
            $i= strpos($str_post, '<h2 data-reactid=');
            if($i !== FALSE)
            { 
              $str_post= substr($str_post, $i+17);
              $i= strpos($str_post, '>');
              $str_post= substr($str_post, $i+1);
              $j = strpos($str_post, '</h2');
              $title = trim(substr($str_post, 0, $j));
            }
            fwrite($file, "title  is: " . $title . "\n");
            
            //mahal esA
            $mahal = ""; 
            $i = strpos($str_post, "<label data-reactid="); 
            if($i !== FALSE)
            { 
              $str_post= substr($str_post, $i+20);
              $i = strpos($str_post, ">"); 
              $str_post= substr($str_post, $i+1);
              $j = strpos($str_post, '<');
              $mahal = trim(substr($str_post, 0, $j));
            }
            fwrite($file, "mahal is:" . $mahal . "\n");

                 
            //howlongago esA
            $howlongago = ""; 
            $i = strpos($str_post, 'class="meta" data-reactid='); 
            if($i !== FALSE)
            { 
              $str_post= substr($str_post, $i+26);
              $i = strpos($str_post, ">"); 
              $str_post= substr($str_post, $i+1);
              $j = strpos($str_post, '<');
              $howlongago = trim(substr($str_post, 0, $j));
            }
            fwrite($file, "howlongago is: " . $howlongago . "\n");

            //This is to avoid too much bothering that site only assuming we do at least one per day, to revise soon en shaa Allah          
            if( (mb_strpos($howlongago, 'ماه') !== FALSE)|| (mb_strpos($howlongago, 'هقته') !== FALSE) || (mb_strpos($howlongago, 'روز') !== FALSE) )
            {
              fwrite($file, "post too old, so breaking". "\n");
              fwrite($file, "---------------------------------------------\n\n");
              break;//keep an eye on it
            }
          
            $howmanysecago    = 0; //NOTE to fix soon en shaa Allah: if the ad is فوری or does not have in the first page howlongago, then howsecago remains 0
            if(strlen(trim($howlongago)) > 0)
              $howmanysecago = calc_sec_ago($howlongago);
            fwrite($file, "howmanysecago is: " . $howmanysecago . "\n");
            //image
            $imagelink = $origimagename= "";
            $newimagename= "dv_no_image.png";//copied manually for the first time        
            $i= strpos($str_post, "<img"); 
            if($i !== FALSE)
            {
              $str_post= substr($str_post, $i);
              $i= strpos($str_post, 'src="');
              if($i !== FALSE)
              {
                $str_post = substr($str_post, $i+5);
                $j= strpos($str_post, '"');
                $imagelink = trim(substr($str_post, 0, $j));
            fwrite($file, "imagelink is:" . $imagelink . "\n");
                $k1= strrpos($imagelink , "/");
                $origimagename= trim(substr($imagelink , $k1 +1)); 
            fwrite($file, "origimagename is:" . $origimagename . "\n");
               
                //avoid unnecessary copies of the default and (esA, previously-copied images):
                if( strcmp($origimagename, "no-picture-thumbnail.9cc062246834.png")!=0 ) 
                {
                  $newimagename = 'dv_' . microtime();              
                  $tmpPath = 'cimg/' . $newimagename;
                  //copy($imagelink , $tmpPath);     
                }           
              }
            }
            fwrite($file, "newimagename is: " . $newimagename. "\n");
          

            //price or rahn va ejare, esA
            if($i_cat==0)
            {
              $price = "";
              $i= strpos($str_post, '<label class="item" data-reactid=');
              if($i !== FALSE)//== ?
              {
                $str_post= substr($str_post, $i+33);
                $i= strpos($str_post, '>');
                $str_post= substr($str_post, $i+1);
                $i= strpos($str_post, ':');
                $str_post= substr($str_post, $i+1);
                $j= strpos($str_post, '<');
                $price= trim(substr($str_post, 0, $j));  //NOTENOTE: this is IRR but the one displayed by divar ti the user is in Tomans
              }
              //$price = intval($price)/10;
              fwrite($file, "price in IRT is: " . $price. "\n");
            }
            else//NOTE : assume i_cat is either 0 or 1
            {
              $ejare= "";
              $i= strpos($str_post, '<label class="item" data-reactid=');
              if($i !== FALSE)//== ?
              {
                $str_post= substr($str_post, $i+33);
                $i= strpos($str_post, '>');
                $str_post= substr($str_post, $i+1);
                $i= strpos($str_post, ':');
                $str_post= substr($str_post, $i+1);
                $j= strpos($str_post, '<');
                $price= trim(substr($str_post, 0, $j));  //NOTENOTE: this is IRR but the one displayed by divar ti the user is in Tomans
              }
              fwrite($file, "ejare is: " . $ejare. "\n");
              $rahn= "";
              $i= strpos($str_post, '<label class="item" data-reactid=');
              if($i !== FALSE)//== ?
              {
                $str_post= substr($str_post, $i+33);
                $i= strpos($str_post, '>');
                $str_post= substr($str_post, $i+1);
                $i= strpos($str_post, ':');
                $str_post= substr($str_post, $i+1);
                $j= strpos($str_post, '<');
                $price= trim(substr($str_post, 0, $j));  //NOTENOTE: this is IRR but the one displayed by divar ti the user is in Tomans
              }
              fwrite($file, "rahn is: " . $rahn. "\n");
            }

       
            //now the internal page:
            $meter="";
            $matn =""; 
            $m="";
/*          fwrite($file, "about to do the internal curl with source: " . $source . "\n");
          $ch = curl_init($source);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          $str_2 = curl_exec_follow($ch);
          curl_close($ch);

      //fwrite($file, "second page: shokr:" . "\n" . $str_2 . "\n");      
        $toal_num_curl++;

          $i= strpos($str_2, "متراژ (متر مربع)</label>");
          $str_2= substr($str_2, $i); 
          $i= strpos($str_2, '<div class="value"');//NOTE: such patterns may exist in the matn  <<<<<<
          $str_2= substr($str_2, $i+18); 
          $i= strpos($str_2, '>');//NOTE: such patterns may exist in the matn  <<<<<<
          if($i !== FALSE)
          {
            $str_2= substr($str_2, $i+1); 
            $j= strpos($str_2, '</div>'); 
            $meter= trim(substr($str_2, 0, $j)); 
          }
          fwrite($file, "meter is:" . $meter. "\n");


          $i = strpos($str_2, '<div');
          $str_2= substr($str_2, $i+4); 
          $i = strpos($str_2, '<div');
          $str_2= substr($str_2, $i+4); 
          $i = strpos($str_2, '<div');
          $str_2= substr($str_2, $i+4); 
          $i = strpos($str_2, '>');
          $str_2= substr($str_2, $i+1); 
          if($i !== FALSE)
          {
              $j= strpos($str_2, '</div>'); 
              $matn= trim(substr($str_2, 0, $j)); 

              $matn = str_replace("  ",     " ", $matn); 
              $matn = str_replace("<br>",   "" , $matn); 
              $matn = str_replace("<br/>",  "" , $matn); 
              $matn = str_replace("</br>",  "" , $matn); 
              $matn = str_replace("<br />", "" , $matn); 
              $matn = trim($matn);
          }
          fwrite($file, "matn after removing breaks is:" . $matn . "\n");
            
            
          //contact later only if mofid esA
*/         

            //for now, to improve soon en shaa Allah
            $tmpFinalMantaghe = $city;
            $tmpFinalMahal = $mahal;
         
            //shokr , now insert the record in to the database
            //remove half spaces with space:
            $tmpFinalMantaghe = cleanedArabicHalfSpaceAndNumbers($tmpFinalMantaghe);
            $tmpFinalMahal = cleanedArabicHalfSpaceAndNumbers($tmpFinalMahal );

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
            $imagelink = mysqli_real_escape_string($conn, $imagelink );          
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
              $sql = "INSERT INTO `cnotes`(`ymd`, `time`, `cat`, `state`, `city`, `mantaghe2`, `mahal2`, `title`, `meter`, `room`, `tp_rahn`, `type`, `image`, `imagelink`, `source`, `matn`, `m`, `howlongago`, `howmanysecago`, `post_id`) values('" . date("Y-M-d") . "', " . time() . ", '" . $cat . "', '" . $state . "', '" . $city . "', '" . $tmpFinalMantaghe . "', '" . $tmpFinalMahal . "', '" . $title . "', '" . $meter . "', '" . $room . "', '" . $price . "', '" . $type . "', '" . $newimagename. "', '" . $imagelink . "', '" . $source . "', '" . $matn . "', '" . $m . "', '" . $howlongago . "', " . $howmanysecago . ", '" . $post_id . "')";
            }
            else//NOTE: assumes that i_cat is either 0 or 1
            {
              $rahn = strip_tags($rahn );          
              $ejare = strip_tags($ejare );          
              $rahn = mysqli_real_escape_string($conn, $rahn);          
              $ejare = mysqli_real_escape_string($conn, $ejare);          
              $sql = "INSERT INTO `cnotes`(`ymd`, `time`, `cat`, `state`, `city`, `mantaghe2`, `mahal2`, `title`, `meter`, `room`, `tp_rahn`, `ppm_ejare`, `type`, `image`, `imagelink`, `source`, `matn`, `m`, `howlongago`, `howmanysecago`, `post_id`) values('" . date("Y-M-d") . "', " . time() . ", '" . $cat . "', '" . $state . "', '" . $city . "', '" . $tmpFinalMantaghe . "', '" . $tmpFinalMahal . "', '" . $title . "', '" . $meter . "', '" . $room . "', '" . $rahn . "', '" . $ejare . "', '" . $type . "', '" . $newimagename. "', '" . $imagelink . "', '" . $source . "', '" . $matn . "', '" . $m . "', '" . $howlongago . "', " . $howmanysecago . ", '" . $post_id . "')";
            }

            //fwrite($file, "the sql is:" . $sql . "\n");
            
            if ($conn->query($sql) === TRUE)
            {
              fwrite($file, "inserted into the dv table successfully, shooooookrr" . "\n");
              $cnt_new_posts++;
            } 
            else 
            {
              fwrite($file, "errroror inserting into the table. sql is: " . $sql . "\n");
              exit("errroror inserting into the table");
            }
          }//else of if old (i.e. a new post)

          fwrite($file, "---------------------------------------------\n\n");
    
          //prepare for next post if exists; try next page otherwise        
          $i= strpos($str, 'class="post-card-link"');
          if( $i===FALSE ) //currently it always curl one extra (unnecessary) page; to fix soon en shaa Allah
          {
            $page++;
            $url .= "&page=" . $page;
            fwrite($file, "----going for next page en shaa Allah: " . $url . " at: " . date("H:i:s") . "---\n\n");
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

            $i= strpos($str, 'class="post-card-link"');
            if($i===FALSE) 
            {
              $flgFinished = 1;
              fwrite($file, "------------------no more post was found in the while so finishing the while loop--------\n\n");
              //break;     
            }
            else
            {
              $str = substr($str, $i + 22); 
            }
          }
          else
            $str = substr($str, $i + 22); 


        }//while
        fwrite($file, "after the while loop for the where_zdv2_select:" . $where_zdv2_select . " and cnt_new_posts=" . $cnt_new_posts . "\n");

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
             
          $sql_zdv2_update = "UPDATE `zdv2` SET `cnt`=" . $P . ", `P`=" . $P . $tmp_zdv2_where;
          if ($conn->query($sql_zdv2_update) === TRUE)
            fwrite($file, "updated cnt and P to: " . $P . "\n");
          else 
            fwrite($file, "error updaing cnt and P" . "\n");         

          fwrite($file, "-----------------------------------------------------------------------" . "\n");      
        }
//      }//for each room 
//    }//for each type
  }//for each city
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

?>
