<?php
session_start();
include "scripts/connect_to_mysql.php";
//cart development

?>
<?php 
// Script Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<?php 
// Check to see the URL variable is set and that it exists in the database
if (isset($_GET['id'])) {
  // Connect to the MySQL database  
  include "scripts/connect_to_mysql.php"; 
  $id = preg_replace('#[^0-9]#i', '', $_GET['id']); 
  // Use this var to check to see if this ID exists, if yes then get the product 
  // details, if no then exit this script and give message why
 
  
$sql = "SELECT * FROM products WHERE product_id='$id' LIMIT 1";
$result = mysqli_query($conn, $sql);
$mysqli_result = mysqli_num_rows($result); // count the output amount
if ($mysqli_result > 0) {
  while($row = mysqli_fetch_array($result)){ 
             $id = $row["product_id"];
       $product_name = $row["product_name"];
       $subcategory = $row["subcategory"];
       $category = $row["category"];
       $oprice = $row['oprice'];
       $size = $row['size'];
       $color = $row['colour'];
       $price = $row["price"];
       $po = floor((($oprice - $price)/$price)*100);
       $details = $row['details'];
       $instock = $row['instock'];
       $rare = $row['rare'];
       $date_added = strftime("%b %d, %Y", strtotime($row["date_added"]));
     ;

           
       }

    }
} else {
  $dynamicList = "We have no products listed in our store yet";
}

function colours(){

include "scripts/connect_to_mysql.php";
$id= preg_replace('#[^0-9]#i', '', $_GET['id']);
$avcolour = '';
$data = "SELECT colour FROM products WHERE product_name IN (SELECT product_name FROM products WHERE product_id=$id) GROUP BY colour";
$output = mysqli_query($conn, $data);

while($row2 = mysqli_fetch_array($output)){

  $avcolour .= '<option value="'.$row2["colour"].'">'.$row2["colour"].'</option>'; 
}
return $avcolour;
}

function similar(){
 include "scripts/connect_to_mysql.php";
 $id= preg_replace('#[^0-9]#i', '', $_GET['id']);
 $similar = "";
 $data2 = "SELECT * FROM products WHERE category IN (SELECT category FROM products WHERE product_id=$id) OR subcategory IN (SELECT subcategory FROM products WHERE product_id=$id) LIMIT 6";
 $output = mysqli_query($conn, $data2);
 while($row3 = mysqli_fetch_array($output)){
  $id = $row3['product_id'];
  $product_name = $row3['product_name'];
  $price = $row3['price'];
  $similar .= '
  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4"><a href="product.php?id='. $id .'"><img src="product_images/' . $id . 'f.jpg" style="width:100%;"><h6><em>' . $product_name . '</em></h6>
<h5 style="line-height:0px; text-align:left;"><strong>&#8358;' . $price . '</strong></h5></a></div>';
}
return $similar;
}

//mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Fascents Closet</title>
<link rel="icon" href="file:///C|/xamppnewest/htdocs/fc/fc_images/fc-icon.png">

<!-- Bootstrap -->
<link rel="stylesheet" href="css/css/bootstrap.css">
<link rel="stylesheet" href="css/animate.css">
<!--materialize-->
    <link type="text/css" rel="stylesheet" href="../css/materialize.min.css" media="screen,projection" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="Inuse/css/easyzoom.css" /> 

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


<!-- Custom Css-->
<style>
.btn {
  font-size: 0.8rem;
  padding: 0.85rem 2.13rem;
  
  border-radius: 2px;
  border: 0px;
  transition: .2s ease-out;
  margin: 6px;
  white-space: normal !important;
  word-wrap: break-word;
  text-transform: uppercase;

}

.btn:hover{
box-shadow: 0 3px 8px 0 rgba(0, 0, 0, 0.2), 0 2px 10px 0 rgba(0, 0, 0, 0.19) !important;
transition:box-shadow 0.2s ease-in-out;
}


