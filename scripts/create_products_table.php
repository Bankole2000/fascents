<?php
// Connect to the MySQL database  
require "connect_to_mysql.php";  

$sqlCommand = "CREATE TABLE products (
		 		 product_id int(11) NOT NULL auto_increment,
				 product_name varchar(255) NOT NULL,
		 		 price int(16) NOT NULL,
				 details text NOT NULL,
				 category varchar(16) NOT NULL,
				 subcategory varchar(16) NOT NULL,
				 colour varchar(16) NOT NULL,
				 size varchar(5) NOT NULL,
				 rare varchar(5) NOT NULL,
				 pimagef varchar (200) NOT NULL,
				 pimages varchar (200) NOT NULL,
				 pimageb varchar (200) NOT NULL,
				 instock int(11) NOT NULL,
				 israre varchar(16) NOT NULL,
		 		 date_added date NOT NULL,
		 		 PRIMARY KEY (product_id)
		 		 ) ";
if (mysql_query($sqlCommand)){ 
    echo "Your products table has been created successfully!"; 
} else { 
    echo "CRITICAL ERROR: products table has not been created.";
}
?>