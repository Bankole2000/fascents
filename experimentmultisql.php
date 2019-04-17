<?php 

include "scripts/connect_to_mysql.php";
$ids ="";
$id = 28;
$output = "";
$sql= "SELECT * FROM products WHERE product_name IN (SELECT product_name FROM products WHERE product_id=$id ) ";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_array($result))
{
	$output .='<p>'. $row["colour"] . ' ' . $row["size"] . ' ' . $row["price"] . ' ' . $row["instock"] . '</p><br/>';
	$ids .= $row["product_id"];
}
echo $output;
echo $ids;
?>