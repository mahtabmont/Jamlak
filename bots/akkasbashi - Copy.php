<?php
$err=''; 
$code=uniqid();
$servername = "81.94.205.194";
$username = "jamlakir_user";
$password = "ras13500";
$dbname = "jamlakir_besm";
$conn = new mysqli($servername, $username, $password, $dbname);
$legal=true;
if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
} 
$conn->set_charset("utf8");
header("content-type:text/html;charset=UTF-8");

$file=fopen("aks.log","w");
$Bot_Token="263233217:AAGwKWJc2wZKtrBQpKSQxA0rcrUDOl9Kihg";
$content = file_get_contents("php://input");
$update = json_decode($content, true);
$c = $update["message"]["from"]["id"];
$text = $update['message']['text'];
$firstname=$update['message']["from"]['first_name'];
$lastname=$update['message']["from"]['last_name'];
$cuser=array('id'=>'','session'=>'','req'=>0,'credit'=>'','memory'=>0, 'uni'=>0);
$refid="";
$msg=substr($text,0,6);
$msg=trim($msg);
$msg=mb_strtolower($msg);
if ($msg==="/start"){
    $refid=substr($text,6,strlen($text));
    $refid=trim($id);
}
registry($c,$firstnam." ".$lastname,$refid);

if (((trim(substr($cuser['session'],0,3))=='mix') || (trim(substr($cuser['session'],0,4))=='fmix') ||(trim(substr($cuser['session'],0,4))=='tmix')) && ($cuser['memory']-0)>0){
        $code=$cuser['uni'].$cuser['memory'];
        $i=$cuser['memory']-1;
        setmemory($cuser['id'],$i);
        fwrite($file,print_r($cuser,1)."code=".$code);
        if ($i>0)
            sendtxt($c,"عکس بعدی ...");
}

