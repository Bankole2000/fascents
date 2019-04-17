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
  </head>

  <body onLoad="MM_preloadImages('../fc_images/fc-icon-gold.png','../fc_images/fc-header-gold.png','../fc_images/twitter.png','../fc_images/facebook.png','../fc_images/instagram.png','../fc_images/youtube.png','../fc_images/pinterest.png','../fc_images/cart-icon2.png')">

    <!-- Fixed navbar -->
    <!--<nav class="navbar navbar-inverse navbar-fixed-top">
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
 <legend><h3>Product Form</h3></legend>
 
 <form class="form-in-line" action="add.php" enctype="multipart/form-data" name="productForm" id="productForm" method="post">
   <div class="form-group">
   <label for="product_name">Product Name</label>
    <input type="text" class="form-control" name="product_name" id="product_name" placeholder="Product name">
  </div>
  <div class="form-group col-sm-6 col-xs-6 col-md-6" style="margin-left:auto;">
      <label for="price">New Price</label>
      <input type="text" class="form-control" name="price" id="price" placeholder="N New Price"></div>
  <div class="form-group col-sm-6 col-xs-6 col-md-6" style="margin-left:auto;">
      <label for="oprice">Old Price</label>
      <input type="text" name="oprice" id="oprice" class="form-control" placeholder="N Old Price"></div>
  <div class="form-group col-sm-6 col-xs-6 col-md-6" style="margin-left:auto;">
          <label for="category" type="text" id="price" size="12" >Category
        </label>
        <select class="form-control" name="category" id="category">
            <option value=""></option>
          <option value="Ladies">Ladies</option>
          <option value="Men">Men</option>
          <option value="Accessories">Accessories</option>
          </select></div>
<div class="form-group col-sm-6 col-xs-6 col-md-6" style="margin-left:auto;">
          <label for="category" type="text" id="price" size="12" />Subcategory
        </label>
        <select class="form-control" name="subcategory" id="subcategory">
            <option value=""></option>
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
        <option value=""></option>
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
        <option value=""></option>
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
        <input class="form-control" name="instock" type="text" id="instock" size="12" />
</div>
 <div class="form-group col-sm-6 col-xs-6 col-md-3" style="margin-left:auto;">
        <label>Rare?</label>
        <select class="form-control" name="rare" id="rare" placeholder="rare">
        <option value=""></option>
          <option value="yes">Yes</option>
          <option value="no">No</option></select></div>
        <div class="form-group col-sm-12 col-xs-12 col-md-12" style="margin-left:auto;">
        <label>Product Details</label>
          <textarea class="form-control" name="details" id="details" rows="5"></textarea>
        </div>
        <div class="form-group col-sm-12 col-xs-12 col-md-4" style="margin-left:auto;">
      Product Image (Front)<label class="btn btn-default"><input type="file" name="fileField1" id="fileField1"/>
        </label></div>
        <div class="form-group col-sm-12 col-xs-12 col-md-4" style="margin-left:auto;">
        Product Image (Side)<label class="btn btn-default"><input type="file" name="fileField2" id="fileField2"/>
        </label></div>
        <div class="form-group col-sm-12 col-xs-12 col-md-4" style="margin-left:auto;">
      Product Image (Back)<label class="btn btn-default"><input type="file" name="fileField3" id="fileField3"/>
        </label></div> <div class="form-group col-sm-12 col-xs-12 col-md-6" style="margin-left:auto;">
        <input class="btn btn-success btn-md" style="width:100%;" type="button" name="addnew" id="addnew" value="Add This Item Now" />
         </div><div class="form-group col-sm-12 col-xs-12 col-md-6" style="margin-left:auto;">
        <input class="btn btn-warning btn-md" style="width:100%;" type="button" name="cancel" id="cancel" value="Cancel" />
        </div>
         </form>
 </div>
</form></div>
<hr/>
<br/>

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
<div class="container">
  <div class="row text-center">
    <div class="col-sm-4 col-md-4 col-lg-4 col-xs-6">
      <div class="thumbnail"> <img src="css/img/400X200.gif" alt="Thumbnail Image 1" class="img-responsive">
        <div class="caption">
          <h3>Product</h3>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing.</p>
          <p><a href="#" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Add to Cart</a></p>
        </div>
      </div>
    </div>
    <div class="col-sm-4 col-md-4 col-lg-4 col-xs-6">
      <div class="thumbnail"> <img src="css/img/400X200.gif" alt="Thumbnail Image 1" class="img-responsive">
        <div class="caption">
          <h3>Product</h3>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing.</p>
          <p><a href="#" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Add to Cart</a> </p>
        </div>
      </div>
    </div>
    <div class="col-sm-4 col-md-4 col-lg-4 col-xs-6">
      <div class="thumbnail"> <img src="css/img/400X200.gif" alt="Thumbnail Image 1" class="img-responsive">
        <div class="caption">
          <h3>Product</h3>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing.</p>
          <p><a href="#" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Add to Cart</a> </p>
        </div>
      </div>
    </div>
    <div class="col-sm-4 col-md-4 col-lg-4 col-xs-6 hidden-lg hidden-md hidden-sm">
      <div class="thumbnail"> <img src="css/img/400X200.gif" alt="Thumbnail Image 1" class="img-responsive">
        <div class="caption">
          <h3>Product</h3>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing.</p>
          <p><a href="#" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Add to Cart</a></p>
        </div>
      </div>
    </div>
  </div>
  <div class="row text-center hidden-xs">
    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
      <div class="thumbnail"> <img src="css/img/400X200.gif" alt="Thumbnail Image 1" class="img-responsive">
        <div class="caption">
          <h3>Product</h3>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing.</p>
          <p><a href="#" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Add to Cart</a> </p>
        </div>
      </div>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
      <div class="thumbnail"> <img src="css/img/400X200.gif" alt="Thumbnail Image 1" class="img-responsive">
        <div class="caption">
          <h3>Product</h3>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing.</p>
          <p><a href="#" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Add to Cart</a> </p>
        </div>
      </div>
    </div>
    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
      <div class="thumbnail"> <img src="css/img/400X200.gif" alt="Thumbnail Image 1" class="img-responsive">
        <div class="caption">
          <h3>Product</h3>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing.</p>
          <p><a href="#" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Add to Cart</a> </p>
        </div>
      </div>
    </div>
  </div>
  <nav class="text-center">
    <!-- Add class .pagination-lg for larger blocks or .pagination-sm for smaller blocks-->
    <ul class="pagination">
      <li> <a href="#" aria-label="Previous"> <span aria-hidden="true">&laquo;</span> </a> </li>
      <li class="active"><a href="#">1</a></li>
      <li><a href="#">2</a></li>
      <li><a href="#">3</a></li>
      <li><a href="#">4</a></li>
      <li class="disabled"><a href="#">5</a></li>
      <li> <a href="#" aria-label="Next"> <span aria-hidden="true">&raquo;</span> </a> </li>
    </ul>
  </nav>
</div>
<hr>
<h2 class="text-center">FEATURED PRODUCTS</h2>
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
<script>


$(document).ready(function()
  {
    $("#addnew").click(function()
    {
     
      alert('you clicked me');
    });
  }
  );


</script>
<script src="../js/jquery-3.1.1.min.js"></script> 
<script src="css/js/bootstrap.min.js"></script>
<script type="text/javascript" src="jquery.popconfirm.js"></script>
</body>
</html>