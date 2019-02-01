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
?>




<!DOCTYPE html>
<html>
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  
  <link href="bootstrap3.css" rel="stylesheet">  
  
  <link rel="stylesheet" href="http://cafebots.ir/bootstrap-rtl.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <style>
    /* Remove the navbar's default margin-bottom and rounded borders */ 
     .modal-header, .modal-header > h4, .close {
      background-color: #5cb85c;
      color:white !important;
      text-align: center;
      font-size: 30px;
  }
  .logo_img{
      width:100px;
      heigth:100px;
   }
   .c_img{
      width:80px;
      height:80px;
  }

  .my_well{
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
.my_well_inside{text-align:right;}
.add_to{text-align:left;}
  .modal-footer {
      background-color: #f9f9f9;
  } 	
    .navbar {
      margin-bottom: 0;
      border-radius: 0;
    }
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
    .row.content {height: 450px}
    
    /* Set gray background color and 100% height */
    .sidenav {
      padding-top: 20px;
      background-color: #f1f1f1;
      height: 100%;
    }
    
    /* Set black background color, white text and some padding */
    footer {
      background-color: #555;
      color: white;
      padding: 15px;
    }
    
    /* On small screens, set height to 'auto' for sidenav and grid */
    @media screen and (max-width: 767px) {
      .sidenav {
        height: auto;
        padding: 15px;
      }
      .row.content {height:auto;} 
    }
    

  </style>
</head>
<body>

<nav class="navbar navbar-inverse">
  <button type="button" class="btn btn-danger btn-lg" id="myBtn" onclick="doadd()"><span class="glyphicon glyphicon-plus"> افزودن کانال شما
</span></button>
</nav>
  
<div class="container-fluid text-center">    
  <div class="row content">
    <div class="col-sm-2 sidenav text-right">
      <div class="panel-group" id="accordion">

        <div class="panel panel-primary">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-parent="#accordion" onclick='filter("آموزشی")'><span class="badge"><?php echo chcount("آموزشی"); ?> </span>آموزشی</a>
            </h4>
          </div>
        </div>
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-parent="#accordion" onclick='filter("اجتماعی")'><span class="badge"><?php echo chcount("اجتماعی"); ?> </span>اجتماعی</a>
            </h4>
          </div>
        </div>
     <div class="panel panel-primary">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-parent="#accordion" onclick='filter("اخبار")'><span class="badge"><?php echo chcount("اخبار"); ?> </span>اخبار</a>
        </h4>
      </div>
     </div>
     <div class="panel panel-primary">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-parent="#accordion" onclick='filter("اقتصادی")'><span class="badge"><?php echo chcount("اقتصادی"); ?> </span>اقتصادی</a>
        </h4>
      </div>
     </div>
     <div class="panel panel-primary">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">بانوان</a>
        </h4>
      </div>
      <div id="collapse1" class="panel-collapse collapse">
        <ul class="list-group">
			<li class="list-group-item" onclick='filter("آشپزی")'><span class="badge"><?php echo chcount("آشپزی"); ?> </span> آشپزی</li>
			<li class="list-group-item" onclick='filter("تربیت کودک")'><span class="badge"><?php echo chcount("تربیت کودک"); ?> </span> تربیت کودک</li>
                        <li class="list-group-item" onclick='filter("خانه")'><span class="badge"><?php echo chcount("خانه"); ?> </span> خانه داری</li>                                                
                        <li class="list-group-item" onclick='filter("مد و زیبایی")'><span class="badge"><?php echo chcount("مد و زیبایی"); ?> </span> مد و زیبایی</li>
		</ul>
      </div>
    </div>     
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-parent="#accordion" onclick='filter("بهداشت و سلامت")'><span class="badge"><?php echo chcount("بهداشت و سلامت"); ?> </span>بهداشت و سلامت</a>
        </h4>
      </div>
     </div>
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-parent="#accordion" onclick='filter("تکنولوژی")'><span class="badge"><?php echo chcount("تکنولوژی"); ?> </span>تکنولوژی</a>
        </h4>
      </div>
     </div> 
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">سرگرمی</a>
        </h4>
      </div>
      <div id="collapse2" class="panel-collapse collapse">
        <ul class="list-group">
			<li class="list-group-item" onclick='filter("انیمیشن")'><span class="badge"><?php echo chcount("انیمیشن"); ?> </span> انیمیشن</li>
			<li class="list-group-item" onclick='filter("بازی")'><span class="badge"><?php echo chcount("بازی"); ?> </span> بازی</li>
			<li class="list-group-item" onclick='filter("کلیپ/فیلم/ویدیو")'><span class="badge"><?php echo chcount("کلیپ/فیلم/ویدیو"); ?> </span> کلیپ/فیلم/ویدیو</li>
			<li class="list-group-item" onclick='filter("طنز وفکاهی")'><span class="badge"><?php echo chcount("طنز وفکاهی"); ?> </span> طنز وفکاهی</li>
                        <li class="list-group-item" onclick='filter("عکس")'><span class="badge"><?php echo chcount("عکس"); ?> </span> عکس</li>
                        <li class="list-group-item" onclick='filter("مجله")'><span class="badge"><?php echo chcount("مجله"); ?> </span> مجله</li>
                        <li class="list-group-item" onclick='filter("موزیک")'><span class="badge"><?php echo chcount("موزیک"); ?> </span> موزیک</li>
  		</ul>
		</div>
    </div>
     <div class="panel panel-primary">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-parent="#accordion" onclick='filter("عاطفی")'><span class="badge"><?php echo chcount("عاطفی"); ?> </span>عاطفی</a>
        </h4>
      </div>
     </div>
      <div class="panel panel-primary">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-parent="#accordion" onclick='filter("علمی")'><span class="badge"><?php echo chcount("علمی"); ?> </span>علمی</a>
        </h4>
      </div>
     </div>
     <div class="panel panel-primary">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-parent="#accordion" onclick='filter("گردشگری")'><span class="badge"><?php echo chcount("گردشگری"); ?> </span>گردشگری</a>
        </h4>
      </div>
     </div>
       <div class="panel panel-primary">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-parent="#accordion" onclick='filter("مذهبی")'><span class="badge"><?php echo chcount("مذهبی"); ?> </span>مذهبی</a>
        </h4>
      </div>
     </div>
     
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">مشاغل</a>
        </h4>
      </div>
      <div id="collapse3" class="panel-collapse collapse">
        <ul class="list-group">
			<li class="list-group-item" onclick='filter("آژانس سیاحتی زیارتی")'><span class="badge"><?php echo chcount("آژانس سیاحتی زیارتی"); ?> </span>آژانس سیاحتی زیارتی</li>
			<li class="list-group-item" onclick='filter("پرده سرا")'><span class="badge"><?php echo chcount("پرده سرا"); ?> </span> پرده سرا</li>
			<li class="list-group-item" onclick='filter("خودرو / موتور")'><span class="badge"><?php echo chcount("خودرو / موتور"); ?> </span>خودرو / موتور</li>
                        <li class="list-group-item" onclick='filter("خدمات عمومی")'><span class="badge"><?php echo chcount("خدمات عمومی"); ?> </span>خدمات عمومی</li>
                        <li class="list-group-item" onclick='filter("خدمات کامپیوتری")'><span class="badge"><?php echo chcount("خدمات کامپیوتری"); ?> </span>خدمات کامپیوتری</li>
                        <li class="list-group-item" onclick='filter("خدمات مهندسی")'><span class="badge"><?php echo chcount("خدمات مهندسی"); ?> </span>خدمات مهندسی</li>
                        <li class="list-group-item" onclick='filter("رستوران")'><span class="badge"><?php echo chcount("رستوران"); ?> </span>رستوران</li>
                        <li class="list-group-item" onclick='filter("دکوراسیون")'><span class="badge"><?php echo chcount("دکوراسیون"); ?> </span>دکوراسیون</li>
                        <li class="list-group-item" onclick='filter("فروشگاه")'><span class="badge"><?php echo chcount("فروشگاه"); ?> </span>فروشگاه</li>
                        <li class="list-group-item" onclick='filter("قنادی")'><span class="badge"><?php echo chcount("قنادی"); ?> </span>قنادی</li>
                        <li class="list-group-item" onclick='filter("سالن زیبایی/آرایشگاه")'><span class="badge"><?php echo chcount("سالن زیبایی/آرایشگاه"); ?> </span>سالن زیبایی/آرایشگاه</li>
                        <li class="list-group-item" onclick='filter("کابینت")'><span class="badge"><?php echo chcount("کابینت"); ?> </span> کابینت</li>              
                        <li class="list-group-item" onclick='filter("کاریابی")'><span class="badge"><?php echo chcount("کاریابی"); ?> </span> کاریابی</li>
                        <li class="list-group-item" onclick='filter("کودک وسیسمونی")'><span class="badge"><?php echo chcount("کودک وسیسمونی"); ?> </span> کودک وسیسمونی</li>
                        <li class="list-group-item" onclick='filter("گرافیک/هنر/طراحی")'><span class="badge"><?php echo chcount("گرافیک/هنر/طراحی"); ?> </span> گرافیک/هنر/طراحی</li>
                        <li class="list-group-item" onclick='filter("مزون")'><span class="badge"><?php echo chcount("مزون"); ?> </span>مزون</li>
                        <li class="list-group-item" onclick='filter("موسسه آموزشی/مدارس")'><span class="badge"><?php echo chcount("موسسه آموزشی/مدارس"); ?> </span>موسسه آموزشی/مدارس</li>
                        <li class="list-group-item" onclick='filter("موبایل")'><span class="badge"><?php echo chcount("موبایل"); ?> </span> موبایل</li>
                        <li class="list-group-item" onclick='filter("باشگاه ورزشی")'><span class="badge"><?php echo chcount("باشگاه ورزشی"); ?> </span> باشگاه ورزشی</li>
                        <li class="list-group-item" onclick='filter("هتل/مهمانسرا")'><span class="badge"><?php echo chcount("هتل/مهمانسرا"); ?> </span>هتل/مهمانسرا</li>
                        <li class="list-group-item" onclick='filter("سایر")'><span class="badge"><?php echo chcount("سایر"); ?> </span>سایر</li>

		</ul>
      </div>
    </div>
      <div class="panel panel-primary">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-parent="#accordion" onclick='filter("ورزشی")'><span class="badge"><?php echo chcount("ورزشی"); ?> </span>ورزشی</a>
        </h4>
      </div>
     </div>
      <div class="panel panel-primary">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-parent="#accordion" onclick='filter("سایر")'><span class="badge"><?php echo chcount("سایر"); ?> </span>سایر</a>
        </h4>
      </div>
     </div>




   
      </div> 
     
    </div>    

    <div id="main_grid" class="col-sm-10 text-left">
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
             کانالهای تلگرام: همه
      </h3>

<?php
$per_page = 30;  
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
$start_from = ($page-1) * $per_page;  
$sql = "select `chid`, `name`, `class`, `image`, `desc` from `main` ORDER BY ID ASC LIMIT $start_from, $per_page" ;

$result = $GLOBALS['conn']->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
?>
<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <div class="my_well">
        
        <div class="float_R" style="margin: 0px 8px 8px 10px ">
            <a href=""><img class="c_img img-circle" src= <?php echo '"http://cafebots.ir/img/' . $row["image"] . '"'; ?>" alt=<?php echo '"' . $row["name"] . '"'; ?>></a>
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
?>

 
 
    </div>
    </div>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4>لطفااطلاعات زیرراواردکنید</h4>
        </div>
        <div class="modal-body" style="padding:40px 50px;">
          <form role="form" method="post">
            <div class="form-group">
              <label for="nameid"> نام کانال</label>
              <input type="text" class="form-control" id="nameid" name="name">
            </div>
            <div class="form-group">
              <label for="chidid">آی دی کانال</label>
              <input type="text" class="form-control" id="chidid" name="chid" placeholder="@">
            </div>
            <div class="form-group">
              <label for="classid"> دسته بندی کانال</label>
              <select class="form-control selectpicker" id="classid" name="class" data-live-search="true">
                   <option>آموزشی</option>
                   <option>اجتماعی</option>
                   <option>اخبار</option>
                   <option>اقتصادی</option>
                   <optgroup label="بانوان">
                       <option>آشپزی</option>
                       <option>تربیت کودک</option>
                       <option>خانه داری</option>
                       <option>مد و زیبایی</option>
                   </optgroup>
                   <option>بهداشت و سلامت</option>
                   <option>IT تکنولوژی</option>

                   <optgroup label="سرگرمی">
                       <option>انیمیشن</option>
                       <option>بازی</option>
                       <option>کلیپ/فیلم/ویدیو</option>
                       <option>طنز وفکاهی</option>
                       <option>عکس</option>
                       <option>مجله</option>
                       <option>موزیک</option>
                   </optgroup>
                   <option>عاطفی</option>
                   <option>علمی</option>
                   <option>گردشگری</option>
                   <option>مذهبی</option>

                   <optgroup label="مشاغل">
                        <option>آژانس سیاحتی زیارتی</option>
                        <option>پرده سرا</option>
                        <option>خودرو/موتور</option>
                        <option>خدمات عمومی</option>
                        <option>خدمات کامپیوتری</option>
                        <option>خدمات مهندسی</option>
                        <option>رستوران</option>
                        <option>دکوراسیون</option>
                        <option>فروشگاه</option>
                        <option>قنادی</option>
                        <option>سالن زیبایی/آرایشگاه</option>
                        <option>کابینت</option>
                        <option>کاریابی</option>
                        <option>کودک وسیسمونی</option>
                        <option>گرافیک/هنر/طراحی</option>
                        <option>مزون</option>
                        <option>موسسه آموزشی/مدارس</option>
                        <option>موبایل</option>
                        <option>باشگاه ورزشی</option>
                        <option>هتل/مهمانسرا</option>
                        <option>سایر</option>
                   </optgroup>
                   <option>ورزشی</option>
                   <option>سایر</option>

              </select>
            </div>
            
            <div class="form-group">
              <label for="descid">توضیح کانال</label>
              <input type="text" class="form-control text-right" id="descid" name="desc" placeholder="... این کانال">
            </div>
            <!--div class="form-group">
              <label for="imageid">تصویر کانال</label>
              <input type="file" id="imageid" name="image" title="" class="form-control" >
	    </div-->
              <div class="modal-footer">
		  <input onclick="chsubmit()" name="submit" value="ثبت" class="btn btn-success btn-default">
                  <input type="reset" value="انصراف" class="btn btn-danger btn-default" data-dismiss="modal">
        </div>
          </form>
        </div>
        
      </div>
      
    </div>
  </div>
</div>

<footer class="container-fluid text-center">

<?php 
$sql = "select count(*) AS N from `main`";
$result = $GLOBALS['conn']->query($sql);
$row = $result->fetch_assoc();
$total = $row["N"];
$GLOBALS['conn']->close();
echo mypagination("?", $page, $total, $per_page); 
?>

</footer>

</body>



<script>
function doadd(){
            $('#myModal').modal('show');
}
function filter(classname) {    
 //alert (classname);
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
     //alert("returned text is:" + xhttp.responseText);
     document.getElementById("main_grid").innerHTML = xhttp.responseText;    
    }
  };
  xhttp.open("GET", "filter.php?classname=" + classname, true);
  xhttp.send();
}


