<html><head><title> Checkout
</title></head>
<?php include "connection.php"; include "style.php";
    
$items = array(); // create the array

if(array_key_exists('cart', $_REQUEST) || array_key_exists('placeorder', $_REQUEST)) {	// maintain the cart array
  $items = unserialize(base64_decode($_REQUEST['cart']));
}
    
$itemprices = 0;
$totalweight = 0;
$addfees = 0;
$finalprice = 0;
    
if(array_key_exists('gotocheckout', $_REQUEST) || array_key_exists('placeorder', $_REQUEST)) { //if array for checkout
  $itemprices = round(floatval($_REQUEST['itemprices']), 2);
  $totalweight = round(floatval($_REQUEST['totalweight']), 2);
  $addfees = round(floatval($_REQUEST['addfees']), 2);
  $finalprice = round(floatval($_REQUEST['finalprice']), 2);
}

// credentials prototype
$name = "-----";
$email = "----@----------";
$address = "-----";
$cnumber = "----------------";
$cxp = "--/----";
	
if(array_key_exists('placeorder', $_REQUEST)) {
  $name = $_REQUEST['name'];
  $email = $_REQUEST['email'];
  $address = $_REQUEST['address'];
  $cnumber = $_REQUEST['cnumber'];
  $cexp = $_REQUEST['cexp'];
}    

echo "<form method=post action=cart.php>"; // cart button to submit the cart.php
  $cart_item = base64_encode(serialize($items)); // serializing the array
  echo "<input type=hidden name='cart' value=$cart_item/>";
  echo "<input type=submit name='backtocart' value='Go Back To Your Cart'/>";
echo "</form>";    
    
if (!(array_key_exists('placeorder', $_REQUEST))) {
  echo "<form method=post action=checkout.php>"; // on checkout page
    $cart_item2 = base64_encode(serialize($items)); // serializing the array
    echo "<input type=hidden name='cart' value=$cart_item2/>";
    echo "<input type=hidden name='itemprices' value=$itemprices/>";
    echo "<input type=hidden name='totalweight' value=$totalweight/>";
    echo "<input type=hidden name='addfees' value=$addfees/>";
    echo "<input type=hidden name='finalprice' value=$finalprice/>";
    echo "<h1>Order Checkout: </h1>";
    echo "<label>Your Name: </label>";
    echo "<input type=text name='name' placeholder='Your Name Here' required/> <br><br>";
    echo "<label>Your Email: </label>";
    echo "<input type=text name='email' placeholder='Your Email Here' required/> <br><br>";
    echo "<label>Your Address: </label>";
    echo "<input type=text name='address' placeholder='Your Address Here' required/> <br><br>";
    echo "<label>Credit Card Number: </label>";
    echo "<input type=text name='cnumber' placeholder='Correct Format' required/> <br>";
    echo '<label><small>(The format is "6011 1234 4321 1234")</small></label>';
    echo "<br><br>";
    echo "<label>Card Expiration Date: </label>";
    echo "<input type=text name='cexp' placeholder='Expiration Date' required/> <br>";
    echo '<label><small> (The format is "MM/YYYY")</small></label><br>';
    echo "<br> <input type=submit name='placeorder' value='Confirm Your Details'/>";
  echo "</form>";
}    

