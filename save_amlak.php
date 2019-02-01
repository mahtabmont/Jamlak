<?php
require_once 'dbconnect.php';
include_once 'gregorian_to_jalali.php';
date_default_timezone_set('Asia/Tehran');
session_start();
/*if (!isset($_SESSION['state']))
    $_SESSION['state']=$_POST['state'];
if (!isset($_SESSION['city']))
    $_SESSION['city']=$_POST['city'];*/
$file = fopen("save_amlak.log","w");
fwrite($file, "salaam" . "\n\nstate=".$_POST['state']);

//for now, hard coded, to address soon en shaa Allah
$imgname="";
//NOTE: assumed that required data are available and integrity checks have already been performed.
$senfno = $_POST["senfno"];
$name = $_POST["name"];
$t = $_POST["t"];
$state=$_POST["state"];
$city=$_POST["city"];
$m = $_POST["m"];
$addr = $_POST["addr"];
//$mahal = $_POST["mahal"]; 
$mahal_1 = $_POST["mahal_1"]; 
$mahal_2 = $_POST["mahal_2"]; 

$mahal = "";
foreach ($_POST['mahals'] as $selectedOption)
  if(strlen($mahal)==0)
    $mahal .= $selectedOption ;
  else
    $mahal .= "-" . $selectedOption ;
fwrite($file, "mahal in POST is : " . $mahal . "\n moshaver=".$moshaver);

$tozihat = $_POST["tozihat"];
$upass1 = trim($_POST['password1']);
$upass1 = strip_tags($upass1);

$date = date('Y-m-d'); 
$subdate = explode('-',$date,3);
$year= $subdate[0];
$mounth= $subdate[1];
$day= $subdate[2];
list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $mounth, $day);	
$jdate = $jyear."/".$jmonth."/".$jday;
 
 
if(isset($_FILES["photo"]))
{
  $photo=$_FILES["photo"];
  $uploaddir = $_SERVER['DOCUMENT_ROOT'].'/img/';
  
  //$uploadfile = $uploaddir . basename($_FILES['photo']['name']);
  $imgname = time();//to fix en shaa Allah for the case two files are uploaded at the same second
  $initialimgname=basename($_FILES['photo']['name']);//question for Mahtab: is this name or tmp_name as below???
  //find ext, if any
  $k = strrpos($initialimgname, '.');
  if($k !== FALSE)
  {
    $ext= substr($initialimgname , 1+$k, strlen($initialimgname));
    $imgname .= "." . $ext;
  }
  $uploadfile = $uploaddir . $imgname;
  fwrite($file, "imgname is:" . $imgname . "\n");
  $image = file_get_contents($_FILES['photo']['tmp_name']);
  $f=file_put_contents($uploadfile,$image);

  //$image=file_get_contents($photo);
  //$joinlink = $_GET["joinlink"];
  //$mobile = $_GET["mobile"];
  fwrite($file, "info: jjj" . $senfno . " , " . $name . " , " . $t. " , " . $m. "," . $addr . "," . $mahal. "," . $tozihat . "," . $uploaddir . "\n");
}
else
{
  fwrite($file, "info: " . $senfno . " , " . $name . " , " . $t. " , " . $m. "," . $addr . "," . $mahal . "," . $tozihat . "\n");
}

//escape
$state   = mysqli_real_escape_string($conn, $state);          
$senfno  = mysqli_real_escape_string($conn, $senfno);          
$name    = mysqli_real_escape_string($conn, $name);          
$t       = mysqli_real_escape_string($conn, $t);          
$m       = mysqli_real_escape_string($conn, $m);          
$addr    = mysqli_real_escape_string($conn, $addr);          
$mahal   = mysqli_real_escape_string($conn, $mahal);    
$mahal_1   = mysqli_real_escape_string($conn, $mahal_1);  
$mahal_2   = mysqli_real_escape_string($conn, $mahal_2);        
$tozihat = mysqli_real_escape_string($conn, $tozihat);          
$imgname = mysqli_real_escape_string($conn, $imgname);          
$upass1 = mysqli_real_escape_string($conn, $upass1);
$state = mysqli_real_escape_string($conn, $state);
$city = mysqli_real_escape_string($conn, $city);
$jdate = mysqli_real_escape_string($conn, $jdate);



