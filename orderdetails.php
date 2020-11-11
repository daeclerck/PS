<!-- Needs Revisions -->
<html><head><title>Order Details
</title></head>
<h1> Order Details: </h1>
<?php include "connection.php"; include "style.php";
    
$sql1 = "SELECT ordersID, custID, finalprice, date, status FROM orders";

if (array_key_exists('sql', $_REQUEST)) {
  $sql1 = "";
  $asarray = unserialize(base64_decode($_REQUEST['sql']));

  foreach($asarray as $word) {
    $sql1 = ($sql1 . $word . " ");
  }

  $sql1 = substr($sql1, 0, -1);
}

$toarray = explode(" ", $sql1);

echo "<form method=post action=http://students.cs.niu.edu/~z1877438/PS/orders.php>"; // submit data to orders.php using POST method
  $order_array= base64_encode(serialize($toarray)); // re-serialize items to an array
  echo "<input type=hidden name='sql' value=$order_array/>";
  echo "<input type=hidden name='viewall' value='View All Orders'/>";
  echo "<input type=hidden name='search' value='R'/>";
  echo "<input type=submit name='orders' value='Return to Search'/>";
echo "</form>";
    
$orderid = 0;
    
if (array_key_exists('oid', $_REQUEST)) { // if requested for a valid order id
  $orderid = intval($_REQUEST['oid']);
}

$sqlord = "SELECT * FROM orders WHERE ordersID = $orderid"; // select the requested order id from database
$queryord = $pdo->query($sqlord);
$arrayord = $queryord->fetchAll(PDO::FETCH_ASSOC); // fetch all orders with this id

$order = $arrayord[0];
$customer = $arrayord[0]['custID']; // pull up customer with this id

echo "<h3>Order Details</h3>";                                
echo "<table border=3>";                                      
echo "<tr>";
  echo "<th>Order ID</th>";
  echo "<th>Customer ID</th>";
  echo "<th>Status</th>";
  echo "<th>Total Weight</th>";
  echo "<th>Additional Charges</th>";
  echo "<th>Price</th>";
  echo "<th>Total Price</th>";
  echo "<th>Date of Order</th>";
echo "</tr>";

echo "<tr>";
  foreach($order as $data) {
    echo "<td>";
      echo "$data";                                             
    echo "</td>";
  }
echo "</tr>";
echo "</table>"; // end of table
    
echo "<h3>Item Details</h3>";
$sqlprod = "SELECT productID, quantity FROM ordereditems WHERE orderID = $orderid";
$queryprod = $pdo->query($sqlprod);
$arrayprod = $queryprod->fetchAll(PDO::FETCH_ASSOC); // fetch info for all orders with this id

echo "<table border=3>"; 
echo "<tr>";            
  echo "<th>Product Number</th>";
  echo "<th>Product Description</th>";
  echo "<th>Quantity Ordered</th>";
  echo "<th>Product Price</th>";
  echo "<th>Added Price</th>";
  echo "<th>Product Weight</th>";
  echo "<th>Added Weight</th>";
echo "</tr>";
   
foreach($arrayprod as $ordereditem) {
  $databdesc= "SELECT description, price, weight FROM parts WHERE number = $ordereditem[productID]"; // select data for product with this ID
  $querydesc= $pdo2->query($databdesc);
  $arraydesc = $querydesc ->fetchAll(PDO::FETCH_ASSOC);
  $description = $arraydesc[0]['description'];
  $weight = $arraydesc[0]['weight'];
  $price = $arraydesc[0]['price'];

  echo "<tr>";
    echo "<td>";
      echo "$ordereditem[productID]";  
    echo "</td>";

    echo "<td>";
      echo "$description";
    echo "</td>";

    echo "<td>";
      echo "$ordereditem[quantity]";                                                       
    echo "</td>";

    echo "<td>";
      echo "$price";                                         
    echo "</td>";

    $trueprice = ($ordereditem['quantity'] * $price); // multiply unit price for 1 item with the quantity ordered
    echo "<td>";
      echo "$trueprice";             
    echo "</td>";

    echo "<td>";
      echo "$weight";
    echo "</td>";

    $trueweight = ($ordereditem['quantity'] * $weight); // multiply unit weight for 1 item with the quantity ordered
    echo "<td>";
      echo "$trueweight";                           
    echo "</td>";
  echo "</tr>";
}
echo "</table>"; // end of table   

echo "<h3>Customer Details</h3>";

$internetcustomer = "SELECT name, email, address, ccnum FROM customer WHERE customerID = $customer";   // select customer info from database for viewing on page
$querycust = $pdo->query($internetcustomer);
$arraycust = $querycust->fetchAll(PDO::FETCH_ASSOC);

echo "<table border=3>";
echo "<tr>";                                                   
  echo "<th>Customer Name</th>";
  echo "<th>Customer Email</th>";
  echo "<th>Customer Address</th>";
  echo "<th>Credit Card</th>";
echo "</tr>";

echo "<tr>";
  foreach($arraycust as $cust) {
    echo "<td>";
      echo "$cust[name]";
    echo "</td>";

    echo "<td>";
      echo "$cust[email]";                                
    echo "</td>";

    echo "<td>";
      echo "$cust[address]";                                 
    echo "</td>";

    $star = "************";
    $censor = substr_replace($cust['ccnum'], $star, 0, -4);
    
    echo "<td>";
      echo "$censor";
    echo "</td>";
  }
echo "</tr>";
echo "</table>"; // end of table
    
?>
</html>
