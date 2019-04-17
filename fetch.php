<?php
//fetch modal content data
if(isset($_POST["product_id"]))
{
	include "scripts/connect_to_mysql.php";
	$modal_content="";
	$colour="";
	$query = "SELECT * FROM products WHERE product_id='".$_POST["product_id"]."'";
	$result = mysqli_query($conn, $query);
	while($row = mysqli_fetch_array($result))
	{
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
		$modal_content='<div class="modal-dialog">
   
    <div class="modal-content" style="overflow:hidden;" >
      <div class ="modal-body" style="overflow: hidden; margin-left: 0px; align:center;">
   <div class="table-responsive">
    <table class="table" style="border:0px; margin:0px; padding:0px;">
    <tr>
    <td>
   
      <div class="easyzoom easyzoom--adjacent is-ready easyzoom--with-thumbnails">
        <a href="product_images/' . $id . 'f.jpg">
          <img src="product_images/' . $id . 'f.jpg" alt="" width="240" height="300"/>
        </a>
      </div><hr/>
      <ul class="thumbnails" style="margin-left: 0px !important; padding-left:0px !important;">
        <li>
          <a href="product_images/' . $id . 'f.jpg" data-standard="product_images/' . $id . 'f.jpg">
            <img src="product_images/' . $id . 'f.jpg" alt="" style="width:74px !important; height:93px !important;"/>
          </a>
        </li>
        <li>
          <a href="product_images/' . $id . 's.jpg" data-standard="product_images/' . $id . 's.jpg">
            <img src="product_images/' . $id . 's.jpg" alt="" width="74" height="93"/>
          </a>
        </li>
        <li>
          <a href="product_images/' . $id . 'b.jpg" data-standard="product_images/' . $id . 'b.jpg">
            <img src="product_images/' . $id . 'b.jpg" alt="" width="74" height="93"/>
          </a>
        </li>
       
      </ul>

      </td>
      <td style="text-align:left;">
      <h2 style="margin:0; padding:0px; border:0px;">' . $product_name . '</h2>
      <h5 class="po" style="line-height:15px !important; margin-right:10px !important;">&darr;' . $po . '%</h5><h3 style="line-height:0px; text-align:left;"><strong>&#8358;' . number_format($price, 2) . '</strong></h3><h5 class="op"style = "line-height:0px !important;">&#8358;' . number_format($oprice, 2) . '</h5><hr/>
      <p>' . $details . '</p>
       <div class="input-group">
       <p>Select Product Options</p>
       <form class="form-in-line" id="demigod" action="product_form.php" enctype="multipart/form-data" name="productForm" id="productForm" method="post">
  <div class="form-group col-sm-4 col-xs-4 col-md-4" style="margin:0px;">      
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
          <input name="product_id" id="product_id" type="hidden" value="" />
       <div class="form-group col-sm-4 col-xs-4 col-md-4" style="margin-left:auto;">
        <label>Colour</label>
        <select class="form-control" name="colour" id="coloursssss" placeholder="colour">
        
          </select></div>
          <div class="form-group col-sm-4 col-xs-4 col-md-4">      
        <label>Qty</label>
        <select class="form-control" name="qty" id="qty">
        <option value=""></option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
          </select></div><div class="col-sm-12 col-xs-12 col-md-12">
          <input type="hidden" name="hidden name" value="'. $id .'">
        <input class="btn btn-warning btn-md" style="width:100%;" type="submit" name="button" id="button" value="Add To Cart" />
         </div>
         </form>
 </div>
</form>
 <div class="btn-group btn-group-justified" role="group" aria-label="...">
              
             <div class="btn-group" role="group">
            <a href="#"><button type="button" class="btn btn-secondary">Details &raquo;</button></a>
          </div>
          <div class="btn-group" role="group">
              <a href="#quickview" data-toggle="modal"><button type="button" class="btn btnfcblck">Back</button></a>
            </div>
              </div>
        </td>
        </tr>
        </table>
        </div>
        </div>
      </div>
    </div>';
	}
	$query2 = "SELECT colour FROM products WHERE product_name='".$_POST["product_name"]."'";
	$result2 = mysqli_query($conn, $query2);
	while($row2 = mysqli_fetch_array($result2))
	{
		 $colour .= '<option value="'.$row2["colour"].'">'.$row2["colour"].'</option>';
	}
	

	echo $modal_content, $colour;
}

?>