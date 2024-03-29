<!-- Needs Revisions -->
<html><head><title>Orders
</title></head>
<h1> Orders Page: </h1>
<?php include "connection.php"; include "style.php";

// handle a search for date
if (array_key_exists('searchdate', $_REQUEST)) {
  $lowval = date('Y-m-d', strtotime($_REQUEST['lowerdate']));   // include the dates
  $highval = date('Y-m-d', strtotime($_REQUEST['higherdate'])); // include the dates

  $statement = "SELECT ordersID, custID, finalprice, date, status FROM orders
                WHERE date BETWEEN \"$lowval\" AND \"$highval\"";  
  $_POST['sql'] = $statement;
}

// handle search for status
else if (array_key_exists('searchstatus', $_REQUEST)) {
  $statusval = $_REQUEST['status'];
  $statement = "SELECT ordersID, custID, finalprice, date, status FROM orders
                WHERE status = \"$statusval\"";
  $_POST['sql'] = $statement;
}  
    
// search for price
else if (array_key_exists('searchprice', $_REQUEST)) {
  $lownum = round(floatval($_REQUEST['lowerprice']), 2);
  $highnum = round(floatval($_REQUEST['higherprice']), 2);

  $statement = "SELECT ordersID, custID, finalprice, date, status FROM orders
                WHERE finalprice BETWEEN $lownum AND $highnum";
  $_POST['sql'] = $statement;
}    
    
// checking if the array exists already
if (!array_key_exists('viewall', $_REQUEST) && !array_key_exists('search', $_REQUEST)) {
  echo "<form method=post action=orders.php>";
    echo "<input type=submit name='viewall' value='View All Orders'/>"; // view the values
  echo "</form>";

  echo "<form method=post action=admin.php>";
    echo "<input type=submit name='goweights' value='Adjust Charges'/>"; // view the charges
  echo "</form>";
}
    
else {
  echo "<form method=post action=orders.php>";
    echo "<input type=submit name='closeall' value='Close Orders'/>"; // close the orders
  echo "</form>";

  // call the orders.php
  echo "<form method=post action=orders.php>";
    echo "<input type=submit name='searchdate' value='Search Dates Between'/>"; 
    echo "<input type=text name='lowerdate' placeholder='Lower Bound' required/>"; 
    echo "<input type=text name='higherdate' placeholder='Upper Bound' required/>";
    echo "<input type=hidden name='search' value='D'/>";
  echo "</form>";

  // code required for the search status
  echo "<form method=post action=orders.php>";
    echo "<input type=submit name='searchstatus' value='Search by Status of      '/>";
    echo "<input type=text name='status' placeholder='Status' required/>";
    echo "<input type=hidden name='search' value='S'/>";
  echo "</form>";

  // code required for the searching of the price
  echo "<form method=post action=orders.php>";
    echo "<input type=submit name='searchprice' value='Search Prices Between'/>";
    echo "<input type=text name='lowerprice' placeholder='Lower Bound' required/>";
    echo "<input type=text name='higherprice' placeholder='Upper Bound' required/>";
    echo "<input type=hidden name='search' value='P'/>";
  echo "</form>";
    
  // the sql statement to gather the appropriate information
  $sql1 = "SELECT ordersID, custID, finalprice, date, status FROM orders";
      
  if(array_key_exists('sql', $_POST) && array_key_exists('sql', $_POST)) {
    $sql1 = $_POST['sql'];
  }

  // throw error message if there is a problem
  if(array_key_exists('sql', $_REQUEST)) {
    $sql1 = "";
    $asarray = unserialize(base64_decode($_REQUEST['sql']));

    foreach($asarray as $word) {
      $sql1 = ($sql1 . $word . " ");
    }

    $sql1 = substr($sql1, 0, -1);
  }

  // call the query
  $query1 = $pdo->query($sql1);
  $allorders = $query1->fetchAll(PDO::FETCH_ASSOC);

  $toarray = explode(" ", $sql1);
    
  // creating design for the page
  echo "<table border=5,cellspacing=5,cellpadding=5>";
  // talbe settings for the page
  echo "<tr>";
    echo "<th>Order ID</th>";
    echo "<th>Customer ID</th>";
    echo "<th>Price</th>";
    echo "<th>Date</th>";
    echo "<th>Status</th>";
    echo "<th> More </th>";
  echo "</tr>";

  foreach($allorders as $order) {
    echo "<tr>";
      echo "<td>";
        echo "$order[ordersID]"; 
      echo "</td>";

      echo "<td>";
        echo "$order[custID]";
      echo "</td>";

      echo "<td>";
        echo "$$order[finalprice]"; 
      echo "</td>";

      echo "<td>";
        echo "$order[date]"; 
      echo "</td>";

      echo "<td>";
        echo "$order[status]"; 
      echo "</td>";

      echo "<td>";
        echo "<form method=post action=orderdetails.php>";
          echo "<input type=hidden name='oid' value=$order[ordersID]/>";
          $order_array= base64_encode(serialize($toarray));
          echo "<input type=hidden name='sql' value=$order_array/>";
          echo "<input type=submit name=$order[ordersID] value='See Details'/>";
        echo "</form>";
      echo "</td>";
    echo "</tr>";
  }
  echo "</table>";
}       

// return to index
echo "<form method=post action=index.php>";
  echo "<input class=home type=submit name'gohome' value='Return Home'/>";
echo "</form>";
?>    
</html>
