<?php
ob_start();
session_start();
require_once 'dbconnect.php';
 require_once 'parameters.php';
if($_POST['state'])
{
$state=$_POST['state'];
$sql1 = "select distinct `mantaghe` from `omm` where state='" . $state . "' ORDER BY `ID`"; 
//to create index for efficiency soon en shaa Allah
$result1 = $conn->query($sql1);
echo "<option value=''> انتخاب کنید... </option>";
while($row1 = $result1->fetch_assoc())
{
echo '<option>'.$row1['mantaghe'].'</option>';
}
}
?>
