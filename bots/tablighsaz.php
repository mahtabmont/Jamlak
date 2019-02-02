<?php
include('SimpleImage.php');
include('GIFEncoder.class.php');
include '../Persian-Log2Vis-master/persian_log2vis.php';

$APP_ID="4c70bdf182a7a43ec4027bd90d418eda"; 
$KEY= "1fa9a3f146e435a856ba633c9956baf5"; 

$code=uniqid();
$servername = "78.129.241.86";
$username = "jamlakir_user";
$password = "ras13500";
$dbname = "jamlakir_besm";
$conn = new mysqli($servername, $username, $password, $dbname);
$framed=[];
$frames=[];

if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
} 
$conn->set_charset("utf8");
header("content-type:text/html;charset=UTF-8");

$file=fopen("aks.log","w");
$Bot_Token="396260885:AAEnkc6ZjEi3vDqMyS61lHUzAbXWrJdvnp4";
$content = file_get_contents("php://input");

$update = json_decode($content, true);
$c = $update["message"]["from"]["id"];
$text = $update['message']['text'];
$firstname=$update['message']["from"]['first_name'];
$lastname=$update['message']["from"]['last_name'];

$cuser=array('theme'=>'','id'=>'','session'=>0,'memory'=>0, 'text1'=>'', 'text2'=>'');

$msg=mb_substr($text,0,8);
$msg=trim($msg);
$msg=mb_strtolower($msg);
fwrite($file,"text=".$text."msg=".$msg);
$register=registry($c,$firstname." ".$lastname);

