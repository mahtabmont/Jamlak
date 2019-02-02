<?php
//returns a record of the main table given its ID 
if ( isset( $_POST["ID"] ) )
{
  $ID = $_POST["ID"];
  
  require_once "dbconnect.php";
  $sql = "select * from `enote` where ID=" . $ID ;

  $result = $conn->query($sql);
  if($row = $result->fetch_assoc())
  {
  
    echo json_encode(
    array(
        "ID" => $row["ID"], 
        "amlakid"=>$row["amlakid"],
        "mtype" => $row["mtype"], 
        "beds" => $row["beds"],
        "floor" => $row["floor"], 
        "metraj" => $row["metraj"], 
"btype" => $row["btype"], 
        "bar" => $row["bar"],
        "tfloor" => $row["tfloor"], 
        "tmetraj" => $row["tmetraj"],
        "age" => $row["age"], 
        "moghiat"=>$row["moghiat"],
        "ufloor" => $row["ufloor"], 
          "tozihat" => $row["comment"], 
        "sanad" => $row["sanad"], 
        "takhlie" => $row["takhlie"], 
        "totalp" => $row["totalp"], 
        "unitp" => $row["unitp"], 
        "malek" => $row["malek"], 
        "tamas" => $row["tamas"], 

        "state" => $row["state"], 
        "city" => $row["city"], 
        "mahale" => $row["mahale"], 
        "addr" => $row["addr"], 
        "image1" => $row["image1"], 
        "image2" => $row["image2"],
        "image3" => $row["image3"],
        "kaf" => $row["kaf"],
        "nama" => $row["nama"],
        "divar" => $row["divar"],
        "parking" => $row["parking"],
        "kitchen" => $row["kitchen"],
"facilities" => $row["facilities"],


        )
    );  
    
  }
}
?>
