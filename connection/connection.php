<?php

$connection = new mysqli('localhost', 'root', '', 'asti2');

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}

?>
