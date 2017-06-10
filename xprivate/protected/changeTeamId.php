<?php
$participantId = $_POST["participantId"];
$newTeamNumber = $_POST["newTeamNumber"];

include '../db.php';

$query = "UPDATE `vesnasoft_participants` SET `team_id` = '$newTeamNumber' WHERE `vesnasoft_participants`.`id` =$participantId";
echo mysqli_query($con, $query);
?>
