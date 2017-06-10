<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'db.php';

$result = $con->query("SELECT * FROM `vesnasoft_participants`");
$emails = ["kboyko1999@gmail.com"];

if ($result->num_rows > 0)
    while($row = $result->fetch_assoc())
        if($row['guest'] == 'true' || $row['guest'] == '1')
            array_push($emails, $row['email']);

$subject = 'Чекаємо вас на Vesna-Soft 2017 вже в цю субботу';
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: vesna@programming.kr.ua". "\r\n";

$message = file_get_contents('emails/invitationGuest.html');
foreach($emails as $email){
    mail($email, $subject, $message, $headers);
    echo "<p> Sent to ".$email.'</p>';
}
//    mail("kboyko1999@gmail.com", $subject, $message, $headers);




