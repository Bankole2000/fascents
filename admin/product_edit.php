<?php 
session_start();
if (!isset($_SESSION["manager"])) {
    header("location: admin_login.php"); 
    exit();
}
// Be sure to check that this manager SESSION value is in fact in the database
$managerID = preg_replace('#[^0-9]#i', '', $_SESSION["id"]); // filter everything but numbers and letters
$manager = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["manager"]); // filter everything but numbers and letters
$password = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["password"]); // filter everything but numbers and letters
// Run mySQL query to be sure that this person is an admin and that their password session var equals the database information
// Connect to the MySQL database  
include "../scripts/connect_to_mysql.php"; 
$result = mysqli_query($conn, "SELECT * FROM admin WHERE id='$managerID' AND username='$manager' AND password='$password' LIMIT 1"); // query the person
// ------- MAKE SURE PERSON EXISTS IN DATABASE ---------
$existCount = mysqli_num_rows($result); // count the row nums
if ($existCount == 0) { // evaluate the count
	 echo "Your login session data is not on record in the database.";
     exit();
}
?>

<?php


if (isset($_POST['update'])){
$pid = mysql_real_escape_string($_POST['thisID']);
$product_name = mysql_real_escape_string($_POST['product_name']);
    $oprice=mysql_real_escape_string($_POST['oprice']);
  $price = mysql_real_escape_string($_POST['price']);
  $category = mysql_real_escape_string($_POST['category']);
  $subcategory = mysql_real_escape_string($_POST['subcategory']);
  $details = mysql_real_escape_string($_POST['details']);
  $colour = mysql_real_escape_string($_POST['colour']);
  $size = mysql_real_escape_string($_POST['size']);
  $instock = mysql_real_escape_string($_POST['instock']);
  $rare = mysql_real_escape_string($_POST['rare']);
  if($conn->connect_error){
die("Connection failed:". $conn->connect_error); }

  $sql= "UPDATE products SET product_name='" . $product_name . "', oprice='" . $oprice . "', price ='".$price."', details='".$details."', category='".$category."', subcategory='".$subcategory."', size='".$size."', colour='".$colour."', instock='".$instock."', rare='".$rare."' WHERE product_id='".$pid. "'";
if($conn->query($sql)==TRUE){
echo "Record updated successfully";
} else {

  echo "error updating record: ". $conn->error;
}  
  //$update = mysqli_query($conn, $sql) or die ("Query failed");
  if ($_FILES['fileField1']['tmp_name'] != "") {
      // Place image in the folder 
      $newname1 = "{$pid}f.jpg";
      $newname2 = "{$pid}s.jpg";
      $newname3 = "{$pid}b.jpg";
      
      move_uploaded_file($_FILES['fileField1']['tmp_name'], "../product_images/$newname1");
      move_uploaded_file($_FILES['fileField2']['tmp_name'], "../product_images/$newname2");
      move_uploaded_file($_FILES['fileField3']['tmp_name'], "../product_images/$newname3");
  
  }

  header("location: product_list.php?updat=". urlencode("Item successfully Updated")); 
  exit();

}

