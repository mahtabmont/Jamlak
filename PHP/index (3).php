<?php
  require_once 'dbconnect.php';
  require_once 'parameters.php';
 
  ob_start();
  session_start();

  //1.
  $file=fopen("index.log","w");
  fwrite($file, "Salaam". "\n");
  
  //init
  $curid = "";
  $user ="";

  //decide based in id in GET or in SESSION (in turn)
  if(isset($_GET["id"]))//there are two routes for puting an id in the seesion, one through the GET parameter and one through login
  {
    fwrite($file, "id is set as a GET parameter to: " . $_GET["id"] . "\n");
    $curid =$_GET['id'];
    $query ="SELECT `state`, `name` FROM `amlakin` WHERE `ID`='" . $_GET["id"] . "'";
    $res = $conn->query($query);
    $row = $res->fetch_assoc();  
    if( mysqli_num_rows($res) == 1)
    {
      $user = $row["name"];
      $state = $_SESSION["state"] = $row["state"] ;    
    }
    $_SESSION['id'] = $_GET["id"];
  }
  else
  {
    //see if id in session
    if(isset($_SESSION["id"]))
    {
      fwrite($file, "id is in the session as : " . $_SESSION["id"] . "\n");
      $curid =$_SESSION['id'];
      $query ="SELECT `state`, `name` FROM `amlakin` WHERE `ID`='" . $_SESSION["id"] . "'";
      $res = $conn->query($query);
      $row = $res->fetch_assoc();  
      if( mysqli_num_rows($res) == 1)
      {
        $user = $row["name"];
        if( isset($_SESSION["state"]) && strlen(trim($_SESSION['state']))>0 )
          $state = $_SESSION["state"] ;    
        else
          $state = $_SESSION["state"] = $row["state"] ;    
      }
    }
    else
    {
      if( isset($_SESSION["state"]) && strlen(trim($_SESSION['state']))>0 )
        $state = $_SESSION["state"] ;    
      else
        $state = $_SESSION["state"] = $default_state;
    }
  }
  
  //overwrite state and its session, if specified in GET:
  if( isset($_GET["state"]) && strlen(trim($_GET['state']))>0 )
  {
    fwrite($file, "state is set as a GET parameter to: " . $_GET["state"] . "\n");
    $state = $_SESSION["state"] = $_GET["state"];
  }

  //see also if mantaghe in GET
  $city = "" ;
  if(isset($_GET["city"]) && strlen(trim($_GET['city']))>0 )
  {
    fwrite($file, "city is set as a GET parameter to: " . $_GET["city"] . "\n");
    $city = $_GET["city"];
  }//if 
  

  //3., this code also assumes ID in the session, if exists
  include_once("myfunctions.php");
  inc_visits("index");
  
//fillin_mahals();
/* remove_duplicates(); */
  fwrite($file, "3..". $_SESSION['state']);
  
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
     #counter{border: 2px solid #fff; color:#fff; text-align:center; padding:4px; width:150px; background:#176173; font:normal 12px tahoma, verdana,        Arial; }

    #counter span {font-weight:bold;}
    .navbar-header{font-family:tahoma; font-weight:bold; font-size:10pt;}

    .container > .sidebar {
         position: absolute;
   
     }
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
    .setstate{width:auto;height:35px;}
    .setstate:focus {
      box-shadow:none;
      -webkit-box-shadow:none; 
      border-color:#cccccc; 
    }
    .setcity{width:auto;height:35px;}
    .setcity:focus {
      box-shadow:none;
      -webkit-box-shadow:none; 
      border-color:#cccccc; 
    }
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
    .add_to{text-align:left;}
    .list-group-item span.badge {
        position:relative;
        top: 0px;
        right:3%;
    }
    .glinkclass:hover{background-color:inherit!important;}
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
    .well{overflow:none;
background-color:#D7E3FD;}

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
   input[type="radio"]{
      -webkit-appearance: radio!important;
}
  </style>
  <script>
function getSearchParameters() {
      var prmstr = window.location.search.substr(1);
      return prmstr != null && prmstr != "" ? transformToAssocArray(prmstr) : {};
}

function transformToAssocArray( prmstr ) {
    var params = {};
    var prmarr = prmstr.split("&");
    for ( var i = 0; i < prmarr.length; i++) {
        var tmparr = prmarr[i].split("=");
        params[tmparr[0]] = tmparr[1];
    }
    return params;
}
function load()//NOTE that callBot and the related modal works based on the id in _GET and not the session, to revise soon enshaa Allah
{
   var params = getSearchParameters();

   var xhttp = new XMLHttpRequest();
   xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
          if(xhttp.responseText.length > 0)//NOTE: assumes that callBot returns '' if no id (who does not have chat is) is found
          {
                $('#callbot_modal').modal('show');
                document.getElementById("calling_bot").innerHTML=xhttp.responseText;                                    
          }
        }  
   };
   var url = "callBot.php?id=" + params.id;
   xhttp.open("GET", url, true);
   xhttp.send();
}
</script>
</head>
<body onload="load()">

    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="row"><div class="col-sm-4 pull-left"><img id="mainlogoid" src="logo1.png" class="img-rounded">
        </div></div>
           <div class="row">
               <div class="col-sm-12">

                  <div class="nav navbar-nav">

                     <a class="btn btn-default btn-outline btn-circle" id="myloginlink" <?php if (strlen($curid)==0) echo 'href="#nav-collapse1"'; else echo "disabled='disabled' href='#'";?> data-toggle="modal">ورود</a>
                     <a class="btn btn-default btn-outline btn-circle"  data-toggle="modal" href="#nav-collapse2" >افزودن</a>
                     <a class="btn btn-default btn-outline btn-circle"  id="myeditlink" <?php if ($curid=='') echo 'href="#nav-collapse1"'; else echo "href='#'  onclick='doedit(".$curid.")'";?> data-toggle="modal">ویرایش</a>
                     <a class="btn btn-default btn-outline btn-circle"  data-toggle="modal" href="#nav-contactus" >تماس و پشتیبانی</a>
                     <!--a class="btn btn-default btn-outline btn-circle" href="files.php" >فایلهای من</a-->
