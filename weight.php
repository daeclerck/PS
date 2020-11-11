<html><head><title>Admin Page
</title></head>

<h1> Weight Brackets</h1>
<?php include "connection.php"; include "style.php";

echo "<form method=post action=orders.php>"; 
  echo "<input type=submit name='backorder' value='Go Back to Orders Page'/>";
echo "</form>";

echo "<h2>Adjusting Weight Bracket and Charges</h2>";    
    
if(array_key_exists('add', $_REQUEST)) {
  $weightnum = $_REQUEST['addnum'];
  $floatnum1 = round(floatval($weightnum), 2);

  if($floatnum1 < 0) {
    $floatnum1 = 0; // ensure num won't be less than 0
  }

  $chargenum = $_REQUEST['addcharge'];
  $floatnum2 = round(floatval($chargenum), 2);

  if($floatnum2 < 0) {
    $floatnum2 = 0; // ensure num won't be less than 0
  }
    
  //reset the first element
  $sql = "SELECT * FROM admin WHERE bracket = $floatnum1";
  $query = $pdo->query($sql);
  $match = $query->fetchAll(PDO::FETCH_ASSOC);

  if(count($match) != 0) {
    $sql2 = "UPDATE admin SET charge = $floatnum2 WHERE bracket = $floatnum1";
    $query2 = $pdo->query($sql2);
  }

  else {
    $sqladd = "INSERT INTO admin (bracket, charge) VALUES ($floatnum1, $floatnum2)";
    $queryadd = $pdo->query($sqladd);
  }
}
    
if(array_key_exists('delete', $_REQUEST)) {
  $number = round(floatval($_REQUEST['weightdel']), 2);
  $sqldel = "DELETE FROM admin WHERE bracket = $number";
  $querydel = $pdo1->query($sqldel);
}

$sql3 = "SELECT * FROM admin ORDER BY bracket";
$query3 = $pdo->query($sql3);
$brackets = $query3->fetchAll(PDO::FETCH_ASSOC);
    
echo "<table border=5,cellspacing=5,cellpadding=5>";
echo "<tr>";
  echo "<th>Weights</th>";
  echo "<th>Charges</th>"; 
  echo "<th>Delete</th>"; 
echo "</tr>";

foreach($brackets as $bracket) {
  echo "<tr>";
    echo "<td>";
      echo "$bracket[bracket]";
    echo "</td>";

    echo "<td>";
      echo "$bracket[charge]";
    echo "</td>";

    echo "<td>";
      echo "<form method=post action=weight.php>";
        echo "<input type=hidden name='weightdel' value=$bracket[bracket]/>";
        echo "<input type=submit name='delete' value='Remove'/>";
      echo "</form>";
    echo "</td>";
  echo "</tr>";
      }
echo "</table>"; // end of table
    
echo "<br><br>";

echo "<h3> To Add A New Bracket Please Enter Bracket Below: </h3>";
// buttons for adding new charge and weight
echo "<form method=post action=weight.php>";
  echo "<input type=submit name='add' value='Add A New Entry'/>";
  echo "<input type=text name='addnum' placeholder='Weight To Be Added' required/>";
  echo "<input type=text name='addcharge' placeholder='Charge Added For Weight' required/>";
echo "</form>";
    
// return index
echo "<form method=post action=index.php>";
  echo "<input class=home type=submit name'gohome' value='Return Home'/>";
echo "</form>";    
?>
</html>
