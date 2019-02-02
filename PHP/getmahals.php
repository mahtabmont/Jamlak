<?php
require_once 'dbconnect.php';
require_once 'parameters.php';
$file = fopen("getmahals.log","w");
if ($_POST['city'])
{
$state=$_POST['state'];
$city=$_POST['city'];
fwrite($file,$city.$state);
$sql1 = "select distinct `mahal` from `omm` where state='" . $state . "' AND mantaghe='".$city."' ORDER BY `mahal`"; 
//to create index for efficiency soon en shaa Allah
$result1 = $conn->query($sql1);
//fwrite($file,$result1);
//echo "<option value=''> انتخاب کنید... </option>";
while($row1 = $result1->fetch_assoc())
{
echo '<option>'.$row1['mahal'].'</option>';
}
echo '<option>سایر</option>';
}
?>
