<?php 
//action.php for ajax cart
session_start();
include "scripts/connect_to_mysql.php";
if(isset($_POST["product_id"]))
{
	$order_table = '';
	$message = '';
	if($_POST["action"] == "add")
	{
		if(isset($_SESSION["shopping_cart"]))
		{
			$is_available = 0;
			foreach($_SESSION["shopping_cart"] as $keys => $values)
			{
				if($_SESSION["shopping_cart"][$keys]['product_id'] == $_POST["product_id"])
				{
					$is_available++;
					$_SESSION["shopping_cart"][$keys]['product_quantity'] = $_SESSION["shopping_cart"][$keys]['product_quantity'] + $_POST["product_quantity"];
				}
			}
			if($is_available < 1) 
			{
				$item_array = array(
						'product_id' => $_POST["product_id"],
						'product_name' => $_POST["product_name"],
						'product_price' => $_POST["product_price"],
						'product_quantity' => $_POST["product_quantity"],
						'size' => $_POST["size"],
						'colour' => $_POST["colour"]

					);
				$_SESSION["shopping_cart"][]=$item_array;
			}
		}
		else 
		{
			$item_array = array(
						'product_id' => $_POST["product_id"],
						'product_name' => $_POST["product_name"],
						'product_price' => $_POST["product_price"],
						'product_quantity' => $_POST["product_quantity"],
						'size' => $_POST["size"],
						'colour' => $_POST["colour"]
				);
			$_SESSION["shopping_cart"][] = $item_array;
		}
		$order_table .= '<div class="table-responsive">
			<table class="table table-bordered">
				<tr>
					<th width="20%">Image</th>
					<th width="25%">Name</th>
					<th width="5%">Size</th>
					<th width="10%">Colour</th>
					<th width="15%">Price</th>
					<th width="5%">Qty</th>
					<th width="15%">Total</th>
					<th width="5%">Action</th>
				</tr>';
			if(!empty($_SESSION["shopping_cart"]))
			{	
				$total = 0;
				foreach($_SESSION["shopping_cart"] as $keys => $values)
				{
					$order_table.='
				<tr>
					<td><img src="product_images/' .$values["product_id"] . 'f.jpg" width="49px" height="60px"></td>
					<td>'.$values["product_name"].'</td>
					<td>'.$values["size"].'</td>
					<td>'.$values["colour"].'</td>
					<td>&#8358;'.$values["product_price"].'</td>
					<td>'.$values["product_quantity"].'</td>
					<td>&#8358;'.number_format($values["product_quantity"] * $values["product_price"], 2).'</td>
					<td>Action</td>
				</tr>

					';
					$total = $total + ($values["product_quantity"] * $values["product_price"]);
				}
				$order_table .=  '
				<tr>
					<td colspan="3" align="right">Total</td>
					<td align="right">&#8358; '.number_format($total, 2).'</td>
					<td></td>
				</tr>';
			}
			$order_table .=  '</table>';
			$output = array(
					
						'order_table' => $order_table,
						'cart_item' => count($_SESSION["shopping_cart"])

						);
			} echo json_encode($output);
			

}

?>