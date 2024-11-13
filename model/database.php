<?php
$servername = "";
$username = "";
$password = "";
$database = "P3Database";

// Create connection
$db = new mysqli($servername, $username, $password, $database);

// Check connection
if ($db->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

function display_db_error($error_message) {
  echo $error_message;
  exit;
}
?>