.flip3d {
margin: 0px;
border-radius:0px;
  
}


.flip3d .front {

-webkit-transform: perspective( 600px ) rotateY( 0deg );
transform: perspective( 600px ) rotateY( 0deg );
;
border-radius: 0px;
backface-visibility: hidden;
transition: transform .3s ease-out 0s;

}


.flip3d .back {
position: absolute;
-webkit-transform: perspective( 600px ) rotateY( 180deg );
transform: perspective( 600px ) rotateY( 180deg );
background-image:none;
border-radius: 0px;
backface-visibility: hidden;
transition: transform .3s ease-out 0s;

}



    .thumbnail1:hover img.info {
   
    -ms-transform:scale(1.2);
-webkit-transform:scale(1.2);
transform:scale(1.2);
    }

    .thumbnail1 img.info {
      transition:all 0.2s linear;
      width:100%;
      height:100%;
      border:0;
      padding:0;
      margin:0;


    }
    .thumbnail1{
      overflow:hidden;
      position:relative;
      float:center;
      text-align:center;
border:0;
padding:0;
margin:0;
    }

    .thumbnail1 a.info {
text-decoration:none;
display:inline-block;
float:center;
text-transform:uppercase;
color:#fff;
border:1px solid #fff;
background-color:transparent;
opacity:1;
margin:50px;
margin-top:90%;
padding:2px 5px;
}
.thumbnail1 a.info:hover{
box-shadow: 1 1 5px #fff;
background-color:#fff;
color:#000;
}

.thumbnail1:hover a.info {
opacity:1;

}

.thumbnail1:hover a.info {
-webkit-transition-delay:.2s; transition-delay:.2s;
}

.easyzoom {
    float: none;
}
.easyzoom img {
    display: block;
}
.easyzoom {
    display: inline-block;
}
.easyzoom img {
    vertical-align: bottom;
}

.op {
text-decoration:line-through;
color: #888;
line-height:30px;
float:right;

border:0;
padding:0;
margin:0;
margin-right:10px;

}
.po{
color:red;
float:right;
padding:0;
margin:0;
margin-right:10px;
line-height:0px;

}

.btnfcblck {
border-radius:0px !important;
border-color: #777;
 background: #313131!important;
color: #ddd;
}

.btnfcblck:hover{
 background-color: #191919 !important;
color: #fff;
transition:all 0.1s linear;
      
}
.btnfcblck:focus{
background-color:black;
color: #fff;
transition:all 0.1s linear;
      
}

.btnfcgld {
border-radius:0px !important;
border:0px solid #ffb820;
background-color:#ffb820;
color:black;

}
.btnfcgld:hover{
background-color: #ffc930;
color:black;
transition:all 0.1s linear;

}
.modal-content {
  box-shadow: 0 8px 17px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19) !important;
border-radius:4px;

background-color: #fff;


}
 .form-control
  { border-radius:0px !important;
    border:1px solid #999;
    border-top: none;
    border-left:none;
    border-right:none;
}
.thumbnails{
 display: inline-block;
  width:114 !important;
  height:140;

}
.thumbnails li {

  display: inline-block;
  
}
.thumbnails img {
  display: block;
  
}
    </style>
<script>
function flip(el){
  
  el.children[1].style.transform = "perspective(600px) rotateY(-180deg)";
  el.children[1].style.opacity="0.1";
  el.children[0].style.transform = "perspective(600px) rotateY(0deg)";
  el.children[0].style.opacity="1";


}

function flipback(el){
  
  el.children[1].style.transform = "perspective(600px) rotateY(0deg)";
  el.children[0].style.transform = "perspective(600px) rotateY(180deg)";
el.children[1].style.opacity="1";
el.children[0].style.opacity="0.1";

}
</script>

<!--/ End custom css-->
 <script type="text/javascript">
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
  </script>
  <style>
.navbar {
  box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12) !important;
}

