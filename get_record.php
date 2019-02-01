<?php
//returns a record of the main table given its ID 
$file=fopen("gerrecord.log","w");
fwrite($file,"salaam");
if ( isset( $_POST["ID"] ) )
{
  $ID = $_POST["ID"];
  fwrite($file,$ID);
  require_once "dbconnect.php";
  $sql = "select * from `amlakin` where ID=" . $ID ;

  $result = $conn->query($sql);
  if($row = $result->fetch_assoc())
  {
    //fwrite($file,"row==".print_r($row,1));
   
    echo json_encode(
    array(
        "state" => $row["state"], 
        "mantaghe" => $row["mantaghe"],
        "senfno" => $row["senfno"], 
        "name" => $row["name"], 
        "t" => $row["t"], 
        "m" => $row["m"], 
        "addr" => $row["addr"], 
        "mahal" => $row["mahal"], 
        "mahal_1" => $row["mahal_1"],
        "mahal_2" => $row["mahal_2"],
        "hamkaran" => $row["hamkaran"], 
        "tozihat" => $row["tozihat"], 
        "image" => $row["image"], 
        "ID" => $row["ID"]
      )
    );  
    
  }
}
?>