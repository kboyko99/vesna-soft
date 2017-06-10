<?php 

if( !isset($_POST['fio']) || !isset($_POST['email']) || !isset($_POST['about']) ) {
	exit;
}

$fio = htmlspecialchars(strip_tags($_POST['fio']), ENT_QUOTES);
$email = htmlspecialchars(strip_tags($_POST['email']), ENT_QUOTES);
mb_internal_encoding("UTF-8");
$about = mb_substr(htmlspecialchars(strip_tags($_POST['about']), ENT_QUOTES), 0, 550);
$date = date("d.m.y")." ".date("H:i");

$newAnketa = [
	"date" => $date,
	"fio" => $fio,
	"about" => $about,
	"email" => $email
];

$backup = json_encode($newAnketa);
$microtime = microtime(true)*10000;
file_put_contents("./protected/guest_want_backup/guest_$microtime.json", $backup);

$anketa = file_get_contents('./protected/guest_want.json');
$anketa = json_decode($anketa);
if(gettype($anketa) == "array") {
	array_push($anketa, $newAnketa);
	$anketa = json_encode($anketa);
	file_put_contents('./protected/guest_want.json', $anketa);
}
else {
	echo "ERROR: wrong type of guest_want.json file: ".gettype($anketa);
}

// ====================================================
/*
$mailBodyForUser = "
	Доброго дня!
	<br><br>

	Дякуємо за реєстрацію на Vesna-Soft 2015!<br>
	Чекаємо на тебе 23 травня 2015 року о 17:00 за адресою пров. Василівський, 10, 5й поверх.
	<br><br>

	З повагою,<br>
	Команда Vesna-Soft 2015<br>
	----------------------------------<br>
	<a href='http://vesnasoft.org'>vesnasoft.org</a> | <a href='https://vk.com/vesna_soft'>vk</a>
";

require_once 'lib/swift_required.php';

// Create the mail transport configuration
$transportForUser = Swift_MailTransport::newInstance();

// Create the message
$messageForUser = Swift_Message::newInstance();
$messageForUser->setTo(array(
  $email => "Registration"
));
$messageForUser->setContentType("text/html");
$messageForUser->setSubject("Дякуємо за реєстрацію на Vesna-Soft 2015!");
$messageForUser->setBody($mailBodyForUser);
$messageForUser->setFrom("vesna@programming.kr.ua", "Vesna-Soft");
 
// Send the email
$mailer = Swift_Mailer::newInstance($transportForUser);
$mailer->send($messageForUser);
*/