.thumbnail:hover
{
box-shadow: 0 8px 17px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19) !important;
transition:box-shadow 0.2s ease-in-out;}

.thumbnail {
  box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12) !important;
}
  </style>
  <script src="js/jquery-3.1.1.min.js"></script>
  </head>

  <body onLoad="MM_preloadImages('fc_images/fc-icon-gold.png','fc_images/fc-header-gold.png','fc_images/twitter.png','fc_images/facebook.png','fc_images/instagram.png','fc_images/youtube.png','fc_images/pinterest.png','fc_images/cart-icon2.png')">

    <!-- Fixed navbar -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-header">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <div class="navbar-brand" style="align: center;">
         <a class="navbar-brand" href="#">
        </a><a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image7','','fc_images/fc-icon-gold.png',1)"><img src="fc_images/fc-icon-inv.png" alt="" width="32" height="42" id="Image7" style="margin-top: -10px; margin-left: -40px; padding:0px;">
        </a></div>
          <div class="navbar-header"><a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image8','','fc_images/fc-header-gold.png',1)"><img src="fc_images/fc-header-inv.png" alt="" width="245" height="42" id="Image8" class="img-responsive" style="position: fixed; margin-left: 45px; margin-top: 5px;"></a></div>
      
    </div>
 
        
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="index.php">Home</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" id="btn3">Blog <span class="caret"></span></a>
             <ul class="dropdown-menu" id="d3">
                <li><a href="#">Latest gist</a></li>
                <li><a href="#">DiYs</a></li>
                <li><a href="#">FC Style Guide</a></li>
                <li><a href="#">Customer Showcase</a></li>
              <li><a href="#">Gallery</a></li></ul></li>
                
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" id="btn4">Shop <span class="caret"></span></a>
              <ul class="dropdown-menu" id="d4">
                <li class="dropdown-header">Categories</li>
                <li><a href="#">Ladies</a></li>
                <li><a href="#">Men</a></li>
                <li><a href="#">Accessories</a></li>
                <li role="separator" class="divider"></li>
                <li class="dropdown-header">Subcategories</li>
                <li><a href="#">Beauty</a></li>
                <li><a href="#">Rare Items</a></li>
                <li><a href="#">Deals/Under 3k</a></li>
                </ul>
             </li>
             <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" id="btn5">FC <span class="caret"></span></a>
              <ul class="dropdown-menu" id="d5">
              <li><a href="#">Tailor</a></li>
                <li><a href="#">Size Guide</a></li>
              <li><a href="#">FAQs</a></li>
                <li><a href="#">The Forum</a></li>
                <li><a href="#">Meet the FC Squad</a></li>
                <li><a href="#">Legals</a></li>
                </ul>
            </li>
             <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" id="btn6">Sign in <span class="caret"></span></a>
              <ul class="dropdown-menu" id="d6">
                
                <form class="navbar-form navbar-center">
            <input type="text" class="form-control" width="90%" style="margin-left: 4px; width: 98%;"; placeholder="Username...">
            <input type="text" class="form-control" style="margin-top: 4px; margin-left: 4px; width: 98%;" placeholder="Password...">
            <div style="margin-top: 4px; margin-left: 4px;" class="checkbox">
          <label>
            <input type="checkbox" value="remember-me"> Remember me
          </label>
        </div>
            <div style="margin-top: 4px; margin-left: 4px;"><button type="submit" class="btn btn-sm btn-success">Log in</button><button type="submit" style="margin-left: 4px;" class="btn btn-sm btn-warning">Sign up</button></div>
          </form>
            </ul></li>
            
          <li> <a href="#cart" data-toggle="modal" class="" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image9','','fc_images/cart-icon.png',1)"><img src="fc_images/cart-icon1grey.png" alt="" width="28" height="26" style="margin-bottom:-4px; margin-top:-4px;" id="Image9"><span class="badge" style="background-color:green;"><?php if(isset($_SESSION["shopping_cart"])){echo count($_SESSION["shopping_cart"]);} else {echo '0';}?></span> My Cart &nbsp; </a></li>
          </ul>
        </div>
      </div>
    </nav>
