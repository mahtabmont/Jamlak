<?php
 ob_start();
 session_start();
 $file=fopen("logout.log","w");
 fwrite($file, "salam");
 session_destroy();
 unset($_SESSION['id']);
 unset($_SESSION['state']);
 header("Location: index.php");
 ?>
