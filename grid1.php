<?php
  require_once 'dbconnect.php';
  require_once 'parameters.php';

  $logfile = fopen("grid.log","w");
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

  if(strlen(trim($tmpsearch_text))>0)
    echo '<h3 id="grid_title" class="text-center" style="margin:30px 0px 30px 0px!important;color:#1f004d;">' . ' (' . $tmpsearch_text . ')' . '</h3>' ;
  else
    //echo '<h3 id="grid_title" class="text-center" style="margin:30px 0px 30px 0px!important;color:#1f004d;">' . ' ( همه )' . '</h3>' ;
    echo '<br>';


  $sql = "select * from `amlakin`";
  if(strcmp($where_cond, "")!=0)
    $sql .= " where " . $where_cond ;  
    
  //just in the very special case when ID is in the GET, find the user's page number
  if(isset($_GET["id"]))
  {
    $result = $conn->query($sql);
    $tmpid = $_GET["id"];
    $flgFound = false; $tmpno = 0;
    while( !$flgFound && ($row = $result->fetch_assoc()) )
    {
      $tmpno++;
      if($row["ID"]==$tmpid)
        $flgFound = true;      
    }
    if($flgFound)
      $page = ceil($tmpno / $per_page);
    else
    {
      fwrite($logfile, "Error: the record for the given id was NOT found!!!!" . $sql);
      $page = 1;
    }
  }
  else
  {
    if (isset($_GET["page"]))
      $page  = $_GET["page"]; 
    else 
      $page=1;   
  }
  
  $start_from = ($page-1) * $per_page;  
  $sql .= " LIMIT " . $start_from . "," . $per_page;

  fwrite($logfile, "sql is:" . $sql);
     
  $result = $conn->query($sql);
  if ($result->num_rows > 0)
  { 
    while($row = $result->fetch_assoc())
    { 
    ?>

    
    <!--div class="col-xs-12 col-sm-6 col-md-4 col-lg-4"-->
      <div class="col-sm-4">       
        <div class="my_well">
          <div class="float_R" style="margin: 0px 8px 8px 10px ">
            <a>
              <img class="c_img img-circle" src= <?php if ((trim($row["image"])!=="") && ((file_exists('img/'.$row["image"])))) echo '"img/' . $row["image"].'"'; else echo '"img/noimagegreen.jpg"'?> alt=<?php echo '"' . $row["image"] . '"'; ?>>
            </a> 
          </div>        
        <div class="my_well_inside">          
        <h4>
          <span style='<?php if(isset($_SESSION["id"])){if( strcmp($row["ID"], $_SESSION["id"])==0) echo "pointer-events:none; cursor: default;background-color:LightSkyBlue!important; color: DarkGreen!important"; else echo "pointer-events: none; cursor: default; color:#000066!important;";} else echo "pointer-events: none; cursor: default;color:#000066!important";?>'>
            <?php echo $row["name"]; ?> 
          </span> <br>
          </h4>
          <span class="myalign" id="<?php echo 'mahal'.$row['ID']?>"> <?php if(strcmp($row["mahal"],"")!=0){echo $row["mahal"];} else echo mb_substr($row["addr"],0,20)."...";?></span><br>               
                
                 <!------------ detail part ---------------->
                <div id="<?php echo 'hidden'.$row['ID']?>" style="display:none;"><table><tr><td>
                    <?php if(strcmp($row["addr"],"")!=0){?>       <span class="myalign" id="<?php echo 'addr'.$row['ID']?>">     </span> <br> <?php } ?></td></tr><tr><td> 
                    <?php if(strcmp($row["t"],"")!=0){?>          <span class="myalign" id="<?php echo 't'.$row['ID']?>">        </span> <br> <?php } ?> </td></tr><tr><td>
                    <?php if(strcmp($row["m"],"")!=0){?>          <span class="myalign" id="<?php echo 'm'.$row['ID']?>">        </span> <br> <?php } ?> </td></tr><tr><td>
                    <?php if(strcmp($row["hamkaran"],"")!=0){?>   <span class="myalign" id="<?php echo 'hamkaran'.$row['ID']?>"> </span> <br> <?php } ?> </td></tr><tr><td>
                    <?php if(strcmp($row["tozihat"],"")!=0){?>    <span class="myalign" id="<?php echo 'tozihat'.$row['ID']?>">  </span> <br> <?php } ?> </td></tr></table>
                    
<?php   
      if(isset($_SESSION["id"]))
      {
        if( strcmp($row["ID"], $_SESSION["id"])==0)
        {
        ?>
        <button type="button" class="btn btn-success btn-xs float_R " id="editBtn" onclick="doedit(<?php echo $_SESSION["id"];?>)"><span class="glyphicon glyphicon-edit"></span>&nbsp;ویرایش</button>
      
      <?php
        }
      }
       ?>

                </div>     
             
                <div class="add_to" id="#addto">
                   <button class="btn btn-info btn-xs" onclick="add_details(<?php echo $row['ID'];?>)" id="<?php echo 'detailid'.$row['ID']?>" >نمایش جزییات</button>
                   <!--button type="button" class="btn btn-link btn-xs float_R" id="deleteBtn" onclick="dodelete(<?php echo $row['ID'];?>)"><span class="glyphicon glyphicon-trash"></span>&nbsp;حذف</button>
                   <button type="button" class="btn btn-link btn-xs float_R" id="editBtn" onclick="doedit(<?php echo $row['ID'];?>)"><span class="glyphicon glyphicon-edit"></span>&nbsp;ویرایش</button-->
	            </div>
            </div>
            
        
        </div>
    </div>  <!--col-xs-12 col-sm-6 col-md-4 col-lg-4   -->
    <?php
        }
    }  


  fclose($logfile);
    ?>

	