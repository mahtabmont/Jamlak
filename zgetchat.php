<?php
//ma shaa Allah la ghovvata ella beAllah 

  $file=fopen("zgetchat.log","w");
  fwrite($file, "salaam" . "\n\n");
  fwrite($file, "started on " . date("d-M-Y") . " at " . date("H:i:s") . "\n\n");



$bot_url    ="https://api.telegram.org/bot263233217:AAGwKWJc2wZKtrBQpKSQxA0rcrUDOl9Kihg/getChatMembersCount?chat_id=112423114";

//$url = $bot_url . "getChat?chat_id=" . "112423114";

 // $url = "https://api.telegram.org/bot263233217:AAGwKWJc2wZKtrBQpKSQxA0rcrUDOl9Kihg/sendMessage";
 
  $content = array(
        'chat_id' => '112423114'
        );

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($content));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec ($ch);


fwrite($file, $output);





fclose($file);






//------------------------------------------------------------------------------

function sendtxt($ms, $user_id)
{
//  $url = "https://api.telegram.org/bot348901737:AAGGqJbE0mz73wglk4TYTxQKgFklF67_9kk/sendMessage";
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
}
//-----------------------------

function sendphotos($chat_id,$filename,$caption){	

//$bot_url    ="https://api.telegram.org/bot348901737:AAGGqJbE0mz73wglk4TYTxQKgFklF67_9kk/";
$bot_url    ="https://api.telegram.org/bot263233217:AAGwKWJc2wZKtrBQpKSQxA0rcrUDOl9Kihg/";

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
//-----------------------------------------------------------------------

?>