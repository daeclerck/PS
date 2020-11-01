<html><head><title> Product System Catalog
</title></head>
<?php include "connection.php"; ?>
<?php

$cart = array();  // Users cart order

if(array_key_exists('cart', $_REQUEST)) { // check if cart exists
  $cart = unserialize(base64_decode($_REQUEST['cart'])); // decode the array
}

echo "<form method=post action=http://students.cs.niu.edu/~z1877438/PS/catalog.php>"; // post to catalog page
  $cart_item = base64_encode(serialize($cart)); // seriazlize the contenets again
  echo "<input type=hidden name='cart' value=$cart_item/>";
  echo "<input type=hidden name='list' value='filler'/>";
  echo "<input type=submit name='button1' value='View Catalog'/>";
  echo "<input type=submit name='button2' value='Close Catelog'/>";
echo "</form>"; // end of the form for openeing and closing the catalog 

// fill the cart button
echo "<form method=post action=http://students.cs.niu.edu/~z1877438/PS/catalog.php>"; // cart button to be submitted to cart.php
  $cart_item_2 = base64_encode(serialize($cart)); // serialze the contents back to array
  echo "<input type=hidden name='cart' value=$cart_item_2/>"; 
  echo "<input type=submit name='button7' value='Your Cart'/>";
echo "</form>"; // end of cart.php entry form

if(isset($_POST['button2'])) {
  unset($_REQUEST['list']);
}

if(array_key_exists('list', $_REQUEST)) {
  echo "<h1>Product Catalog Page</h1>";	// header
  echo "<h3> TEST TEST TEST </h3>";
  echo "<h3> TEST TEST TEST </h3>";

  // test a query
  $sql = "SELECT * FROM parts";
  $query1 = $pdo->query($sql);
  $rows = $query1->fetchALL(PDO::FETCH_ASSOC);

  //use a table to print the results
  echo "<table width='40%'border=3, cellspacing=15,cellpadding=1>"; // table settings
  echo '<tbody style="background-color:#FF726F">'; // coloring
    echo "<tr>"; 			
      echo "<th>Product #</th>";
      echo "<th>Product Name</th>";
      echo "<th>More information</th>";
    echo "</tr>"; 				
    foreach($rows as $value) { // displaying the database contents
      echo "<tr>"; //new row for the loop
        echo "<td>";
          echo "$value[number]"; // the product number here
        echo "</td>"; 

        echo "<td>";
          echo "$value[description]"; // product name here
        echo "</td>";
        
	// button to see more detials, more informatino on the product
        echo "<td>";
          echo "<form method=post action=http://students.cs.niu.edu/~z1877438/PS/moredetails.php>"; // button for taking to details.php page
            $details = base64_encode(serialize($value));
            echo "<input type=hidden name=pnum value=$details/>";
            $cart_details = base64_encode(serialize($cart));
            echo "<input type=hidden name='cart' value=$cart_details/>";
            echo "<input type=submit name='$value[number]' value='See More Information'/>";
          echo "</form>"; 	
        echo "</td>"; 	
      echo "</tr>"; 	
    }
  echo "</table>";
}
?>
</html>
  
