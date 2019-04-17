<?php
// Connect to the MySQL database  
require "connect_to_mysql.php";  

$sqlCommand = "CREATE TABLE customers (
		 		 customer_id int(11) NOT NULL auto_increment,
				 first_name varchar(255) NOT NULL,
		 		 last_name varchar(255) NOT NULL,
		 		 email varchar(255) NOT NULL,
		 		 password varchar(255) NOT NULL,
		 		 address text NOT NULL,
				 date_added date NOT NULL,
		 		 PRIMARY KEY (product_id)
		 		 FOREIGN KEY (customer_id)
		 		 ) ";
if (mysql_query($sqlCommand)){ 
    echo "Your customer table has been created successfully!"; 
} else { 
    echo "CRITICAL ERROR: products table has not been created.";
}
?>