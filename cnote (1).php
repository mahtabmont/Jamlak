<?php
 
  require_once 'parameters.php';
  require_once 'dbconnect.php';

  $cperpage = 12;
  ob_start();
  session_start();
  session_unset();
  if (isset($_REQUEST['m'])) {

        $selected_m = $_REQUEST['m'];

    }
    else {

        $selected_m = "none";

    }
   if (isset($_REQUEST['t'])) {

        $selected_t = $_REQUEST['t'];

    }
    else {

        $selected_t = "none";

    }
  
  //1.
  $file=fopen("cnote.log","w");
  fwrite($file, "Salaam , " . date('d-M-Y h:i:s') . "\n");
  
  if(isset($_GET["id"]))//there are two routes for puting an id in the session, one through the GET parameter and one through login
  {
    fwrite($file, "id is set as a GET parameter to: " . $_GET["id"] . "\n");
    $_SESSION['id'] = $_GET["id"];
  }//if 
 
  //2., i.e. this block of code MUST execute after block 1, above
  $curid = "";
  $user ="";
  $mantaghe ="";
  $mantaghe2 ="";
  $mahal ="";
  $mahal2 ="";
  if (!isset($_SESSION['id']))
  {
    fwrite($file, "id is not in the session" . "\n");
    die("کاربر گرامی، " . "\n" . "با سلام و احترام" . "\n" . "لطفاً از طریق لینک ارسالی وارد شوید." . "\n");
  }
  $curid =$_SESSION['id'];
  $query ="SELECT `state`, `city`, `mantaghe` , `mahal`, `mantaghe2` , `mahal2` , `name` FROM `amlakin` WHERE `ID`='" . $_SESSION["id"] . "'";
  $res = $conn->query($query);
  if ($res->num_rows ===0)
  {
    fwrite($file, "sql: " . $sql . " returned empty, so dying" . "\n");
    echo "کاربر گرامی، " . "\n" . "با سلام و احترام" . "\n" . "متاسفانه ظاهراً مشکلی پیش آمده که بزودی بررسی میگردد. با عذرخواهی فراوان." . "\n";
  }
  $row = $res->fetch_assoc();  
  if( mysqli_num_rows($res) === 1)
  {
    $user = trim($row["name"]);
    $city = cleanup($row["city"]);
    $origmantaghe = $row["mantaghe"];
    $mantaghe = cleanup($row["mantaghe"]);
    $mantaghe2 = cleanup($row["mantaghe2"]);   
    $mahal = str_replace('_', '-', cleanup($row["mahal"]));//for now; to tidy up soon en shaa Allah 
    $mahal2 = cleanup($row["mahal2"]);
    if ( !isset($_SESSION['state']))
      $_SESSION["state"] = trim($row["state"]) ;    
  }
  //what about else: to make robust soon en shaa Allah
  $state = $_SESSION["state"];


  //3., this code also assumes ID in the session, if exists
  include_once("myfunctions.php");
  inc_visits("cnote");

//So, at the end, here, the following are determined: 
//curid
//user (to display in the page)
//mahal/mahal2 and state (state is retrieved directly from the session and independently from the user) --> mahal/mahal2 and state are used to filter the records
?>


<!DOCTYPE html>
<html>
<head>

        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <meta name="description" content="Jamea Moshaverin Amlak" />
        <meta name="keywords" content="خرید, آپارتمان, بنگاه, پیش فروش, مسکن, ملک, املاک, فروش,ویلایی,jamlak" />
        <meta name="author" content="jamlak company" /-->
	    <title>جامعه مشاورین و همیاران املاک</title><!--ان شاءالله در نهایت از یک جدول و بر اساس استان / شهر انتخاب شده بیاید-->
        <link rel="shortcut icon" href="../favicon.ico"> 
	    <link rel="stylesheet" type="text/css" href="css/demo.css" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
	    <link rel="stylesheet" type="text/css" href="css/animate-custom.css" />
	  
 
<link rel="stylesheet" href="bootstrap-3.3.7-dist/css/bootstrap.min.css">
<link rel="stylesheet" href="bootstrap-select-1.12.2/dist/css/bootstrap-select.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
  <script src="bootstrap-select-1.12.2/dist/js/bootstrap-select.js"></script>
   <script src="bootstrap-select-1.12.2/dist/js/bootstrap-select.min.js"></script>
        <link rel="stylesheet" href="bootstrap-rtl.min.css">
        <script src="bootstrap-filestyle-1.2.1/src/bootstrap-filestyle.min.js"> </script>
        <script>
  $(document).ready(function () {
    $('.selectpicker').selectpicker();
  });
</script>
    <style>
    .bootstrap-select.btn-group:not(.input-group-btn), .bootstrap-select.btn-group[class*="col-"] {margin-left:10%;}
