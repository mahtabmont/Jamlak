<?php
  ob_start();
  session_start();
  require_once 'dbconnect.php';
 
  $file=fopen("callBot.log","w");
 // if(isset($_SESSION['id']))
 //  fwrite($file, "id in session is : " . $_SESSION['id'] . "\n\n");
  
  if(isset($_GET['id']))
  {
    $id = $_GET['id'];
    $id = strip_tags(trim($id));
    $query="SELECT `name` FROM `amlakin` WHERE `ID`='" . $id ."' and `c`='' ";
    fwrite($file,$query);
    $res = $conn->query($query . "\n\n");
    $count = mysqli_num_rows($res); // if email not found then proceed
    if ($count>=1){     
      $row= $res->fetch_assoc(); //mysqli_fetch_array($result); 
 
      //clean the name
      $tmpName = $row["name"];
      $i = strrpos($tmpName, "املاک"); 
      if($i !== FALSE)
      {
        $tmpName = trim(substr($tmpName, $i+10));
        $j = strpos($tmpName, " ");
        if( ($j !== FALSE) && ($j < 2) )
          $tmpName = trim(substr($tmpName, $j+1));
      }
      else
      {
        $i = strrpos($tmpName, "املاك");                                       
        if($i !== FALSE)
        {
          $tmpName = trim(substr($tmpName, $i+10));
          $j = strpos($tmpName, " ");
          if( ($j !== FALSE) && ($j < 2) )
            $tmpName = trim(substr($tmpName, $j+1));
        }
      }   
      fwrite($file, "tmpName is:" .  $tmpName);
    
      echo "<span style='color:green;'>" . "سلام املاک محترم " . $tmpName . "\n" . " خوش آمدید </span>";
      echo "<br><br><span style='font-size:12pt;text-align:right!important;'>یکی از سرویسهای ارائه شده در جامعه مشاورین، ارسال رایگان درخواستهای مشتریان خرید/اجاره و فروش ملک در محدوده شما، روی تلفن همراه شماست. ";
      echo "<br> ما همچنین مشتریان منطقه شما را بطور رایگان به سمت شما هدایت خواهیم کرد. ";
     // echo "<br><br> جهت آغاز این سرویس ها روی لینک زیر کلیک کرده و سپس  start نمایید:</span>";
    }
  }
?>