else {
  //check if the customer already exists
  $sql = "SELECT * FROM customer WHERE name = '$name' AND email = '$email'"; // select from customers in DB
  $query = $pdo->query($sql);
  $match = $query->fetchAll(PDO::FETCH_ASSOC);
  $customerid = 0;
  if (count($match) == 0) {
    // add customer to database
    $sql2 = "INSERT INTO customer (name, email, address, ccnum, ccexp)
             VALUES ('$name', '$email', '$address', '$cnumber', '$cexp')";
    $query2 = $pdo->query($sql2);
    $customerid = $pdo->lastInsertId();
  }
    
  else {
    // grab the customer id
    $customerid = $match[0]['customerID'];

    $sql3 = "UPDATE customer SET address = '$address', ccnum = '$cnumber', ccexp = '$cexp'
             WHERE customerID = '$customerid'";
    $query3 = $pdo->query($sql3);
  }
    
  $ordersid = 0;
  // place the order into the orders table
  $orderstatus = 'A';
  $date = date("Y-m-d");
  $sql4 = "INSERT INTO orders (custID, status, totalweight, addfees, totalprice, finalprice, date)
           VALUES ('$customerid', '$orderstatus', '$totalweight', '$addfees', '$itemprices', '$finalprice', '$date')";
  $query4 = $pdo->query($sql4);

  $ordersid = $pdo->lastInsertId();
    
  // place each item into the ordered items table
  foreach($items as $citem) {
    $quantity = $citem['quantity'];
    $prodid = $citem['product'];
    $sql5 = "INSERT INTO ordereditems (orderID, quantity, productID)
             VALUES ('$ordersid', '$quantity', '$prodid')";
    $query5 = $pdo->query($sql5);
  }
    
  // generate a random vendor id
  function randstring($n) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < $n; $i++) {
      $index = rand(0, strlen($characters) - 1);
      $randomString .= $characters[$index];
    }

    return $randomString;
  }
    
  function randnum($n) {
    $characters = '0123456789';
    $randomString = '';

    for ($i = 0; $i < $n; $i++) {
      $index = rand(0, strlen($characters) - 1);
      $randomString .= $characters[$index];
    }

    return $randomString;
  }
    
  $vendorid = ("VE" . randnum(3) . "-" . randnum(2));
  $transactionid = (randnum(3) . "-" . randnum(6) . "-" . randnum(3));

  // send data to the credit cart authorization system
  $url = 'http://blitz.cs.niu.edu/CreditCard/';
  $data = array(
    'vendor' => $vendorid,
    'trans' => $transactionid,
    'cc' => $cnumber,
    'name' => $name,
    'exp' => $cexp,
    'amount' => $finalprice);

  $options = array(
    'http' => array(
    'header' => array('Content-type: application/json', 'Accept: application/json'),
    'method' => 'POST',
    'content'=> json_encode($data)
    )
  );  

  $context  = stream_context_create($options);
  $result = file_get_contents($url, false, $context);

  $nobracket1 = explode("{", $result);
  $nobracket2 = explode("}", $nobracket1[1]);
  $nocommas = explode(",", $nobracket2[0]);
  $quotes = array();
  $values = array();
    
  foreach($nocommas as $pair) {
    $kyval = explode(":", $pair);
    array_push($quotes, $kyval[0]);
    array_push($values, $kyval[1]);
  }
    
  $keys = array();
    
  foreach($quotes as $kval) {
    $noquotes = explode('"', $kval);
    array_push($keys, $noquotes[1]);
  }

  $resultarray = array_combine($keys, $values);

  // check the result
  if (array_key_exists('errors', ($resultarray))) {
    // delete all entries
    $sqldel = "DELETE FROM ordereditems WHERE orderID = $ordersid";
    $querydel = $pdo->query($sqldel);

    // delete order
    $sqldel2 = "DELETE FROM orders WHERE ordersID = $ordersid";
    $querydel2 = $pdo->query($sqldel2);

    echo "There was a problem processing your order.<br>";
    echo "ERROR: $resultarray[errors] <br>";
    echo "Please head back to your cart and try again.";
  }
   
  else {
    // if the order was successful, reduce the inventory for that item
    foreach ($items as $inventoryitem) {
      $quantity = $inventoryitem['quantity'];
      $refid = $inventoryitem['product'];

      $databquantity= "SELECT quantity FROM inventory WHERE productID = $refid";
      $queryquan = $pdo->query($databquantity);
      $quanrow = $queryquan->fetchAll(PDO::FETCH_ASSOC);
      $foundquantity = $quanrow[0]['quantity'];
      $newquantity = ($foundquantity - $quantity);

      $sqlinv = "UPDATE inventory SET quantity = $newquantity WHERE productID = $refid";
      $queryinv = $pdo->query($sqlinv);
    }

    // send email
    $subject = "Confirm Order Number $ordersid";
    $message = "This message confirms that you made a purchase with confirmation code $resultarray[authorization]";
    $headers = "From: <noreply@PSdaeclerck.com>";
    mail($email, $subject, $message, $headers);

    echo "<h1>Thank you for your order!</h1>";
    echo "Your confirmation number is: $resultarray[authorization] <br>";
    echo "An email has been sent to you at $email.";

    $items = array();
    echo "<form method=post action=catalog.php>";
      echo "<input type=submit name='gotocatelog' value='Clear And Go Back To Catalog'/>";
    echo "</form>";
  }  
}
    
?>    
</html>