#counter{border: 2px solid #fff; color:#fff; text-align:center; padding:4px; width:150px; background:#176173; font:normal 12px tahoma, verdana,        Arial; }

    #counter span {font-weight:bold;}
    .navbar-header{font-family:tahoma; font-weight:bold; font-size:10pt;}
    .container > .sidebar {
         position: absolute;
   
     }
    #grid_title{font-family: "Times New Roman", Georgia, Serif; font-size:16pt!important;color:#4286f4!important;font-weight:bold; }
    #advertis{margin:0px;padding:0px;height:700px;}
    .nav-header,.nav-header:hover{background-color:rgb(111, 84, 153);color:lightyellow; font-weight:bold; font-size:14pt;}
    li img {width:180px;; height:auto;}
    li,li:hover {font-size:12pt;}
    li a {background-color: #fff;text-align:left;}
    li a:hover{color:rgb(153, 51, 102);background-color: #fff!important;}
    .btn{font-weight:bold;}
    .adver{ padding-top:50px;}
    .active, .active:hover {background-color: #fff;font-size:12pt; color:rgb(153, 51, 102);}
    .add-on .input-group-btn > .btn {
      border-left-width:0;left:-2px;
      -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
      box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    }
    .glinkclass:hover{background-color:inherit!important;}
    .add-on .form-control:focus {
      box-shadow:none;
      -webkit-box-shadow:none; 
      border-color:#cccccc; 
    }
    .form-control{width:50%;}
    
    body,html {background: #fff url(bg.jpg) repeat top left; height:100%; width:100%;margin:0px;padding:0px;}
    
    h5 { font-size: 18px; }
    .c_img{
      width:60px;
      height:60px;
    }
        
    #hidden{display:none;}
    .my_well,.well{
       background-color:#fff;
       border-width:1px;
       border-style:solid;
       border-bottom-color:#aaa;
       border-right-color:#aaa;
       border-top-color:#ddd;
       border-left-color:#ddd;
       border-radius:3px;
       -moz-border-radius:3px;
       -webkit-border-radius:3px;
    }
    .float_R{
       float:right;
    }
	.normal{font-size:10px!important; display: inline-block; width:20%!important;}
    .my_well_inside{text-align:right;}
    .my_well_inside h4 span{
    font-size:12pt!important;
    font-weight:bold;
    display: inline-block;
    width: 60%;height:1.1em;
    overflow: hidden;
    word-wrap: break-word; 
    text-overflow: ellipsis;}
    

    .add_to{text-align:left;}
    .list-group-item span.badge {
        position:relative;
        top: 0px;
        right:3%;
    }
    a span.badge {
        position:relative;
        top: 0px;
        right:80%;
    }
    /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
    
    #title h3{margin:30px 0px 30px 0px!important;}
    /* Set gray background color and 100% height */
    .sidenav {
      padding-top: 20px;
      background-color: #f1f1f1;
      height: 100%;
    }
    
    .navbar-brand { position: relative; z-index: 2; }
    .btn.btn-circle { border-radius: 50px; }
    .btn.btn-outline { background-color: transparent; }
    .navbar-nav .btn { position: relative; z-index: 2; padding: 4px 10px; margin: 8px auto; transition: transform 0.3s; }
    .navbar-nav .btn:hover {
    background-color: rgb(111, 84, 153)!important;
    border-color: rgb(111, 84, 153);
    color: rgb(255, 255, 255);
    }

     @media screen and (max-width: 767px) {
    
    .navbar .navbar-collapse > li:last-child { padding-left: 15px; padding-right: 15px; } 
    
    .navbar .nav-collapse { margin: 7.5px auto; padding: 0; }
    .navbar .nav-collapse .navbar-form { margin: 0; }
    .nav-collapse>li { float: none; }
      
    .navbar.navbar-default .nav-collapse,
    .navbar.navbar-inverse .nav-collapse {
        transform: translate(-100%,0px);
    }
    .navbar.navbar-default .nav-collapse.in,
    .navbar.navbar-inverse .nav-collapse.in {
        transform: translate(0px,0px);
    }
    
    .navbar.navbar-default .nav-collapse.slide-down,
    .navbar.navbar-inverse .nav-collapse.slide-down {
        transform: translate(0px,-100%);
    }
    .navbar.navbar-default .nav-collapse.in.slide-down,
    .navbar.navbar-inverse .nav-collapse.in.slide-down {
        transform: translate(0px,0px);
    }
   
    }
    .well{overflow:none;background-color:#D7E3FD;}
.blink {
           color:lightyellow;
           animation: blink-animation 1s steps(5, start) infinite;
           -webkit-animation: blink-animation 1s steps(5, start) infinite;
    }
    @keyframes blink-animation {
       to {
        visibility: hidden;
       }
    }
    @-webkit-keyframes blink-animation {
       to {
         visibility: hidden;
       }
    }
    .form-control{padding-bottom:10px!important;}
    fieldset.fsStyle {
font-family: Verdana, Arial, sans-serif;
font-size: small;
font-weight: normal;
border: 1px solid #999999;
max-width:500px;
padding: 4px;
margin: 0px 10% 0px 30%;
}
legend.legendStyle {
font-size: 90%;
color: #888888;
background-color: transparent;
font-weight: bold;
}

legend {
width: auto;
border-bottom: 0px;
}

  </style>
</head>
<body>

    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <!-- div class="row"><div class="col-sm-3 pull-left"><img src="logo_<?php echo $state;?>.png" class="img-rounded"></div></div -->
        <div class="row"><div class="col-sm-4 col-xs-13 pull-left"><img id="mainlogoid" src="hamyaran.png" class="img-rounded"></div></div>
           <div class="row">
               <div class="col-sm-12">

                  <div class="nav navbar-nav">

                     <a class="btn btn-default btn-outline btn-circle"  id="myeditlink" <?php if (strlen(trim($curid))>0) echo "href='#'  onclick='doedit(".$curid.")'";?> data-toggle="modal">ویرایش</a>
                     <a class="btn btn-default btn-outline btn-circle"  data-toggle="modal" href="#nav-collapse2" >افزودن مشاور</a>
                     <a class="btn btn-default btn-outline btn-circle"  data-toggle="modal" href="#nav-contactus" >تماس و پشتیبانی</a>

                 </div><!--nav navbar-->
              </div><!--col-sm-12-->
           </div><!-- row -->
       <div class="row">
           
           <div id="counter" class="col-sm-12 pull-left">بازدید امروز <br /><span><?php $sql="select count(*) as n from `visits` where `dmy`='".date('d-M-Y')."'";
         fwrite($file, $sql . "\n");
         $result = $conn->query($sql);  $row = $result->fetch_assoc(); echo $row['n'];?>  نفر  </span></div></div>
        <div class="row">
           <div class="col-sm-12">
		  <div class="navbar-header pull-left" id="currentuser">
		     <?php echo " کاربر فعلی :".$user;?>
                  </div>
                  
           </div>
        </div>


      </div><!-- /.container -->
    </nav><!-- /.navbar --> 
   
 
    <div class="container-fluid text-center"> 
          <div class="row content">  
           <div class="col-sm-10">
<!-----------------   main grid   --------------->
       <div id="main_grid_id">
         <?php 



    //to further optimize soon en shaaAllah:
$needs_footer = 1;
if(strlen($mantaghe) > 0)
{
  $tmpSinceSec = time()-86400; //floor(time()/86400)*86400 - 86400;
  if (isset($_GET['t']))
  {
    $tmpSinceSec = time()-86400*$_GET['t'];
    //fwrite($file, "offset is is " . 86400*$_GET['t'] . ", and tmpSinceSec is " . $tmpSinceSec . "\n");
  }

      //$tmpmantaghe = "";
      //if(strlen($mantaghe) > 0)
        $tmpmantaghe = $mantaghe;
      //else
        //$tmpmantaghe = $mantaghe2;

       $sql = "select * from `cnotes` where ";


       $where_clause = " (" . sqlcleanup("`state`") . "='" . cleanup($state) . "') AND (`mantaghe2`<>'') AND (`time`-`howmanysecago` >= " . 
                       $tmpSinceSec . ") ";

       $tmpp =$state . '  ' . $city . '  ' . $mantaghe . '  ';// increase to 3 when city in cnotes used later en shaa Allah
       $tmpt = "CONCAT('%',  " . sqlcleanup("`mantaghe2`") ; //note the blanks, shokr
       $tmpt2 = $tmpt;

       $tmpm="";
       if(isset($_GET["m"]))
         $tmpm = $_GET["m"];
       if( (strcmp($mahal,'همه')!=0) && (strcmp($tmpm,'همه')!=0) )
       {
         $tmpp .=$state . '  ' . $city . '  ' . $mantaghe . '  ';
         $tmpt .= ", '%-%', " . sqlcleanup("`mahal2`") . " , '%-%')" ;//note the blanks, shokr
         $tmpt2.= ", '%-', " . sqlcleanup("`mahal2`") . " , '-%')" ;//ma shaa Allah

         for($k=0; $k<1; $k++)
         {
           if($k==0)$mahalk=$mahal;
           if($k==1)
           {
             if(strlen($mahal)==0)
               $mahalk=$mahal2;
             else
               continue;//here acts as break indeed
           }
         
           $tmpp .= '-' . $mahalk . '-' ;
         
           if( strcmp($tmpmantaghe, "تهران")==0 ) 
           {
             if( (strpos("dummy" . $mahalk, "منطقه")>0) && (strlen($mahalk)<strlen("منطقه ۱۱۱")) ) //keep an eye in this for now, to improve soon en shaa Allah
             {
              $sql_itsmahal = "select `mahal` from `t22mm` where replace(`mantaghe`, ' ','') = '" . $mahalk . "'";
              fwrite($file, "sql_itsmahal is:" . $sql_itsmahal . "\n");
              $result_itsmahal = $conn->query($sql_itsmahal);
              while($row_itsmahal = $result_itsmahal -> fetch_assoc())
              {
                $itsmahal = cleanup($row_itsmahal["mahal"]);
                $tmpp .= '-' .$itsmahal . '-' ;
              } 
             }
           }
         }//for 

         //$where_clause .= " AND '" . $tmpp . "' LIKE " . $tmpt ;
         $where_clause .= " AND ( ('" . $tmpp . "' LIKE " . $tmpt . " and length(`mahal2`)>4) or ('" . $tmpp . "' LIKE " . $tmpt2 . ") ) ";
       }
       else
       {
         $tmpt .= ", '%')";

         $where_clause .= " AND ( " . sqlcleanup("mantaghe2") . " = '" . cleanup($mantaghe) . "' OR " . sqlcleanup("mahal2") . " = '" . cleanup($mantaghe) . "')  AND '" . $tmpp . "' LIKE " . $tmpt ;
       }





      
      
      $sql .= $where_clause ;
//      $sql .= " order by `time`-`howmanysecago` desc";
      $sql .= " order by `source` asc, `mahal2` desc, `time`-`howmanysecago` desc";



  if (isset($_GET["page"]))
    $page  = $_GET["page"]; 
   else 
    $page=1;     
  
  $start_from = ($page-1) * $cperpage;  
  $sql .= " LIMIT " . $start_from . "," . $cperpage;

  fwrite($file, "\n----------------------------sql is:" . $sql . "\n\n");
     
  $result = $conn->query($sql);
  if ($result->num_rows > 0)
  { 
    if( strcmp($tmpm,'همه')!=0 )
    {
      $tmptitle = "برخی آگهی های اخیر در مناطق کاری شما  ";
      echo '<h3 id="grid_title" class="text-center" style="margin:30px 0px 30px 0px!important;color:#1f004d;">' . $tmptitle  . '</h3>' ;
      echo '<h2 id="grid_title" class="text-center" style="margin:30px 0px 30px 0px!important;color:#1f004d;">' . "اگر منطقه کاری شما اشتباه است لطفاً آنرا در بخش ویرایش (بالا-سمت راست) بررسی و مناطق مورد پوشش خود را تعیین بفرمایید."  . '</h2>' ;
    }  
    else
    {
      $tmptitle = "برخی آگهی های اخیر در " . $origmantaghe;
      echo '<h3 id="grid_title" class="text-center" style="margin:30px 0px 30px 0px!important;color:#1f004d;">' . $tmptitle  . '</h3>' ;
    }

    while($row = $result->fetch_assoc())
    { 
    ?>
    
    <!--div class="col-xs-12 col-sm-6 col-md-4 col-lg-4"-->
      <div class="col-sm-4">       
        <div class="my_well">
          <div class="float_R" style="margin: 0px 8px 8px 10px ">
            <a>

              <!--img class="c_img img-circle" src= <?php if ((trim($row["image"])!=="") && ((file_exists('cimg/'.$row["image"])))) echo '"cimg/' . $row["image"].'"'; else echo '"img/noimagegreen.jpg"'?> alt=<?php echo '"' . $row["image"] . '"'; ?>-->

              <img class="c_img img-circle" src= <?php if (trim($row["imagelink"])!=="") echo '"' . $row["imagelink"] . '"'; else echo '"img/noimagegreen.jpg"'?> >

            </a> 
          </div>        
        <div class="my_well_inside">          
          <h4>
            <span class="myalign" >   <?php echo $row["title"];?>   </span> <br>               
            <span class="myalign" >   <?php echo $row["cat"];?>   </span> <br>               
            <span class="myalign" >   <?php echo $row["mantaghe2"]; if(strlen($row["mahal2"])>0) echo '، ' . $row["mahal2"];?>   </span> <br>               
          </h4>

          <!--span class="myalign" >   <?php echo $row["meter"] . " متر " . $row["room"] . " خوابه " ;?>  </span> <br-->               

          <?php if(strcmp($row["cat"],"فروش")==0){?>
            <?php if(strcmp($row["tp_rahn"],"")!=0){?> 
              <span class="myalign" > <?php echo " قیمت کل: " . $row["tp_rahn"] ;?>  </span> <br> <?php } ?>
            <?php if(strcmp($row["ppm_ejare"],"")!=0){?> 
              <span class="myalign" > <?php echo " هر متر: " . $row["ppm_ejare"] ;?>  </span> <br> <?php } ?>

          <?php } ?>               

          <?php if(strcmp($row["cat"],"رهن و اجاره")==0){?>
            <?php if(strcmp($row["tp_rahn"],"")!=0)?> 
              <span class="myalign" > <?php echo " رهن: " . $row["tp_rahn"] ;?>  </span> <br> 
            <?php if(strcmp($row["ppm_ejare"],"")!=0)?> 
              <span class="myalign" > <?php echo " اجاره: " . $row["ppm_ejare"] ;?>  </span> <br> 

          <?php } ?>               

                
          <a class="link_1" href="<?php echo $row["source"];?>" style="color:#000066"> جزییات و تماس</a> <br>
             
        </div>
            
        
      </div>
    </div>  <!--col-xs-12 col-sm-6 col-md-4 col-lg-4   -->
    <?php
        }
  }  
  else
  {
    $needs_footer =0; //although not needed
    $tmptitle = "لطفاً محدوده کاری خود را در بخش ویرایش (بالا سمت راست)، بررسی و در صورت نیاز تصحیح بفرمایید. ";
    echo '<h3 id="grid_title" class="text-center" style="margin:30px 0px 30px 0px!important;color:#1f004d;">' . $tmptitle  . '</h3>' ;
  }

}
else
{
    $needs_footer=0;
    $tmptitle = "لطفاً محدوده کاری خود را در بخش ویرایش (بالا سمت راست)، بررسی و در صورت نیاز تصحیح بفرمایید. ";
    echo '<h3 id="grid_title" class="text-center" style="margin:30px 0px 30px 0px!important;color:#1f004d;">' . $tmptitle  . '</h3>' ;
}

?>



  

   





 
   </div> 

   </div><!--col-sm-10 -->
<!--------------  end of main grid- column for advertisement   ------------->
     <div class="col-sm-2">
          <div class="sidebar">
              <div class="well" id="advertis">
                 <ul class="nav">
                     <?php
                     
                     $sql1 = "select * from `advertis` where (`paytype`=2 OR `state`='".$state."' AND `mantaghe`='".$origmantaghe."') AND `paid`=1 AND `checked`=1 AND `date` >= DATE_ADD(NOW(),INTERVAL -8 DAY)"; 
  fwrite($file, "sql1 is " . $sql1 . "\n");
                     $i=0;
                     $result1 = $conn->query($sql1);
				     while($row1 = $result1->fetch_assoc())	{ 
                          $i++;
                          echo '<li class="nav-header text-center"><span class="blink">'.$row1['title'].'</span><br></li>';
                          if (trim($row1['image'])===""){
                             //echo '<li class="text-center" style="color:darkred;margin-right:10px;font-size:16px;font-weight:800;">'.$row1['title'].'</li>';
                             $maxlen=50;
                             //echo '<li><img src="adimg/defaultadv.jpg" class="img-rounded" style="max-width:280px;max-height:120px;"></li>';
                            }
                          else{
                             echo '<li><img src="adimg/'.$row1['image'].'" class="img-rounded" style="max-width:280px;max-height:120px;"></li>';
                             $maxlen=50;
                          }
                          if (mb_strlen($row1['tozihat'])>$maxlen){
                          echo '<li class="text-center" style="color:darkblue;margin-right:10px;font-size:12px;font-weight:500;">'.mb_substr($row1['tozihat'],0,$maxlen).
                          ' ...<a class="glinkclass" id="glink'.$i.'" href="#/" onclick="showhide('.$i.')" style="background:inherit;" >ادامه مطلب </a></li>';
                          }
                          else {
                          echo '<li class="text-center" style="color:darkblue;margin-right:10px;font-size:12px;font-weight:500;">'.$row1['tozihat'].'</li>';
                          echo '<li class="text-center" style="margin:auto;font-size:12px;color:#400000;font-weight:800;">'.$row1['adtel'].'</li>';}
                          echo '<li id="spanhide'.$i.'" style="display:none;font-size:12px;font-weight:500;color:darkred;">'.$row1['tozihat']."<br>".$row1['adtel'].'</li>';
                          
                
                   
        	          }
                    ?>
                    <script>
                    function showhide(i){
                            txt="spanhide"+i;
                            if (document.getElementById(txt).style.display == "inline") 
    	                    {
    	                             document.getElementById(txt).style.display = 'none';
    		                         document.getElementById(txt).style.visibility = 'none';
    	                    }
                         	else 
    	                    {
    	                          	document.getElementById(txt).style.display = 'inline';
    		                        document.getElementById(txt).style.visibility = 'visible';
    	                    }
                    }
                    
                    </script>
                        <li class="nav-header text-center">مکان آگهی شما</li>
		        <li class="active text-center" style="color:#400000;font-weight:bolder;">با توجه به محدود بودن محل تبلیغات سایت فقط تعداد محدودی از عزیزان می توانند از این مکان استفاده نمایند
			</li>
                  </ul> 
                   <div class="adver text-center">
                    <button class="btn btn-primary btn-md" data-toggle="modal" data-target="#adverModal">ثبت آگهی</button> 
                  </div>
		</div>
	    </div>            
      </div>
<!-------    adver modal  ------------------------------------------------------------------------------------------------------------------------------>
<div class="modal fade small" id="adverModal">
    <div class="modal-dialog" id="myadver_demo">
              
	<div class="modal-content">
            <div id="addvetising" class="animate form">
	          <form role="form" method="post" enctype="multipart/form-data" id="adformid" action="https://jamlak.ir/save_adver.php">
<div class="panel panel-primary text-right"><h5 class="panel-heading">  کاربر گرامی  هزینه چاپ آگهی شما در منطقه/شهر محل ملک برای مدت یک هفته، هزار تومان می باشد. لطفا اطلاعات زیر را تکمیل  و سپس گزینه پرداخت را انتخاب بفرمایید.</h5></div>
                        <input type="hidden" name="state" value="<?php echo $state;?>" />			
			<input type="hidden" name="from" value="1" /><!--  1 means cnote, 2 means index -->			
		   <p>
			    <label class="col-sm-6" style="font-size:10px!important;"><input type="radio" name="paytype" value="1" checked/>نمایش  بمدت یک هفته  در یک شهر( هزینه 1000 تومان)</label>
			
			    <label  class="col-sm-6" style="font-size:10px!important;"><input type="radio" name="paytype" value="2"/>نمایش بمدت یک هفته در کل کشور( هزینه ده هزارتومان)</label>
			</p>
			<p>
			     <label style="clear:right; padding-top:3%;">عنوان آگهی</label>
                             <input type="text" id="adtitleid" name="adtitle" placeholder="تیتر جهت چاپ در قسمت بالای آگهی" required="required" 
                        oninvalid="this.setCustomValidity('لطفا عنوان را وارد کنید')" oninput="setCustomValidity('')">
			</p>
			<p>
			     <label > شهر</label>
			     <select id="adcityid" name="city" data-live-search="true" > <!---->
		                     <option disabled selected value> انتخاب کنید... </option>
                                <?php
				     $sql1 = "select distinct `mantaghe` from `omm` where `state`='".$_SESSION['state']."' ORDER BY `ID`"; 
                                     $result1 = $conn->query($sql1);
				     while($row1 = $result1->fetch_assoc())	{ 
			         ?>
			     
			             <option> <?php echo $row1["mantaghe"]; ?> </option>
			     <?php
				 } 
			     ?>
                               
                                </select>
			 </p>
			 <!--script>
			       $('#adcityid').change(function() {
			            var city = document.getElementById("adcityid").value; 
                        var fd = new FormData();
                        add=false;
                        fd.append('city',city);
                        fd.append('add',add);
                   
                        $.ajax({
                        type:'POST',
                        url:'getmahal1.php',
                        data:fd,
                        processData: false,
                        contentType: false,
                        success:function(data){
                            $("#admahalid").html(data).selectpicker('refresh');
                        }
                        });
			       });
			 </script-->
             <!--p>
			     <label > منطقه</label>
			     <select class="selectpicker" id="admahalid" name="mahal" title="انتخاب کنید"  style="margin-right:10%!important">
		                        </select>
			     
			 </p-->
  
                 	<p>
			     <label for="adcontactid"> تلفن تماس </label>
			     <input type="text" id="adcontactid" name="adtel" placeholder="تلفن تماس جهت درج در آگهی" required="required" 
                        oninvalid="this.setCustomValidity('لطفا تلفن تماس را وارد کنید')" oninput="setCustomValidity('')" onchange="chktel(2,'adcontactid')">
			</p>
                        <p>
			     <label for="admobileid"> تلفن همراه </label>
			     
			     <input type="text" id="admobileid" name="admobile" placeholder="جهت ارتباط برای تایید آگهی" required="required" 
                        oninvalid="this.setCustomValidity('لطفا تلفن همراه را وارد کنید')" oninput="setCustomValidity('')" onchange="chktel(1,'admobileid')">
			</p>
			<p>
			     <label for="adtozihatid"> متن آگهی </label>
			     <textarea id="adtozihatid" maxlength="250" name="adtozihat" rows="5" placeholder="متن کامل آگهی" required="required" oninvalid="this.setCustomValidity('لطفا متن آگهی را وارد کنید')" oninput="setCustomValidity('')"></textarea>
			</p>
                        <p>
			     <table><tr>
			     <td><input type="file" class="filestyle" id="adimageid"  name="image" data-badge="false" data-input="false" data-buttonText="تصویر" data-buttonName="btn-default" /></td>
			     <td><img class="img-rounded" id="adoutput" /></td>
			     </tr></table>
			</p>
                        <p id="adalert_placeholder"></p>             
                        <p class="login button"> 
			     
			     <input type="reset" value="خروج" style="background-color:#ff704d!important;margin-left:5px;" data-dismiss="modal">
                             <input type="submit"  name="submit" value="پرداخت" />

                    <br/>   <br/>  
  
                  <p>  <script src="https://www.zarinpal.com/webservice/TrustCode" type="text/javascript"></script> </p>

			     
		</p>
		     </form>
                 </div>
            </div>
       </div>
</div>
<script>
      <!------------- upload image for advertisment  --> 
      function chktel(flag,id){
           var mnumber = document.getElementById(id).value;
           if ((flag===1) && (!/^\d{11}$/.test(mnumber))) 
           { 
               alert("موبایل عدد یازده رقمی");
               document.getElementById(id).value = "";
               window.setTimeout(function ()
               {
                   document.getElementById(id).value='';
                   document.getElementById(id).focus();
                    }, 0);
                   return false;
          }
          if ((flag===2) && (!/^[0-9]{7,11}$/.test(mnumber)))
          { 
               alert("شماره تلفن معتبر نیست");
               document.getElementById(id).value = "";
               window.setTimeout(function ()
               {
                   document.getElementById(id).value="";
                   document.getElementById(id).focus();
                    }, 0);
                   return false;
          }
      }
      $(function(){
	   $('#adimageid').change(function(){
		var url = $(this).val();
		var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                if (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg") 
		{
		    var reader = new FileReader();
		    reader.onload = function (e) {
		        $('#adoutput').attr('src', e.target.result);
	            }
		    reader.readAsDataURL(this.files[0]);
		}
		else
		{
		     $('#adoutput').attr('src', 'adimg/noimage.jpg');
		}
	   });
       });
       

</script>
<!---------------------------------->
   
   </div>  <!-- row first-->
   <footer id="footer_id" class="container-fluid text-center" style="z-index:1;height:auto;width:100%;background-color:#1f004d;">
       <?php 
if($needs_footer==1)
{
        $footer_sql = "select count(*) as N from cnotes where " . $where_clause;
        fwrite($file, "footer_sql is:" . $footer_sql . "\n");
     
        $footer_result = $conn->query($footer_sql);
        $footer_row = $footer_result->fetch_assoc();
        $footer_total = $footer_row["N"];
        $footer_url ="?id=" . $_SESSION['id'] ;
        if(isset($_GET["t"])) $footer_url .= "&t=" . $_GET["t"];
        if(isset($_GET["m"])) $footer_url .= "&m=" . $_GET["m"];
        $footer_url .= "&";
        echo mypagination($footer_url, $page, $footer_total, $cperpage ); 
}
       ?>
   </footer>





<script>
function showmahalat(val){
                        var city = val; 
                        var fd = new FormData();
                        add=false;
                        fd.append('city',city);
                        fd.append('add',add);
                   
                        $.ajax({
                        type:'POST',
                        url:'getmahal1.php',
                        data:fd,
                        processData: false,
                        contentType: false,
                        success:function(data){
                            $("#Emahalid").html(data).selectpicker('refresh');
                        }
                        });
} 
function fillmahals(state,city,mahal,id,no){
     var fd = new FormData();
     fd.append('city',city);
     fd.append('state',state);
     $.ajax({
        type:'POST',
        url:'getmahals.php',
        data:fd,
        processData: false,
        contentType: false,
        success:function(data){
             $("#Emahalid").html(data).selectpicker('refresh');
             if (mahal.trim()!==""){
                 arr=mahal.split("-");
                 $('#Emahalid').selectpicker('val', arr);
             }
             /*var mySelect = document.getElementById(id);
             for (var i, j = 0; i = mySelect.options[j]; j++) {       
                if ( i==null) {
                    break;
                }
                if (i.value==mahal) {
                    break;
                }   
             }
      
             if ( i!=null)
                 mySelect.options[j].selected = "selected";    */
       
             
        }
     });
       

}
function doedit(ID)
{ 
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200)
    {  
     
        obj=new Object();
        $('#myModalEdit').modal('show');
        var obj = JSON.parse(xhttp.responseText); 
        document.getElementById("EIDid").value = obj.ID;  
        
        document.getElementById("fromamlakinid").innerHTML = "مناطق فعلی شما: "+obj.mahal ;
        
        document.getElementById("Esenfnoid").value = obj.senfno;
        
        document.getElementById("Enameid").value = obj.name;
        document.getElementById("Etid").value = obj.t;
        document.getElementById("Emid").value = obj.m;
        
        document.getElementById("Eaddrid").value = obj.addr;
        document.getElementById("Ecityid").value = obj.mantaghe;
        
        document.getElementById("Emahalid").value = obj.mahal;
 //document.getElementById("Emahalid_1").value = obj.mahal_1;
// document.getElementById("Emahalid_2").value = obj.mahal_2;
        //document.getElementById("Ehamkaranid").value = obj.hamkaran;
        document.getElementById("Etozihatid").value = obj.tozihat;
        
        if (obj.mantaghe.trim()!=="")
             $('.selectpicker').selectpicker('val', obj.mantaghe);
        
        fillmahals(obj.state,obj.mantaghe,obj.mahal,"Emahalid",1);
        
      
        Eoutput = document.getElementById('Eoutput');
        Eoutput.src = "img/"+obj.image;
        document.getElementById("Eimageid").name=obj.image;
         
    }

  };
  var fd=new FormData();
  fd.append('ID',ID);
  xhttp.open("POST", "get_record.php", true);//to avoid reading old catched data. May shorten Date() late en shaa Allah
  xhttp.send(fd);
}
//------------------------------------


</script>
<!---------------------------     az inja ------------ edit ------------------>
<div class="modal fade" id="myModalEdit">
    <div class="modal-dialog" id="mycontainer_id">
	<div class="modal-content">
	     <div id="editting" class="animate form">
                <a class="pull-right" href="#" data-dismiss="modal">x</a>
        	<form role="form" method="post" enctype="multipart/form-data" id="formedit">
	             <h4>اطلاعات خود را ویرایش و سپس کلید ثبت را فشاردهید</h4>
		     <input type="text" id="EIDid" name="EID" style="display:none">
		     
		     <div class="col-sm-6">
			<label class="normal"> شماره صنفی</label>
			<input type="text"  id="Esenfnoid" name="senfno">
		     </div>
		     <div class="col-sm-6">
			<label class="normal"> نام </label>
			<input type="text"  id="Enameid" name="name">
		     </div>
                     <div class="col-sm-6">
			<label class="normal"> تلفن ثابت </label>
			<input type="text"  id="Etid" name="t" required onchange="chktel(2,'Etid')">
		     </div>
                     <div class="col-sm-6">
			<label class="normal"> تلفن همراه </label>
			<input type="text" id="Emid" name="m" required onchange="chktel(1,'Emid')">
		     </div>
                     <input type="hidden" id="Estateid" name="state" value="<?php echo $_SESSION['state'];?>" />
                     <div class="col-sm-6">
				<label class="normal">شهر</label>
                                <select class="selectpicker Ecities" id="Ecityid" name="city" data-live-search="true" onchange="showmahalat(this.value)"> <!---->
		                     <option disabled selected value> انتخاب کنید... </option>
                                <?php
				     $sql1 = "select distinct `mantaghe` from `omm` where `state`='".$_SESSION['state']."' ORDER BY `ID`"; 
                                     $result1 = $conn->query($sql1);
				     while($row1 = $result1->fetch_assoc())	{ 
			         ?>
			     
			             <option> <?php echo $row1["mantaghe"]; ?> </option>
			     <?php
				 } 
			     ?>
                               
                                </select>
			     </div>
			     <div class="col-sm-6">
				<label class="normal">مناطق</label>
				<!--select class="selectpicker Emahalat" id="Emahalid" name="mahal" data-live-search="true" multiple onchange="doaddoption('Emahalid','Estateid','Ecityid',this.value);"-->
				<select class="Emahalat selectpicker" id="Emahalid" name="mahals[]" size="1" title="انتخاب کنید" multiple onchange="doaddoption('Emahalid','Estateid','Ecityid',this.value);">
		                 <!--option disabled> انتخاب کنید... </option-->
                                </select>
			      </div>
                 
                             
<!--div class="col-sm-6">
				<label class="normal">منطقه دوم</label>
				<select class="selectpicker Emahalat_1" id="Emahalid_1" name="mahal_1" data-live-search="true" onchange="doaddoption('Emahalid_1','Estateid','Ecityid',this.value);">
		                 <option disabled> انتخاب کنید... </option>
                                </select>
			      </div-->
   <div class="col-sm-6 pull-right" id="fromamlakinid" style="color:red;margin-top:5%;line-height:120%;">
    			</div>
   <div class="col-sm-6">
				<label class="normal"> آدرس </label>
				<input type="text" id="Eaddrid" name="addr" required="required" placeholder="آدرس"
                        oninvalid="this.setCustomValidity('لطفا آدرس را وارد کنید')" oninput="setCustomValidity('')">
			     </div>
		
            	        <div class="col-sm-6">
			     <!--label class="normal">سایر مناطق</label-->
			     <input type="text" id="Esayermantagheid" name="tozihat1" placeholder="منطقه..." style="display:none">
			</div>

            	        <div class="col-sm-6">
			     <!--label class="normal">سایر محل ها</label-->
			     <input type="text" id="Esayermahalid" name="tozihat2" placeholder="محل..." style="display:none">
			</div>
            	        <div class="col-sm-6">
			     <label class="normal">توضیحات</label>
			     <input type="text" id="Etozihatid" name="tozihat" placeholder="تخصص ما...">
			</div>


			<div class="col-sm-6">
			     <table><tr>
			     <td><input type="file" class="filestyle" id="Eimageid"  data-badge="false" name="photo" data-input="false" data-buttonText="تصویر" data-buttonName="btn-default" /></td>
			     <td><img class="img-rounded" id="Eoutput" />&nbsp;&nbsp;<a id="delimgEdit" href="#" onclick="dodelimg('Eimageid','Eoutput','EIDid')"><span class="glyphicon glyphicon-trash"></span></a></td>
			     </tr></table>
                        </div>
            
                        <p class="col-sm-6 login button"> 
                             <img src="ajax-loader.gif" id="loading-indicator" style="display:none;">
                             <input type="reset" value="خروج" style="background-color:#ff704d!important;" data-dismiss="modal">
                             <input type="submit" name="submit" value="ثبت" />
			</p> 
                        <!--div class="col-sm-12 col-xs-6 pull-right"><a id="Echangpass" href="#" onclick="$('#chpass_modal').modal('show');"> مایلید رمز خود را تغییر دهید؟</a>			</div--> 
                                  
                </form>
<!------------------------- upload image in Edit modal ------------------------->		
                <script>
                        $(document).ajaxSend(function(event,request,settings){
                             $('#loading-indicator').show();
                        });
                        $(document).ajaxComplete(function(event,request,settings){
                             $('#loading-indicator').hide();
                        });
                        $(document).ajaxSend(function(event,request,settings){
                             $('#loading-indicator1').show();
                        });
                        $(document).ajaxComplete(function(event,request,settings){
                             $('#loading-indicator1').hide();
                        });
                        function doaddoption(id,id1,id2,val){
                             var option = document.createElement("option");
                             //commented by rasoul on 2 Khordad document.getElementById("fromamlakinid").innerHTML="منطقه فعلی شما: "+val;
                             var select = document.getElementById(id);
                             var DropdownValue =val;
                             //x=document.getElementById(id);
                             if (DropdownValue=='سایر'){
                                var OtherData=prompt("نام مناطق را با خط فاصله وارد فرمایید مثلاً آزادی-اکباتان:");
                                if (OtherData) {
                                  option.text = OtherData;
                                  option.value = OtherData;                                  
                                  option.setAttribute('selected', true);
                                  select.appendChild(option);
  		             
                                  //x.append("<option value='"+OtherData+"' selected>"+OtherData+"</option>");
                                  var xhttp = new XMLHttpRequest();
                                  xhttp.onreadystatechange = function() {
                                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                                                           
                                    }  
                                  };
                                  city=document.getElementById(id2).value;
                                  ostan=document.getElementById(id1);
                                  var url = "addmahal.php?mahal=" + OtherData+"&city="+city+"&ostan="+ostan;
                                  xhttp.open("GET", url, true);
                                  xhttp.send();
                               }
                            }
                        }
                        function dodelimg(image,output,Eid){
                            document.getElementById(output).src="";
                            document.getElementById(image).files="";
                            if (Eid!==''){
                              var ID=document.getElementById(Eid).value;
                              var fd = new FormData();
                              fd.append('ID',ID);
                              $.ajax({
				type: "POST",
				url: "delimg.php",
                                data:fd,
				processData: false,
                                contentType: false,
				success: function(data)
				{                                     
				}
			      });
                            }

                        }
			$(function(){
			    $('#Eimageid').change(function(){
			       var url = $(this).val();
			       var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
			       if (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg") 
			       {
				   var reader = new FileReader();
				   reader.onload = function (e) {
					$('#Eoutput').attr('src', e.target.result);
				   }
				   reader.readAsDataURL(this.files[0]);
				}
				else
				{
				   $('#Eoutput').attr('src', 'img/noimagegreen.jpg');
				}
			     });
			});
<!------------------------- save information in edit modal ------------------------->
			$(document).on('submit',"#formedit", function(e) {
                            var url = "save_amlak.php"; 
                            var fd = new FormData(this);
                            var ID=document.getElementById("EIDid").value;
                            fd.append('ID',ID);
                            var file = document.getElementById('Eimageid').files[0];
                            if (file) {   
                               fd.append('photo', file);
                            }
                            $.ajax({
				type: "POST",
				url: url,
                                data:fd,
				processData: false,
                                contentType: false,
				success: function(data)
				{
				     alert ("اطلاعات شما با موفقیت ثبت گردید.");
                                     //$('#myModalEdit').modal('hide');
                                     location.href='cnote.php?id='+ID;
				}
			    });
                            e.preventDefault(); 
			});
		</script>
             </div>        
        </div>      
    </div>
</div>
<!---------------------------       ta inja edit ---------------------------->
<!----------------------- contact modal ---------------------------->

<div class="modal fade" id="nav-contactus" role="dialog">
    <div class="modal-dialog" id="contactdemo_id">
    	<div class="modal-content" id="modal-contactus">
            <div id="contactusid" class="animate form">
	      <a class="pull-right" href="#" data-dismiss="modal">x</a>	
              <form  method="post" id="contactus" autocomplete="on">                
		<h1>تماس با ما</h1> 
                <input name="id" type="hidden" id="msgid" value='<?php echo $_SESSION["id"];?>'/>
                <p>
		   <label class="col-md-3 control-label" for="message">متن پیام</label>
		   <textarea class="form-control" maxlength="250" id="msgcontactid" name="message" placeholder="لطفاً هر گونه پیشنهاد، گزارش مشکل، و پیام خود را اینجا وارد نمایید." rows="5"></textarea>
		</p>
	   </form>
               <p class="login button">
                   
                   
                   <input type="reset" value="خروج" style="background-color:#ff704d!important;margin-left:5px;" data-dismiss="modal" >
                   <input type="submit" value="ارسال" onclick="dosendmsg();" /> 
		</p>
           </div>
        </div>
    </div>
</div>
<script>
function dosendmsg(){
      //mobile=document.getElementById("mobileno").value;
      id=document.getElementById("msgid").value;
      message=document.getElementById("msgcontactid").value;
          var xhttp = new XMLHttpRequest();
          fd=new FormData();
          
          fd.append('id',id);
          fd.append('message',message);
          xhttp.onreadystatechange = function() {
             if (xhttp.readyState == 4 && xhttp.status == 200) {
               if (xhttp.responseText){
                    alert("با تشکر، پیام شما با موفقیت ثبت شد و در اسرع وقت مورد رسیدگی قرار میگردد.");
               }  
               else
                    alert("خطا در ثبت پیام!!!");               
            }
          };
          var url = "contactus1.php"; 
          xhttp.open("POST", url, true);
          xhttp.send(fd);
       
}
</script>
<!------------------------ Contact Us Modal --------------->
<!------------------------------  insert modal ---------------->
<!---------------------insert modal - to add new profile-------------------------------------------->
      <div class="modal fade small" id="nav-collapse2">
           <div class="modal-dialog" id="mycontainer_demo">
	       <div class="modal-content">
	            <div id="adding" class="animate form">
                        <a class="pull-right" href="#" data-dismiss="modal">x</a>
          		<form role="form" method="post" enctype="multipart/form-data" id="formid">
                     			    
                             <h4>ورود اطلاعات مشاور</h4>
			     <div class="col-sm-6">
				<label class="normal"> نام مشاور </label>
				<input type="text" id="nameid" name="name" placeholder="نام مشاور "
                        oninvalid="this.setCustomValidity('لطفا نام مشاور را وارد کنید')" oninput="setCustomValidity('')">
                </div>
                             
			     <div class="col-sm-6">
				<label class="normal"> تلفن همراه </label>
				
				<input type="text" id="mid" name="m" required="required" placeholder="شماره موبایل"
                        oninvalid="this.setCustomValidity('لطفا شماره موبایل را وارد کنید')" oninput="setCustomValidity('')" onchange="chktel(1,'mid')">
                                                        
                        
			     </div>
                             
                             <div class="col-sm-6">
				<label class="normal">استان</label>
                                <select class="selectpicker states" id="stateid" name="state" data-live-search="true" required ><!---->
				     <option disable value=""> انتخاب کنید... </option>
				     <?php
					for ($i=0;$i<31;$i++){ 
			             ?>
			     
			            <option><?php echo $ostanha[$i]; ?> </option>
			        <?php
				  } 
			        ?>
			       </select>
                            
       </div>
       
                             <div class="col-sm-6">
				<label class="normal"> شهر </label>
				<select class="cities" id="cityid" name="city" data-live-search="true" required>
		                 <option disabled> انتخاب کنید... </option>
                                </select>
			      </div>
   
			     
                  <script>
                  $('.states').change(function() {
                        var state = $(this).val(); 
                        var fd = new FormData();
                        fd.append('state',state);
                        
                        $.ajax({
                        type:'POST',
                        url:'getcities.php',
                        data:fd,
                        processData: false,
                        contentType: false,
                        success:function(data){
                           $(".cities").html(data).selectpicker('refresh');
                       
                        }
                        });
                  });
                 
                  </script>
		 
                             <div class="col-sm-6">
				<label class="normal">مناطق</label>
				<select class="mahalat" id="mahaleid" name="mahals[]" size="1" title="انتخاب کنید" multiple onchange="doaddoption('mahaleid','stateid','cityid',this.value);" required>
		                 <!--option disabled> انتخاب کنید... </option-->
                                </select>


			      </div>
<script>
 $('.cities').change(function() {
                        var city = $(this).val(); 
                        var fd = new FormData();
                        state= document.getElementById("stateid").value;
                        fd.append('state',state);
                        fd.append('city',city);
                        $.ajax({
                        type:'POST',
                        url:'getmahals.php',
                        data:fd,
                        processData: false,
                        contentType: false,
                        success:function(data){
                          
                          $(".mahalat").html(data).selectpicker('refresh');
                        }
                        });
                  });
</script>   

                              <p class="col-sm-6 login button"> 
                                 <img src="ajax-loader.gif" id="loading-indicator1" style="display:none;">
				 <input type="reset" value="خروج" style="background-color:#ff704d!important;" data-dismiss="modal">
                                 <input type="submit"  name="submit" value="ثبت" />
				 
			      </p>
			</form>
			<script>
                             $(document).on('submit',"#formid", function(e) {
                                var url = "save_amlak.php"; 
                                var fd = new FormData(this);
                                var mobile = document.getElementById('mid').value.trim();
                                var state = document.getElementById('stateid').value.trim();                     
                                var city = document.getElementById('cityid').value.trim();
                                var mahal = document.getElementById('mahaleid').value.trim();
                                if ((mobile!=='') && (state!=='') && (city!='') && (mahal!='')){
                                   $.ajax({
				     type: "POST",
				     url: url,
                                     data:fd,
				     processData: false,
                                     contentType: false,
				     success: function(data)
				     {
					alert ("اطلاعات شما با موفقیت ثبت گردید.\n جهت دریافت اطلاعات منطقه درخواستی، لطفا لینکی که از طریق تلگرام برای شما ارسال می شود را به  گوشی مشاوری که افزوده اید، فروارد نمایید.  لطفا از ایشان بخواهید لینک فوروارد شده را استارت نمایند.");
					location.reload();
				     }
				   });
                                   e.preventDefault(); 
                                }
                                else alert ("لطفا اطلاعات خواسته شده را تکمیل نمایید");
			      });
		       </script>
		</div>
	</div>
    </div>
</div>
<!-------------------------------------------------------------->
</html>

<?php 
fclose($file); 

//----------------------

function sendtxt($c, $ms)
{
  $url = "https://api.telegram.org/bot254204272:AAGw4J_0T2j4x4iQQvcezVJps-0E0i0veqU/sendMessage";
  $content = array(
    'chat_id' => $c,
    'text' => $ms
  );

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($content));
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

  //receive server response ...
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_exec ($ch);
}
//------------------------------------------------------

function prepareamlakiname($tmpName)
{
        $i = strrpos($tmpName, "املاک و مستغلات"); 
        if($i !== FALSE)
        {
          $tmpName = trim(substr($tmpName, $i+29));
          $j = strpos($tmpName, " ");
          if( ($j !== FALSE) && ($j < 2) )
            $tmpName = trim(substr($tmpName, $j+1));
        }
        else
        {
          $i = strrpos($tmpName, "املاک"); 
          if($i !== FALSE)
          {
            $tmpName = trim(substr($tmpName, $i+10));
            $j = strpos($tmpName, " ");
            if( ($j !== FALSE) && ($j < 2) )
              $tmpName = trim(substr($tmpName, $j+1));
          }
          else
          {
            $i = strrpos($tmpName, "املاك");                                       
            if($i !== FALSE)
            {
              $tmpName = trim(substr($tmpName, $i+10));
              $j = strpos($tmpName, " ");
              if( ($j !== FALSE) && ($j < 2) )
                $tmpName = trim(substr($tmpName, $j+1));
            }
            else
            {
              $i = strrpos($tmpName, "بنگاه معاملاتی");                                       
              if($i !== FALSE)
              {
                $tmpName = trim(substr($tmpName, $i+28));
                $j = strpos($tmpName, " ");
                if( ($j !== FALSE) && ($j < 2) )
                  $tmpName = trim(substr($tmpName, $j+1));
              }
              else
              {
                $i = strrpos($tmpName, "بنگاه معاملات ملکی");                                       
                if($i !== FALSE)
                {
                  $tmpName = trim(substr($tmpName, $i+35));
                  $j = strpos($tmpName, " ");
                  if( ($j !== FALSE) && ($j < 2) )
                    $tmpName = trim(substr($tmpName, $j+1));
                }
              }
            }
          }
        }
  return $tmpName;
}//prepareamlakiname
//------------------------------------------------------

function cleanup($s)
{
  //arabic and half space are supposed to be done before inserting the data into the DB, hence commented out here
  //also, numbers are assumed done before inserting the database and not included here
  /*$s = str_replace('ك', 'ک', $s );
  $s = str_replace('ئ', 'ی', $s );     
  $s = str_replace('ي', 'ی', $s );     
  $s = str_replace('‌', ' ', $s );*/  

  $s = str_replace('آ', 'ا', $s );     
  $s = str_replace('میدان', '', $s );     
  $s = str_replace('خیابان', '', $s );     
  $s = str_replace('فلکه', '', $s );     
  $s = str_replace('بلوار', '', $s );     
  $s = str_replace('چهارراه', '', $s );     
  $s = str_replace('چهار راه', '', $s );     
  $s = str_replace('شهید', '', $s );     
    
  $s = str_replace(' ', '', $s );//this is the last thing to do after the others
  return $s;
}
//-----------------------

function sqlcleanup($s)
{
  //arabic and half space are supposed to be done before inserting the data into the DB, hence commented out here
  /*return "replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(" . $s . ",'بلوار',''),'فلکه',''),'چهارراه',''),'چهار راه',''),'ي','ی'),'ك','ک'),'آ','ا'),'خیابان',''),'شهید',''),'بلوار',''),'میدان',''), ' ', '')" ;*/
  
  return "replace(replace(replace(replace(replace(replace(replace(replace(replace(" . $s . ",'فلکه',''),'چهار راه',''),'چهارراه',''),'آ','ا'),'خیابان',''),'شهید',''),'بلوار',''),'میدان',''), ' ', '')";
}
//-------------------------------------------------------



?>



<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-88743171-2', 'auto');
  ga('send', 'pageview');

</script>


