<?php
require_once 'dbconnect.php';

$sql = "UPDATE `amlakin` set `image`= '' where ID='" . $_POST["ID"] . "'" ;
if ($conn->query($sql) === TRUE)
echo "true";
else
echo "false";
?>