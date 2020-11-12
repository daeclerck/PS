<html><body>

<h1>Enter Admin Username and Password</h1>

<?php
include "connection.php"; include "style.php";

if(isset($_POST['submit']) && $_POST['user'] == 'admin' && $_POST['pwd'] == 'Z1877438') {
  header('Location: weight.php'); // Take to weight.php if username and password are entered correctly
}

echo "<form method=post action=admin.php>";
  echo "<label for='user'>Username:</label>";
  echo "<input type=text name='user'/><br><br>";
  echo "<label for='pwd'>Password:</label>";
  echo "<input type=password name='pwd' minlength=8/><br><br>";
  echo "<input type=submit name='submit' value='Sign in'/>";
echo "</form>";

// return index
echo "<form method=post action=index.php>";
  echo "<input class=home type=submit name'gohome' value='Return Home'/>";
echo "</form>";
?>

</body>
</html>