elseif (isset($_POST['addnew'])) {
  
    $product_name = mysql_real_escape_string($_POST['product_name']);
    $oprice=mysql_real_escape_string($_POST['oprice']);
  $price = mysql_real_escape_string($_POST['price']);
  $category = mysql_real_escape_string($_POST['category']);
  $subcategory = mysql_real_escape_string($_POST['subcategory']);
  $details = mysql_real_escape_string($_POST['details']);
  $colour = mysql_real_escape_string($_POST['colour']);
  $size = mysql_real_escape_string($_POST['size']);
  $instock = mysql_real_escape_string($_POST['instock']);
  $rare = mysql_real_escape_string($_POST['rare']);
  // See if that product name is an identical match to another product in the system
  $sql = mysqli_query($conn, "SELECT product_id, product_name, colour, size FROM products WHERE product_name='$product_name' AND colour='$colour' AND size='$size' LIMIT 1");
  $productMatch = mysqli_num_rows($sql); // count the output amount
    if ($productMatch > 0) {
      while ($row= mysqli_fetch_array($sql)){
     $product_id = $row['product_id'];
     echo 'Sorry, this product: ' . $product_name . ' Size: ' . $size . ', Colour: ' . $colour . ' is already in the system. would you like to change the <a href="product_edit.php?p_id=' . $product_id . '">number in stock</a> instead? or <a href="product_list.php">go back</a>';
    //exit();
  }}else{
  // Add this product into the database now
  
  $insert = mysqli_query($conn, "INSERT INTO products (product_name, oprice, price, details, category, subcategory, colour, size, instock, rare, date_added) 
  VALUES('$product_name', '$oprice', '$price','$details','$category','$subcategory','$colour','$size','$instock','$rare', now())"); 
  $p_id = mysqli_insert_id($conn);
     
  // Place images in the folder 
  $newname1 = "{$p_id}f.jpg";
  $newname2 = "{$p_id}s.jpg";
  $newname3 = "{$p_id}b.jpg";
  move_uploaded_file( $_FILES['fileField1']['tmp_name'], "../product_images/$newname1");
  move_uploaded_file( $_FILES['fileField2']['tmp_name'], "../product_images/$newname2");
  move_uploaded_file( $_FILES['fileField3']['tmp_name'], "../product_images/$newname3");
  
  header("location: product_list.php?succ=". urlencode("Success: Item Successfully added")); 
    exit();
}} 
elseif (isset($_POST['cancel'])){
header("location: product_list.php");
exit();

}
?>

<?php 
//gather url product data and prepopulate form
If (isset($_GET['p_id'])){
$targetid=$_GET['p_id'];
$result= mysqli_query($conn, "SELECT * FROM products WHERE product_id='$targetid' LIMIT 1");
$productCount= mysqli_num_rows($result);
if ($productCount > 0){
while ($row= mysqli_fetch_array($result)){

$product_name = $row['product_name'];
$oprice = $row['oprice'];
$price = $row['price'];
$category = $row['category'];
$subcategory =$row['subcategory'];
$size = $row['size'];
$colour = $row['colour'];
$instock = $row['instock'];
$rare = $row['rare'];
$details = $row['details'];
    }
}
else {
      echo "Sorry dude that crap dont exist.";
    exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>FC Admin</title>
<link rel="icon" href="../fc_images/fc-icon.png">

<!-- Bootstrap -->
<link rel="stylesheet" href="../css/css/bootstrap.css">
<!--materialize-->
    <link type="text/css" rel="stylesheet" href="../css/materializeo.min.css" media="screen,projection" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]--> <script type="text/javascript">
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
  .form-control
  { border-radius:0px !important;
    border:1px solid #999;
    border-top: none;
    border-left:none;
    border-right:none;
}
.btn {
border-radius:0px !important;

}
  </style>
   <script src="../js/vendor/jquery.min.js"></script>
 

  </head>

  <body onLoad="MM_preloadImages('../fc_images/fc-icon-gold.png','../fc_images/fc-header-gold.png','../fc_images/twitter.png','../fc_images/facebook.png','../fc_images/instagram.png','../fc_images/youtube.png','../fc_images/pinterest.png','../fc_images/cart-icon2.png')">

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
        </a><a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image7','','../fc_images/fc-icon-gold.png',1)"><img src="../fc_images/fc-icon-inv.png" alt="" width="32" height="42" id="Image7" style="margin-top: -10px; margin-left: -40px; padding:0px;">
        </a></div>
          <div class="navbar-header"><a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image8','','../fc_images/fc-header-gold.png',1)"><img src="../fc_images/fc-header-inv.png" alt="" width="245" height="42" id="Image8" class="img-responsive" style="position: fixed; margin-left: 45px; margin-top: 5px;"></a></div>
      
    </div>
 
        
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#">Home</a></li>
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
            
            <li><a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image9','','../fc_images/cart-icon.png',1)"><img src="../fc_images/cart-icon1grey.png" alt="" width="28" height="26" style="margin-bottom:-4px; margin-top:-4px;" id="Image9"><span class="badge">0</span> My Cart &nbsp; </a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
<div class="jumbotron" style="margin-top:50px; height:30%;">
  <div class="container"><?php echo"<h1>Hello ".$manager."</h1>"?>
  <h2>what would you like to do today?</h2><br><br>
  </div></div>
 
 <!--//add product item here FORM-->
 <div class="container" >
  <div class="row">
    <div class="col-xs-12 col-sm-12">
 <h3>Product Form</h3>
 
<form class="form-in-line" action="product_edit.php" enctype="multipart/form-data" name="productForm" id="productForm" method="post">
    <div class="form-group">
    <label for="product_name">Product Name</label>
    <input type="text" class="form-control" name="product_name" id="product_name" value ="<?php echo $product_name; ?>"/>
    </div>
    <div class="form-group col-sm-6 col-xs-6 col-md-6" style="margin-left:auto;">
      <label for="price">New Price</label>
      <input type="text" class="form-control" name="price" id="price" value ="<?php echo $price; ?>" placeholder="N New Price"></div>
    <div class="form-group col-sm-6 col-xs-6 col-md-6" style="margin-left:auto;">
      <label for="oprice">Old Price</label>
      <input type="text" name="oprice" id="oprice" class="form-control" value ="<?php echo $oprice; ?>" placeholder="N Old Price"></div>
    <div class="form-group col-sm-6 col-xs-6 col-md-6" style="margin-left:auto;">
          <label for="category" type="text" id="price" size="12" >Category
        </label>
        <select class="form-control" name="category" id="category">
            <option value ="<?php echo $category; ?>"><?php echo $category; ?></option>
          <option value="Ladies">Ladies</option>
          <option value="Men">Men</option>
          <option value="Accessories">Accessories</option>
          </select></div>
    <div class="form-group col-sm-6 col-xs-6 col-md-6" style="margin-left:auto;">
          <label for="category" type="text" id="price" size="12" />Subcategory
        </label>
        <select class="form-control" name="subcategory" id="subcategory">
            <option value ="<?php echo $subcategory; ?>"><?php echo $subcategory; ?></option>
          <option value="Tops">Tops</option>
          <option value="Bottoms">Bottoms</option>
          <option value="Dress">Dress</option>
          <option value="Shoes">Shoes</option>
          <option value="Beauty">Beauty</option>
          <option value="Accessories">Accessories</option>
          </select></div>
    <div class="form-group col-sm-6 col-xs-6 col-md-3" style="margin-left:auto;">      
        <label>Size</label>
        <select class="form-control" name="size" id="size">
        <option value ="<?php echo $size; ?>"><?php echo $size; ?></option>
          <option value="Universal">Universal</option>
          <option value="XS">XS</option>
          <option value="S">S</option>
          <option value="M">M</option>
          <option value="L">L</option>
          <option value="XL">XL</option>
          <option value="XXL">XXL</option>
          </select></div>
       <div class="form-group col-sm-6 col-xs-6 col-md-3" style="margin-left:auto;">
        <label>Colour</label>
        <select class="form-control" name="colour" id="colour" placeholder="colour">
        <option value ="<?php echo $colour; ?>"><?php echo $colour; ?></option>
          <option value="White">White</option>
          <option value="Black">Black</option>
          <option value="Brown">Brown</option>
          <option value="Blue">Blue</option>
          <option value="Red">Red</option>
          <option value="Green">Green</option>
          <option value="Orange">Orange</option>
          <option value="Yellow">Yellow</option>
          <option value="Pink">Pink</option>
          <option value="Purple">Purple</option>
          <option value="Silver">Silver</option>
          <option value="Gold">Gold</option>
          <option value="Multicolour">Multicolour</option>
          </select></div>
          <div class="form-group col-sm-6 col-xs-6 col-md-3" style="margin-left:auto;">
      <label>Number in Stock</label>
        <input class="form-control" name="instock" type="text" value ="<?php echo $instock ?>" id="instock" size="12" />
    </div>
    <div class="form-group col-sm-6 col-xs-6 col-md-3" style="margin-left:auto;">
        <label>Rare?</label>
        <select class="form-control" name="rare" id="rare" placeholder="rare">
        <option value ="<?php echo $rare; ?>"><?php echo $rare; ?></option>
          <option value="yes">Yes</option>
          <option value="no">No</option></select></div>
        <div class="form-group col-sm-12 col-xs-12 col-md-12" style="margin-left:auto;">
        <label>Product Details</label>
          <textarea class="form-control" name="details" id="details" rows="5"><?php echo $details ?></textarea>
        </div>
        <div class="form-group col-sm-12 col-xs-12 col-md-4" style="margin-left:auto;">
      Product Image (Front)<label class="btn btn-default"><input type="file" name="fileField1" id="fileField1"/>
        </label></div>
        <div class="form-group col-sm-12 col-xs-12 col-md-4" style="margin-left:auto;">
        Product Image (Side)<label class="btn btn-default"><input type="file" name="fileField2" id="fileField2"/>
        </label></div>
        <div class="form-group col-sm-12 col-xs-12 col-md-4" style="margin-left:auto;">
      Product Image (Back)<label class="btn btn-default"><input type="file" name="fileField3" id="fileField3"/>
        </label></div>
        <div class="form-group col-sm-12 col-xs-12 col-md-4" style="margin-left:auto;">
        <input name="thisID" type="hidden" value="<?php echo $targetid; ?>" />
        <input class="btn btn-primary btn-md" style="width:100%;" type="submit" name="update" id="update" value="Update Item" />
        </div><div class="form-group col-sm-12 col-xs-12 col-md-4" style="margin-left:auto;">
        <input class=" btn btn-success btn-md" style="width:100%;" type="submit" name="addnew" id="addnew" value="Add As New Item" />
        </div><div class="form-group col-sm-12 col-xs-12 col-md-4" style="margin-left:auto;">
        <input class="btn btn-warning btn-md" style="width:100%;" type="submit" name="cancel" id="cancel" value="Cancel" />
        </div>
       
   </div>
</form></div></div></div>
<hr/>
 <a class="popconfirm3" href="">Link</a>
<br/>
<!-- ===========================================================
  test page


<div class="container">
  <div class="row">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
      <div class="row">
        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-2"><img class="img-circle" alt="Free Shipping" src="css/img/40X40.gif"></div>
        <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
          <h4>Free Shipping</h4>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
      <div class="row">
        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-2"><img class="img-circle" alt="Free Shipping" src="css/img/40X40.gif"></div>
        <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
          <h4>Free Returns</h4>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
      <div class="row">
        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-2"><img class="img-circle" alt="Free Shipping" src="css/img/40X40.gif"></div>
        <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9">
          <h4>Low Prices</h4>
        </div>
      </div>
    </div>
  </div>
</div>
<hr>
<h2 class="text-center">RECOMMENDED PRODUCTS</h2>
<hr>

<hr> ==================================== -->

    <script src="../../../../js/vendor/jquery.min.js"></script>
  </head>
  <body>
    <div class="container">
      <div class="page-header">
        <h1>PopConfirm Tests !</h1>
        <p class="lead">Testing page for PopConfirm functionalities. Sorry, tests are not automated ... Maybe next time :)</p>
      </div>
      
      <h2>Handling actions</h2>
      
      <h4>One or several jquery click click events</h4>
      
      <div class="btn btn-success popconfirm test-jquery-click">Test jQuery Click</div>
      <script type="text/javascript">
        $(document).ready(function() {
          $('.test-jquery-click').click(function() {
            alert('Event 1');
          });
          $('.test-jquery-click').bind('click', function() {
            alert('Event 2');
          });
        });
      </script>
      
      <h4>Hard Onclick attribute</h4>
      
      <div class="btn btn-success popconfirm" onclick="alert('Test Onclick !');">Test Onclick Attribute</div>
      
      <h4>Href link</h4>
      
      <a href="http://anaelfavre.github.io/PopConfirm/" class="btn btn-success popconfirm">Link</a>
      
      <h4>Form submit test</h4>
      <form method="POST" action="#">
        <button type="submit" class="btn btn-success popconfirm">Submit</button>
      </form>
      
      <h4>Test two classes (single instance across several handlers)</h4>
      <div><a href="#" class="btn btn-success popconfirm1">Class 1</a></div>
      <div><a href="#" class="btn btn-success popconfirm1">Class 1</a></div>
      <div><a href="#" class="btn btn-success popconfirm1">Class 1</a></div>
      <div><a href="#" class="btn btn-success popconfirm2">Class 2</a></div>
    </div>
    
    <script src="../../../../js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../jquery.popconfirm.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        $(".popconfirm").popConfirm();
        
        $(".popconfirm1").popConfirm();
        $(".popconfirm2").popConfirm({
           title: "Confirmation",
          content: "(Please be sure to Check all Images and fields) Update THIS Item?",
          placement: "top"

        });
        $(".popconfirm3").popConfirm({
          title: "Confirmation",
          content: "(Please be sure to Check all Images and fields) Add as NEW Item?",
          placement: "top"

        })
      });
    </script>


<script src="../js/jquery-3.1.1.min.js"></script> 
<script src="../js/bootstrap.min.js"></script>
<script type="text/javascript" src="jquery.popconfirm.js"></script>

</body>
</html>