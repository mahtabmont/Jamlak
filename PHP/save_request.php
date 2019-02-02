<?php
 
 include_once 'dbconnect.php';
 $file=fopen("save_request.log","w");
 $name = trim($_POST['name']);
 $mobile = trim($_POST['mobile']);
 $state = trim($_POST['state']);
 $city = trim($_POST['city']);
 $mahale = trim($_POST['mahale']);
 $btype = trim($_POST['btype']);
 $bed = trim($_POST['bed']);
 $sharh = trim($_POST['sharh']);
 $unitp = trim($_POST['unitp']);
 $totalp = trim($_POST['totalp']);
 $date = date('Y-m-d H:i:s');
 $cus_id = trim($_POST['cus_id']);

 $name   = mysqli_real_escape_string($conn, $name);          
 $mobile   = mysqli_real_escape_string($conn, $mobile);          
 $state   = mysqli_real_escape_string($conn, $state);          
 $city   = mysqli_real_escape_string($conn, $city);          
 $mahale   = mysqli_real_escape_string($conn, $mahale);          
 $btype  = mysqli_real_escape_string($conn, $btype);          
 $bed   = mysqli_real_escape_string($conn, $bed);          
 $unitp   = mysqli_real_escape_string($conn, $unitp);          
 $totalp   = mysqli_real_escape_string($conn, $totalp);          
 $sharh   = mysqli_real_escape_string($conn, $sharh);          
 $date   = mysqli_real_escape_string($conn, $date);          
 $cus_id   = mysqli_real_escape_string($conn, $cus_id);          
 
 $sql = "INSERT INTO `r` (`cus_ID`,`name`, `m`,`mahal`,`state`,`mantaghe`,`type`,`room`,`matn`,`ppm_ejare`,`tp_rahn`,`insertdate`)";
      $sql .= " values ('" . $cus_id . "','". $name . "', '" . $mobile . "','" . $mahale . "','".     $state."','".$city."','".$btype."','".$bed."','".$sharh."','".$unitp."','".$totalp."','". $date."')";  
  fwrite($file,$sql);

      if ($conn->query($sql) === TRUE)
        echo "true";
      else 
        echo "false"; 
  fclose($file);
?>
