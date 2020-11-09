<html><head><title>Your Cart
</title></head>
<?php include "connection.php"; include "style.php"; 

$items = array(); // create the array

if(array_key_exists('cart', $_REQUEST)) {
  $items = unserialize(base64_decode($_REQUEST['cart'])); // unserialize array
}

if(isset($_POST['update_quantity'])) {
  $index = intval($_POST['index']);
  $quantity = intval($_POST['quantity']);
  $items[$index]['quantity'] = $quantity;
}

echo "<form method=post action=http://students.cs.niu.edu/~z1877438/PS/catalog.php>"; 
  $cart_item = base64_encode(serialize($items));
  echo "<input type=hidden name='cart' value=$cart_item/>";
  echo "<input type=submit name='button5' value='Return To The Catalog'/>"; // return back to catalog page 
echo "</form>";

echo "<form method=post action=http://students.cs.niu.edu/~z1877438/PS/cart.php>";
  echo "<input type=submit name='button8' value='Clear Your Cart'/>"; // cleaning the cart and resubmit to cart.php
echo "</form>";

if(array_key_exists('pnum', $_REQUEST)) {
  echo "<form method=post action=http://students.cs.niu.edu/~z1877438/PS/details.php>";
    echo "<input type=hidden name='pnum' value=$_REQUEST[pnum]/>";
    $cart_items2 = base64_encode(serialize($items));
    echo "<input type=hidden name='cart' value=$cart_items2/>";
    echo "<input type=submit name='button6' value='Return to Previous Page'/>"; //button for going back to prodcut information
  echo "</form>";
}

