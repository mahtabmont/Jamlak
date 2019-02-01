<?php
$bot_id="58526754:AAGqdsEWv2zm8ki6DwXplcjaaxmjdDxy4LI";
$content = file_get_contents("php://input");
$update = json_decode($content, true);
$chatID = $update["message"]["from"]["id"];
$text = $update['message']['text'];

if($text=="/start"){
$txt="کاربر گرامی به بات گیفی نو خوش آمدید. این بات از این پس برای شما در مناسبت های ویژه گیف و کارتهای زیبا ارسال خواهد کرد.";
sendtxt($chatID,$txt);
sendgif($chatID,"welcome.gif");
$servername = "www.kodoom1.com";
$username = "kodoomc1_guser";
$password = "Greeting123";
$dbname = "kodoomc1_greetings";
	

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
$t="مشکل در ارتباط بر قرار کردن با دیتا بیس ";
sendtxt($id,$t);
    die("Connection failed: " . mysqli_connect_error());

}

$sql = "INSERT INTO chatTable(cID) VALUES ('$chatID')";

if(mysqli_query($conn, $sql)){
$t="با تشکر از شما ورود شما را خوشامد می گویم. ";
sendtxt($id,$t);
}

mysqli_close($conn);

}

function sendtxt($user_id,$ms){

    $url = "https://api.telegram.org/bot58526754:AAGqdsEWv2zm8ki6DwXplcjaaxmjdDxy4LI/sendMessage";
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
function sendgif($chat_id,$filename){
	

$bot_url    ="https://api.telegram.org/bot58526754:AAGqdsEWv2zm8ki6DwXplcjaaxmjdDxy4LI/";

$url = $bot_url . "senddocument?chat_id=" . $chat_id;

$post=array("chat_id"=>$chat_id,"document"=>new CURLFILE(realpath($filename)));

$ch = curl_init(); 
curl_setopt($ch,CURLOPT_HTTPHEADER,array("Content-Type:multipart/form-data"));
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch, CURLOPT_POSTFIELDS,$post); 

$output = curl_exec($ch);

}
?>