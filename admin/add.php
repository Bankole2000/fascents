<?php 
// Parse the form data and add inventory item to the syste
include "../scripts/connect_to_mysql.php"; 


if (isset($_POST['addnew'])) {
	
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

}} /*elseif (isset($_POST['update'])){
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

  $sql= "UPDATE products SET product_name='" . $product_name . "', oprice= '" . $oprice . "', price ='".$price."', details='".$details."', category='".$category."', subcategory='".$subcategory."', size='".$size."', colour='".$colour."', instock='".$instock."', rare='".$rare."' WHERE product_id='".$pid. "'";
if($conn->query($sql)==TRUE){
echo "Record updated successfully";
} else {

  echo "error updating record: ". $conn->error;
}  
  //$update = mysqli_query($conn, $sql) or die ("Query failed");
  if ($_FILES['fileField1']['tmp_name'] != "") {
      // Place image in the folder 
      $newname1 = "{$id}f.jpg";
      $newname2 = "{$id}s.jpg";
      $newname3 = "{$id}b.jpg";
      
      move_uploaded_file($_FILES['fileField1']['tmp_name'], "../inventory_images/$newname1");
      move_uploaded_file($_FILES['fileField2']['tmp_name'], "../inventory_images/$newname2");
      move_uploaded_file($_FILES['fileField3']['tmp_name'], "../inventory_images/$newname3");
  
  }
  header("location: product_list.php"); 
  exit();

}*/

elseif (isset($_POST['cancel'])){
header("location: product_list.php");
exit();

}
?>