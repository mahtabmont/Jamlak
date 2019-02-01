<?php
 
  require_once 'parameters.php';
  require_once 'dbconnect.php';

  $cperpage = 10;
  ob_start();
  session_start();
  session_unset();
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
  $mahal_1 ="";
  $mahal_2 ="";
  $mahal2 ="";
  if (!isset($_SESSION['id']))
  {
    fwrite($file, "id is not in the session" . "\n");
    die("کاربر گرامی، " . "\n" . "با سلام و احترام" . "\n" . "لطفاً از طریق لینک ارسالی وارد شوید." . "\n");
  }
  $curid =$_SESSION['id'];
  $query ="SELECT `state`, `mantaghe` , `mahal`, `mahal_1`, `mahal_2`, `mantaghe2` , `mahal2` , `name` FROM `amlakin` WHERE `ID`='" . $_SESSION["id"] . "'";
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
    $mantaghe = cleanup($row["mantaghe"]);
    $mantaghe2 = cleanup($row["mantaghe2"]);   
    $mahal = cleanup($row["mahal"]);
    $mahal_1 = cleanup($row["mahal_1"]);
    $mahal_2 = cleanup($row["mahal_2"]);
    $mahal2 = cleanup($row["mahal2"]);
    if ( !isset($_SESSION['state']))
      $_SESSION["state"] = trim($row["state"]) ;    
  }
  //what about else: to make robust soon en shaa Allah
  $state = $_SESSION["state"];


  //3., this code also assumes ID in the session, if exists
  include_once("myfunctions.php");
  inc_visits();// en shaa Allah, soon, to log where users visit

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
	    <title>جامعه مشاورین املاک استان</title><!--ان شاءالله در نهایت از یک جدول و بر اساس استان / شهر انتخاب شده بیاید-->
        <link rel="shortcut icon" href="../favicon.ico"> 
	    <link rel="stylesheet" type="text/css" href="css/demo.css" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
	    <link rel="stylesheet" type="text/css" href="css/animate-custom.css" />
	  
  <!--link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script-->
  
<link rel="stylesheet" href="bootstrap-3.3.7-dist/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
  
        <link rel="stylesheet" href="bootstrap-rtl.min.css">
        <script src="bootstrap-filestyle-1.2.1/src/bootstrap-filestyle.min.js"> </script>
    <style>
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
  </style>