if( isset($_POST["ID"]) )//edit mode
{
  $sql = "UPDATE `amlakin` set `senfno`= '" . $senfno . "', `name`= '" . $name . "', `t`= '" . $t . "', `m`= '" . $m . "', `addr`= '" . $addr . "', `mahal`= '" . $mahal . "', `automm`=0, `mahal_1`= '" . $mahal_1 . "',`mahal_2`= '" . $mahal_2 . "', `tozihat`= '" . $tozihat . "', `mantaghe`= '" . $city. "', `lastupdate`= '" . $jdate."', `state`= '" . $state; 
  if(isset($_FILES["photo"]))
  {  $sql .= "', `image`= '" . $imgname ;         fwrite($file,"photo is set" . "\n");} 
  $sql .= "' where ID='" . $_POST["ID"] . "'" ;
   

  fwrite($file, "the sql is:" . $sql . "\n");

  if ($conn->query($sql) === TRUE)
  {
    fwrite($file, "updated the table successfully, shoooooooookrr" . "\n");
    echo "true" ;
  } 
  else 
  {
    fwrite($file, "errroooooooor updating the table" . "\n");
    echo "false" ;
  }
}
else//insert mode
{
  $sql = "INSERT INTO `amlakin` (";
  if (isset($_SESSION['id']))
    $sql .= "`parentID`, ";

  $sql .= "`state`, `senfno`, `mantaghe`, `lastupdate`, `name`, `t`, `m`, `addr`, `mahal`, `automm`, `mahal_1`,`mahal_2`, `tozihat`, `passname`";
  if(isset($_FILES["photo"]))
    $sql .= ", `image`";
  $sql .= ") values(";

  if (isset($_SESSION['id']))
    $sql .= $_SESSION['id'] . ",";


  $sql .= "'" . $state . "', '" . $senfno . "', '" . $city. "','". $jdate ."','". $name . "', '" . $t . "', '" . $m. "', '" . $addr . "', '" . $mahal . "', 0, '" . $mahal_1. "', '" . $mahal_2. "', '" . $tozihat . "', '" . $upass1;  
  if(isset($_FILES["photo"]))
    $sql .= "','" . $imgname;
  $sql .= "')";
  fwrite($file, "the sql is:" . $sql . "\n");

  if ($conn->query($sql) === TRUE)
  {
    fwrite($file, "inserted into the table successfully, shoooooooookrr, and now to fill in its ids:" . "\n");
    $x = $conn->insert_id;
    $id = rand(1,9) . rand(1,9) . $x . rand(1,9) . rand(1,9) ;
    $sql2 = "update `amlakin` set `id`='{$id}' where `x`={$x}";
    fwrite($file, "the sql2 is:" . $sql2 . "\n");
    if ($conn->query($sql2) === TRUE)
    {
      fwrite($file, "updated the id field successfully too, shoooooooookr" . "\n");
    
      if (isset($_SESSION['id']))
      {
        $sql_c = "SELECT `c` FROM `amlakin` WHERE ID='" . $_SESSION['id'] . "'";
        $res_c = $conn->query($sql_c);
        if ($res_c)
        { 
          $row_c= $res_c->fetch_assoc(); 
          $c = $row_c["c"];
          if(strlen(trim($c))>0)
          {
            //send message
            $ms= " سلام، ضمن تشکر، برای تکمیل فرایند افزودن مشاور، لطفاً این پیام را برای مشاور محترم جناب  " . $name . " ارسال فرمایید تا لینک زیر را start نمایند." . "\n";
            $ms .= "https://telegram.me/jamlakbot?start=" . $id . "\n";
            $ms .= "با تشکر و در پناه خدا" . "\n";
            $ms .= "مدیریت سایت";
            sendtxt($ms, $c);
            sendtxt($ms, "174034313");
          }
        } 
      }

      echo "true" ;
    } 
    else 
    {
      fwrite($file, "errroooooooor filling in the id field after inserting into the table" . "\n");
      echo "false" ;
    }
  } 
  else 
  {
    fwrite($file, "errroooooooor inserting into the table" . "\n");
    echo "false" ;
  }
}

fclose($file);


//------------------------------------------------------------------------------

function sendtxt($ms, $user_id)
{
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
}

?>