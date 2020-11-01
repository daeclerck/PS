<?php
  $username='z1877438';
  $password='1993Nov02';
  $username2='student';
  $password2='student';

  try{
    $dsn = "mysql:host=courses;dbname=z1877438";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
  catch(PDOexception $e) {
    echo "Connection to database failed: " . $e->getMessage();
  }

  try{
    $dsn2 = "mysql:host=blitz.cs.niu.edu;dbname=csci467";
    $pdo2 = new PDO($dsn2, $username2, $password2);
    $pdo2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
  catch(PDOexception $f) {
    echo "Connection to database failed: " . $f->getMessage();
  }
?>
