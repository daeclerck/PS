<html><head><title>Receiving Desk
</title></head>
<h1> Receiving Desk </h1>
<?php include "connection.php"; include "style.php";
 
if(array_key_exists('change', $_REQUEST)) {
  $changenum = intval($_REQUEST['quantity']);
  $productnum = intval($_REQUEST['pnmbr']);
  $sqlchange = "UPDATE inventory SET quantity = quantity + $changenum WHERE productID = $productnum";
  $qchange = $pdo->query($sqlchange);
}    
    
echo "<h2>Current Inventory</h2>";

$sqlparts = "SELECT number FROM parts";
$queryparts = $pdo2->query($sqlparts);
$arrayparts = $queryparts->fetchAll(PDO::FETCH_ASSOC);

$mainarray = array();
    
foreach($arrayparts as $pnumb) {// find the proper value
  $sqlfind = "SELECT productID, quantity FROM inventory WHERE productID = $pnumb[number]";
  $queryfind = $pdo->query($sqlfind);
  $arrayfind = $queryfind->fetchAll(PDO::FETCH_ASSOC);
  array_push($mainarray, $arrayfind[0]);
}    
    
echo "<table border=5, cellspacing=5, cellpadding=5>"; // formatting for the table
echo "<tr>";
  echo "<th>Product #</th>";
  echo "<th>Product Name</th>";
  echo "<th>Quantity on Hand</th>";
  echo "<th>Edit Quantity</th>";
echo "</tr>";

foreach($mainarray as $num) {
  echo "<tr>";
    $sqldesc = "SELECT description FROM parts WHERE number = $num[productID]";
    $querydesc = $pdo2->query($sqldesc);
    $arraydesc = $querydesc->fetchAll(PDO::FETCH_ASSOC);
    $description = $arraydesc[0]['description'];

    echo "<td>";
      echo "$num[productID]"; 
    echo "</td>";

    echo "<td>";
      echo "$description";
    echo "</td>";

    echo "<td>";
      echo "$num[quantity]";
    echo "</td>";

    echo "<td>";
      echo "<form method=post action=http://students.cs.niu.edu/~z1877438/PS/desk.php>";
        echo "<input type=number name='quantity' min=1 required/>";
        echo "<input type=hidden name='pnmbr' value=$num[productID]/>";
        echo "<input type=submit name='change' value='Order'/>";
      echo "</form>";
    echo "</td>";

  echo "</tr>";
}  

// return to index
echo "<form method=post action =http://students.cs.niu.edu/~z1877438/PS/index.php>";
  echo "<input class=home type=submit name'gohome' value='Return Home'/>";
echo "</form>";
?>

</html>
