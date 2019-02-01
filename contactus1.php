<?php
$id = $_POST['id'];
$msg = $_POST['message'];
$file = fopen("contact.log","w");

require_once 'dbconnect.php';
$date = date('Y-m-d H:i:s');

//escape, just in case
$id = mysqli_real_escape_string($conn, $id);                    
$msg = mysqli_real_escape_string($conn, $msg );          
$date = mysqli_real_escape_string($conn, $date );          
if (!empty(trim($msg))){ 
  $sql = "INSERT INTO `contact` (`contact_id`, `msg`, `date`) values('" . $id . "', '" . $msg . "', '" . $date ."')";
  fwrite($file, "the sql is:" . $sql . "\n");

  if ($conn->query($sql) === TRUE)
  {
    fwrite($file, "inserted into the table successfully, shoooooooookrr" . "\n");
    sendtxt("112423114","یک تماس جدید ثبت شد");
    sendtxt("174034313","یک تماس جدید ثبت شد");
    echo "true" ;
  } 
  else 
  {
    fwrite($file, "errroooooooor inserting into the table" . "\n");
    echo "false" ;
  }
} 
fclose($file);
$conn->close();

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

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec ($ch);

}
?>