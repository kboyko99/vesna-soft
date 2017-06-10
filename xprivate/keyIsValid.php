<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['hackathon_key'])) {
  $hackathon_key = $_GET['hackathon_key'];
  include 'db.php';
  $keys = mysqli_num_rows($con->query("SELECT id FROM `vesnasoft_participants` WHERE `hackathon_key` = '$hackathon_key'"));
  echo $keys;
}

?>
