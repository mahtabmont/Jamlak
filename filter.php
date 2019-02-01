<?php

$file = fopen("filterLog.txt","w");
fwrite($file, "salaam" . "\n\n");
fwrite($file, "classname is" . $_GET["classname"] . "\n\n");
fwrite($file, "search_text is" . $_GET["search_text"] . "\n\n");

?>




<div id="custom-search-input">
  <div class="input-group col-md-3 col-md-offset-9">
                                <input type="text" id="search_text_id" class="search-query form-control text-right" placeholder="جستجو" />
                                <span class="input-group-btn">
                                    <button id="search_button_id" onclick="search()" class="btn btn-danger" type="button">
                                        <span class=" glyphicon glyphicon-search"></span>
                                    </button>
                                </span>
  </div>
</div> 
     <h3 id="title" class="text-center">
         <?php
           if(isset($_GET["classname"]))
             echo "کانالهای تلگرام: " . $_GET["classname"]; //assumed either filter by class or search or all only
           else
           {
             if(isset($_GET["search_text"]))
               echo " کانالهای تلگرام: نتایج جستجو برای " . $_GET["search_text"];
             else
             {
               echo "کانالهای تلگرام: همه";
             }
           }
          ?>
     </h3>


<?php
$servername = "localhost";
$username = "kodoomco_user";
$password = "ras1350";
$dbname = "kodoomco_besm";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
 if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$conn->set_charset("utf8");
header("content-type:text/html;charset=UTF-8");

$sql = "select `chid`, `name`, `class`, `image`, `desc` from `main`";
if(isset($_GET["classname"]))
   $sql .= " where `class`='" . $_GET["classname"] . "'"; //assumed either filter by class or search or all only
else
{
  if(isset($_GET["search_text"]))
  {
    $search_text= $_GET["search_text"];
  }
    $sql .= " where `chid` like '%" . $search_text. "%' OR `name` like '%" . $search_text. "%' OR `class` like '%" . $search_text. "%' OR `desc` like '%" . $search_text. "%'" ;
}
  
$limit = 30;  
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
$start_from = ($page-1) * $limit;  

$sql .= " ORDER BY ID ASC LIMIT $start_from, $limit" ;

fwrite($file, "sql is:" . $sql);

$result = $conn->query($sql);

fwrite($file, "number of recirds read is:" . $result->num_rows);

if ($result->num_rows > 0) {
    // output data of each row
    //echo $result->num_rows ;   NOTE: ANY ECHO PUT IT IN NOT WORKING!
    while($row = $result->fetch_assoc()) {
?>


<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <div class="my_well">
        
        <div class="float_R" style="margin: 8px 8px 8px 10px ">
            <a href=""><img class="c_img img-circle" src= <?php echo '"http://cafebots.ir/img/' . $row["image"] . '"'; ?>" alt=<?php echo '"' . $row["title"] . '"'; ?>></a>
        </div>
        
        <div class="my_well_inside">          
        <h4>
            <a class="link_1" href="">
                <?php echo $row["name"]; ?>
            </a>
        </h4>
        <span> <?php echo $row["class"]; ?> </span>    
        <br>
        <span> <?php echo $row["desc"]; ?> </span>
        <br>            
        <div class="add_to">
		<button type="button" class="btn btn-info" onclick= "openWin(<?php echo "'https://telegram.me/" . $row["chid"] . "'" ?>);" ><i class="glyphicon glyphicon-send myicon2"></i> افزودن به کانالهای خود</button>
           <!--button></button-->       
	   </div>
       </div>
       
        
        </div>
    </div>



<?php
  }
}  
else
{

}
$conn->close();
?>

 
    

<?php
fclose($file);
?>