if ($update['message']['photo']){
    end($update['message']['photo']);
    $i=key($update['message']['photo']);
    $loadfile=true;
    $fileid = $update['message']['photo'][$i]['file_id'];
    $url = 'https://api.telegram.org/bot'. $Bot_Token .'/getFile?file_id='.$fileid;
    $result = file_get_contents($url);
    $result = json_decode($result, true);
    
    $path = $result['result']['file_path'];
    
    $url="https://api.telegram.org/file/bot".$Bot_Token."/".$path;
    $result = file_get_contents($url);
    $i=$cuser['session']-4;
    file_put_contents("tablighPhoto/a".$c.$cuser['memory'].$i.".jpg", file_get_contents($url));
    if ($cuser['session']>=5 && $cuser['session']<=6){
        setsession($cuser['id'],$cuser['session']+1);
        sendtxt($c,"📎عکس بعدی ...");
    }
    
}
else if ($update['message']['document']){
    //end($update['']['photo']);
    //$i=key($update['message']['photo']);
    //$loadfile=true;
    $fileid = $update['message']['document']['file_id'];
    $url = 'https://api.telegram.org/bot'. $Bot_Token .'/getFile?file_id='.$fileid;
    $result = file_get_contents($url);
    $result = json_decode($result, true);
    
    $path = $result['result']['file_path'];
    
    $url="https://api.telegram.org/file/bot".$Bot_Token."/".$path;
    $result = file_get_contents($url);
    $i=$cuser['session']-4;
    file_put_contents("tablighPhoto/a".$c.$cuser['memory'].$i.".jpg", file_get_contents($url));
    if ($cuser['session']>=5 && $cuser['session']<=6){
        setsession($cuser['id'],$cuser['session']+1);
        sendtxt($c,"📎عکس بعدی ...");
    }
    
}

       if(strtolower(trim($text))==="/start" && $register) { /*unicode.org/emoji/charts/full-emoji-list.html#1f600*/
             $key=array(array("راهنما📘", "ساخت گیف🛠", ),);
             $reply=array('keyboard'=>$key,'resize_keyboard'=>true, 'on_time_keyboard'=>true);
	         sendkey($c,"لطفا یک گزینه را انتخاب کنید",json_encode($reply));
               setsession($cuser['id'],1);
          
	   }
	   else if(mb_substr(trim($text),0,6)==="بازگشت") {
	        $key=array(array("راهنما📘", "ساخت گیف🛠", ),);
             
	        //$key=array(array("راهنما📘","ساخت گیف🛠","بازگشت🔙"),);
             $reply=array('keyboard'=>$key,'resize_keyboard'=>true, 'on_time_keyboard'=>true);
	         sendkey($c,"لطفا یک گزینه را انتخاب کنید",json_encode($reply));
	         setsession($cuser['id'],1);
	       }
       elseif (mb_substr(trim($text),0,6)==="راهنما"){
              sendtxt($c,"🌺به ربات تبلیغ ساز خوش آمدید.🌺
 این ربات کاملا رایگان است و  کار با آن  بسیار ساده و لذت بخش است، مراحل کار به این صورت می باشد:
1.	بعد از کلیک  دکمه استارت ربات فعال می شود و بعد از پیام خوش آمدگویی از شما می خواهد که یکی از گزینه های زیر را انتخاب کنید. 

2.	در قسمت پایین صفحه روی گزینه ساخت گیف کلیک کنید. اگر صفحه کلید را مشاهده نمی کنید روی ایکون صفحه کلید کلیک کنید.

3.	با  انتخاب گزینه ساخت گیف، ربات از شما می خواهد متنی را جهت نمایش بعنوان تیتر در بالای  گیف  تایپ کنید. این متن حداکثر می تواند سه خط و حدود 150 حرف  باشد.

4.	سپس ربات از شما می خواهد که متن دوم را برای نمایش زیر گیف تبلیغاتی ارسال کنید، این متن حداکثر دو خط و حدود 100 حرف می تواند باشد، به همان روش مرحله قبل انجام دهید. 

5.	در مرحله بعد ربات از شما می خواهد عکسها را ارسال کنید. توجه داشته باشید عکسها حتما بایستی جداگانه و هر بار با توجه به پیام ربات ارسال شوند. پس ابتدا عکس اول مورد نظر خود را انتخاب و سپس ارسال کنید.

6.	 سپس ربات عکس دوم را درخواست می کند و در جواب شما عکس دوم را ارسال کنید و در مرحله بعد به ترتیب مشابه عکس سوم را ارسال کنید.

7.	در انتها رنگ تم گیف از شما پرسیده می شود. رنگ موردنظر خود را انتخاب کنید. گیف در کسری از ثانیه برای شما ارسال خواهد شد.

 توجه کنید که  برای امتحان تمهای مختلف لزومی ندارد مراحل را از اول اجرا کنید. با تغییر تم  گیف جدید ساخته  و ارسال می شود.  تنها اگر قصد تغییر متنها و یا عکسها را دارید ، مراحل کار  را از ابتدا با فشردن کلید بازگشت آغاز کنید.


");
        }
        else if (mb_substr(trim($text),0,8)==="ساخت گیف" ){
              $key=array(array("راهنما📘",  "بازگشت🔙"),);
	          $reply=array('keyboard'=>$key,'resize_keyboard'=>true, 'on_time_keyboard'=>true);
	          sendkey($c,"لطفا عنوان بالای گیف خود را وارد کنید،  توجه کنید بیش از سه  سطر(150 حرف) نباشد",json_encode($reply));
             
              //sendtxt($c,""");
              setsession($cuser['id'],3);
              setmemory($cuser['id'],$code);
        }
        else if ($cuser['session']==0 && trim($text)!=="" && $register) {
             savejob($cuser['id'],$text);
             $key=array(array("راهنما📘", "ساخت گیف🛠", ),);
             $reply=array('keyboard'=>$key,'resize_keyboard'=>true, 'on_time_keyboard'=>true);
	         sendkey($c,"لطفا یک گزینه را انتخاب کنید",json_encode($reply));
             
        }
        
        else if ($cuser['session']==1 && trim($text)!==""){
             $key=array(array("راهنما📘", "ساخت گیف🛠", ),);
             //$key=array(array("راهنما📘","ساخت گیف🛠","بازگشت🔙"),);
	         $reply=array('keyboard'=>$key,'resize_keyboard'=>true, 'on_time_keyboard'=>true);
	         sendkey($c,"لطفا یک گزینه را انتخاب کنید",json_encode($reply));
                  
        }
        
        
        else if ($cuser['session']==3 && trim($text)!=="") {
             $key=array(array("راهنما📘",  "بازگشت🔙"),);
	         $reply=array('keyboard'=>$key,'resize_keyboard'=>true, 'on_time_keyboard'=>true);
	         sendkey($c,"لطفا عنوان پایین گیف خود را وارد کنید،  توجه کنید بیش از دو  سطر(100 حرف) نباشد",json_encode($reply));
             
             savetext1($cuser['id'],$text);
             //sendtxt($c,"");
             
        }
        else if ($cuser['session']==4 && trim($text)!=="") {
             $key=array(array("راهنما📘",  "بازگشت🔙"),);
	         $reply=array('keyboard'=>$key,'resize_keyboard'=>true, 'on_time_keyboard'=>true);
	         sendkey($c,"📎عکس  اول را ارسال کنید",json_encode($reply));
             
            savetext2($cuser['id'],$text);
            //sendtxt($c,"📎عکس  اول را ارسال کنید");
             
        }
        else if (trim($cuser['session'])==7){
             $key=array(array("آبی","سبز","سفید","بنفش"),array("نارنجی","خاکستری","صورتی","بازگشت"),);
	         $reply=array('keyboard'=>$key,'resize_keyboard'=>true, 'on_time_keyboard'=>true);
	         sendkey($c,"🌈لطفا رنگ گیف خود را انتخاب کنید",json_encode($reply));
             setsession($cuser['id'],8);
        }
        else if ($cuser['session']==8 && trim($text)!=="") {
             if (trim($text)=="آبی"){
                 savetheme($cuser['id'],"blue");
             }
             else  if (trim($text)=="سبز"){
                 savetheme($cuser['id'],"green");
             } 
             else  if (trim($text)=="بنفش"){
                 savetheme($cuser['id'],"magenta");
             } 
             else  if (trim($text)=="سفید"){
                 savetheme($cuser['id'],"white");
             }
             else  if (trim($text)=="نارنجی"){
                 savetheme($cuser['id'],"orange");
             }
             else  if (trim($text)=="خاکستری"){
                 savetheme($cuser['id'],"grey");
             }
             else  if (trim($text)=="صورتی"){
                 savetheme($cuser['id'],"pink");
             }
            
        }
        if (trim($cuser['session'])==9) {
            sendtxt($c,"⏳ لطفا چند ثانیه صبر کنید");
            $i=rand(1,5);
            buildme("tablighPhoto/a".$c.$cuser['memory']."1.jpg",$frames,$framed,$cuser['text1'],$cuser['text2'],$cuser['theme'],$i);
            buildme("tablighPhoto/a".$c.$cuser['memory']."2.jpg",$frames,$framed,$cuser['text1'],$cuser['text2'],$cuser['theme'],$i);
            buildme("tablighPhoto/a".$c.$cuser['memory']."3.jpg",$frames,$framed,$cuser['text1'],$cuser['text2'],$cuser['theme'],$i);
            $gif = new GIFEncoder($frames,$framed,0,2,0,0,0,'bin');
            $fp = fopen("tablighPhoto/r".$c.$cuser['memory'].".gif", 'w');
            fwrite($fp, $gif->GetAnimation());
            fclose($fp);
            sendvideo($c,"tablighPhoto/r".$c.$cuser['memory'].".gif");
            sendtxt($c,"اکنون می توانید گیف خود را به دوستان و گروههای  خود ارسال کنید✅");
            setsession($cuser['id'],8);
           
        } 
        
function savetheme($id,$text){
    global $conn;
    global $cuser;
    $sql = "UPDATE `tabligh` SET theme='".$text."' WHERE `ID`= '" . $id ."'" ; 
    $res = $conn->query($sql);
    setsession($cuser['id'],9);
$cuser['theme']=$text;
$cuser['session']=9;
}          
          
function savetext2($id,$text){
    global $conn;
    global $cuser;
    $sql = "UPDATE `tabligh` SET text2='".$text."' WHERE `ID`= '" . $id ."'" ; 
    $res = $conn->query($sql);
    setsession($cuser['id'],5);
}          
function savejob($id,$text){
    global $conn;
    global $cuser;
    $sql = "UPDATE `tabligh` SET job='".$text."' WHERE `ID`= '" . $id ."'" ; 
    $res = $conn->query($sql);
    setsession($cuser['id'],2);
}
function savetext1($id,$text){
    global $conn;
    global $cuser;
    
    $sql = "UPDATE `tabligh` SET text1='".$text."' WHERE `ID`= '" . $id ."'" ; 
    $res = $conn->query($sql);
    setsession($cuser['id'],4);
}

function executeMessage($url){
	
	$ch=curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	$data =curl_exec($ch);
	curl_close($ch);
	$obj = json_decode($data);
}
function sendkey($user_id,$ms,$key){

    $url = "https://api.telegram.org/bot396260885:AAEnkc6ZjEi3vDqMyS61lHUzAbXWrJdvnp4/sendMessage";
$content = array(
        'chat_id' => $user_id,
        'text' => $ms,
'reply_markup'=>$key
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

//</send>
}

function setsession($id,$val){
      global $conn;
      global $cuser;
      $sql = "UPDATE `tabligh` SET session='".$val."' WHERE `ID`= '" . $id ."'" ; 
      $res = $conn->query($sql);
     
}
function setmemory($id,$val){
      global $conn;
      global $cuser;
      
      $sql = "UPDATE `tabligh` SET memory='".$val."' WHERE `ID`= '" . $id ."'" ; 
      $res = $conn->query($sql);
     
}

function registry($c,$name){
      global $conn;     
      global $cuser; 
      global $legal;
      $file=fopen("registry.txt","w"); 
      fwrite($file,"c=".$c."name".$name);
      $sql= "SELECT * FROM `tabligh` WHERE c='".$c."'";
      $res = $conn->query($sql);
      if (mysqli_num_rows($res)==0){ 
          sendtxt($c,"چند نمونه از گیفهای تبلیغاتی ساخته شده بکمک این بات را در زیر مشاهده می کنید  👇👇👇👇 👇👇");
          sendvideo($c,"r1.gif");
          sendvideo($c,"r2.gif");
           sendvideo($c,"r4.gif");
            sendvideo($c,"r6.gif");
          $sql = "insert into `tabligh` (`c`,`name`,`theme`,`session`) values('" . $c . "','" .$name."','',0)" ; 
          $res = $conn->query($sql);
          sendtxt($c,"لطفا شغل خود را وارد کنید");
          $cuser['session']=0;
          return false;   
      }
      else   {
            $row= $res->fetch_assoc(); 
            $cuser["id"]=$row['ID'];
            $cuser["session"]=$row['session'];
            $cuser["text1"]=$row['text1'];
            $cuser["text2"]=$row['text2'];
            $cuser["theme"]=$row['theme'];
            
            if ($row['session']>=5){
                $cuser["memory"]=$row['memory'];
            }
            
            return true;
          
      }
}

//------------------------

function sendphotos($chat_id,$filename,$caption){	

$bot_url    ="https://api.telegram.org/bot396260885:AAEnkc6ZjEi3vDqMyS61lHUzAbXWrJdvnp4/";

$url = $bot_url . "sendphoto?chat_id=" . $chat_id;

$post=array("chat_id"=>$chat_id,"caption"=>$caption,"photo"=>new CURLFILE(realpath($filename)));

$ch = curl_init(); 
curl_setopt($ch,CURLOPT_HTTPHEADER,array("Content-Type:multipart/form-data"));
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch, CURLOPT_POSTFIELDS,$post); 

$output = curl_exec($ch);

}
function senddoc($chat_id,$filename,$caption){	

$bot_url    ="https://api.telegram.org/bot396260885:AAEnkc6ZjEi3vDqMyS61lHUzAbXWrJdvnp4/";

$url = $bot_url . "sendDocument?chat_id=" . $chat_id;

$post=array("chat_id"=>$chat_id,"caption"=>$caption,"Document"=>new CURLFILE(realpath($filename)));

$ch = curl_init(); 
curl_setopt($ch,CURLOPT_HTTPHEADER,array("Content-Type:multipart/form-data"));
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch, CURLOPT_POSTFIELDS,$post); 

$output = curl_exec($ch);

}
function sendvideo($chat_id,$filename){
	
$caption="@tablighsaz_bot";
$bot_url    ="https://api.telegram.org/bot396260885:AAEnkc6ZjEi3vDqMyS61lHUzAbXWrJdvnp4/";

$url = $bot_url . "sendVideo?chat_id=" . $chat_id;

$post=array("chat_id"=>$chat_id,"caption"=>$caption,"video"=>new CURLFILE(realpath($filename)));

$ch = curl_init(); 
curl_setopt($ch,CURLOPT_HTTPHEADER,array("Content-Type:multipart/form-data"));
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch, CURLOPT_POSTFIELDS,$post); 

