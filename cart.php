<?php 

session_start(); // Start session first thing in script
// Script Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
// Connect to the MySQL database  
include "scripts/connect_to_mysql.php"; 
?>
<?php 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 1 (if user attempts to add something to the cart from the product page)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_POST['pid'])) {
    $pid = $_POST['pid'];
  $wasFound = false;
  $i = 0;
  // If the cart session variable is not set or cart array is empty
  if (!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1) { 
      // RUN IF THE CART IS EMPTY OR NOT SET
    $_SESSION["cart_array"] = array(0 => array("item_id" => $pid, "quantity" => 1));
  } else {
    // RUN IF THE CART HAS AT LEAST ONE ITEM IN IT
    foreach ($_SESSION["cart_array"] as $each_item) { 
          $i++;
          while (list($key, $value) = each($each_item)) {
          if ($key == "item_id" && $value == $pid) {
            // That item is in cart already so let's adjust its quantity using array_splice()
            array_splice($_SESSION["cart_array"], $i-1, 1, array(array("item_id" => $pid, "quantity" => $each_item['quantity'] + 1)));
            $wasFound = true;
          } // close if condition
          } // close while loop
         } // close foreach loop
       if ($wasFound == false) {
         array_push($_SESSION["cart_array"], array("item_id" => $pid, "quantity" => 1));
       }
  }
  header("location: cart.php"); 
    exit();
}
?>
<?php 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 2 (if user chooses to empty their shopping cart)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_GET['cmd']) && $_GET['cmd'] == "emptycart") {
    unset($_SESSION["cart_array"]);
}
?>
<?php 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 3 (if user chooses to adjust item quantity)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_POST['item_to_adjust']) && $_POST['item_to_adjust'] != "") {
    // execute some code
  $item_to_adjust = $_POST['item_to_adjust'];
  $quantity = $_POST['quantity'];
  $quantity = preg_replace('#[^0-9]#i', '', $quantity); // filter everything but numbers
  if ($quantity >= 100) { $quantity = 99; }
  if ($quantity < 1) { $quantity = 1; }
  if ($quantity == "") { $quantity = 1; }
  $i = 0;
  foreach ($_SESSION["cart_array"] as $each_item) { 
          $i++;
          while (list($key, $value) = each($each_item)) {
          if ($key == "item_id" && $value == $item_to_adjust) {
            // That item is in cart already so let's adjust its quantity using array_splice()
            array_splice($_SESSION["cart_array"], $i-1, 1, array(array("item_id" => $item_to_adjust, "quantity" => $quantity)));
          } // close if condition
          } // close while loop
  } // close foreach loop
}
?>

