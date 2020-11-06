<html><head><title> Additional Cart Information
</title></head>
<?php
include "connection.php";
include "style.php";

  $cart = array();

  if (array_key_exists('cart', $_REQUEST)) { // Make sure the cart is populated
    $cart = unserialize(base64_decode($_REQUEST['cart'])); // unserialze the cart to be used
  }

  if (array_key_exists('pnum', $_REQUEST)) {// check to see if item is there
    $item = unserialize(base64_decode($_REQUEST['pnum'])); // get the part number of item to display
  }
  else { // display part number, description, price, weight and picture
    $item = array("number"=>"-1", "description"=>"NO ITEM CHOSEN", "price"=>"-1", "weight"=>"-1", "https://base.imgix.net/files/base/ebm/mhlnews/image/2019/03/mhlnews_4187_out_stock_1.png?auto=format&fit=crop&h=432&w=768"=>"pictureURL");
  }

  if (isset($_POST['add_to_cart'])) {
    $add_item = array("product"=>$item['number'], "quantity"=>$_POST['quantity']);
    array_push($cart, $add_item); // push any new item into array
  }

  echo "<form method=post action=http://students.cs.niu.edu/~z1877438/PS/catalog.php>";
    $item_array= base64_encode(serialize($cart)); // re-serialize items in cart
    echo "<input type=hidden name='cart' value=$item_array/>";
    echo "<input type=submit name='button3' value='Return to Catalog'/>";  // go back to product catalog
  echo "</form>";

  echo "<form method=post action=http://students.cs.niu.edu/~z1877438/PS/cart.php>"; // return to cart if user adds an item and wants to go back to cart
    echo "<input type=hidden name='pnum' value=$_REQUEST[pnum]/>";
    $item_array= base64_encode(serialize($cart)); // re-serialize the cart
    echo "<input type=hidden name='cart' value=$item_array/>";
    echo "<input type=submit name='button4' value='Your Cart'/>"; // button for user to view the contents of their cart
  echo "</form>";
  ?>

  <h1>Additional Product Information</h1>

<?php
    $sql = "SELECT quantity FROM inventory WHERE productID = $item[number]"; // get the quantity available for this item
    $query = $pdo->query($sql);
    $rows = $query->fetchAll(PDO::FETCH_ASSOC);
    $numitems = $rows[0]['quantity'];

    echo "<img src=$item[pictureURL] style=width:500px;height=500px>";// table and ceting
    echo "<br><br>";

    echo "<table width='50%' border=4,cellspacing=10, cellpadding=1>";
      echo "<tr>";
        echo "<th>Product #</th>";  // These identify the table's column headers
        echo "<th>Product Description</th>";
        echo "<th>Product Price</th>";
        echo "<th>Product Weight (lbs) </th>";
        echo "<th>Amount In Stock</th>";
      echo "</tr>";

      echo "<tr>";
        echo "<td>$item[number]</td>"; 					
        echo "<td>$item[description]</td>"; 	
        echo "<td>$$item[price]</td>"; 				
	echo "<td>$item[weight]</td>"; 					
       	echo "<td>$numitems</td>"; 						
      echo "</tr>";
    echo "</table>"; // end of table
?>

<?php
  echo "<form method=post action=http://students.cs.niu.edu/~z1877438/PS/details.php>"; // submit data using POST method
    echo "<input type=hidden name='pnum' value=$_REQUEST[pnum]/>"; // show valid part number
    echo "<input type=hidden name='cart' value=$_REQUEST[cart]/>";

    $found = False; // item not found by default

    foreach($cart as $cartcheck) {
      if ($cartcheck['product'] == $item['number']) { // if cart's item number was found, set condition as true
        $found = True;
      }
    }

    if ($found == False) {
      echo "<h5>Quantity You Would Like To Purchase:</h5>"; // user can choose how much to purchase
      echo "<input type=number name='quantity' min=1 max='$numitems' required/>";
      echo "<input type=submit name='add_to_cart' value='Add To Your Cart'/>"; 
    }

    else {
      echo "You added this item to your cart. Please edit your decision there."; // if user would like to edit their cart
    }
  echo "</form>";
?>

</html>


<footer>

CSCI 467

</footer>