$output = curl_exec($ch);

}
//-------------------------------------------

function sendtxt($user_id,$ms){
  if( (strlen(trim($user_id))>0) && (strlen(trim($ms)) ) )
  {
    $url = "https://api.telegram.org/bot396260885:AAEnkc6ZjEi3vDqMyS61lHUzAbXWrJdvnp4/sendMessage";
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
//</send>
}


function buildme($imgname,&$frames,&$framed,$text1,$text2,$theme,$i){
$contrast=0;
$font = '../Persian-Log2Vis-master/A.khat.khati_www.Downloadha.com.ttf';
persian_log2vis($text1);
persian_log2vis($text2);
$mydim=600;
$im = imagecreatetruecolor($mydim, $mydim);
$white = imagecolorallocate($im, 255, 255, 255);
imagefill($im, 0, 0, $white);

// need if background should be plain white- div.png in this case is perfect
/*$back = imagecreatetruecolor($mydim, $mydim);
$white = imagecolorallocate($back, 255, 255, 255);
imagefill($back, 0, 0, $white);*/

// need if background should be a colored theme

if (trim($theme)==="blue")//40,41,28,33,6,1
$backfile="backb".$i.".jpg";  
else if (trim($theme)==="green")
$backfile="backg".$i.".jpg"; 
else if (trim($theme)==="pink")
$backfile="backp".$i.".jpg"; 
else if (trim($theme)==="orange")
$backfile="backo".$i.".jpg"; 
else if (trim($theme)==="grey")
$backfile="backy".$i.".jpg";
else if (trim($theme)==="white")
$backfile="backw".$i.".jpg"; 
else if (trim($theme)==="magenta")
$backfile="backn".$i.".jpg";  

$img=crop($backfile,$mydim,$mydim);
$back = imagecreatefromjpeg($img);

imagecopy($im, $back, 0, 0, 0, 0, imagesx($im), imagesy($im));



$div= imagecreatefrompng("divblue.png");//divblue and div4 together->1,3,4,divblue
$div1= imagecreatefrompng("divblue.png");

imagecopy($im, $div, 15, 90, 0,0, imagesx($div), imagesy($div));//15,90->divblue,div4 --0,55 ->div3 
imagecopy($im, $div1, 5, 370, 0,0, imagesx($div1), imagesy($div1));//5,370->divblue-->5,390->div4 -- 0,370->div3
$color1=choosecolor($back,$mydim/2,30);

$y=splitme($im, 24, $mydim/2, 30, $color1, $font, $text1,$mydim);
//$color1=choosecolor($back,$mydim/2,$mydim/2+$y+50);

splitme($im, 20, $mydim/2, $y+50+$mydim/2, $color1, $font, $text2,$mydim);
$frame=imagecreatefrompng("frame2.png");
imagecopy($im, $frame, (imagesx($im)/2)-(imagesx($frame)/2), $y+10, 0, 0, imagesx($frame), imagesy($frame));

ob_start();

//$image=store_uploaded_image($imgname, 200, 300);
$img=crop($imgname,410,260);
$im2 = imagecreatefromjpeg($img);

imagecopy($im, $im2, (imagesx($im)/2)-(imagesx($im2)/2), $y+30, 0, 0, imagesx($im2), imagesy($im2));
imagegif($im);
$frames[]=ob_get_contents();
$framed[]=70; // Delay in the animation.
ob_end_clean();
}
function fromRGB($R, $G, $B)
{
    $R = dechex($R);
    if (strlen($R)<2)
    $R = '0'.$R;

    $G = dechex($G);
    if (strlen($G)<2)
    $G = '0'.$G;

    $B = dechex($B);
    if (strlen($B)<2)
    $B = '0'.$B;
    return '#' . $R . $G . $B;
}

function choosecolor($f,$w,$h){
    global $contrast;
    //imagepng(imagecreatefromstring(file_get_contents($f)), "output.png");
    //$picture = imagecreatefrompng("output.png");
    $index = imagecolorat( $f, $w, $h);
    
    $Colors = imagecolorsforindex($f,$index);
    $Colors['red']=intval((($Colors['red'])+15)/32)*32;    //ROUND THE COLORS, TO REDUCE THE NUMBER OF COLORS, SO THE WON'T BE ANY NEARLY DUPLICATE COLORS!
    $Colors['green']=intval((($Colors['green'])+15)/32)*32;
    $Colors['blue']=intval((($Colors['blue'])+15)/32)*32;
    if ($Colors['red']>=256)
       $Colors['red']=255;
    if ($Colors['green']>=256)
       $Colors['green']=255;
    if ($Colors['blue']>=256)
       $Colors['blue']=255;
    
    $a = 0.299 * $Colors['red'] + 0.587 * $Colors['green'] + 0.114 * $Colors['blue'];
    if ($a > 186){
       $r = rand(0,100); 
       $g = rand(0,100); 
       $b = rand(0,100); 
       $d = 0; // bright colors - black font
       $contrast=1;
    }
    else{
       $r = rand(200,255); 
       $g = rand(200,255); 
       $b = rand(200,255); 
       $d = 255; // dark colors - white font
       $contrast=0;
    }
    $col=hexdec(fromRGB($r,$g,$b));
    
    //$col=hexdec(fromRGB(127-($Colors['red']-127),127-($Colors['green']-127),127-($Colors['blue']-127)));
    
    return $col;
}
function splitme($im, $size, $x, $y, $color, $font, $text,$mydim){
 global $contrast;
 $lines = explode(PHP_EOL, mb_wordwrap($text, 40, false));
 $lines = array_filter( $lines, 'strlen' );
 $lineno = count($lines);
 $f=fopen("text.log","a");
 fwrite($f, print_r($lines, true));
 fwrite($f,mb_strlen($text));
 //file_put_contents('text.log', print_r($lines, true));
 $yellow = imagecolorallocate($im, 255, 255, 204);
 $red = imagecolorallocate($im, 77, 0, 0);
 if ($contrast)
 $col=$yellow;
 else
 $col=$red;
 if ($size>20){
    $limit=3;
    $y=$y+40*(3-$lineno);
}
else
    $limit=2;
// Loop through the lines and place them on the image
//for ($i=0;$i<=$lineno-1 && $y<$mydim;$i++)
$lineno=1;
foreach ($lines as $line)
{
    if ($lineno<=$limit) {
       $box = imagettfbbox($size, 0, $font, $line);
       $xr = abs(max($box[2], $box[4]));
       $yr = abs(max($box[5], $box[7]));
       //imagettfborder($im, $size, 0, $x-($xr/2)-1, $y-1, $col, $font, $lines[$i],1); 
       imagettftext($im, $size, 0, $x-$xr/2, $y, $color, $font, $line);

        // Increment Y so the next line is below the previous line
       if ($size>20)
          $y += 45;
       else
          $y+=35;
       $lineno++;
    }
}
return $y;
}
function mb_wordwrap($str, $width = 30, $cut = false) {
    $lines = explode(PHP_EOL, $str);
    $f=fopen("words.log","w");
    fwrite($f,"lines->".print_r($lines,true));
        
    foreach ($lines as &$line) {
        $line = ltrim($line);
        if (mb_strlen($line) <= $width)
            continue;
        $words = explode(' ', $line);
        $words=array_reverse($words);
        $line = '';
        $actual = '';
        foreach ($words as $word) {
                
            if (mb_strlen($actual.$word) <= $width)
                $actual = ' '.$word.$actual;
            else {
                if ($actual != '')
                     $line .= PHP_EOL.ltrim($actual);
                $actual = $word;
                if ($cut) {
                    while (mb_strlen($actual) > $width) {
                        $line =mb_substr($actual, 0, $width).PHP_EOL.$line;
                        $actual = mb_substr($actual, $width);
                    }
                }
                $actual = ' '.$actual;
            }
        }
        $line .= PHP_EOL.trim($actual);
        //$line =trim($actual).$line;
    
    }
        return implode(PHP_EOL, $lines);
}
function imagettfborder($im, $size, $angle, $x, $y, $color, $font, $text, $width) {
    // top
    imagettftext($im, $size, $angle, $x-$width, $y-$width, $color, $font, $text);
    imagettftext($im, $size, $angle, $x, $y-$width, $color, $font, $text);
    imagettftext($im, $size, $angle, $x+$width, $y-$width, $color, $font, $text);
    // bottom
    imagettftext($im, $size, $angle, $x-$width, $y+$width, $color, $font, $text);
    imagettftext($im, $size, $angle, $x, $y+$width, $color, $font, $text);
    imagettftext($im, $size, $angle, $x-$width, $y+$width, $color, $font, $text);
    // left
    imagettftext($im, $size, $angle, $x-$width, $y, $color, $font, $text);
    // right
    imagettftext($im, $size, $angle, $x+$width, $y, $color, $font, $text);
    for ($i = 1; $i < $width; $i++) {
        // top line
        imagettftext($im, $size, $angle, $x-$i, $y-$width, $color, $font, $text);
        imagettftext($im, $size, $angle, $x+$i, $y-$width, $color, $font, $text);
        // bottom line
        imagettftext($im, $size, $angle, $x-$i, $y+$width, $color, $font, $text);
        imagettftext($im, $size, $angle, $x+$i, $y+$width, $color, $font, $text);
        // left line
        imagettftext($im, $size, $angle, $x-$width, $y-$i, $color, $font, $text);
        imagettftext($im, $size, $angle, $x-$width, $y+$i, $color, $font, $text);
        // right line
        imagettftext($im, $size, $angle, $x+$width, $y-$i, $color, $font, $text);
        imagettftext($im, $size, $angle, $x+$width, $y+$i, $color, $font, $text);
    }
} 
function ImageTTFCenter($image, $text, $font, $size, $angle = 45) 
{   $f=fopen("aks.log","w");
    $xi = imagesx($image);
    $yi = imagesy($image);

    $box = imagettfbbox($size, $angle, $font, $text);

    $xr = abs(max($box[2], $box[4]));
    $yr = abs(max($box[5], $box[7]));
    $scale = ($xi / $xr)*0.7;
    
    $x = intval(($xi - $xr)/2);
    $y = intval(($yi + $yr) /3 );
  
    return array($x, $y,$scale);
}


function crop($img,$thumb_width,$thumb_height){
    imagepng(imagecreatefromstring(file_get_contents($img)), "tablighPhoto/output.png");
    $image = imagecreatefrompng("tablighPhoto/output.png");
    //$image = imagecreatefromstring($img);
    $filename = 'tablighPhoto/crop.jpg';


$width = imagesx($image);
$height = imagesy($image);

$original_aspect = $width / $height;
$thumb_aspect = $thumb_width / $thumb_height;

if ( $original_aspect >= $thumb_aspect )
{
   // If image is wider than thumbnail (in aspect ratio sense)
   $new_height = $thumb_height;
   $new_width = $width / ($height / $thumb_height);
}
else
{
   // If the thumbnail is wider than the image
   $new_width = $thumb_width;
   $new_height = $height / ($width / $thumb_width);
}

$thumb = imagecreatetruecolor( $thumb_width, $thumb_height );

// Resize and crop
imagecopyresampled($thumb,
                   $image,
                   0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
                   0 - ($new_height - $thumb_height) / 2, // Center the image vertically
                   0, 0,
                   $new_width, $new_height,
                   $width, $height);
imagejpeg($thumb, $filename, 80);
return $filename;
}

// this function do not used :) redundant
function resize($newWidth, $targetFile, $originalFile) {

    $info = getimagesize($originalFile);
    $mime = $info['mime'];

    switch ($mime) {
            case 'image/jpeg':
                    $image_create_func = 'imagecreatefromjpeg';
                    $image_save_func = 'imagejpeg';
                    
                    
                    $new_image_ext = 'jpg';
                    break;

            case 'image/png':
                    $image_create_func = 'imagecreatefrompng';
                    $image_save_func = 'imagepng';
                    $new_image_ext = 'png';
                    break;

            case 'image/gif':
                    $image_create_func = 'imagecreatefromgif';
                    $image_save_func = 'imagegif';
                    $new_image_ext = 'gif';
                    break;

            default: 
                    throw new Exception('Unknown image type.');
    }

    $img = $image_create_func($originalFile);
    list($width, $height) = getimagesize($originalFile);

    $newHeight = ($height / $width) * $newWidth;
    $tmp = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    if (file_exists($targetFile)) {
            unlink($targetFile);
    }
    $image_save_func($tmp, "$targetFile.$new_image_ext");
}


?>