<a class="btn btn-default btn-outline btn-circle" onclick="alert ('این بخش بزودی فعال می گردد');">
فایلهای من</a>
                     <a class="btn btn-default btn-outline btn-circle" <?php if ($curid>'') echo 'href="#bs-modal-sm"';?> data-toggle="modal" id="mylogoutlink" <?php if ($curid=='') echo "disabled='disabled' href='#'";?>>خروج</a>
                 </div><!--nav navbar-->
              </div><!--col-sm-12-->
           </div><!-- row -->
        <div class="row">
           
           <div id="counter" class="col-sm-12 pull-left">بازدید امروز <br /><span><?php $sql="select count(*) as n from `visits` where `dmy`='".date('d-M-Y')."'";
         fwrite($file,$sql);
         $result = $conn->query($sql);  $row = $result->fetch_assoc(); echo $row['n'];?> نفر </span></div></div>
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
                    <!--form class="navbar-form" id="searchform" action="">
                        <input class="form-control pull-right" placeholder="جستجوی منطقه، نام، ..." name="search_text" id="search_text_id" type="text">
                        <button class="btn btn-default pull-right" id="searchbtn"><i class="glyphicon glyphicon-search"></i></button>
                    </form-->
<!----------------------------------------------->
       <div>
           <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
                   <label for="setstateid" style="font-size:16px;">استان</label>
                    <select class="setstate" id="setstateid" name="state" >
				     <option disable value=""> انتخاب کنید... </option>
				     <?php
					for ($i=0;$i<31;$i++){ 
			             ?>
			     
			            <option <?php if (trim($ostanha[$i])==($state)) echo 'selected'?>><?php echo $ostanha[$i]; ?> </option>
			     <?php
				 } 
			     ?>
			     </select>
			     <label style="font-size:16px;">شهر</label>
                    <select class="setcity" id="setcityid" name="city"  >
				     <option disable value=""> انتخاب کنید... </option>
				       <?php
				     if (trim($_SESSION['state'])!=="") {
				     $sql1 = "select distinct `mantaghe` from `omm` where `state`='".$_SESSION['state']."' ORDER BY `ID`"; 
                                     $result1 = $conn->query($sql1);
				     while($row1 = $result1->fetch_assoc())	{ 
			         ?>
			     
			             <option <?php if (trim($row1['mantaghe'])==trim($city)) echo 'selected';?>> <?php echo $row1["mantaghe"]; ?> </option>
			     <?php
				     }
				 } 
			     ?>
			     </select>
                 <button type="submit" class="glyphicon glyphicon-search btn-sm btn-primary" ></button>			
			     </form>
       </div>
       <script>
                 $('.setstate').change(function() {
                        var state = document.getElementById("setstateid").value; 
                        $.ajax({
                        type:'POST',
                        url:'getcities.php',
                        data:{'state':state},
                        success:function(data){
                           $("#setcityid").html(data);
                          }
                        });
                        
                  });
                  
       </script>
<!-----------------   main grid   --------------->
       <div id="main_grid_id">
         <?php include_once "grid1.php"; ?> 
       </div> 

     </div><!--col-sm-10 -->