</head>
<body>

    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <!-- div class="row"><div class="col-sm-3 pull-left"><img src="logo_<?php echo $state;?>.png" class="img-rounded"></div></div -->
        <div class="row"><div class="col-sm-4 pull-left"><img id="mainlogoid" src="logo1.png" class="img-rounded"></div></div>
           <div class="row">
               <div class="col-sm-12">

                  <div class="nav navbar-nav">

                     <a class="btn btn-default btn-outline btn-circle"  id="myeditlink" <?php if (strlen(trim($curid))>0) echo "href='#'  onclick='doedit(".$curid.")'";?> data-toggle="modal">ویرایش</a>
                     <a class="btn btn-default btn-outline btn-circle"  data-toggle="modal" href="#nav-collapse2" >افزودن مشاور</a>
                     <a class="btn btn-default btn-outline btn-circle"  data-toggle="modal" href="#nav-contactus" >تماس</a>

                 </div><!--nav navbar-->
              </div><!--col-sm-12-->
           </div><!-- row -->
       <div class="row">
           
           <div id="counter" class="col-sm-12 pull-left">بازدید امروز <br /><span><?php $sql="select count(*) as n from `visits` where `dmy`='".date('d-M-Y')."'";
         fwrite($file,$sql);
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
if(    (  (strlen($mantaghe) > 0) || (strlen($mantaghe2) > 0)   )
	   AND (  (strlen($mahal) > 0) || (strlen($mahal_1) > 0) || (strlen($mahal_2) > 0) || (strlen($mahal2) > 0) ) )
{
      $tmpSinceSec = time()-86400; //floor(time()/86400)*86400 - 86400;

      $tmpmantaghe = "";
      if(strlen($mantaghe) > 0)
        $tmpmantaghe = $mantaghe;
      else
        $tmpmantaghe = $mantaghe2;

      $sql = "select * from `cnotes` where ";
      
      
      
/*      
      $where_clause = " (" . sqlcleanup("`state`") . "='" . cleanup($state) . "') AND (`mahal2`<>'') AND (`mantaghe2`<>'') AND (`time`-`howmanysecago` >= " . $tmpSinceSec . ") ";
      $where_clause .= " AND (" . sqlcleanup("`mantaghe2`") . "='" . $tmpmantaghe . "') " ;
      $where_clause .= " AND ( (1=2) " ; //just a dummy false clause for now; to imrove soon en shaa Allah
  
        for($k=0; $k<4; $k++)
        {
          if($k==0)$mahalk=$mahal;
          if($k==1)$mahalk=$mahal_1;
          if($k==2)$mahalk=$mahal_2;
          if($k==3)
          {
            if( (strlen($mahal)==0) && (strlen($mahal_1)==0) && (strlen($mahal_2)==0) )
              $mahalk=$mahal2;
            else
              continue;//here acts as break indeed
          }

          
          if(strlen($mahalk) > 0)
          {
            $where_clause .= " || ('" . $mahalk . "' = 'همه') || (" . sqlcleanup("`mahal2`") . "='" . $mahalk . "')" ;

            if( strcmp($tmpmantaghe, "تهران")==0 ) 
            {
              if( (strpos("dummy" . $mahalk, "منطقه ")>0) && (strlen($mahalk)<strlen("منطقه 123")) ) //keep an eye in this for now, to improve soon en shaa Allah
              {
                $where_clause .= " || ( " . sqlcleanup("`mahal2`") . " IN 
                                       (select " . sqlcleanup("`mahal`") . " from t22mm where REPLACE(mantaghe, ' ', '') = " . $tmpmantaghe . ")  ) ";
              }
              else
              {
                $where_clause .= " || (REPLACE(`mantaghe2`, ' ','') in (select REPLACE(`mantaghe`, ' ','') from `t22mm` where " . sqlcleanup("`mahal`") . "='" . $mahalk . "') )";
              } 
            }
          }  
        }//for 
	  
      $where_clause .= " )" ;
      
*/      
      
      
       $where_clause = " (" . sqlcleanup("`state`") . "='" . cleanup($state) . "') AND (`mahal2`<>'') AND (`mantaghe2`<>'') AND (`time`-`howmanysecago` >= " . 
                       $tmpSinceSec . ") ";



       $tmpp =$state . '  ' . $city . '  ' . $mantaghe . '  ';// increase to 3 when city in cnotes used later en shaa Allah
       $tmpt = "CONCAT('%',  " . sqlcleanup("`mantaghe2`") ; //note the blanks, shokr

       if( (strcmp($mahal,'همه')!=0)&& (strcmp($mahal_1,'همه')!=0) && (strcmp($mahal_2,'همه')!=0) )
       {
         $tmpp .=$state . '  ' . $city . '  ' . $mantaghe . '  ';
         $tmpt .= ", '%-%', " . sqlcleanup("`mahal2`") . " , '%-%')" ;//note the blanks, shokr

         for($k=0; $k<  3  ; $k++)
         {
           if($k==0)$mahalk=$mahal;
           if($k==1)$mahalk=$mahal_1;
           if($k==2)$mahalk=$mahal_2;
           if($k==3)
           {
             if( (strlen($mahal)==0) && (strlen($mahal_1)==0) && (strlen($mahal_2)==0) )
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
              fwrite($file, "sql_itsmahal is:" . $sql_itsmahal);
              $result_itsmahal = $conn->query($sql_itsmahal);
              while($row_itsmahal = $result_itsmahal -> fetch_assoc())
              {
                $itsmahal = cleanup($row_itsmahal["mahal"]);
                $tmpp .= $itsmahal . '  ' ;
              } 
             }
           }
         }//for 

         $where_clause .= " AND '" . $tmpp . "' LIKE " . $tmpt ;
       }
       else
       {
         $tmpt .= ", '%')";

         $where_clause .= " AND ( " . sqlcleanup("mantaghe2") . " = '" . cleanup($mantaghe) . "' OR " . sqlcleanup("mahal2") . " = '" . cleanup($mantaghe) . "')  AND '" . $tmpp . "' LIKE " . $tmpt ;
       }





      
      
    
      
      
      
      
      
      
      
      
      
      $sql .= $where_clause ;