<br/><br/>
<!---=============================Modal Cart begins here ====================-->
 <div class="modal fade" id="cart" role="dialog" style=" align:center;">
<div class="modal-dialog">
   
    <div class="modal-content" style="overflow:hidden;" >
       <div class="modal-header" style="overflow:hidden;"><h1>Your Cart</h1></div>
      <div class ="modal-body" style="overflow: hidden; margin-left: 0px; align:center;" id="order_table">
  </div><div class="modal-footer" style="overflow:hidden;"><h1>Proceed to Checkout</h1><h1>continue shopping</h1></div></div></div></div>
<!---=============================Modal Cart ends here ====================-->

<div class="container"><legend><h2 class="animated fadeInDown" id="product_name"><?php echo $product_name; ?></h2></legend></div>
       <div class="container">
       <div class="row">
       <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12 clearfix">
       <div class="easyzoom easyzoom--adjacent is-ready easyzoom--with-thumbnails">
        <a href="product_images/<?php echo $id?>f.jpg" class="imgf">
          <img src="product_images/<?php echo $id?>f.jpg" class= "img-responsive" id="imgf" alt=""/>
        </a>
      </div><hr/>
      <ul class="thumbnails" style="margin-left: 0px !important; padding-left:0px !important;">
        <li>
          <a href="product_images/<?php echo $id?>f.jpg" data-standard="product_images/<?php echo $id?>f.jpg" class="imgf">
            <img src="product_images/<?php echo $id?>f.jpg" class="imgf" alt="" style="width:94px !important; height:125px !important;"/>
          </a>
        </li>
        <li>
          <a href="product_images/<?php echo $id?>s.jpg" data-standard="product_images/<?php echo $id?>s.jpg" class="imgs">
            <img src="product_images/<?php echo $id?>s.jpg" class="imgs" alt="" width="94" height="125"/>
          </a>
        </li>
        <li>
          <a href="product_images/<?php echo $id?>b.jpg" data-standard="product_images/<?php echo $id?>b.jpg" class="imgb">
            <img src="product_images/<?php echo $id?>b.jpg" class="imgb" alt="" width="94" height="125"/>
          </a>
        </li>
       
      </ul></div><div class="col-mid-6 col-lg-6 col-sm-6 col-xs-12"><div class="row"><div class="col-lg-7 col-md-7 col-sm-7 col-xs-7" style="padding-right:0px;margin-right:0px;"><h2><?php echo $product_name?></h2></div><div class="col-lg-5 col-md-5 col-sm-5 col-xs-5"> <?php if($po > 0){?><a href="sale.php"><img style="float:right; width:55px;" src="fc_images/salenew.png"></a><?php }?><?php if($rare != 'no'){?><img style="float:right; width:45px;" src="fc_images/rarenew.png"><?php }?></div></div><hr/><?php if($oprice != 0){ ?><h5 class="po" style="line-height:-0px; margin-right:0px;"><strong><em>&darr;<?php echo $po;?>%</em></strong></h5><?php } ?><h3 style="line-height:0px; text-align:left;"><strong>&#8358;<?php echo number_format($price, 2);?></strong></h3><?php if($oprice > 0){ ?><h5 class="op" style = "line-height:5px !important; text-align:right; margin-right:0px;">&#8358;<?php echo number_format($oprice, 2); ?></h5><?php } ?><hr/>
      <p>Category : <strong><?php echo $category.', '. $subcategory;?></strong></p> <hr/>
      <p>Details : <em><?php echo $details?></em></p><hr/>
       <div class="input-group">
       <p style="margin:0px; padding:0px; border:0px;">Select Product Options</p>
       <p style="font-size:11px;">(Wondering what your size is? Take a look at the <a href="faqs.php">size guide</a> to help you out)</p>
      
  <div class="form-group col-sm-4 col-xs-4 col-md-4" style="margin:0px;">      
        <label>Size</label>
        <select class="form-control" name="size" id="size">
        <option value="<?php echo $size?>"><?php echo $size?></option>
          </select></div>

       <div class="form-group col-sm-4 col-xs-4 col-md-4" style="margin-left:auto;">
        <label>Colour</label>
        <select class="form-control" name="colour" id="colour" placeholder="colour">
       <?php echo colours();?>
          
          </select></div>
          <div class="form-group col-sm-4 col-xs-4 col-md-4">      
        <label>Qty</label>
        <select class="form-control" name="quantity" id="quantity">
        <?php $i=""; while($i <= $instock){ ?><option value="<?php echo $i?>"><?php echo $i?></option>
          
          <?php ; $i++;} ?>
          </select></div><div class="col-sm-12 col-xs-12 col-md-12">
      <input type="hidden" id="<?php echo $id;?>" value="<?php echo $id;?>">
        <input  id="product_id" type="hidden" value="<?php echo $id;?>">
         <input  id="price" type="hidden" value="<?php echo $price;?>">
          
        <input class="btn btn-warning btn-md" style="width:100%;" type="submit" name="add_to_cart" id="add_to_cart" value="Add To Cart" />
         </div>
        
 </div>
 <div class="btn-group btn-group-justified" role="group" aria-label="...">
              
             <div class="btn-group" role="group">
            <a href="#"><button type="button" class="btn btn-secondary">Details &raquo;</button></a>
          </div>
          <div class="btn-group" role="group">
              <a href="#quickview" data-toggle="modal"><button type="button" class="btn btnfcblck">Back</button></a>
            </div>
              </div>
      </div></div></div>
 