if(!empty($items)) {
  $plist = array();         //defining arrays
  $calclist = array();
  $calcentry = array();
  foreach($items as $citem) {
    $sql2 = "SELECT * FROM parts WHERE number = $citem[product]";   //query for parts with product num
    $query2 = $pdo2->query($sql2);
    $rows2 = $query2->fetchAll(PDO::FETCH_ASSOC);
    array_push($plist, $rows2[0]);
  }

  $count = 0;
  $itemprices = 0;
  $totalweight = 0;
  $addfees = 0;
  $finalprice = 0;
  foreach($items as $centry) {
    $sql = "SELECT quantity FROM inventory WHERE productID = $centry[product]"; //showing the quantity
    $query = $pdo->query($sql);
    $rows = $query->fetchAll(PDO::FETCH_ASSOC);
    $numitems = $rows[0]['quantity'];

    $pentry = $plist[$count];
    $calcentry = array("pid"=>($centry["product"]),
                       "des"=>($pentry["description"]),
                       "qty"=>($centry["quantity"]),
                       "tpr"=>"$".number_format(($centry["quantity"] * $pentry["price"]), "2"),
                       "twe"=>($centry["quantity"] * $pentry["weight"]),
                       "mqy"=>($numitems));
    array_push($calclist, $calcentry);

    //increment the summary variables
    $itemprices += ($centry["quantity"] * $pentry["price"]);
    $totalweight += ($centry["quantity"] * $pentry["weight"]);

    $count++;
  }

  //set the additional fees
  $sqladd = "SELECT * FROM admin ORDER BY bracket ASC";
  $queryadd = $pdo->query($sqladd);
  $weightarray = $queryadd->fetchAll(PDO::FETCH_ASSOC);

  //set default charge
  if(count($weightarray) == 0) {
    $addfees = 0;
  }
  else {
    // set the additional fees
    foreach($weightarray as $weightbracket) {
      if ($totalweight <= $weightbracket['bracket']) {
        $addfees = $weightbracket['charge'];
        break;
      }
    }
  }

  $itemprices = number_format(round($itemprices, 2), "2");
  $totalweight = round($totalweight, 2);
  $addfees = round($addfees, 2);
  $finalprice = number_format(round(($itemprices + $addfees), 2), "2");

  echo "<h1>Review Your Items</h1>";

  echo "<table border=4, cellspacing=10, cellpadding=1>";  //designing the border
    echo "<tr>"; // headers for table here
    echo "<th>Product ID</th>";
    echo "<th>Product Name</th>";
    echo "<th>Quantity Ordered</th>";
    echo "<th>Added Price</th>";
    echo "<th>Added Weight (lbs)</th>";
    echo "<th>Change Quantity</th>";
    echo "<th>Delete?</th>";
    echo "<th>Go To Item Details</th>";
    echo "</tr>";

    $count2 = 0;
    foreach($calclist as $cinfo) {
      echo "<tr>";
      foreach($cinfo as $label=>$data) {
        if ($label != "mqy") {
          echo "<td>$data</td>"; //the data for the product
        }
      }

      echo "<td>";
      echo "<form method=post action=http://students.cs.niu.edu/~z1877438/PS/cart.php>"; // moving to cart

      if (array_key_exists('pnum', $_REQUEST)) { // current cart
        echo "<input type=hidden name='pnum' value=$_REQUEST[pnum]/>";
      }

        $cart_item3 = base64_encode(serialize($items));
        echo "<input type=hidden name='cart' value=$cart_item3/>";
        echo "<input type=hidden name='index' value=$count2/>";
        echo "<input type=number name='quantity' min=1 max='$cinfo[mqy]'/>";
        echo "<input type=submit name='update_quantity' value='Change The Amount'/>";
      echo "</form>"; //end of changes made in the currrent caart
      echo "</td>";

      echo "<td>";
      echo "<form method=post action=http://students.cs.niu.edu/~z1877438/PS/cart.php>";
      if (array_key_exists('pnum', $_REQUEST)) {
        echo "<input type=hidden name='pnum' value=$_REQUEST[pnum]/>";
      }

        $cartarray = array();
        foreach($items as $cartitem) {
          if ($cartitem['product'] != $cinfo['pid']) {
            array_push($cartarray, $cartitem);
          }
        }
        $cart_item4 = base64_encode(serialize($cartarray));
        echo "<input type=hidden name='cart' value=$cart_item4/>";
        echo "<input type=submit name=$cinfo[pid] value='Delete'/>";
      echo "</form>";
      echo "</td>";
      echo "<td>";
      echo "<form method=post action=http://students.cs.niu.edu/~z1877438/PS/details.php>";
        $cart_item5 = base64_encode(serialize($plist[$count2]));
        echo "<input type=hidden name='pnum' value=$cart_item5/>";
        $cart_item6 = base64_encode(serialize($items));
        echo "<input type=hidden name='cart' value=$cart_item6/>";
        echo "<input type=submit name='revisit' value='View Item'/>";
      echo "</form>";
      echo "</td>";
      echo "</tr>";
      $count2++;
    }
    echo "</table>";

    echo "<h2>Total Order Statistics</h2>";  // order and desgining of the table
    echo "<table border=1, cellspacing=10, cellpadding=1>";

    echo "<tr>"; // table headers
      echo "<th>Total Price</th>";
      echo "<th>Total Weight (lbs)</th>";
      echo "<th>Additional Fees</th>";
      echo "<th>Final Price</th>";
    echo "</tr>";
    echo "<tr>";
      echo "<td>$$itemprices</td>";
      echo "<td>$totalweight</td>";
      echo "<td>$$addfees</td>";
      echo "<td>$$finalprice</td>";
      echo "</tr>";
    echo "</table>";

    echo "<h2>Ready to Order? Continue to checkout.</h2>";
    // moving to the checkout page and pulling everything over
    echo "<form method=post action=http://students.cs.niu.edu/~z1877438/PS/checkout.php>";

    $final_cart = base64_encode(serialize($items));
      echo "<input type=hidden name='cart' value=$final_cart/>";
      echo "<input type=hidden name='itemprices' value=$itemprices/>";
      echo "<input type=hidden name='totalweight' value=$totalweight/>";
      echo "<input type=hidden name='addfees' value=$addfees/>";
      echo "<input type=hidden name='finalprice' value=$finalprice/>";
      echo "<input type=submit name='gotocheckout' value='Go To Checkout'/>";
    echo "</form>";
  }

  else {
    echo "Your Cart is empty";
  }
?>
</html>

