<html><head><title>Invoice Details
</title></head>
<h1> Invoice View </h1>
<h2> Enter an Order ID </h2>
<?php include "connection.php"; include "style.php";
  
echo "<form action=invoice.php method=post>";
  echo "<label>Order ID:</label><input type=text name=orderid id=orderid><br>"; 
  echo "<br><input type=submit name=submit value=Submit><br>"; 
echo "</form>";

echo "<h2>Order Details:</h2>";
if($_SERVER['REQUEST_METHOD'] == 'POST') {
  $orderid = $_POST['orderid'];
  $sql = "SELECT * FROM ordereditems WHERE orderID = '$orderid'";
  $query = $pdo->query($sql);
  $match = $query->fetchALL(PDO::FETCH_ASSOC);
  echo "<table border=5, cellspacing=5, cell padding=5>";
    echo "<th>Order ID</th>";			
    echo "<th>Product Name</th>";
    echo "<th>Unit Price</th>";
    echo "<th>Amount On Hand</th>";
	    
    foreach($match as $row) {
      $productid = $row['productID'];
      $sqldesc = "SELECT * FROM parts WHERE number = '$productid'";
      $querydesc = $pdo2->query($sqldesc);
      $matchdesc = $querydesc->fetchALL(PDO::FETCH_ASSOC);
      // display the part from DB with the appropriate order ID

      foreach($matchdesc as $description) {
        echo "<tr>";
          echo "<td>$row[orderID]</td>"; 
          echo "<td>$description[description]</td>"; 
          echo "<td>$$description[price]</td>";
          echo "<td>$row[quantity]</td>";
        echo "</tr>";
      }
    } 
  echo "</table>";
  echo "<br><br>";
  echo "<h2>Total Order Stats:</h2>"; // display the total price and shipping fees for the order

  $sql2 = "SELECT * FROM orders WHERE ordersID = '$orderid'";
  $query2 = $pdo->query($sql2);
  $match2 = $query2->fetchALL(PDO::FETCH_ASSOC);
    
  foreach($match2 as $row2)	{
    echo "<table border=5,cellspacing=5,cellpadding=5>";	
    echo "<tr>";
    echo "<th>Net Price Of Products</th>";
      echo "<td>$$row2[totalprice]</td>";				
    echo "</tr>";

    echo "<tr>";
    echo "<th>Shipping and Handling Fees</th>";		
      echo "<td>$$row2[addfees]</td>";					
    echo "</tr>";

    echo "<tr>";
    echo "<th>Total Price</th>";		
      echo "<td>$$row2[finalprice]";					
    echo "</tr>";

    echo '</table>'; // end table
  }

}
    
// return index
echo "<form method=post action=index.php>";
  echo "<input class=home type=submit name'gohome' value='Return Home'/>";
echo "</form>";    
?>
</html>


