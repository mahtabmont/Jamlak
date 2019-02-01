<?php
  require_once 'dbconnect.php';
  require_once 'parameters.php';
  
  $logfile = fopen("footer.log","w");
  fwrite($logfile, "Salaam". "\n" );
    
  $state = $_SESSION['state'];
         
  //construct the sql
  $where_cond = " `state`='" . $state . "'"; 

  if(isset($_GET["city"]) && strlen(trim($_GET['city']))>0 )
    $where_cond .= " AND `mantaghe`='" . $_GET['city'] . "'";


  if(isset($_GET["mahal"]))
  {
    if(strcmp($_GET["mahal"], "همه محلها") != 0)
    {
      if(strcmp($where_cond, "")!=0)
        $where_cond .= " AND " ;  
      $where_cond .= " `mahal`='" . $_GET["mahal"] . "'";
    }
  }
 
  $tmpsearch_text='';
  if(isset($_GET["search_text"]))
  {
    if(strlen($_GET["search_text"]) > 0)
    {
      fwrite($logfile, "search_text = " . $_GET["search_text"] . "\n" );
      if(strcmp($where_cond, "")!=0)
        $where_cond .= " AND " ;
    
      $tmpsearch_text .= " ".$_GET["search_text"];
      $where_cond .= " (`name` like '%"      . $tmpsearch_text . "%' OR
                        `addr` like '%"      . $tmpsearch_text . "%' OR
                        `hamkaran` like '%"  . $tmpsearch_text . "%' OR
                        `tozihat` like '%"   . $tmpsearch_text . "%') " ;
    }
  }

  $sql = "select count(*) AS N from `amlakin`";
  if(strcmp($where_cond, "")!=0)
    $sql .= " where " . $where_cond ;  

  $result = $conn->query($sql);

  $row = $result->fetch_assoc();
  $total = $row["N"];

  $url ="?";
  if(isset($_SESSION["state"]) && strlen(trim($_SESSION["state"]))>0 )
    $url .= "state=" . $_SESSION["state"] . "&";

  if(isset($_GET["city"]) && strlen(trim($_GET['city']))>0 )
    $url .= "city=" . $_GET["city"] . "&";

  if(isset($_GET["mahal"]))
    $url .= "mahal=" . $_GET["mahal"] . "&";

  if(isset($_GET["search_text"]))
    $url .= "search_text=" . $_GET["search_text"] . "&";

  include_once("myfunctions.php");
  
  echo mypagination($url, $page, $total, $per_page); 
  fwrite($logfile, "called pagination with url:" . $url . ", page:" . $page . ", total:" . $total . ", per_page:" . $per_page . " \n");
  
  fclose($logfile);
?>