<hr>
<div class="container">

<legend>You might also like</legend>
<div class= "container "><div class="row wow animated fadeInRight">
<?php echo similar();?></div></div>
</div><hr>
<h2 class="text-center wow animated fadeInDown">LATEST NEWS FROM THE BLOG</h2>
<hr>
<div class="container clearfix" align="center">
  <div class="row">
<div class="col-sm-6 col-md-6 wow fadeInLeft">
    <div class="thumbnail">
      <img src="fc_images/blog_image.gif" alt="...">
      <div class="caption">
        <h3>Blog Gist entry 1</h3>
        <p>What it takes to make it in today's competitive world. The Story of Fascents Closet</p>
        <p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-md-6 wow animated fadeInRight">
    <div class="thumbnail">
      <img src="fc_images/blog_image.gif" alt="...">
      <div class="caption">
        <h3>Blog Gist entry 2</h3>
        <p>What it takes to make it in today's competitive world. The Story of Fascents Closet</p>
        <p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
      </div>
    </div>
  </div>
  
  </div>
  </div>

<hr>
<h2 class="text-center">The Gallery</h2>
<p class="text-center">Check out our <strong><em>Awesome</em></strong> customers</p>
<hr>
<div class="container">
  <div class="row">
    <div class="col-lg-4 col-md-6">
      <div class="media-object-default">
        <div class="media">
          <div class="media-left"> <a href="#"> <img class="media-object" src="css/img/100X125.gif" alt="placeholder image"> </a> </div>
          <div class="media-body">
            <h4 class="media-heading">Product</h4>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facilis, vitae doloremque voluptatum doloribus neque assumenda velit sapiente quas aliquam ratione. Sed possimus corporis dolorum optio eaque in asperiores soluta expedita! </div>
        </div>
        <div class="media">
          <div class="media-left"> <a href="#"> <img class="media-object" src="css/img/100X125.gif" alt="placeholder image"> </a> </div>
          <div class="media-body">
            <h4 class="media-heading">Product</h4>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Velit, quasi doloribus non repellendus quae aperiam. Quos, eligendi itaque soluta ut dignissimos reprehenderit commodi laboriosam quis atque minus enim magnam delectus.</div>
        </div>
        <div class="media">
          <div class="media-left"> <a href="#"> <img class="media-object" src="css/img/100X125.gif" alt="placeholder image"></a></div>
          <div class="media-body">
            <h4 class="media-heading">Product</h4>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Temporibus, repellendus, ad, adipisci neque earum culpa magnam veritatis ipsum dolores odio laboriosam sed veniam accusamus! Architecto, provident nulla recusandae repellendus illo!</div>
        </div>
      </div>
    </div>
    <hr class="hidden-md hidden-lg">
    <div class="col-lg-4 col-md-6">
      <div class="media-object-default">
        <div class="media">
          <div class="media-left"> <a href="#"> <img class="media-object" src="css/img/100X125.gif" alt="placeholder image"></a></div>
          <div class="media-body">
            <h4 class="media-heading">Product</h4>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sit, eos, et in quam laboriosam ipsum laudantium laborum provident nihil modi reprehenderit tempora voluptatum quasi non libero quaerat vel. Assumenda, officiis?</div>
        </div>
        <div class="media">
          <div class="media-left"> <a href="#"> <img class="media-object" src="css/img/100X125.gif" alt="placeholder image"></a></div>
          <div class="media-body">
            <h4 class="media-heading">Product</h4>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Obcaecati, minus, praesentium dignissimos non provident et consectetur illo expedita aliquam laboriosam esse incidunt deleniti accusantium debitis voluptas. Non vitae quos dolorem.</div>
        </div>
        <div class="media">
          <div class="media-left"> <a href="#"> <img class="media-object" src="css/img/100X125.gif" alt="placeholder image"></a></div>
          <div class="media-body">
            <h4 class="media-heading">Product</h4>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, ducimus quidem aliquam voluptate quas impedit modi neque quasi deleniti dicta. Dolore, provident, unde voluptas dicta fugit odit maxime eius minus!</div>
        </div>
      </div>
    </div>
    <hr class="hidden-lg">
    <div class="col-lg-4 col-md-12 hidden-md">
      <div class="media-object-default">
        <div class="media">
          <div class="media-left"> <a href="#"> <img class="media-object" src="css/img/100X125.gif" alt="placeholder image"></a></div>
          <div class="media-body">
            <h4 class="media-heading">Media heading 1</h4>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Porro dolorum reprehenderit vitae omnis. Quidem, recusandae, magni ut perspiciatis nobis consequuntur ullam molestias molestiae obcaecati ea labore aspernatur modi. Impedit, fugit!</div>
        </div>
        <div class="media">
          <div class="media-left"> <a href="#"> <img class="media-object" src="css/img/100X125.gif" alt="placeholder image"></a></div>
          <div class="media-body">
            <h4 class="media-heading">Media heading 2</h4>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolore, libero, ea itaque atque vero quidem esse optio minus tenetur dolorem delectus nemo fugit deserunt quibusdam veritatis assumenda obcaecati praesentium omnis!</div>
        </div>
        <div class="media">
          <div class="media-left"> <a href="#"> <img class="media-object" src="css/img/100X125.gif" alt="placeholder image"></a></div>
          <div class="media-body">
            <h4 class="media-heading">Media heading 2</h4>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Totam, cumque, ad voluptatibus vel perspiciatis reprehenderit magni in recusandae voluptatum iusto commodi laudantium veritatis esse labore nisi error tempora debitis impedit.</div>
        </div>
      </div>
    </div>
  </div>