function search() 
{    
  var search_text = document.getElementById("search_text_id").value.trim();
  if(search_text != "")
  {  
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (xhttp.readyState == 4 && xhttp.status == 200) {
       //alert("returned text is:" + xhttp.responseText);
       document.getElementById("main_grid").innerHTML = xhttp.responseText;    
      }  
  };
  xhttp.open("GET", "filter.php?search_text=" + search_text, true);
  xhttp.send();
  }

}


function openWin(targeturl) {
  //alert ("salaam2222");
   window.open(targeturl);
}

function chsubmit()
{
  //alert ("salaam");
  var chid = document.getElementById("chidid").value.trim();
  var name = document.getElementById("nameid").value.trim();
  var classname = document.getElementById("classid").value.trim();
  var desc = document.getElementById("descid").value.trim();

 // alert ("info" + chid + "  ,  " + name + "  ,  " + classname + "  ,  " + desc );

  if( (chid != "") && (name != "") && (classname != "") && (desc != "") )
  {  
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (xhttp.readyState == 4 && xhttp.status == 200) {
       if(xhttp.responseText== "true")
         alert ("کانال شما با موفقیت ثبت شد و" . "\n" . "پس از تایید نهایی ظرف یک ساعت در سایت قابل مشاهده خواهد بود.");
       else
         alert ("خطا در ثبت داده ها صورت گرفت و متاسفانه کانال ثبت نشد.");
      }  
    };
    xhttp.open("GET", "savech.php?chid=" + chid + "&name=" + name + "&classname=" + classname + "&desc=" + desc, true);
    xhttp.send();
  }
  else
  {
    alert ("لطفاً فرم را به طور کامل پر بفرمایید.");
  }

}


</script>




</html>