<!--------------  end of main grid- column for advertisement   ------------->
     <!--------------  end of main grid- column for advertisement   ------------->
 <div class="col-sm-2">
          <div class="sidebar">
              <div class="well" id="advertis">
                 <ul class="nav">
                     <?php
                     
                     $sql1 = "select * from `advertis` where (`paytype`=2 OR `state`='".$state."' AND `mantaghe`='".$city."') AND `paid`=1 AND `checked`=1 AND `date` >= DATE_ADD(NOW(),INTERVAL -8 DAY)"; 
  fwrite($file, "sql1 is " . $sql1 . "\n");
                     $i=0;
                     $result1 = $conn->query($sql1);
				     while($row1 = $result1->fetch_assoc())	{ 
                          $i++;
                          echo '<li class="nav-header text-center"><span class="blink">'.$row1['title'].'</span><br></li>';
                          if (trim($row1['image'])===""){
                             //echo '<li class="text-center" style="color:darkred;margin-right:10px;font-size:16px;font-weight:800;">'.$row1['title'].'</li>';
                             $maxlen=50;
                             echo '<li><img src="adimg/defaultadv.jpg" class="img-rounded" style="max-width:280px;max-height:120px;"></li>';
                            }
                          else{
                             echo '<li><img src="adimg/'.$row1['image'].'" class="img-rounded" style="max-width:280px;max-height:120px;"></li>';
                             $maxlen=50;
                          }
                          if (mb_strlen($row1['tozihat'])>$maxlen){
                          echo '<li class="text-center" style="color:darkblue;margin-right:10px;font-size:12px;font-weight:500;">'.mb_substr($row1['tozihat'],0,$maxlen).
                          ' ...<a class="glinkclass" id="glink'.$i.'" href="#/" onclick="showhide('.$i.')" style="background:inherit;" >ادامه مطلب </a></li>';
                          }
                          else
                          echo '<li class="text-center" style="color:darkblue;margin-right:10px;font-size:12px;font-weight:500;">'.$row1['tozihat'].'</li>';
                          echo '<li id="spanhide'.$i.'" style="display:none;font-size:10px;font-weight:500;color:#660035;">'.$row1['tozihat']."<br>".$row1['adtel'].'</li>';
                          //echo '<li class="text-center" style="margin:auto;font-size:12px;color:#400000;font-weight:800;">'.$row1['adtel'].'</li>';
                
                   
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
			<!--p>
			    <label class="col-sm-6 radio-inline" style="font-size:10px!important;"><input type="radio" name="paytype" value="1" checked/>نمایش  بمدت یک هفته </label>
			
			    <label  class="col-sm-6 radio-inline" ><input type="radio" name="paytype" value="2"/>نمایش بمدت یک ماه</label>
			</p-->
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
   <footer id="footer_id" class="container-fluid text-center" style="z-index:1;height:auto;width:100%;backgroung-color:#1f004d;">
       <?php include_once "footer.php"; ?>
   </footer>

</div>  <!-- container -->

<!---------------------insert modal - to add new profile-------------------------------------------->
      <div class="modal fade small" id="nav-collapse2">
           <div class="modal-dialog" id="mycontainer_demo">
	       <div class="modal-content">
	            <div id="adding" class="animate form">
                        <a class="pull-right" href="#" data-dismiss="modal">x</a>
          		<form role="form" method="post" enctype="multipart/form-data" id="formid">
                     			    
                             <h4>ورود اطلاعات بنگاه املاک</h4>
			     <div class="col-sm-6">
				<label class="normal">شماره صنفی</label>
				<input type="text" id="senfnoid" name="senfno" required="required" placeholder="شماره صنفی"
                        oninvalid="this.setCustomValidity('لطفا شماره صنفی را وارد کنید')" oninput="setCustomValidity('')">
			     </div>
      			     <div class="col-sm-6">
				<label class="normal"> نام </label>
				<input type="text" id="nameid" name="name" required="required" placeholder="نام بنگاه ملکی شما"
                        oninvalid="this.setCustomValidity('لطفا نام بنگاه را وارد کنید')" oninput="setCustomValidity('')">
			     </div>
                             <div class="col-sm-6">
				<label class="normal"> تلفن ثابت </label>
				<input type="text" id="tid" name="t" required="required"  placeholder="شماره تلفن ثابت"
                        oninvalid="this.setCustomValidity('لطفا شماره تلفن را وارد کنید')" oninput="setCustomValidity('')" onchange="chktel(2,'tid')">
			     </div>
			     <div class="col-sm-6">
				<label class="normal"> تلفن همراه </label>
				
				<input type="text" id="mid" name="m" required="required" placeholder="شماره موبایل"
                        oninvalid="this.setCustomValidity('لطفا شماره موبایل را وارد کنید')" oninput="setCustomValidity('')" onchange="chktel(1,'mid')">
                                                        
                        
			     </div>
			     <input type="hidden" id="stateid" name="state" value="<?php echo $_SESSION['state'];?>" />
                                
			     <div class="col-sm-6">
				<label class="normal">شهر</label>
                                <select class="selectpicker cities" id="cityid" name="city" data-live-search="true" required >
		                <option disabled> انتخاب کنید... </option>
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
                  $('.cities').change(function() {
                        var city = $(this).val(); 
                        var fd = new FormData();
                        var option = document.createElement("option");
                             
                        var select = document.getElementById("cityid");
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
                           $("#mahaleid").html(data).selectpicker('refresh');
                        }
                        });
                  });
                  </script>
		 
                             <div class="col-sm-6">
				<label class="normal"> منطقه </label>
					<select class="selectpicker mahalat" id="mahaleid" name="mahals[]" size="1" title="انتخاب کنید" multiple  onchange="doaddoption('mahaleid','stateid','cityid',this.value);" required>
		                 <!--option disabled> انتخاب کنید... </option-->
                                </select>
			      </div>
   <div class="col-sm-6">
				<label class="normal"> آدرس </label>
				<input type="text" id="addrid" name="addr" required="required" placeholder="آدرس"
                        oninvalid="this.setCustomValidity('لطفا آدرس را وارد کنید')" oninput="setCustomValidity('')">
			     </div>
			              		 
     <div class="col-sm-6">
				 <label class="normal">توضیحات</label>
				 <input type="text" id="tozihatid" name="tozihat" placeholder="... تخصص ما">
			      </div>

                              <div class="col-sm-6"> 
                                  <label class="normal youpasswd" >رمز عبور </label>
                                  <input id="passwordins" name="password1" required="required" type="password" 						oninvalid="this.setCustomValidity('لطفا رمز عبورراواردکنید')" oninput="setCustomValidity('')" />
                              </div>
                              <div class="col-sm-6"> 
                                  <label class="normal youpasswd" >تکرار رمز عبور</label>
                                  <input id="passwordins_confirm" name="password2" required="required" type="password" 
						onchange="checkisequal();" />
                              </div>
			      <div class="col-sm-6">
				 <table><tr>
				 <td><input type="file" class="filestyle" id="imageid"  name="photo" data-badge="false" data-input="false" data-buttonText="تصویر" data-buttonName="btn-default" /></td>
				 <td><img class="img-rounded" id="output" />&nbsp;&nbsp;<a id="delimgEdit" href="#" onclick="dodelimg('imageid','output','')"><span class="glyphicon glyphicon-trash"></span></a></td>
				 </tr></table>
			      </div>             
                              <p class="col-sm-6 login button"> 
                                 <img src="ajax-loader.gif" id="loading-indicator1" style="display:none;">
				 <input type="reset" value="خروج" style="background-color:#ff704d!important;" data-dismiss="modal">
                                 <input type="submit"  name="submit" value="ثبت" />
				 
			      </p>
			</form>
			<script>
 <!------------------------- upload image in insert modal ------------------------->
			     $(function(){
				$('#imageid').change(function(){
				    var url = $(this).val();
				    var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
				    if (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg") 
				    {
					var reader = new FileReader();
					reader.onload = function (e) {
					    $('#output').attr('src', e.target.result);
					}
					reader.readAsDataURL(this.files[0]);
				    }
				    else
				    {
					$('#output').attr('src', 'img/noimagegreen.jpg');
				    }
				});
			     });
<!------------------------------  save insert modal information ------------------------->
			     function checkisequal(){
                                var pass1 = document.getElementById('passwordins').value.trim();
                                var pass2 = document.getElementById('passwordins_confirm').value.trim();
                                if (pass1.localeCompare(pass2)!==0)
                                {
                                       alert("دو رمز را  یکسان وارد نمایید");
                                       document.getElementById('passwordins_confirm').value = "";
                                       document.getElementById('passwordins').value = "";

                                       window.setTimeout(function ()
                                       {
                                          document.getElementById('passwordins').focus();
                                       }, 0);
                                       return false;
                                }
                                return true;
                             }
                             $(document).on('submit',"#formid", function(e) {
                                var url = "save_amlak.php"; 
                                var fd = new FormData(this);
                                var senfno = document.getElementById('senfnoid').value.trim();
                                var name = document.getElementById('nameid').value.trim();
                                var mobile = document.getElementById('mid').value.trim();
                                var addr = document.getElementById('addrid').value.trim();
                                var pass1 = document.getElementById('passwordins').value.trim();
                                var pass2 = document.getElementById('passwordins_confirm').value.trim();
                                var file = document.getElementById('imageid').files[0];
                                if (file) {   
                                     fd.append('photo', file);
                                }
                                if ((senfno!=='') && (name!=='') && (mobile!=='') && (addr!=='') && (pass1!='')){
                                   $.ajax({
				     type: "POST",
				     url: url,
                                     data:fd,
				     processData: false,
                                     contentType: false,
				     success: function(data)
				     {
					alert ("اطلاعات شما با موفقیت ثبت گردید.");
					location.reload();
				     }
				   });
                                   e.preventDefault(); 
                                }
			      });
		       </script>
		</div>
	</div>
    </div>
</div>

  <!------------------------- Edit modal ------------------------->
		
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
<!------------------------- Login modal ------------------------->
<div class="modal fade" id="nav-collapse1" role="dialog" >
    <div class="modal-dialog" id="container_id">
    	<div class="modal-content" id="modal-content">
	     <div id="login" class="animate form">
		 <a class="pull-right" href="#" data-dismiss="modal">x</a>
                 <form  method="post" id="flogin" autocomplete="on">                
		     <h1>ورود به حساب کاربری</h1> 
                     <p> 
                        <label for="username" class="uname" style="width:20%!important;"> نام کاربری </label>
                        <input id="username" name="username" required="required" type="text" placeholder="شماره صنفی"
                        oninvalid="this.setCustomValidity('لطفا نام کاربری(شماره صنفی) را وارد کنید')" oninput="setCustomValidity('')" style="width:50%!important; margin-top:0px!important;"/><span style="color:red;">&nbsp;*&nbsp; شماره صنفی &nbsp; </span>
                     </p>
                     <p> 
                        <label for="password" class="youpasswd" style="width:20%!important;"> رمز عبور</label>
                        <input id="password" name="password" required="required" type="password"
                        oninvalid="this.setCustomValidity('لطفا رمز عبور را وارد کنید')" oninput="setCustomValidity('')" style="width:50%!important; margin-top:0px!important;" />
                     </p>                                
                     <p class="login button"> 
                       
                        <input type="reset" value="خروج" style="background-color:#ff704d!important;margin-left:5px;" data-dismiss="modal" >
                        <input type="submit" name="btn-login" id="btnsubmit" value="ورود" /> 
		     </p>
		     <p class="forgetpass"> 
			<a class="alert" id="forgetpass" href="#">رمز عبور خود را فراموش کرده اید؟ </a>									
		     </p>
		     <!--p class="changpass"> 
			<a class="alert" id="changpass" href="#" onclick="$('#chpass_modal').modal('show');"> مایلید رمز خود را تغییر دهید؟</a>				
		     </p-->
                     <div id="alert_placeholder"></div>
		     <!--p class="change_link">
			<a id="to_registerlink" class="to_login">عضویت</a>
						عضو نیستید؟
		     </p-->
                </form>
	    </div>
          </div>
     </div>
</div>
<!------------------------- activate modals for each link in login modal ------------------------->
			<!--script>	
                               $(document).on('click', '#to_registerlink', function () {
                                   $('#nav-collapse11').append('body').modal('show');
                               })
                               
                               $(document).on('click', '#to_loginlink', function () {
                                   $('#nav-collapse1').append('body').modal('show');
                               })
                               
                               $('#nav-collapse1').on('show.bs.modal', function () {
                                   $('.modal').not($(this)).each(function () {
                                       $(this).modal('hide');
                                   });
                               });
                               $('#nav-collapse11').on('show.bs.modal', function () {
                                   $('.modal').not($(this)).each(function () {
                                       $(this).modal('hide');
                                   });
                               });
 </script-->       
 <!------------------------- retrieve password from db - forget password ------------------------->
<script>	
			$(function(){
				    $('#forgetpass').on('click',function(e){
					var url = "forget.php";
					$.ajax({
					   type: "POST",
					   url: url,
					   data: $("#flogin").serialize(), // serializes the form's elements.
					   success: function(data)
					   {
					      data = JSON.parse(data);
					      $('#alert_placeholder').append('<div id="alertdiv" class="text-right alert '+ data.errTyp+'"><span>'+data.errMSG+'</span></div>');
                                              setTimeout(function() { 
                                                  $("#alertdiv").remove();
                                              }, 3000);
					   }
					});
					e.preventDefault(); 
				     });
				});    
<!------------------------- save information in Login modal form ------------------------->

                            $(document).on('submit',"#flogin", function(e) {
                                var url = "login.php"; // the script where you handle the form input.
                                $.ajax({
				   type: "POST",
				   url: url,
				   data: $("#flogin").serialize(), 
				   success: function(data)
				   {
                             		data = JSON.parse(data);
					typ=data.errTyp;
					$('#alert_placeholder').append('<div id="alertdiv" class="text-right alert '+ data.errTyp+'"><span>'+data.errMSG+'</span></div>');
                                        setTimeout(function() { 
                                            $("#alertdiv").remove();
                                        }, 3000); 
                                        <!---------- toactive and inactive main buttons in top when someone logins--------->
                                        if (typ.localeCompare("alert-success")==0){
                                           document.getElementById("currentuser").innerHTML=data.user+ " خوش آمدید ";
                                           $("#myeditlink").attr('disabled',false);
                                           $("#mylogoutlink").attr('disabled',false);
                                           var link = document.getElementById("myeditlink");
                                           txt="doedit('"+data.id+"')";
                                           link.setAttribute('onclick', txt);
                                           var link = document.getElementById("mylogoutlink");
                                           link.setAttribute('href', "#bs-modal-sm");
                                           location.href='index.php?id='+data.id;
                                        }
                                     }
				});
				e.preventDefault(); 
			    });
						
		     </script>
<!-------------------------    register modal --------------------------------->
<div class="modal fade" id="nav-collapse11" role="dialog">
    <div class="modal-dialog" id="container_id2">
    	<div class="modal-content" id="modal-content2">
            <div id="register" class="animate form">
		<a class="pull-right" href="#" data-dismiss="modal">x</a>	     
                <form  method="post" id="fregister" autocomplete="on"> 
                    <h1> عضویت </h1> 
                    <p> 
                        <label for="usernamesignup" class="uname" style="width:20%!important;">نام کاربری</label>
                        <input id="usernamesignup" name="usernamesignup" required="required" type="text" placeholder="شماره صنفی" 
                        oninvalid="this.setCustomValidity('لطفا نام کاربری را وارد کنید')" oninput="setCustomValidity('')" style="width:50%!important; margin-top:0px!important;"/>
                    </p>
                    <p> 
                        <label for="passwordsignup" class="youpasswd" style="width:20%!important;">رمز عبور </label>
                        <input id="passwordsignup" name="passwordsignup1" required="required" type="password" placeholder="eg. X8df!90EO"
						oninvalid="this.setCustomValidity('لطفا رمز عبورراواردکنید')" oninput="setCustomValidity('')" style="width:50%!important; margin-top:0px!important;"/>
                    </p>
                    <p> 
                        <label for="passwordsignup_confirm" class="youpasswd" style="width:20%!important;">تکرار رمز عبور</label>
                        <input id="passwordsignup_confirm" name="passwordsignup2" required="required" type="password" placeholder="eg. X8df!90EO"
						oninvalid="this.setCustomValidity('لطفا رمز عبورراواردکنید')" oninput="setCustomValidity('')" style="width:50%!important; margin-top:0px!important;"/>
                    </p>
                    <p class="signin button"> 
						
                        <input type="reset" value="خروج" style="background-color:#ff704d!important;" data-dismiss="modal"> 
                        <input type="submit" name='btn-signup' value="عضویت" />
		    </p>
		    <div id="alert_placeholder1"></div>
                    <p class="change_link">  
			<a id="to_loginlink" class="to_register"> ورود به حساب کاربری</a>
						عضو هستید؟
		    </p>
                </form>
            </div>
	</div>
    </div>  
</div>
<!-------------   to save information in register Modal form ----------->
		   <script>
			$("#fregister").submit(function(e) {
                            var url = "register.php"; // the script where you handle the form input.
                            $.ajax({
				type: "POST",
				url: url,
				data: $("#fregister").serialize(), // serializes the form's elements.
				success: function(data)
				{
				    data = JSON.parse(data);
				    $('#alert_placeholder1').append('<div id="alertdiv1" class="text-right alert '+ data.errTyp+'"><a class="close" data-dismiss="alert">×</a><span>'+data.errMSG+'</span></div>');
                                    // to close the alert and remove this if the users doesnt close it in 5 secs                                    
                                    setTimeout(function() { 
					$("#alertdiv1").remove();
                                    }, 2000);
				}
			     });
			     e.preventDefault(); // avoid to execute the actual submit of the form.
			});
		  </script>

<!------DELETE PROFILE ENTIRE MODAL برای حذف اطلاعات بنگاه this section currently has not been used ------------>		  
<div class="modal fade" id="delete_confirmation_modal" role="dialog" style="display: none;">
     <div class="modal-dialog" style="margin-top: 260.5px;">
	<div class="modal-content">
	    <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">آیا واقعا مایلید اطلاعات خود را حذف کنید؟</h4>
	    </div>
	   <form role="form" method="post" id="delete_data">
		<input type="hidden" id="delete_item_id" name="id" />
		<div class="modal-footer" >
		    
		    <input type="reset"  class="btn1 btn-info btn-sm" data-dismiss="modal"  style="font-weight:bold;font-size:12pt;width:10%!important;" value="خیر"/>
                    <input type="submit" class="btn1 btn-danger btn-sm"  style="font-weight:bold;font-size:12pt;width:10%!important;" value="بله" />
		</div>
	    </form>
	</div>
    </div>
</div>	  

<!------------------------ to delete a profile - this function has not been used --------------->
<script>
    $("#delete_data").submit(function(e) {
	var ID=$('#delete_item_id').val();
	var myurl = "delete_amlak.php"; // the script where you handle the form input.
	$.ajax({
	    type: "GET",	
	    url: myurl,
	    data: {'id':ID},
	    success: function(data)
	    {   if (data.trim()=="true"){ alert("رکورد شما حذف شد.");location.reload(); }
		else alert("خطا رخ داد.");
	    }
	});
	$('#delete_confirmation_modal').modal('hide');
        e.preventDefault();
    });
</script>
<!-------------------------- calling bot modal ------------------>
<div class="modal fade" id="callbot_modal" role="dialog" style="display: none;">
    <div class="modal-dialog" id="callbot_demo" >
	<div class="modal-content">
           
           <div  id="calling" class="animate form text-center">
                <a class="pull-right" href="#" data-dismiss="modal">x</a>
                <h4 class="text-center " id="calling_bot"></h4>

                <a style="color:red;" href=<?php echo "https://telegram.me/jamlakbot?start=".$_GET['id'];?>>جهت فعالسازی اینجا را کلیک و سپس بات تلگرام را start نمایید</a>
                
           </div>
	</div>
    </div>
</div>	

<!------------------------ change password Modal --------------->

<div class="modal fade" id="chpass_modal" role="dialog" style="display: none;">
    <div class="modal-dialog" id="chpass_demo" >
	<div class="modal-content">
           <div id="changing" class="animate form">
            <a class="pull-right" href="#" data-dismiss="modal">x</a>
            <form role="form" method="post" id="chpass_modal_form">
		<h1>تغییر رمز عبور</h1> 
                <p><label>نام کاربری</label><input type="text" id="user_id" name="username"/></p>
     	        <p><label>رمز قبلی</label><input type="password" id="pass_id_old" name="passold" /></p>
                <p><label>رمز جدید</label><input type="password" id="pass_id_1" name="passid1" /></p>
                <p><label>تکرار رمز</label><input type="password" id="pass_id_2" name="passid2" /></p>
		<p class="login button"> 
		    
		    <input type="reset"  class="btn btn-danger btn-sm" data-dismiss="modal"  style="font-weight:bold;font-size:12pt;width:10%!important;background-color:#ff704d!important;margin-left:5px;" value="خروج"/>
                    <input type="submit" class="btn btn-info btn-sm"  style="font-weight:bold;font-size:12pt;width:10%!important;" value="تایید" />
		</p>
	    </form>
          </div>
	</div>
    </div>
</div>	
<!------------------------ function foe changing password --------------->
<script>
    $("#chpass_modal_form").submit(function(e) {                                
        var myurl = "chang_pass.php"; // the script where you handle the form input.
        $.ajax({
	    type: "POST",
	    url: myurl,
            data:$("#chpass_modal_form").serialize() ,
	    success: function(data)
	    {   if (data.trim().localeCompare("true")==0){ alert("رمز عبور شما تغییر کرد."); }
                else alert("خطا رخ داد.");
            }
        });
        $('#chpass_modal').modal('hide');
        e.preventDefault();
    });
</script>

<!------------------------ Contact Us Modal --------------->
<div class="modal fade" id="nav-contactus" role="dialog">
    <div class="modal-dialog" id="contactdemo_id">
    	<div class="modal-content" id="modal-contactus">
            <div id="contactusid" class="animate form">
	      <a class="pull-right" href="#" data-dismiss="modal">x</a>	
              <form  method="post" id="contactus" autocomplete="on">                
		<h1>تماس با ما</h1> 
                <p> 
                   <label class="col-md-3 control-label" for="name">نام و نام خانوادگی</label>
                   <input name="name" type="text" id="namecontactid" placeholder="نام شما" class="form-control">
                </p>
		<p>
		   <label class="col-md-3 control-label"  for="mobile">* تلفن همراه</label>
                   <input id="mobileno" name="mobile" type="text" placeholder="همراه شما" class="form-control" onchange="chktel(1,'mobileno')">
                </p>
		<p>
		   <label class="col-md-3 control-label" for="message">متن پیام</label>
		   <textarea class="form-control" maxlength="250" id="msgcontactid" name="message" placeholder="لطفا پیام خود را اینجا تایپ کنید..." rows="5"></textarea>
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
 
<!------------------------ logout Modal --------------->
<div class="modal bs-modal-sm" id="bs-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
     <div class="modal-dialog modal-sm">
	<div class="modal-content">
	    <div class="modal-header"><h4>خروج از حساب کاربری<i class="fa fa-lock"></i></h4></div>
	    <div class="modal-body"><i class="fa fa-question-circle"></i><h4>آیا مطمئن هستید که خارج می شوید؟</h4></div>
	    <div class="modal-footer"><a href="logout.php" class="btn1 btn-danger btn-sm text-center" onclick="dodisable()"> بله </a>
                 <a class="btn1 btn-primary btn-sm" data-dismiss="modal">خیر</a>
	    </div>
	</div>
     </div>
</div>
		
<!--------------------------------- end modals------------------>
<script>
    $(document).on('change', ':file', function() {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });
</script>

</body>


<script>
// ---------- to disable edit link in main menu top -----
function dodisable(){
      $('#myeditlink').attr('disabled',true);
      var link = document.getElementById("myeditlink");
      link.setAttribute('onclick', "");
}

function doadd(){
      $('#myModal').modal('show');
}
//------------------------------------
function dodelete(ID){

    document.getElementById('delete_item_id').value=ID;
    $('#delete_confirmation_modal').modal('show');
}
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
}//------------------------------------

