<?php
ob_start();
session_start();
require_once 'parameters.php';
$fp=fopen("setsession.log","w");
unset($_SESSION["state"]);
if (!empty(trim($_GET["ostan"])) && isset($_GET["ostan"])){
    $_SESSION["state"]=trim($_GET["ostan"]);
}
else {
    $_SESSION["state"]=$default_state;
}
echo "logos/".$_SESSION["state"].".png";
fwrite($fp,$_SESSION['state']);
session_write_close();
//header('location:http://www.jamlak.ir/myindex.php');
?>