</div>
<hr>

<a href="admin/index.php">Fascents</a>
<script src="js/wow.js"></script>
<script src="js/jquery-3.1.1.min.js"></script> 
  <script>
  $(document).ready(function()
  {  


   
    $('#add_to_cart').click(function(){
   
    var product_id = $('#product_id').val();
    var product_name= document.getElementById('product_name').innerHTML;
    var product_price = $('#price').val(); 
    var product_quantity = $('#quantity').val();
    var size = $('#size').val(); 
    var colour = $('#colour').val();
    var action="add";
    if(product_quantity > 0)
    {
      $.ajax({
        url:"action.php",
        method:"POST",
        dataType:"json",
        data:{
          product_id:product_id,
          product_name:product_name,
          product_price:product_price,
          product_quantity:product_quantity, 
          size:size,
          colour:colour,
          action:action

        },
        success:function(data)
        {
            $('#order_table').html(data.order_table);
            $('#badge').text(data.cart_item);
            alert('product has been added to cart');
        }

      })
    }
    else
    {
      alert('please select quantity');
    }
    //var add = ;
   // alert(product_id);
    //alert(product_name);
    //alert(product_price);
    //alert(product_quantity);
    //alert(size);
    //alert(colour);
     //alert('you clicked me :)!');
    
  })
   
    
    $('#colour').change(function(){
    var colour = $(this).val();
    var product_name= document.getElementById('product_name').innerHTML;
   alert(product_name);

     // alert(colour);
      $.ajax({
      url:"colour.php",
      method:"POST",
      data:{colour:colour, product_name:product_name},
      dataType:"text",
      success:function(value)
      {
        var data = value.split(",");
        $('#size').html(data[0]);
        $('.imgf').attr({'src':data[1], 'href':data[1], 'data-standard':data[1]});
        $('.imgs').attr({'src':data[2], 'href':data[2], 'data-standard':data[2]});
        $('.imgb').attr({'src':data[3], 'href':data[3], 'data-standard':data[3]});
        $('#imgf').attr({'src':data[1], 'href':data[1], 'data-standard':data[1]});
       
       
    // $('#imgs').attr({'href':data[2], 'data-standard':data[2]});
    // $('#imgb').attr({'href':data[3], 'data-standard':data[3]});
    // $('#imgm').attr('href',data[1]);
    // $('#imgm').attr('src',data[1]);
    //   $('easyzoom-flyout img').attr('src',data[1]);
     // $('easyzoom a img').attr('src',data[1]);
      //  $('#imgf').attr('data-standard', data[1]);
      //  $('#imgs').attr('data-standard', data[2]);
      //  $('#imgb').attr('data-standard', data[3]);
      }

  }) 
    });
   

   
     $("#btn3").click(function()
    {
      $("#d3").slideToggle(200);
      $("#d4").slideUp(200); //2seconds
      $("#d5").slideUp(200);
      $("#d6").slideUp(200); //2seconds
    });
      $("#btn4").click(function()
    {
      $("#d3").slideUp(200); //2seconds
      $("#d4").slideToggle(200);
      $("#d5").slideUp(200);
      $("#d6").slideUp(200);
    });
       $("#btn5").click(function()
    {
      $("#d3").slideUp(200); //2seconds
      $("#d4").slideUp(200);
      
      $("#d6").slideUp(200);
      $("#d5").slideToggle(200); //2seconds
    });
        $("#btn6").click(function()
    {
      $("#d3").slideUp(200); //2seconds
      $("#d4").slideUp(200);
      $("#d5").slideUp(200);
      $("#d6").slideToggle(200); //2seconds
    });

  });
  </script>
<script src="js/jquery-3.1.1.min.js"></script>
  <script src="Inuse/dist/easyzoom.js"></script>
  <script type="text/javaScript">
  // Instantiate EasyZoom instances
    var $easyzoom = $('.easyzoom').easyZoom();

    // Setup thumbnails example
    var api1 = $easyzoom.filter('.easyzoom--with-thumbnails').data('easyZoom');

    $('.thumbnails').on('click', 'a', function(e) {
      var $this = $(this);

      e.preventDefault();

      // Use EasyZoom's `swap` method
      api1.swap($this.data('standard'), $this.attr('href'));
    });

    // Setup toggles example
   
  </script>
<script src="css/js/bootstrap.min.js"></script>
<script src="js/midway.js"></script>
</body>
</html>