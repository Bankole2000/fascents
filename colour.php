<?php 

include "scripts/connect_to_mysql.php";
$output = "";
$image = "";
$colour= $_POST["colour"];
$product_name=$_POST["product_name"];

$sql="SELECT product_id, size FROM products WHERE colour='$colour' AND product_name='$product_name' ";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_array($result))
{
		
	$output .= '<option value="'.$row["size"].'">'. $row["size"] .'</option>';
	$id = $row['product_id'];
	$imagef = 'product_images/'.$id.'f.jpg';
	$images = 'product_images/'.$id.'s.jpg';
	$imageb = 'product_images/'.$id.'b.jpg';

}

echo $output.",".$imagef.",".$images.",".$imageb;

?>






