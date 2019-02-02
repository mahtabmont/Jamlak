<?php
$file=fopen("welcome.log","w");
require_once 'dbconnect.php';
$bot_id="254204272:AAGw4J_0T2j4x4iQQvcezVJps-0E0i0veqU";
$content = file_get_contents("php://input");
$update = json_decode($content, true);
//fwrite($file,"update= " .print_r($update, true));
$c = $update["message"]["from"]["id"];
$text = $update['message']['text'];
$firstname=$update['message']["from"]['first_name'];
$lastname=$update['message']["from"]['last_name'];
fwrite($file,$lastname.$firstname."salaam".$text);
$msg=substr($text,0,6);
$msg=trim($msg);
$id=substr($text,6,strlen($text));
$id=trim($id);
$msg=mb_strtolower($msg);
fwrite($file,"id=".$id."msg=".$msg);
if($msg==="/start"){
  $found = 0;
  
     fwrite($file,"HHHHHHHHHHHHora\n");
     $conn = new mysqli($servername, $username, $password, $dbname);
     if ($conn->connect_error) {
         die("Connection failed: " . $conn->connect_error);
     } 
     $conn->set_charset("utf8");
     header("content-type:text/html;charset=UTF-8");

     if ($id>''){
       //first see if alread has
        $sql= "SELECT `c` FROM `amlakin` WHERE ID='" . $id . "'";
        $res = $conn->query($sql);
        if ($res){ 
            $row= $res->fetch_assoc(); 
            $pc = $row["c"];
            if(strlen(trim($pc))>0)
            {
              if(strcmp($pc,$c)==0)
              {
                //update its pname
                $sql = "UPDATE `amlakin` SET pname='" . $firstname . " " . $lastname."' WHERE `c`= '" . $c ."'" ; 
                $res = $conn->query($sql);
              }
              else
              {
                $sql = "insert into `amlakinpc`(`parentID`,`newc`,`pname`) values('" . $id . "', '" . $c . "','" . $firstname." ". $lastname . "')" ; 
                $res = $conn->query($sql);
                fwrite($file,"new child was added for parent_id=" . $id . "\n");
              }
            }
            else
            {
              $sql = "UPDATE `amlakin` SET c='".$c."' ,pname='".$firstname." ". $lastname."' WHERE `ID`= '" . $id ."'" ; 
              $res = $conn->query($sql);
            }
        } 
     }
     else {
        $sql= "SELECT `ID` FROM `amlakin` WHERE c='".$c."'";
        $res = $conn->query($sql);
        if ($res){ 
            $row= $res->fetch_assoc(); 
            $id=$row["ID"];
        } 
     }   
     
     $amlakname="";
     if (strlen($id) > 0){
         $sql = "SELECT * FROM `amlakin` WHERE `ID`= '" . $id ."'" ;
         fwrite($file, "sql is: " . $sql . "\n");
         $res = $conn->query($sql);
         if ($res)
         {
           if(mysqli_num_rows($res) == 1) //NOTE: ASSUMED ID is unique, to make it more robust soon en shaa Allah
           {
             $found =1;
             $row= $res->fetch_assoc(); 
             $amlakname=$row["name"];
             $senfno= $row["senfno"];
             $pass= $row["passname"];
             $conn->close();
             $link = "jamlak.ir/?id=" . $row["ID"];
             $txt=$firstname." ". $lastname."\n".$amlakname."\n".
             "سلام
             \n\xF0\x9F\x8F\xA1 به جامعه همیاران املاک خوش آمدید.\xF0\x9F\x8F\xA1
             \n هدف ارائه مطالب مفید، و تسهیل ارتباط مشتریان با مشاورین محترم املاک می باشد. ";
     
/*             $txt.="\nلطفاً اطلاعات خود- بخصوص محل بنگاه- را در سایت زیر ویرایش بفرمایید: 
             {$link}" .
"           \nنام کاربری شما (شماره صنفی):
           {$senfno}
           \nکلمه عبور (میتوانید پس از  ورود، آنرا به دلخواه  تغییر دهید): 
           {$pass} " ; 
*/         
           $txt .= "         \n\nمنتظر خبرهای بعدی ما باشید. 
           \n\xF0\x9F\x8C\xBA خدا نگهدار\xF0\x9F\x8C\xBA";
           }
         }
      } 
 
      if($found == 0)
      {
        $amlakname = "کاربر گرامی";
        $txt=$firstname." ". $lastname."\n".$amlakname."\n".
        "سلام
        \n\xF0\x9F\x8F\xA1 به جامعه همیاران املاک خوش آمدید.\xF0\x9F\x8F\xA1
        \n هدف ارائه مطالب مفید، و تسهیل ارتباط مشتریان با مشاورین محترم املاک می باشد. ";
     
//         $txt.="\nمتاسفانه اطلاعات بنگاه شما در لیست مشاورین املاک استان پیدا نشد. لطفا برای افزودن اطلاعات خود در سایت، به لینک زیر رجوع بفرمایید:  
//         jamlak.ir\n ";

         $txt.="\nمتاسفانه اطلاعات بنگاه شما در سایت مشاورین املاک استان پیدا نشد. لطفا لینک ارسال شده قبلی در پیامک را start فرمایید. \n ";

         $txt.="
         \nمنتظر خبرهای بعدی ما باشید. 
         \n\xF0\x9F\x8C\xBA خدا نگهدار\xF0\x9F\x8C\xBA";
     }

     sendphotos($c,"index.jpg");
     sendtxt($c,$txt);
}
else
{
    echo "false" ;
}

function sendtxt($user_id,$ms){

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

//</send>
}
function sendphotos($chat_id,$filename){
	

$bot_url    ="https://api.telegram.org/bot254204272:AAGw4J_0T2j4x4iQQvcezVJps-0E0i0veqU/";

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
?>
