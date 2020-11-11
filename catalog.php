<html><head><title> Product System Catalog
</title></head>
<?php include "connection.php"; include "style.php" ?>
<?php

$cart = array();  // Users cart order

if(array_key_exists('cart', $_REQUEST)) { // check if cart exists
  $cart = unserialize(base64_decode($_REQUEST['cart'])); // decode the array
}

if(!array_key_exists('list', $_REQUEST)) {
  echo "<form method=post action=catalog.php>"; // post to catalog page
    $cart_item = base64_encode(serialize($cart)); // seriazlize the contenets again
    echo "<input type=hidden name='cart' value=$cart_item/>";
    echo "<input type=hidden name='list' value='filler'/>";
    echo "<input type=submit name='button1' value='View Catalog'/>";
  echo "</form>"; // end of the form for openeing and closing the catalog 
}

if(isset($_POST['button1'])) {
  echo "<form method=post action=catalog.php>";
    echo "<input type=submit name='closecat' value='Close Catelog'/>";
  echo "</form>";
}

// fill the cart button
echo "<form method=post action=cart.php>"; // cart button to be submitted to cart.php
  $cart_item_2 = base64_encode(serialize($cart)); // serialze the contents back to array
  echo "<input type=hidden name='cart' value=$cart_item_2/>"; 
  echo "<input type=submit name='button7' value='Your Cart'/>";
echo "</form>"; // end of cart.php entry form


if(isset($_POST['closecat'])) {
  unset($_REQUEST['list']);
}

if(array_key_exists('list', $_REQUEST)) {
  echo "<h1>Product Catalog Page</h1>";	// header
  echo "<h3> TEST TEST TEST </h3>";
  echo "<h3> TEST TEST TEST </h3>";

  // get available quantity
  $sql = "SELECT * FROM inventory";
  $query = $pdo->query($sql);
  $rows = $query->fetchALL(PDO::FETCH_ASSOC);

  // get parts from legacy DB
  $sql2 = "SELECT * FROM parts"; 
  $query2 = $pdo2->query($sql2);
  $rows2 = $query2->fetchALL(PDO::FETCH_ASSOC); 

  // use a table to print the results
  echo "<table width='50%' border=4, cellspacing=5,cellpadding=1>"; // table settings
    echo "<tr>"; 			
      echo "<th>Product #</th>";
      echo "<th>Product Name</th>";
      echo "<th>Product Price</th>";
      echo "<th>Product Weight (lbs)</th>";
      echo "<th>Available Quantity</th>";
      echo "<th>Proceed to Cart</th>";
    echo "</tr>"; 				
    foreach($rows2 as $key2 => $value2) { // displaying the database contents
      echo "<tr>"; // new row for the loop
        echo "<td>";
          echo "$value2[number]"; // the product number here
        echo "</td>"; 

        echo "<td>";
          echo "$value2[description]"; // product name here
	echo "</td>";
	
	echo "<td>";
	  echo "$" . "$value2[price]"; // product price
	echo "</td>";

	echo "<td>";
	  echo "$value2[weight]"; // product weight
	echo "</td>";
	
	foreach($rows as $key => $value) {
	  if($key == $key2) { // print quantity for matching parts	
            echo "<td>";
              echo "$value[quantity]";
	    echo "</td>";
	  }
	}

        // button to see more details, more information on the product
        echo "<td>";
          echo "<form method=post action=details.php>"; // button for taking to details.php page
            $details = base64_encode(serialize($value2));
            echo "<input type=hidden name=pnum value=$details/>";
            $cart_details = base64_encode(serialize($cart));
            echo "<input type=hidden name='cart' value=$cart_details/>";
            echo "<input type=submit name='$value2[number]' value='View Cart Options'/>";
          echo "</form>"; 	
        echo "</td>"; 	
        echo "</tr>"; 	
    }
  echo "</table>";
  echo "</font>";
}

// return index
echo "<form method=post action=index.php>";
  echo "<input class=home type=submit name'gohome' value='Return Home'/>";
echo "</form>";
?>
</html>
  
