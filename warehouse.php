<html><head><title>Warehouse Details
</title></head>
<h1> Warehouse Details: </h1>
<?php include "connection.php"; include "style.php";
        
//try adding form to select order id:
echo "<form action=warehouse.php method=post>";
  echo "<label>Order ID:</label><input type=text name=orderid id=orderid required><br>"; //input box for order id number
  echo "<br><input type=submit name=submit value=Submit><br>"; //submit button
echo "</form>";    

if($_SERVER['REQUEST_METHOD'] == 'POST' && is_numeric($_POST['orderid'])) { // fail if there is a space in input string
  $orderid = $_POST['orderid'];

  $sql = "SELECT * FROM ordereditems WHERE orderID = $orderid";
  $query = $pdo->query($sql);
  $match = $query->fetchALL(PDO::FETCH_ASSOC);
   	 
  echo "<table border='5', cellspacing=5, cellpadding=5>";
  echo "<th>Order ID</th>";
  echo "<th>Product Name</th>";
  echo "<th>Product ID </th>";
  echo "<th>Quantity</th>";

  // displaying the information from the query in the table
  foreach($match as $row) {
    $productid = $row['productID'];
    $sqldesc = "SELECT description FROM parts WHERE number = $productid";
    $querydesc = $pdo2->query($sqldesc);
    $matchdesc = $querydesc->fetchALL(PDO::FETCH_ASSOC);
    foreach($matchdesc as $description) {
      echo "<tr>";
        echo "<td>$row[orderID]</td>"; //display orderID
        echo "<td>$description[description]</td>"; //display description
	echo "<td>$row[productID]</td>"; //display productID
	echo "<td>$row[quantity]</td>"; //display quantity
      echo "</tr>";
    }
  } 
  echo '</table>';
}

// return index
echo "<form method=post action=index.php>";
  echo "<input class=home type=submit name'gohome' value='Return Home'/>";
echo "</form>";
?>
</html>