<?php 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 4 (if user wants to remove an item from cart)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_POST['index_to_remove']) && $_POST['index_to_remove'] != "") {
    // Access the array and run code to remove that array index
  $key_to_remove = $_POST['index_to_remove'];
  if (count($_SESSION["cart_array"]) <= 1) {
    unset($_SESSION["cart_array"]);
  } else {
    unset($_SESSION["cart_array"]["$key_to_remove"]);
    sort($_SESSION["cart_array"]);
  }
}
?>
<?php 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 5  (render the cart for the user to view on the page)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$cartOutput = "";
$cartTotal = "";
$pp_checkout_btn = '';
$product_id_array = '';
if (!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1) {
    $cartOutput = "<h2 align='center'>Your shopping cart is empty</h2>";
} else {
  // Start PayPal Checkout Button
  $pp_checkout_btn .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
    <input type="hidden" name="cmd" value="_cart">
    <input type="hidden" name="upload" value="1">
    <input type="hidden" name="business" value="you@youremail.com">';
  // Start the For Each loop
  $i = 0; 
    foreach ($_SESSION["cart_array"] as $each_item) { 
    $item_id = $each_item['item_id'];
    $sql = mysqli_query($conn, "SELECT * FROM products WHERE product_id='$item_id' LIMIT 1");
    while ($row = mysqli_fetch_array($sql)) {
      $product_name = $row["product_name"];
      $price = $row["price"];
      $details = $row["details"];
    }
    $pricetotal = $price * $each_item['quantity'];
    $cartTotal = $pricetotal + $cartTotal;
    setlocale(LC_MONETARY, "en_US");
    // Dynamic Checkout Btn Assembly
    $x = $i + 1;
    $pp_checkout_btn .= '<input type="hidden" name="item_name_' . $x . '" value="' . $product_name . '">
        <input type="hidden" name="amount_' . $x . '" value="' . $price . '">
        <input type="hidden" name="quantity_' . $x . '" value="' . $each_item['quantity'] . '">  ';
    // Create the product array variable
    $product_id_array .= "$item_id-".$each_item['quantity'].","; 
    // Dynamic table row assembly
    $cartOutput .= "<tr>";
    $cartOutput .= '<td><a href="product.php?id=' . $item_id . '">' . $product_name . '</a><br /><img src="inventory_images/' . $item_id . '.jpg" alt="' . $product_name. '" width="40" height="52" border="1" /></td>';
    $cartOutput .= '<td>' . $details . '</td>';
    $cartOutput .= '<td>$' . $price . '</td>';
    $cartOutput .= '<td><form action="cart.php" method="post">
    <input name="quantity" type="text" value="' . $each_item['quantity'] . '" size="1" maxlength="2" />
    <input name="adjustBtn' . $item_id . '" type="submit" value="change" />
    <input name="item_to_adjust" type="hidden" value="' . $item_id . '" />
    </form></td>';
    //$cartOutput .= '<td>' . $each_item['quantity'] . '</td>';
    $cartOutput .= '<td>' . $pricetotal . '</td>';
    $cartOutput .= '<td><form action="cart.php" method="post"><input name="deleteBtn' . $item_id . '" type="submit" value="X" /><input name="index_to_remove" type="hidden" value="' . $i . '" /></form></td>';
    $cartOutput .= '</tr>';
    $i++; 
    } 
  setlocale(LC_MONETARY, "en_US");
    $cartTotal = $cartTotal;
  $cartTotal = "<div style='font-size:18px; margin-top:12px;' align='right'>Cart Total : ".$cartTotal." USD</div>";
    // Finish the Paypal Checkout Btn
  $pp_checkout_btn .= '<input type="hidden" name="custom" value="' . $product_id_array . '">
  <input type="hidden" name="notify_url" value="https://www.yoursite.com/storescripts/my_ipn.php">
  <input type="hidden" name="return" value="https://www.yoursite.com/checkout_complete.php">
  <input type="hidden" name="rm" value="2">
  <input type="hidden" name="cbt" value="Return to The Store">
  <input type="hidden" name="cancel_return" value="https://www.yoursite.com/paypal_cancel.php">
  <input type="hidden" name="lc" value="US">
  <input type="hidden" name="currency_code" value="USD">
  <input type="image" src="http://www.paypal.com/en_US/i/btn/x-click-but01.gif" name="submit" alt="Make payments with PayPal - its fast, free and secure!">
  </form>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bootstrap eCommerce Template</title>

<!-- Bootstrap -->
<link rel="stylesheet" href="css/css/bootstrap.css">
<!--materialize-->
    <link type="text/css" rel="stylesheet" href="../css/materialize.min.css" media="screen,projection" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body onLoad="MM_preloadImages('file:fc_images/fc-icon-gold.png','file:fc_images/fc-header-gold.png')">
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
        </a><a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image7','','file:fc_images/fc-icon-gold.png',1)"><img src="file:fc_images/fc-icon-inv.png" alt="" width="32" height="42" id="Image7" style="margin-top: -10px; margin-left: -40px; padding:0px;">
        </a></div>
          <div class="navbar-header"><a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image8','','file:fc_images/fc-header-gold.png',1)"><img src="file:fc_images/fc-header-inv.png" alt="" width="245" height="42" id="Image8" style="position: fixed; margin-left: 45px; margin-top: 5px;"></a></div>
      
    </div>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li role="separator" class="divider"></li>
                <li class="dropdown-header">Nav header</li>
                <li><a href="#">Separated link</a></li>
                <li><a href="#">One more separated link</a></li>
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="../navbar/">Default</a></li>
            <li><a href="../navbar-static-top/">Static top</a></li>
            <li class="active"><a href="./">Fixed top <span class="sr-only">(current)</span></a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
<div class="container">
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div id="carousel1" class="carousel slide">
          <ol class="carousel-indicators">
            <li data-target="#carousel1" data-slide-to="0" class="active"> </li>
            <li data-target="#carousel1" data-slide-to="1" class=""> </li>
            <li data-target="#carousel1" data-slide-to="2" class=""> </li>
          </ol>
          <div class="carousel-inner">
            <div class="item"> <img class="img-responsive" src="css/img/1920x500.gif" alt="thumb">
              <div class="carousel-caption"> Carousel caption 1. Here goes slide description. Lorem ipsum dolor set amet. </div>
            </div>
            <div class="item active"> <img class="img-responsive" src="css/img/1920x500.gif" alt="thumb">
              <div class="carousel-caption"> Carousel caption 2. Here goes slide description. Lorem ipsum dolor set amet. </div>
            </div>
            <div class="item"> <img class="img-responsive" src="css/img/1920x500.gif" alt="thumb">
              <div class="carousel-caption"> Carousel caption 3. Here goes slide description. Lorem ipsum dolor set amet. </div>
            </div>
          </div>
          <a class="left carousel-control" href="#carousel1" data-slide="prev"><span class="icon-prev"></span></a> <a class="right carousel-control" href="#carousel1" data-slide="next"><span class="icon-next"></span></a></div>
      </div>
</div>
    <hr>
    <div id="pageContent">
    <div style="margin:24px; text-align:left;">
  
    <br />
    <table width="100%" border="1" cellspacing="0" cellpadding="6">
      <tr>
        <td width="18%" bgcolor="#C5DFFA"><strong>Product</strong></td>
        <td width="45%" bgcolor="#C5DFFA"><strong>Product Description</strong></td>
        <td width="10%" bgcolor="#C5DFFA"><strong>Unit Price</strong></td>
        <td width="9%" bgcolor="#C5DFFA"><strong>Quantity</strong></td>
        <td width="9%" bgcolor="#C5DFFA"><strong>Total</strong></td>
        <td width="9%" bgcolor="#C5DFFA"><strong>Remove</strong></td>
      </tr>
     <?php echo $cartOutput; ?>
     <!-- <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr> -->
    </table>
    <?php echo $cartTotal; ?>
    <br />
<br />
<hr>
  </div>
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
<?php include_once("includes/footer.html")?>
<script src="css/js/jquery-1.11.2.min.js"></script> 
<script src="css/js/bootstrap.min.js"></script>
</body>
</html>