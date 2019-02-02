<?php
require_once 'dbconnect.php';
require_once 'parameters.php';
ob_start();
session_start();
$file = fopen("getmahals.log","w");
$state=$_SESSION['state'];
fwrite($file,$_POST['city'].$state.$_POST['add']."ava0le");
$state   = mysqli_real_escape_string($conn, $state);
if (isset($_POST['city']) && !empty($_POST['city'])){
     $city=$_POST['city'];
     $city= mysqli_real_escape_string($conn, $city);
     $sql1 = "select distinct `mahal` from `omm` where state='" . $state . "' AND mantaghe='".$city."' ORDER BY `mahal`"; 
     $result1 = $conn->query($sql1);
     $count = mysqli_num_rows($result1);
     /*if ($_POST['add'] && ($count == 0)){
         $sql = "INSERT INTO `omm` (`state`, `mantaghe`, `mahal`, `sayer`) values('" . $state . "', '" . $city."', '" . $city."', '1')"; 
         $result = $conn->query($sql);
         $sql1 = "select distinct `mahal` from `omm` where state='" . $state . "' AND mantaghe='".$city."' ORDER BY `mahal`"; 
         $result1 = $conn->query($sql1);
         
     } baraye add kardan shahr bood keh dardsar shod */
     
     //echo "<option value=''> انتخاب کنید... </option>";
     while($row1 = $result1->fetch_assoc())
     {
         echo '<option>'.$row1['mahal'].'</option>';
     }
     echo '<option>سایر</option>';
    
}
?>
