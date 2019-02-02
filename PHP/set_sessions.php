<?php
  ob_start();
  session_start();
  
  if (isset($_POST['state']) && !empty(trim($_POST["state"])))
       $_SESSION['state']=trim($_POST['state']);
        
  if (isset($_POST['city']) && !empty(trim($_POST["city"])))
      $_SESSION['city']=trim($_POST['city']);
  else
      unset($_SESSION['city']);      

  if (isset($_POST['mahale']) && !empty(trim($_POST["mahale"])))
      $_SESSION['mahale']=trim($_POST['mahale']);
  else
      unset($_SESSION['mahale']);      
  if (isset($_POST['mtype']) && !empty(trim($_POST["mtype"])))
       $_SESSION['mtype']=trim($_POST['mtype']);
  else
       unset($_SESSION['mtype']);
  if (isset($_POST['btype']) && !empty(trim($_POST["btype"])))
      $_SESSION['btype']=trim($_POST['btype']);
  else
       unset($_SESSION['btype']);
  if (isset($_POST['azmetraj']) && !empty(trim($_POST["azmetraj"])))
  
       $_SESSION['azmetraj']=trim($_POST['azmetraj']);
  else
       unset($_SESSION['azmetraj']);
  if (isset($_POST['tametraj']) && !empty(trim($_POST["tametraj"])))
       $_SESSION['tametraj']=trim($_POST['tametraj']);
  else
       unset($_SESSION['tametraj']);
  if (isset($_POST['bed']) && !empty(trim($_POST["bed"])))
      $_SESSION['bed']=trim($_POST['bed']);
  else
       unset($_SESSION['bed']);
  if (isset($_POST['unitp']) &&  !empty(trim($_POST["unitp"])))
       $_SESSION['unitp']=trim($_POST['unitp']);
  else
       unset($_SESSION['unitp']);
  if (isset($_POST['totalp']) && !empty(trim($_POST["totalp"])))
  
       $_SESSION['totalp']=trim($_POST['totalp']);
  else
       unset($_SESSION['totalp']);
?>