//      $sql .= " order by `time`-`howmanysecago` desc";
      $sql .= " order by `source` desc";



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
    $tmptitle = "آگهی های 24 ساعت اخیر در محدوده کاری شما: ";
    echo '<h3 id="grid_title" class="text-center" style="margin:30px 0px 30px 0px!important;color:#1f004d;">' . $tmptitle  . '</h3>' ;
    echo '<h2 id="grid_title" class="text-center" style="margin:30px 0px 30px 0px!important;color:#1f004d;">' . "اگر منطقه کاری شما اشتباه است لطفاً آنرا در بخش ویرایش (بالا-سمت راست) بررسی و مناطق مورد پوشش خود را انتخاب بفرمایید (حداکثر سه منطقه)."  . '</h2>' ;
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
            <span class="myalign" >   <?php echo $row["mantaghe2"] . ", " . $row["mahal2"];?>   </span> <br>               
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
    $tmptitle = "لطفاً محدوده کاری خود (حداکثر 3 منطقه) را در بخش ویرایش (بالا سمت راست)، بررسی و در صورت نیاز تصحیح بفرمایید. ";
    echo '<h3 id="grid_title" class="text-center" style="margin:30px 0px 30px 0px!important;color:#1f004d;">' . $tmptitle  . '</h3>' ;
  }

}
else
{
    $needs_footer=0;
    $tmptitle = "لطفاً محدوده کاری خود (حداکثر 3 منطقه) را در بخش ویرایش (بالا سمت راست)، بررسی و در صورت نیاز تصحیح بفرمایید. ";
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
<?php if ($state==='البرز') {
                
	          }
else if ($state==='تهران') {
                 echo '       <li class="nav-header text-center"><span class="blink">فروش آپارتمان ١٦٧ </span><br></li>';
                   echo '<li><img src="adimg/IMG_3605.JPG" class="img-rounded" style="width:100%;height:25%;"></li>';
                   echo '<li class="text-center" style="color:darkblue;margin-right:10px;font-size:12px;font-weight:800;">آپارتمان فول امكانات دولت                        </li>';
echo '<li class="text-center" style="color:darkblue;margin-right:10px;font-size:12px;font-weight:800;">  ٣خواب، استخر، سونا، سالن اجتماعات، پاركينگ، بازسازي كامل، طبقه اول با دو حياط خلوت عالي                        </li>';

                   echo '<li class="text-center" style="margin-right:10px;font-size:12px;color:#400000;font-weight:800;">تماس:      09122718121 </li>';
}                  
echo '       <li class="nav-header text-center">فروش بی سابقه <br><span class="blink">واحدهای مسکونی ارزان قیمت در تنکابن و رامسر </span></li>';
                   echo '<li><img src="adimg/adv.jpg" class="img-rounded"></li>';
echo '<li class="text-center" style="color:darkred;font-size:12px;font-weight:800;">فروش بی سابقه واحدهای مسکونی ارزان قیمت باتسهیلات حداکثری براساس مصوبه دولت</li>';
                   echo '<li class="text-center" style="color:darkblue;font-size:12px;font-weight:800;">تنها مجری واگذاری مجتمعهای طرح مسکن ارزان قیمت به شکل اقساط باتسهیلات حداکثر بالادستی
                        </li>';
                   
                   echo '<li class="text-center" style="margin-right:10px;font-size:12px;color:#400000;font-weight:800;">تماس:      05114236957 </li>';
                   
                  

?>
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
	          <form role="form" method="post" enctype="multipart/form-data" id="adformid" action="save_adver.php">
			<div class="panel panel-primary text-right"><h5 class="panel-heading">  کاربر گرامی  هزینه چاپ آگهی شما در استان محل ملک برای مدت یک ماه ۵۰ هزار تومان می باشد. لطفا اطلاعات زیر را تکمیل  و سپس گزینه پرداخت را انتخاب بفرمایید.</h5></div>
			<p>
			     <label for="adtitleid">عنوان آگهی</label>
                             <input type="text" id="adtitleid" name="adtitle" placeholder="تیتر جهت چاپ در قسمت بالای آگهی" required="required" 
                        oninvalid="this.setCustomValidity('لطفا عنوان را وارد کنید')" oninput="setCustomValidity('')">
			</p>
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
       <!------------ save advertisement  --------------->
       $(document).on('submit',"#adformidtttt", function(e) {
           var url = "save_adver.php"; 
           var fd = new FormData(this);
           var file = document.getElementById('adimageid').files[0];
           if (file) {   
              fd.append('image', file);
           }
           $.ajax({
	      type: "POST",
	      url: url,
              data:fd,
	      processData: false,
              contentType: false,
	      success: function(data){
		 data = JSON.parse(data);
		 $('#adalert_placeholder').append('<div id="adalertdiv" class="text-right alert '+ data.errTyp+'"><span>'+data.errMSG+'</span></div>');
                 setTimeout(function() { 
                     $("#adalertdiv").remove();
                 }, 3000);
	      }
	   });
           e.preventDefault(); 
        });

</script>
<!---------------------------------->
   
   </div>  <!-- row first-->
   <footer id="footer_id" class="container-fluid text-center" style="z-index:1;height:auto;width:100%;backgroung-color:#1f004d;">
       <?php 
if($needs_footer==1)
{
        $footer_sql = "select count(*) as N from cnotes where " . $where_clause;
        fwrite($file, "footer_sql is:" . $footer_sql);
     
        $footer_result = $conn->query($footer_sql);
        $footer_row = $footer_result->fetch_assoc();
        $footer_total = $footer_row["N"];
        $footer_url ="?id=" . $_SESSION['id'] . "&";
        echo mypagination($footer_url, $page, $footer_total, $cperpage ); 
}
       ?>
   </footer>





<script>
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
             if (no==1)
             $(".Emahalat").html(data);
             if (no==2)
             $(".Emahalat_1").html(data);
             if (no==3)
             $(".Emahalat_2").html(data);

             var mySelect = document.getElementById(id);
             for (var i, j = 0; i = mySelect.options[j]; j++) {       
                if ( i==null) {
                    break;
                }
                if (i.value==mahal) {
                    break;
                }   
             }
      
             if ( i!=null)
                 mySelect.options[j].selected = "selected";    
       
             
        }
     });
       

}
function showSelect(obj,id){
       var temp = obj;
                  var mySelect = document.getElementById(id);
       for (var i, j = 0; i = mySelect.options[j]; j++) {       
           if ( i==null) {
               break;
           }
           if (i.value==temp) {
               break;
           }   
       }
      
       if ( i!=null)
            mySelect.options[j].selected = "selected";    
            
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
        //document.getElementById("Estateid").value = obj.state;
        document.getElementById("Ecityid").value = obj.mantaghe;
        document.getElementById("Esenfnoid").value = obj.senfno;

        document.getElementById("Enameid").value = obj.name;
        document.getElementById("Etid").value = obj.t;
        document.getElementById("Emid").value = obj.m;
        document.getElementById("Eaddrid").value = obj.addr;
        document.getElementById("Emahalid").value = obj.mahal;
 document.getElementById("Emahalid_1").value = obj.mahal_1;
 document.getElementById("Emahalid_2").value = obj.mahal_2;
        //document.getElementById("Ehamkaranid").value = obj.hamkaran;
        document.getElementById("Etozihatid").value = obj.tozihat;
        if (obj.mantaghe.trim()!=="")
        showSelect(obj.mantaghe,"Ecityid");
        
        fillmahals(obj.state,obj.mantaghe,obj.mahal,"Emahalid",1);
        fillmahals(obj.state,obj.mantaghe,obj.mahal_1,"Emahalid_1",2);
        fillmahals(obj.state,obj.mantaghe,obj.mahal_2,"Emahalid_2",3);

        //showSelect(obj.state,"Estateid");
        //showSelect(obj.mahal,"Emahalid");

        //setTimeout(function(){showSelect(obj.mahal,"Emahalid");}, 100);
    
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
                                <select class="selectpicker Ecities" id="Ecityid" name="city" data-live-search="true" >
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
                  <script>
                  $('.Ecities').change(function() {
                        var city = $(this).val(); 
                        var fd = new FormData();
                        var option = document.createElement("option");
                        var select = document.getElementById("Ecityid");
                        add=false;
                     /*   if (city.trim()=='سایر'){
                                var OtherData=prompt("لطفا شهر موردنظررا وارد کنید");
                                if (OtherData) {
                                  option.text = OtherData;
                                  option.value = OtherData;                                  
                                  option.setAttribute('selected', true);
                                  select.appendChild(option);
                                  city=OtherData;
  		                  add=true;
                               }
                        }*/
                        fd.append('city',city);
                        fd.append('add',add);
                        $.ajax({
                        type:'POST',
                        url:'getmahal1.php',
                        data:fd,
                        processData: false,
                        contentType: false,
                        success:function(data){
                          $(".Emahalat").html(data);
                          $(".Emahalat_1").html(data);
                          $(".Emahalat_2").html(data);

                        }
                        });
                  });
                  </script>
                 
		 
                             <div class="col-sm-6">
				<label class="normal">منطقه اول</label>
				<select class="selectpicker Emahalat" id="Emahalid" name="mahal" data-live-search="true" onchange="doaddoption('Emahalid','Estateid','Ecityid',this.value);">
		                 <option disabled> انتخاب کنید... </option>
                                </select>
			      </div>
<div class="col-sm-6">
				<label class="normal">منطقه دوم</label>
				<select class="selectpicker Emahalat_1" id="Emahalid_1" name="mahal_1" data-live-search="true" onchange="doaddoption('Emahalid_1','Estateid','Ecityid',this.value);">
		                 <option disabled> انتخاب کنید... </option>
                                </select>
			      </div>
<div class="col-sm-6">
				<label class="normal">منطقه سوم</label>
				<select class="selectpicker Emahalat_2" id="Emahalid_2" name="mahal_2" data-live-search="true" onchange="doaddoption('Emahalid_2','Estateid','Ecityid',this.value);">
		                 <option disabled> انتخاب کنید... </option>
                                </select>
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
                             
                             var select = document.getElementById(id);
                             var DropdownValue =val;
                             //x=document.getElementById(id);
                             if (DropdownValue=='سایر'){
                                var OtherData=prompt("لطفا نام مناطق را با خط فاصله وارد فرمایید مثلاً آزادی-اکباتان:");
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
				<label class="normal"> نام </label>
				<input type="text" id="nameid" name="name" placeholder="نام شما"
                        oninvalid="this.setCustomValidity('لطفا نام خود را وارد کنید')" oninput="setCustomValidity('')">
			     </div>
                             
			     <div class="col-sm-6">
				<label class="normal"> تلفن همراه </label>
				
				<input type="text" id="mid" name="m" required="required" placeholder="شماره موبایل"
                        oninvalid="this.setCustomValidity('لطفا شماره موبایل را وارد کنید')" oninput="setCustomValidity('')" onchange="chktel(1,'mid')">
                                                        
                        
			     </div>
                             
                             <div class="col-sm-6">
				<label class="normal">استان</label>
                                <select class="selectpicker states" id="stateid" name="state" data-live-search="true" required >
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
				<select class="selectpicker cities" id="cityid" name="city" data-live-search="true" required>
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
                          $(".cities").html(data);
                        }
                        });
                  });
                 
                  </script>
		 
                             <div class="col-sm-6">
				<label class="normal"> منطقه </label>
				<select class="selectpicker mahalat" id="mahaleid" name="mahal" data-live-search="true" onchange="doaddoption('mahaleid','stateid','cityid',this.value);" required>
		                 <option disabled> انتخاب کنید... </option>
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
                          $(".mahalat").html(data);
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
  $s = str_replace('خیابان ', '', $s );     
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