if (($update['message']['photo']) && $legal){
    end($update['message']['photo']);
    $i=key($update['message']['photo']);
    
    $id = $update['message']['photo'][$i]['file_id'];
    $url = 'https://api.telegram.org/bot'. $Bot_Token .'/getFile?file_id='.$id;
    $result = file_get_contents($url);
    $result = json_decode($result, true);
    
    $path = $result['result']['file_path'];
    
    $url="https://api.telegram.org/file/bot".$Bot_Token."/".$path;
    $result = file_get_contents($url);
    file_put_contents("photos/a".$c.$code.".jpg", file_get_contents($url));
    fwrite($file,"photos/a".$c.$code.".jpg"."\n");
}
if (((trim($cuser['session'])=='smile') || (trim($cuser['session'])=='sad') || (trim($cuser['session'])=='surprised') || (trim($cuser['session'])=='squint') || (trim($cuser['session'])=='wink')) && $legal){
          sendtxt($c,"لطفا چند ثانیه صبر کنید...");
          if (trim($cuser['session'])=='smile')
                 $typ="type=1";
          else if (trim($cuser['session'])=='sad')
                 $typ="type=2";
          else if (trim($cuser['session'])=='surprised')
                 $typ="type=3";
          else if (trim($cuser['session'])=='squint')
                 $typ="type=4";
          else if (trim($cuser['session'])=='wink')
                 $typ="type=6";
         
          doit($c,"animated_caricature",$typ,$code,$code,".gif",1);
          sendvideo($c,"photos/r".$c.$code.".gif");
          setsession($cuser['id'],'');
          if (trim($err)=='')
             addreq($cuser['id'],$cuser['req']);
          
}
if (((trim($cuser['session'])=='Troll') || (trim($cuser['session'])=='Alien') || (trim($cuser['session'])=='Martian') || (trim($cuser['session'])=='Bulb head') || (trim($cuser['session'])=='Tough guy') || (trim($cuser['session'])=='Grotesque') || (trim($cuser['session'])=='Fat-cheeked')) && $legal)
{
         if (trim($cuser['session'])=='Troll')
                 $typ="type=5";
          else if (trim($cuser['session'])=='Alien')
                 $typ="type=9";
          else if (trim($cuser['session'])=='Martian')
                 $typ="type=10";
          else if (trim($cuser['session'])=='Bulb head')
                 $typ="type=11";
          else if (trim($cuser['session'])=='Tough guy')
                 $typ="type=12";
          else if (trim($cuser['session'])=='Grotesque')
                 $typ="type=13";
          else if (trim($cuser['session'])=='Fat-cheeked')
                 $typ="type=14";
          doit($c,"caricature",$typ,$code,$code,".jpg",1);
          sendphotos($c,"photos/r".$c.$code.".jpg");
          setsession($cuser['id'],'');
          if (trim($err)=='')
             addreq($cuser['id'],$cuser['req']);
}
if ((trim(substr($cuser['session'],0,3))=='kid') && $legal){
         $no = trim(substr($cuser['session'],3))-0;
         sendtxt($c,"لطفا چند ثانیه صبر کنید...");
         $typ="collage";
        
         if ($no==1)
             $param="template_name=Shrek Friends;";         
         elseif ($no==2)
             $param="template_name=Magic Pumpkin;";         
         elseif ($no==3)
             $param="template_name=Rio;";
         elseif ($no==4)
             $param="template_name=Ratatouille;";
         elseif ($no==5)
             $param="template_name=Wild Cat Face Paint;";
         elseif ($no==6)
             $param="template_name=Happy Horses;";
         elseif ($no==7)
             $param="template_name=Birthday Hat";         
         elseif ($no==8)
             $param="template_name=Just Hatched";
         elseif ($no==9)
             $param="template_name=Cute Kittens Frame;";
         elseif ($no==10)
             $param="template_name=Baby at Home;";
         elseif ($no==11)//===
             $param="template_name=Honey Bees;";
         elseif ($no==12)//===
             $param="template_name=Elephant Artist;";

         doit($c,$typ,$param,$code,$code,".jpg",1);
         sendphotos($c,"photos/r".$c.$code.".jpg");
         setsession($cuser['id'],'');
         if (trim($err)=='')
             addreq($cuser['id'],$cuser['req']);


}
if ((trim(substr($cuser['session'],0,3))=='mix' && ($cuser['memory']-0)==1) && $legal){
         $no = trim(substr($cuser['session'],3))-0;
         sendtxt($c,"لطفا چند ثانیه صبر کنید...");
         $typ="collage";
        
         if ($no==1)
             $param="template_name=Green Album;";         
         elseif ($no==2)
             $param="template_name=Vintage French Book;";         
         elseif ($no==3)
             $param="template_name=Two Hearts Photo Frame;";
         elseif ($no==4)
             $param="template_name=Reminder Stickers;";
         $i=uniqid();
         doit($c,$typ,$param,$cuser['uni'],$i,".jpg",2);
         sendphotos($c,"photos/r".$c.$i.".jpg");
         setsession($cuser['id'],'');
         /*if (file_exists("photos/r".$c.$i.".jpg"))
                unlink("photos/r".$c.$i.".jpg");*/
         
         if (trim($err)=='')
             addreq($cuser['id'],$cuser['req']);


}
if ((trim(substr($cuser['session'],0,4))=='tmix' && ($cuser['memory']-0)==1) && $legal){
         $no = trim(substr($cuser['session'],4))-0;
         sendtxt($c,"لطفا چند ثانیه صبر کنید...");
         $typ="collage";
        
         if ($no==1)
             $param="template_name=Drying Photos;";         
         elseif ($no==2)
             $param="template_name=Mall Exhibition;";         
         elseif ($no==3)
             $param="template_name=Birthday Cupcakes Card;";
         $i=uniqid();
         doit($c,$typ,$param,$cuser['uni'],$i,".jpg",3);
         sendphotos($c,"photos/r".$c.$i.".jpg");
         setsession($cuser['id'],'');
         /*if (file_exists("photos/r".$c.$i.".jpg"))
                unlink("photos/r".$c.$i.".jpg");*/
         if (trim($err)=='')
             addreq($cuser['id'],$cuser['req']);


}
if ((trim(substr($cuser['session'],0,4))=='fmix' && ($cuser['memory']-0)==1) && $legal){
         $no = trim(substr($cuser['session'],4))-0;
         sendtxt($c,"لطفا چند ثانیه صبر کنید...");
         $typ="collage";
        
         if ($no==1)
             $param='template_name=Old Photo Book;template_variant=0;crop_portrait=true;animation=true';       
         elseif ($no==2)
             $param="template_name=3D Filmstrip;";         
         elseif ($no==3)
             $param="template_name=Old Museum;";
         elseif ($no==4)
             $param="template_name=Love Message;";

         $i=uniqid();
         if ($no==1){
            doit($c,$typ,$param,$cuser['uni'],$i,".gif",4);
            sendvideo($c,"photos/r".$c.$i.".gif");
         }
         else {
            doit($c,$typ,$param,$cuser['uni'],$i,".jpg",4);
            sendphotos($c,"photos/r".$c.$i.".jpg");
         
         }
         setsession($cuser['id'],'');
         /*if (file_exists("photos/r".$c.$i.".jpg"))
                unlink("photos/r".$c.$i.".jpg");*/
         if (trim($err)=='')
             addreq($cuser['id'],$cuser['req']);


}
if ((trim(substr($cuser['session'],0,4))=='girl') && $legal){
         $no = trim(substr($cuser['session'],4))-0;
         sendtxt($c,"لطفا چند ثانیه صبر کنید...");
         $typ="collage";
        
         if ($no==1)
             $param="template_name=Girl Flower Crown Watercolor;";         
         elseif ($no==2)
             $param="template_name=Skier;";         
         elseif ($no==3)
             $param="template_name=Nun Face in Hole;";
         elseif ($no==4)
             $param="template_name=Eastern Stories;";
         elseif ($no==5)
             $param="template_name=Myrtle Tree Fairy;";
         elseif ($no==6)
             $param="template_name=Mona Lisa ;";
         elseif ($no==7)//=====
             $param="template_name=Native American Face in Hole";         
         elseif ($no==8)
             $param="template_name=Female Watercolor Portrait";
         elseif ($no==9)
             $param="template_name=Glamour;";
         elseif ($no==10)
             $param="template_name=Chamomile Dreams;";
         elseif ($no==11)
             $param="template_name=Summer Girl;";
         

         doit($c,$typ,$param,$code,$code,".jpg",1);
         sendphotos($c,"photos/r".$c.$code.".jpg");
         setsession($cuser['id'],'');
         if (trim($err)=='')
             addreq($cuser['id'],$cuser['req']);


}
if ((trim(substr($cuser['session'],0,4))=='boys') && $legal){
         $no = trim(substr($cuser['session'],4))-0;
         sendtxt($c,"لطفا چند ثانیه صبر کنید...");
         $typ="collage";
        
         if ($no==1)
             $param="template_name=Superman;";         
         elseif ($no==2)
             $param="template_name=Tankman;";         
         elseif ($no==3)
             $param="template_name=Knight in Arms;";
         elseif ($no==4)
             $param="template_name=Pirate of the Caribbean;";
         elseif ($no==5)
             $param="template_name=Astronaut;";
         elseif ($no==6)
             $param="template_name=Fireman;";
         elseif ($no==7)
             $param="template_name=Football Fight;";         
         elseif ($no==8)
             $param="template_name=Boxer";
         elseif ($no==9)
             $param="template_name=Iron Man;";
         elseif ($no==10)//====
             $param="template_name=Yoda;";
         elseif ($no==11)
             $param="template_name=Lionel Messi;";
         elseif ($no==12)
             $param="template_name=Motorcyclist;";

         doit($c,$typ,$param,$code,$code,".jpg",1);
         sendphotos($c,"photos/r".$c.$code.".jpg");
         setsession($cuser['id'],'');
         if (trim($err)=='')
             addreq($cuser['id'],$cuser['req']);


}
if ((trim(substr($cuser['session'],0,4))=='fant') && $legal){
         $no = trim(substr($cuser['session'],4))-0;
         sendtxt($c,"لطفا چند ثانیه صبر کنید...");
         $typ="collage";
        
         if ($no==1)
             $param="template_name=Heart in Hands with Mittens on;";         
         elseif ($no==2)
             $param="template_name=Heart in Hands;";         
         elseif ($no==3)
             $param="template_name=New Year Book;";
         elseif ($no==4)
             $param="template_name=Shabby Chic Books;";
         elseif ($no==5)
             $param="template_name=Medallion;";
         elseif ($no==6)
             $param="template_name=US Dollar;";
         elseif ($no==7)
             $param="template_name=Cat near the Pool;";         
         elseif ($no==8)
             $param="template_name=In the Candle Light;crop_portrait=true;animation=true";
         elseif ($no==9)
             $param="template_name=Souvenirs from the Past;";
         elseif ($no==10)
             $param="template_name=Retro Stamp;";
         elseif ($no==11)
             $param="template_name=Glance to the Past;";
         elseif ($no==12)
             $param="template_name=Grunge Photo;";
         if ($no==8){
         doit($c,$typ,$param,$code,$code,".gif",1);
         sendvideo($c,"photos/r".$c.$code.".gif");
         
         }
         else {
         doit($c,$typ,$param,$code,$code,".jpg",1);
         sendphotos($c,"photos/r".$c.$code.".jpg");
         }
         setsession($cuser['id'],'');
         if (trim($err)=='')
             addreq($cuser['id'],$cuser['req']);


}
if ((trim(substr($cuser['session'],0,3))=='art') && $legal){
         $no = trim(substr($cuser['session'],3))-0;
         sendtxt($c,"لطفا چند ثانیه صبر کنید...");
         $typ="collage";
        
         if ($no==1)
             $param="template_name=Graphite Pencil Sketch;template_variant=0";         
         elseif ($no==2)
             $param="template_name=Vintage Charcoal Sketch;template_variant=0";         
         elseif ($no==3)//====
             $param="template_name=Pencil vs Camera";
         elseif ($no==4)
             $param="template_name=Color Pencil Drawing;template_variant=0";
         elseif ($no==5)
             $param="template_name=Torn Color Pencil Sketch";
         elseif ($no==6)
             $param="template_name=Water Color;template_variant=0";
         elseif ($no==7)
             $param="template_name=Watercolor Painting;template_variant=0";         
         elseif ($no==8)
             $param="template_name=Crayon Drawing;template_variant=1";
         elseif ($no==9)
             $param="template_name=Sketch;template_variant=1";
         elseif ($no==10)
             $param="template_name=Pastel Drawing vs Photography";
         elseif ($no==11)
             $param="template_name=Pen Sketch;template_variant=1";
         elseif ($no==12)//===
             $param="template_name=Ballpoint Pen Drawing vs Photography";

         doit($c,$typ,$param,$code,$code,".jpg",1);
         sendphotos($c,"photos/r".$c.$code.".jpg");
         setsession($cuser['id'],'');
         if (trim($err)=='')
             addreq($cuser['id'],$cuser['req']);


}

