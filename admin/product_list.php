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
// Script Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<?php 
// Delete Item Question to Admin, and Delete Product if they choose

if (isset($_GET['deleteid'])) {
  // remove item from system and delete its picture
  // delete from database
  $id_to_delete = $_GET['deleteid'];
  $sql = mysqli_query($conn, "DELETE FROM products WHERE product_id='$id_to_delete' LIMIT 1") or die (mysql_error());
  // unlink the image from server
  // Remove The Pic -------------------------------------------
    $pictodelete1 = ("../product_images/{$id_to_delete}f.jpg");
    $pictodelete2 = ("../product_images/{$id_to_delete}s.jpg");
    $pictodelete3 = ("../product_images/{$id_to_delete}b.jpg");
    
    if (file_exists($pictodelete1)) {
              unlink($pictodelete1);
              unlink($pictodelete2);
              unlink($pictodelete3);
  
  $resultalert = '<div class ="alert alert-info fade in"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Info:</strong> Item sccessfully deleted</div>';            
    }
  header("location: product_list.php?delit=". urlencode("Item Successfully deleted")); 
  
    exit();
}
?>

<?php 
// This block grabs the whole list for viewing
$product_list = "";
$allid="";
$result = mysqli_query($conn, "SELECT * FROM products ORDER BY product_id DESC");
$productCount = mysqli_num_rows($result); // count the output amount
if ($productCount > 0) {
	while($row = mysqli_fetch_array($result)){ 
             $id = $row["product_id"];
			 $product_name = $row["product_name"];
       $category = $row["category"];
       $subcategory = $row["subcategory"];
       $oprice = $row["oprice"];
			 $price = $row["price"];
			 $size= $row["size"];
			 $colour= $row["colour"];
			 $instock= $row["instock"];
			 $date_added = strftime("%b %d, %Y", strtotime($row["date_added"]));
       $product_list .= '<tr><td>' . $id . '</td><td> <strong>' . $product_name . '</strong></td><td>' . $category . '</td><td>' . $subcategory . '</td><td>' . $oprice . '</td><td>' . $price . '</td><td>' . $size . '</td><td>' . $colour . '</td><td>' . $instock . '</td><td><em>'. $date_added . '</em></td><td> <a href="product_edit.php?p_id=' . $id . '">edit</a> &bull; <a class="popconfirm1" href="product_list.php?deleteid=' . $id . '">delete</a></td></tr>';
    }
} else {
	$product_list = "You have no products listed in your store yet";
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
<link rel="stylesheet" href="../css/bootstrap-theme.min.css">
<!--materialize-->
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
  <script src="../js/vendor/jquery.min.js"></script>


<script type="text/javascript">
      $(document).ready(function() {
        // Basic confirmation
$(".popconfirm").popConfirm();
        
        $(".popconfirm1").popConfirm({
          title: "Confirm",
          content: "Delete Item?",
          placement: "left"
        });

        $(".popconfirmlog").popConfirm({
          title: "Logout?",
          content: "All Done? Log out?",
          placement: "right"
        })
      });
    </script>
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
   
    
    <?php if(isset($_GET['succ'])) { ?>
    
    <div class ="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><?php echo $_GET['succ']; ?></div>
    
    <?php } ?>
     <?php if(isset($_GET['delit'])) { ?>
    
    <div class ="alert alert-warning"><a href="#" class="close" data-dismiss="alert">&times;</a><?php echo $_GET['delit']; ?></div>
    
    <?php } ?>
    <?php if(isset($_GET['updat'])) { ?>
    
    <div class ="alert alert-info"><a href="#" class="close" data-dismiss="alert">&times;</a><?php echo $_GET['updat']; ?></div>
    
    <?php } ?>
   <!-- <div class ="alert alert-success fade in"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Success:</strong> Item successfully added</div>
    <div class ="alert alert-warning fade in"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Deleted:</strong> Item successfully deleted</div>
    <div class ="alert alert-info fade in"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Info:</strong> Item successfully deleted</div> 
  </div>-->
  <a href="logout.php" class="popconfirmlog">Log out</a>
  <a href="product_form.php" class="linkout">Add product item</a>
  <div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading" ><h3 style="margin:0; padding:0; border:0;"><strong>Product Table</strong></h3></div>

  <!-- Table -->
  <div class="table-responsive">
  <table class="table">
    <tr>
        <td><strong>ID</strong></td>
        <td><strong>Product Name</strong></td>
        <td><strong>Category</strong></td>
        <td><strong>Subcategory</strong></td>
        <td><strong>Old Price</strong></td>
        <td><strong>Price</strong></td>
        <td><strong>Size</strong></td>
        <td><strong>Colour</strong></td>
        <td><strong>No in Stock</strong></td>
        <td><strong>Date added</strong></td>
        <td><strong>Action</strong></td>
    </tr>
     <a href="#" id="#"><?php echo $product_list ?></a>
  </table>
</div>
</div>
<div class="container-fluid"><?php echo $allid; ?></div> 
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
          <p><a href="#" class="btn btn-primary" id="ask" role="button"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Add to Cart</a><a href="product_form.php" id="ask">Add product item</a></p>
        </div>
      </div>
    </div>
    <div class="col-sm-4 col-md-4 col-lg-4 col-xs-6">
      <div class="thumbnail"> <img src="css/img/400X200.gif" alt="Thumbnail Image 1" class="img-responsive">
        <div class="caption">
          <h3>Product</h3>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing.</p>
          <p><a href="#" class="btn btn-primary" id="ask" role="button"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Add to Cart</a> </p>
        </div>
      </div>
    </div>
    <div class="col-sm-4 col-md-4 col-lg-4 col-xs-6">
      <div class="thumbnail"> <img src="css/img/400X200.gif" alt="Thumbnail Image 1" class="img-responsive">
        <div class="caption">
          <h3>Product</h3>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing.</p>
          <p><a href="#" class="btn btn-primary alink" role="button"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Add to Cart</a> </p>
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

  
  
<script src="../js/jquery-3.1.1.min.js"></script> 
<script src="../js/bootstrap.min.js"></script>
<script type="text/javascript" src="jquery.popconfirm.js"></script>
</body>
</html>