function filter() { 
  var tmpMahal = document.getElementById("filtermahalid").value;   
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
//alert("xhttp response is:" + xhttp.responseText);
     document.getElementById("main_grid_id").innerHTML = xhttp.responseText;    
    }
  };
  var url = "grid.php?dummy=" + Date();
  if(tmpMahal.localeCompare("همه محلها")!=0)
     url = url + "&mahal=" + encodeURIComponent(tmpMahal);
//alert(url);

  xhttp.open("GET", url, true);
  xhttp.send();
   
  //for footer for now, to amend soon en shaa Allah
  var xhttp2 = new XMLHttpRequest();
  xhttp2.onreadystatechange = function() {
    if (xhttp2.readyState == 4 && xhttp2.status == 200) {
       document.getElementById("footer_id").innerHTML = xhttp2.responseText;    
    }
  };

  var url2 = "footer.php?dummy=" + Date();
  if(tmpMahal.localeCompare("همه محلها")!=0)
    url2 = url2 + "&mahal=" + encodeURIComponent(tmpMahal);
  
  xhttp2.open("GET", url2, true);
  xhttp2.send();
}
//------------------------------------

function add_details (ID){
 
//inefficiency here by reading more than once from DB. to use a local hidden flag soon esA
  if(document.getElementById("hidden"+ID).style.display.localeCompare("none")==0)
  {    
    var xhttp = new XMLHttpRequest();
    fd=new FormData();
    fd.append('ID',ID); 
    xhttp.onreadystatechange = function() {
      if (xhttp.readyState == 4 && xhttp.status == 200) {
         var obj = JSON.parse(xhttp.responseText); 
         document.getElementById("mahal"+ID).style.display = "none";

         document.getElementById("hidden"+ID).style.display = "inline";
         document.getElementById("detailid"+ID).innerHTML = "پنهان جزییات";
         if(obj.addr.length > 0)
              document.getElementById("addr"+ID).innerHTML=obj.addr; 
         if (obj.t.length > 0)
              document.getElementById("t"+ID).innerHTML=obj.t;
         if ((obj.m.length > 0)  && (obj.t!==obj.m))
              document.getElementById("m"+ID).innerHTML=obj.m;
         else
              document.getElementById("m"+ID).innerHTML='';

         if(obj.hamkaran.length > 0)
              document.getElementById("hamkaran"+ID).innerHTML=obj.hamkaran;
         if(obj.tozihat.length > 0)
              document.getElementById("tozihat"+ID).innerHTML=obj.tozihat;
       }
     };
     var url = "details.php"; 
     xhttp.open("POST", url, true);
     xhttp.send(fd);
  }  
  else//NOTE: assumed either none or inline only
  {
     document.getElementById("hidden"+ID).style.display = "none";
     document.getElementById("detailid"+ID).innerHTML = "نمایش جزییات";
     document.getElementById("mahal"+ID).style.display = "inline";

  }
}
//------------------------------------

function dosendmsg(){
      mobile=document.getElementById("mobileno").value;
      name=document.getElementById("namecontactid").value;
      message=document.getElementById("msgcontactid").value;
          var xhttp = new XMLHttpRequest();
          fd=new FormData();
          fd.append('mobile',mobile);
          fd.append('name',name);
          fd.append('message',message);
          xhttp.onreadystatechange = function() {
             if (xhttp.readyState == 4 && xhttp.status == 200) {
               //alert(xhttp.responseText);
               if (xhttp.responseText)
                    alert("با تشکر، پیام شما با موفقیت ثبت شد و در اسرع وقت مورد رسیدگی قرار میگیرد.");  
               else
                    alert("خطا در ثبت پیام!!!");               
            }
          };
          var url = "contactus.php"; 
          xhttp.open("POST", url, true);
          xhttp.send(fd);
       
}
//------------------------------------

</script>



<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-88743171-2', 'auto');
  ga('send', 'pageview');

</script>




<a href="kermanshah.php"> املاک کرمانشاه </a>

</html>
<?php fclose($file); ?>

