<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_REQUEST['email'])) {
  $email = $_REQUEST['email'];
  include 'db.php';
  $participant = $con->query("SELECT * FROM `vesnasoft_participants` WHERE `email` = '$email'")->fetch_array(MYSQLI_ASSOC);
  echo json_encode($participant);
}

?>