fwrite($file,"text=".$text);

fwrite($file,"msg=".$msg);
if(($msg==="/start") || (trim($text)=='بازگشت')){ /*unicode.org/emoji/charts/full-emoji-list.html#1f600*/
              $i=$cuser['credit']-$cuser['req'];
              sendtxt($c,"شما می توانید ".$i."درخواست داشته باشید  ");

              $key=array(array("کودکانه","ساخت نقاشی","ساخت گیف","کاریکاتور"),array("پسرانه","فانتزی","میکس","دخترانه","افزایش اعتبار"),);
	      $reply=array('keyboard'=>$key,'resize_keyboard'=>true, 'on_time_keyboard'=>true);
	      sendkey($c,"لطفا یکی از گزینه ها را انتخاب کنید",json_encode($reply));
	   }
           elseif($legal && (trim($text)=='میکس')){
	      $key=array(array("دو عکس ۴","دو عکس ۳","دو عکس ۲", "دو عکس ۱"), array("سه عکس ۳","سه عکس ۲","سه عکس ۱","آلبوم چهارعکس"), array("چهارعکس ۳","چهارعکس ۲","چهارعکس ۱","بازگشت"),);
	      $reply=array('keyboard'=>$key,'resize_keyboard'=>true, 'on_time_keyboard'=>true);
	      sendkey($c,"لطفا یکی از گزینه ها را انتخاب کنید",json_encode($reply));
		    
	   }
           elseif(trim($text)=="افزایش اعتبار"){
              $key=array(array("پرداخت هزار تومان","ارسال به دوستان"),array("استعلام","بازگشت"),);
	      $reply=array('keyboard'=>$key,'resize_keyboard'=>true, 'on_time_keyboard'=>true);
	      sendkey($c,"لطفا یکی از گزینه ها را انتخاب کنید",json_encode($reply));
          
           }
           elseif(trim($text)=='استعلام'){
              $temp=$cuser['credit']-$cuser['req'];
              $tmptxt=" کاربر گرامی ".$firstname." ".$lastname."\n" ;
              $tmptxt .= "تعداد درخواستهای انجام شده:".$cuser['req']."\n";
              $tmptxt.="تعداد درخواستهای باقی مانده:".$temp."\n";
              $tmptxt.="تعداد دوستان شما:".$cuser['ref'];
	      fwrite($file,$tmptxt);
	      sendtxt($c, $tmptxt);            
             
           }

           elseif(trim($text)=='پرداخت هزار تومان'){
              //   for rasoul
              $tmptxt = "در لینک زیر، میتوانید با پرداخت 1 هزار تومان 10 واحد به اعتبار فعلی خود بیفزایید:" . "\n";
	      $tmptxt .="jamlak.ir/a.php?c=" . $c;
	      sendtxt($c, $tmptxt);            
             
           }
           elseif(trim($text)=='ارسال به دوستان'){
             sendtxt($c,":لطفا لینک زیر را به سه فرد یا گروه ارسال کنید\n".'https://telegram.me/akkasbashibot?start='.$cuser['id']);
           }

           elseif((trim($text)=='آلبوم چهارعکس') && $legal){
              setsession($cuser['id'],'fmix1');
              setuni($cuser['id'],uniqid());
              setmemory($cuser['id'],"4");
              sendtxt($c,"عکس  اول را ارسال کنید");
           }
           elseif((trim($text)=='چهارعکس ۱') && $legal){
              setsession($cuser['id'],'fmix2');
              setuni($cuser['id'],uniqid());
              setmemory($cuser['id'],"4");
              sendtxt($c,"عکس  اول را ارسال کنید");
           }
           elseif((trim($text)=='چهارعکس ۲') && $legal){
              setsession($cuser['id'],'fmix3');
              setuni($cuser['id'],uniqid());
              setmemory($cuser['id'],"4");
              sendtxt($c,"عکس  اول را ارسال کنید");
           }
           elseif((trim($text)=='چهارعکس ۳') && $legal){
              setsession($cuser['id'],'fmix4');
              setuni($cuser['id'],uniqid());
              setmemory($cuser['id'],"4");
              sendtxt($c,"عکس  اول را ارسال کنید");
           }
           elseif((trim($text)=='دو عکس ۱') && $legal){
              setsession($cuser['id'],'mix1');
              setuni($cuser['id'],uniqid());
              setmemory($cuser['id'],"2");
              sendtxt($c,"عکس  اول را ارسال کنید");
           }
           elseif((trim($text)=='دو عکس ۲') && $legal){
              setsession($cuser['id'],'mix2');
              setuni($cuser['id'],uniqid());
              setmemory($cuser['id'],"2");
              sendtxt($c,"عکس  اول را ارسال کنید");
           }
           elseif((trim($text)=='دو عکس ۳') && $legal){
              setsession($cuser['id'],'mix3');
              setmemory($cuser['id'],"2");
              setuni($cuser['id'],uniqid());
              sendtxt($c,"عکس  اول را ارسال کنید");
           }
           elseif((trim($text)=='دو عکس ۴') && $legal){
              setsession($cuser['id'],'mix4');
              setmemory($cuser['id'],"2");
              setuni($cuser['id'],uniqid());
              sendtxt($c,"عکس اول را ارسال کنید");
           }
           elseif((trim($text)=='سه عکس ۱') && $legal){
              setsession($cuser['id'],'tmix1');
              setmemory($cuser['id'],"3");
              setuni($cuser['id'],uniqid());
              sendtxt($c,"عکس  اول راارسال کنید");
           }
           elseif((trim($text)=='سه عکس ۲') && $legal){
              setsession($cuser['id'],'tmix2');
              setmemory($cuser['id'],"3");
              setuni($cuser['id'],uniqid());
              sendtxt($c,"عکس  اول راارسال کنید");
           }
           elseif((trim($text)=='سه عکس ۳') && $legal){
              setsession($cuser['id'],'tmix3');
              setmemory($cuser['id'],"3");
              setuni($cuser['id'],uniqid());
              sendtxt($c,"عکس  اول راارسال کنید");
           }
           elseif($legal && (trim($text)=='فانتزی')){
	      $key=array(array("کتابچه دو","کتابچه یک","قلب دو","قلب یک" ),array("قاب ۱","قاب ۲","در آب","دلار","آویز"), 
              array("تمبر","پازل","قدیمی","بازگشت"));
	      $reply=array('keyboard'=>$key,'resize_keyboard'=>true, 'on_time_keyboard'=>true);
	      sendkey($c,"لطفا یکی از گزینه ها را انتخاب کنید",json_encode($reply));
		    
	   }
           elseif((trim($text)=="قلب یک") && $legal){
              setsession($cuser['id'],'fant1');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="قلب دو") && $legal){
              setsession($cuser['id'],'fant2');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="کتابچه یک") && $legal){
              setsession($cuser['id'],'fant3');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="کتابچه دو") && $legal){
              setsession($cuser['id'],'fant4');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="آویز") && $legal){
              setsession($cuser['id'],'fant5');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="دلار") && $legal){
              setsession($cuser['id'],'fant6');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="در آب") && $legal){
              setsession($cuser['id'],'fant7');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="قاب ۲") && $legal){
              setsession($cuser['id'],'fant8');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="قاب ۱") && $legal){
              setsession($cuser['id'],'fant9');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="تمبر") && $legal){
              setsession($cuser['id'],'fant10');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="پازل") && $legal){
              setsession($cuser['id'],'fant11');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="قدیمی") && $legal){
              setsession($cuser['id'],'fant12');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
	   elseif((trim($text)=='ساخت گیف') && $legal){
	      $key=array(array("😀","🙁","😯"),array("😏","😉","بازگشت"),);
	      $reply=array('keyboard'=>$key,'resize_keyboard'=>true, 'on_time_keyboard'=>true);
	      sendtxt($c,"برای داشتن نتیجه ای بهتر، از عکسهای پرتره استفاده کنید");
              sendkey($c,"لطفا یکی از گزینه ها را انتخاب کنید",json_encode($reply));
              
		    
	   }
           elseif((trim($text)=="😀") && $legal){
                  setsession($cuser['id'],'smile');
                  sendtxt($c,"عکس موردنظر راارسال کنید");
                  
           }
           elseif((trim($text)=="🙁") && $legal){
                  setsession($cuser['id'],'sad');
                  sendtxt($c,"عکس موردنظر راارسال کنید");
                  
           }
           elseif((trim($text)=="😯") && $legal){
                  setsession($cuser['id'],'surprised');
                  sendtxt($c,"عکس موردنظر راارسال کنید");
                  
           }
           elseif((trim($text)=="😏") && $legal){
                  setsession($cuser['id'],'squint');
                  sendtxt($c,"عکس موردنظر راارسال کنید");
                  
           }
           elseif((trim($text)=="😉") && $legal){
                  setsession($cuser['id'],'wink');
                  sendtxt($c,"عکس موردنظر راارسال کنید");
                  
           }
           elseif((trim($text)=='کاریکاتور') && $legal){
	      $key=array(array("مدل یک","مدل دو","مدل سه","مدل چهار"),array("مدل پنج","مدل شش","مدل هفت","بازگشت"),);
	      $reply=array('keyboard'=>$key,'resize_keyboard'=>true, 'on_time_keyboard'=>true);
	      sendtxt($c,"برای داشتن نتیجه ای بهتر، از عکسهای پرتره استفاده کنید");
              sendkey($c,"لطفا یکی از گزینه ها را انتخاب کنید",json_encode($reply));
              
		    
	   }
           elseif((trim($text)=="مدل یک") && $legal){
                  setsession($cuser['id'],'Grotesque');
                  sendtxt($c,"عکس موردنظر راارسال کنید");
                  
           }
           elseif((trim($text)=="مدل دو") && $legal){
                  setsession($cuser['id'],'Alien');
                  sendtxt($c,"عکس موردنظر راارسال کنید");
                  
           }
           elseif((trim($text)=="مدل سه") && $legal){
                  setsession($cuser['id'],'Martian');
                  sendtxt($c,"عکس موردنظر راارسال کنید");
                  
           }
           elseif((trim($text)=="مدل چهار") && $legal){
                  setsession($cuser['id'],'Bulb head');
                  sendtxt($c,"عکس موردنظر راارسال کنید");
                  
           }
           elseif((trim($text)=="مدل پنج") && $legal){
                  setsession($cuser['id'],'Tough guy');
                  sendtxt($c,"عکس موردنظر راارسال کنید");
                  
           }
           elseif((trim($text)=="مدل شش") && $legal){
                  setsession($cuser['id'],'Troll');
                  sendtxt($c,"عکس موردنظر راارسال کنید");
                  
           }
           elseif((trim($text)=="مدل هفت") && $legal){
                  setsession($cuser['id'],'Fat-cheeked');
                  sendtxt($c,"عکس موردنظر راارسال کنید");
                  
           }

           elseif((trim($text)=='دخترانه') && $legal){
	      $key=array(array("شرقی","راهبه","اسکی","سربند گل" ),array("پرتره","سرخپوست","مونالیزا","پری"), 
array("روی جلد","قاب","تابستانی","بازگشت"));
	      $reply=array('keyboard'=>$key,'resize_keyboard'=>true, 'on_time_keyboard'=>true);
	      sendkey($c,"لطفا یکی از گزینه ها را انتخاب کنید",json_encode($reply));
		    
	   }
           elseif((trim($text)=="سربند گل") && $legal){
              setsession($cuser['id'],'girl1');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="اسکی") && $legal){
              setsession($cuser['id'],'girl2');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="راهبه") && $legal){
              setsession($cuser['id'],'girl3');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif ((trim($text)=="شرقی") && $legal){
              setsession($cuser['id'],'girl4');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="پری") && $legal){
              setsession($cuser['id'],'girl5');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="مونالیزا") && $legal){
              setsession($cuser['id'],'girl6');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="سرخپوست") && $legal){
              setsession($cuser['id'],'girl7');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="پرتره") && $legal){
              setsession($cuser['id'],'girl8');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="روی جلد") && $legal){
              setsession($cuser['id'],'girl9');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="قاب") && $legal){
              setsession($cuser['id'],'girl10');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="تابستانی") && $legal){
              setsession($cuser['id'],'girl11');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }

          elseif((trim($text)=='پسرانه') && $legal){
	      $key=array(array("دزددریایی","شوالیه","سرباز","سوپرمن" ),array("بکسر","فوتبالیست","آتشنشان","فضانورد"), 
              array("مردآهنی","یودا","لیونل مسی","موتورسوار","بازگشت"));
	      $reply=array('keyboard'=>$key,'resize_keyboard'=>true, 'on_time_keyboard'=>true);
	      sendkey($c,"لطفا یکی از گزینه ها را انتخاب کنید",json_encode($reply));
		    
	   }
           elseif((trim($text)=="سوپرمن") && $legal){
              setsession($cuser['id'],'boys1');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="سرباز") && $legal){
              setsession($cuser['id'],'boys2');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="شوالیه") && $legal){
              setsession($cuser['id'],'boys3');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="دزددریایی") && $legal){
              setsession($cuser['id'],'boys4');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="فضانورد") && $legal){
              setsession($cuser['id'],'boys5');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="آتشنشان") && $legal){
              setsession($cuser['id'],'boys6');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="فوتبالیست") && $legal){
              setsession($cuser['id'],'boys7');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="بکسر") && $legal){
              setsession($cuser['id'],'boys8');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="مردآهنی") && $legal){
              setsession($cuser['id'],'boys9');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="یودا") && $legal){
              setsession($cuser['id'],'boys10');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="لیونل مسی") && $legal){
              setsession($cuser['id'],'boys11');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="موتورسوار") && $legal){
              setsession($cuser['id'],'boys12');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=='کودکانه') && $legal){
	      $key=array(array("ریو","وینی","موش آشپز","شرک" ),array("تخم مرغ","کلاه تولد","نقاشی صورت","قاب کودک"), 
array("قاب یک","قاب دو","قاب سه","قاب چهار","بازگشت"));
	      $reply=array('keyboard'=>$key,'resize_keyboard'=>true, 'on_time_keyboard'=>true);
	      sendkey($c,"لطفا یکی از گزینه ها را انتخاب کنید",json_encode($reply));
		    
	   }
           elseif((trim($text)=="شرک") && $legal){
              setsession($cuser['id'],'kid1');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="وینی") && $legal){
              setsession($cuser['id'],'kid2');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="ریو") && $legal){
              setsession($cuser['id'],'kid3');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="موش آشپز") && $legal){
              setsession($cuser['id'],'kid4');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="نقاشی صورت") && $legal){
              setsession($cuser['id'],'kid5');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="قاب کودک") && $legal){
              setsession($cuser['id'],'kid6');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="کلاه تولد") && $legal){
              setsession($cuser['id'],'kid7');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="تخم مرغ") && $legal){
              setsession($cuser['id'],'kid8');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="قاب یک") && $legal){
              setsession($cuser['id'],'kid9');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="قاب دو") && $legal){
              setsession($cuser['id'],'kid10');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="قاب سه") && $legal){
              setsession($cuser['id'],'kid11');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           elseif((trim($text)=="قاب چهار") && $legal){
              setsession($cuser['id'],'kid12');
              sendtxt($c,"عکس موردنظر راارسال کنید");
           }
           
           elseif ((trim($text)=='ساخت نقاشی') && $legal){
	      $key=array(array("مدادرنگی دو","مدادرنگی یک","زغال","گرافیت"), array("آبرنگ دو","آبرنگ یک","پاستل"), array("خودکار","طراحی یک","طراحی دو","بازگشت"),);
	      $reply=array('keyboard'=>$key,'resize_keyboard'=>true, 'on_time_keyboard'=>true);
	      sendkey($c,"لطفا یکی از گزینه ها را انتخاب کنید",json_encode($reply));
		    
	   }
           elseif ((trim($text)=="گرافیت") && $legal){
                  setsession($cuser['id'],'art1');
                  sendtxt($c,"عکس موردنظر راارسال کنید");
                  
           }
          
           elseif ((trim($text)=="زغال") && $legal){
                  setsession($cuser['id'],'art2');
                  sendtxt($c,"عکس موردنظر راارسال کنید");
                  
           }
           
           elseif ((trim($text)=="مدادرنگی یک") && $legal){
                  setsession($cuser['id'],'art4');
                  sendtxt($c,"عکس موردنظر راارسال کنید");
                  
           }
           elseif ((trim($text)=="مدادرنگی دو") && $legal){
                  setsession($cuser['id'],'art5');
                  sendtxt($c,"عکس موردنظر راارسال کنید");
                  
           }
           elseif ((trim($text)=="آبرنگ یک") && $legal){
                  setsession($cuser['id'],'art6');
                  sendtxt($c,"عکس موردنظر راارسال کنید");
                  
           }
           elseif ((trim($text)=="آبرنگ دو") && $legal){
                  setsession($cuser['id'],'art7');
                  sendtxt($c,"عکس موردنظر راارسال کنید");
                  
           }
           elseif ((trim($text)=="پاستل") && $legal){
                  setsession($cuser['id'],'art8');
                  sendtxt($c,"عکس موردنظر راارسال کنید");
                  
           }
           elseif ((trim($text)=="طراحی یک") && $legal){
                  setsession($cuser['id'],'art9');
                  sendtxt($c,"عکس موردنظر راارسال کنید");
                  
           }
           elseif ((trim($text)=="طراحی دو") && $legal){
                  setsession($cuser['id'],'art10');
                  sendtxt($c,"عکس موردنظر راارسال کنید");
                  
           }
           elseif ((trim($text)=="خودکار") && $legal){
                  setsession($cuser['id'],'art11');
                  sendtxt($c,"عکس موردنظر راارسال کنید");
                  
           }
           
           
           
          
        
if (!$legal)
    sendtxt($c," لطفا گزینه افزایش اعتبار را انتخاب کنید.\n اعتبار شما به اتمام رسیده است");

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

    $url = "https://api.telegram.org/bot263233217:AAGwKWJc2wZKtrBQpKSQxA0rcrUDOl9Kihg/sendMessage";
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

function setuni($id,$val){
      global $conn;
      $sql = "UPDATE `akkasbashi` SET uni='".$val."' WHERE `ID`= '" . $id ."'" ; 
      $res = $conn->query($sql);
}
function setmemory($id,$val){
      global $conn;
      $sql = "UPDATE `akkasbashi` SET memory='".$val."' WHERE `ID`= '" . $id ."'" ; 
      $res = $conn->query($sql);
}

function setsession($id,$val){
      global $conn;
      $sql = "UPDATE `akkasbashi` SET session='".$val."' WHERE `ID`= '" . $id ."'" ; 
      $res = $conn->query($sql);
}

function addreq($id,$val){
      global $conn;
      $val++;
      $sql = "UPDATE `akkasbashi` SET req=".$val." WHERE `ID`= '" . $id ."'" ; 
      $res = $conn->query($sql);
}

function registry($c,$name,$ref){
      global $conn;     
      global $cuser; 
      global $legal; 
      $file=fopen("registry.txt","w"); 
      $sql= "SELECT `ID` FROM `akkasbashi` WHERE c='".$c."'";
      $res = $conn->query($sql);
      if (mysqli_num_rows($res)==0){ 
          sendtxt($c,"شما می توانید عکسهای خود را با صورتهای گوناگون و زیبایی ویرایش کنید. چند نمونه را در زیر می توانید ببینید.");
          sendphotos($c,"sample1.jpg");
sendphotos($c,"sample2.jpg");
sendphotos($c,"sample3.jpg");
          $sql = "insert into `akkasbashi` (`c`,`name`,`credit`,`req`,`ref`) values('" . $c . "','" .$name."', 1,0,0)" ; 
          $res = $conn->query($sql);
          fwrite($file,"new child was added " . "\n");
          if (trim($ref)!==""){
              $sql = "UPDATE `akkasbashi` SET `ref`= `ref`+1 WHERE `ID`= '" . $ref ."'" ; 
              $res = $conn->query($sql);
          }
      }
      $sql= "SELECT * FROM `akkasbashi` WHERE c='".$c."'";
      fwrite($file,$sql);
      $res = $conn->query($sql);
      if (mysqli_num_rows($res)!==0){ 
            $row= $res->fetch_assoc(); 
            $cuser["id"]=$row['ID'];
            $cuser["session"]=$row['session'];
            $cuser["req"]=$row['req'];
            $cuser["credit"]=$row['credit'];
            $cuser["memory"]=$row['memory'];
            $cuser["uni"]=$row['uni'];
            fwrite($file,"registry->".print_r($cuser,1));
            if ($row['ref']>=3){
                 $k=(int)($row['ref']/3)*10;
                 $sql = "UPDATE `akkasbashi` SET `credit`= `credit`+".$k.", `ref`=0 WHERE `c`= '" . $c ."'" ; 
                 $res = $conn->query($sql);
                 fwrite($file,"registry->".$sql);
                 sendtxt($c,"اعتبار شما از طریق دوستانتان به تعداد" .$k. "درخواست اضافه شد");
            }
            if ($row['req']>=$row['credit'])
                 $legal=false;
            else $legal=true;
      }

}
function doit($c,$name,$params,$code,$code1,$ext,$no){
  global $err;
  $file = fopen("besm_" . date("d-M-Y") . ".log", "w");
  fwrite($file, " بسم الله الرحمن الرحیم " . "\n\n");
  $APP_ID= "4c70bdf182a7a43ec4027bd90d418eda"; 
  $KEY= "1fa9a3f146e435a856ba633c9956baf5"; 
  if ($no==1){
  $DATA='<?xml version="1.0"?>
    <image_process_call>
      <image_url>http://jamlak.ir/photos/a'.$c.$code.'.jpg</image_url>
      <methods_list>
        <method>
          <name>'.$name.'</name>
          <params>'.$params.'</params>
        </method>
      </methods_list>
<template_watermark>false</template_watermark>
    </image_process_call>  
  ';
  }   
  else if ($no==2){
    $DATA='<?xml version="1.0"?>
    <image_process_call>
      <image_url order="1">http://jamlak.ir/photos/a'.$c.$code.'1.jpg</image_url>
      <image_url order="2">http://jamlak.ir/photos/a'.$c.$code.'2.jpg</image_url>
      <methods_list>
        <method>
          <name>'.$name.'</name>
          <params>'.$params.'</params>
        </method>
      </methods_list>
      <template_watermark>false</template_watermark>
    </image_process_call>  
  ';
  }
  else if ($no==3){
    $DATA='<?xml version="1.0"?>
    <image_process_call>
      <image_url order="1">http://jamlak.ir/photos/a'.$c.$code.'1.jpg</image_url>
      <image_url order="2">http://jamlak.ir/photos/a'.$c.$code.'2.jpg</image_url>
      <image_url order="3">http://jamlak.ir/photos/a'.$c.$code.'3.jpg</image_url>

      <methods_list>
        <method>
          <name>'.$name.'</name>
          <params>'.$params.'</params>
        </method>
      </methods_list>
      <template_watermark>false</template_watermark>
    </image_process_call>  
  ';
  }
  else if ($no==4){
    $DATA='<?xml version="1.0"?>
    <image_process_call>
      <image_url order="1">http://jamlak.ir/photos/a'.$c.$code.'1.jpg</image_url>
      <image_url order="2">http://jamlak.ir/photos/a'.$c.$code.'2.jpg</image_url>
      <image_url order="3">http://jamlak.ir/photos/a'.$c.$code.'3.jpg</image_url>
      <image_url order="4">http://jamlak.ir/photos/a'.$c.$code.'4.jpg</image_url>

      <methods_list>
        <method>
          <name>'.$name.'</name>
          <params>'.$params.'</params>
        </method>
      </methods_list>
      <template_watermark>false</template_watermark>
    </image_process_call>  
  ';
  }
  fwrite($file, "DATA : " . $DATA . "\n");  
  
  $SIGN_DATA = hash_hmac('SHA1', $DATA, $KEY);

  //set POST variables
  $url = 'http://opeapi.ws.pho.to/addtask';

  $fields_string = "app_id=" . urlencode($APP_ID) . "&key=" . urlencode($KEY) . "&sign_data=" . urlencode($SIGN_DATA) . "&data=" . urlencode($DATA);
  fwrite($file, "fields_string : " . $fields_string . "\n");  

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL,$url);
  curl_setopt($ch, CURLOPT_POST, true);  // tell curl you want to post something
  //curl_setopt($ch,CURLOPT_POST, 3);
  //curl_setopt($ch, CURLOPT_POSTFIELDS, "var1=value1&var2=value2&var_n=value_n"); // define what you want to post
  curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //NOTE: we want to have the result back in the string not in the browser

  $result = curl_exec($ch);

  curl_close($ch);

  fwrite($file, "result : " . $result . "\n");  

  $X = simplexml_load_string ($result);
  fwrite($file, "X======>>>>> " . print_r($X,1) . "\n");  

  $status = $X->status;
  fwrite($file, "status: " . $status . "\n");  

  if(strcmp($status,'OK')!==0)
  {
    $err_code= $X->err_code;
    $err=$err_code;
    fwrite($file, "error occured with err_code :" . $err_code . "\n");  
  }
  else 
  {
    $request_id = $X->request_id;
    fwrite($file, "request_id: " . $request_id. "\n");  
    $resurl= "http://opeapi.ws.pho.to/getresult?request_id=" . $request_id;

   // if ($no==4)
    sleep(15);
   // else sleep(10);
    $ch2 = curl_init();
    curl_setopt($ch2, CURLOPT_URL,$resurl);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true); //NOTE: we want to have the result back in the string not in the browser
    $result2 = curl_exec($ch2);
    curl_close($ch2);
  
    $X2 = simplexml_load_string ($result2);
    fwrite($file, "X2======>>>>> " . print_r($X2,1) . "\n");  

    $status2 = $X2->status;
    fwrite($file, "status2: " . $status2 . "\n");  
    if(strcmp($status2,'OK')!==0)
    {
      $err_code2 = $X2->err_code;
      $err_des = $X2->description;
      $err=$err_code2;
      fwrite($file, "error occured with err_code2 :" . $err_code2 . $err_des."\n");
      sendtxt($c,"صورت پیدا نشد، عکس دیگری انتخاب کنید");  
    }
    else
    {
      //shokr
      $result_url = $X2->result_url;
      fwrite($file, "result_url is : " . $result_url . "\n");  

      $newimagename = 'r'.$c.$code1.$ext;              
      $tmpPath = 'photos/' . $newimagename;
      copy($result_url , $tmpPath);    
    } 
  }

  fwrite($file, "الحمد لله" . "\n");
  fclose($file);

}
function sendphotos($chat_id,$filename){
	

$bot_url    ="https://api.telegram.org/bot263233217:AAGwKWJc2wZKtrBQpKSQxA0rcrUDOl9Kihg/";

$url = $bot_url . "sendphoto?chat_id=" . $chat_id;

$post=array("chat_id"=>$chat_id,"photo"=>new CURLFILE(realpath($filename)));

$ch = curl_init(); 
curl_setopt($ch,CURLOPT_HTTPHEADER,array("Content-Type:multipart/form-data"));
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch, CURLOPT_POSTFIELDS,$post); 

$output = curl_exec($ch);

}
function sendvideo($chat_id,$filename){
	

$bot_url    ="https://api.telegram.org/bot263233217:AAGwKWJc2wZKtrBQpKSQxA0rcrUDOl9Kihg/";

$url = $bot_url . "sendVideo?chat_id=" . $chat_id;

$post=array("chat_id"=>$chat_id,"video"=>new CURLFILE(realpath($filename)));

$ch = curl_init(); 
curl_setopt($ch,CURLOPT_HTTPHEADER,array("Content-Type:multipart/form-data"));
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch, CURLOPT_POSTFIELDS,$post); 

$output = curl_exec($ch);

}
function sendtxt($user_id,$ms){

    $url = "https://api.telegram.org/bot263233217:AAGwKWJc2wZKtrBQpKSQxA0rcrUDOl9Kihg/sendMessage";
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

//</send>
}
//-------------------------------------------

?>

