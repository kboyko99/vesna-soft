<?php 

if( !isset($_POST['fio']) || !isset($_POST['age']) || !isset($_POST['city']) || !isset($_POST['school']) || !isset($_POST['email']) || !isset($_POST['phone']) || !isset($_POST['hakaton']) || !isset($_POST['project']) || !isset($_POST['aboutProject']) ) {
	exit;
}

$fio = htmlspecialchars(strip_tags($_POST['fio']), ENT_QUOTES);
$age = htmlspecialchars(strip_tags($_POST['age']), ENT_QUOTES);
$city = htmlspecialchars(strip_tags($_POST['city']), ENT_QUOTES);
$school = htmlspecialchars(strip_tags($_POST['school']), ENT_QUOTES);
$email = htmlspecialchars(strip_tags($_POST['email']), ENT_QUOTES);
$phone = htmlspecialchars(strip_tags($_POST['phone']), ENT_QUOTES);
$hakaton = htmlspecialchars(strip_tags($_POST['hakaton']), ENT_QUOTES);
$project = htmlspecialchars(strip_tags($_POST['project']), ENT_QUOTES);
mb_internal_encoding("UTF-8");
$aboutProject = mb_substr(htmlspecialchars(strip_tags($_POST['aboutProject']), ENT_QUOTES), 0, 550);
$date = date("d.m.y")."<br>".date("H:i");

$newAnketa = [
	"date" => $date,
	"fio" => $fio,
	"age" => $age,
	"city" => $city,
	"school" => $school,
	"email" => $email,
	"phone" => $phone,
	"hakaton" => $hakaton,
	"project" => $project,
	"aboutProject" => $aboutProject
];

$backup = json_encode($newAnketa);
$microtime = microtime(true)*10000;
file_put_contents("./protected/anketa_backup/anketa_$microtime.json", $backup);

$anketa = file_get_contents('./protected/anketa.json');
$anketa = json_decode($anketa);
if(gettype($anketa) == "array") {
	array_push($anketa, $newAnketa);
	$anketa = json_encode($anketa);
	file_put_contents('./protected/anketa.json', $anketa);
}
else {
	echo "ERROR: wrong type of anketa.json file: ".gettype($anketa);
}

// ====================================================

$mailBodyForAdmin = "";
$mailBodyForAdmin.= "<b>Новий учасник:&nbsp;</b>".$fio."<br />";
$mailBodyForAdmin.= "<b>Вік:&nbsp;</b>".$age."<br />";
$mailBodyForAdmin.= "<b>Місто:&nbsp;</b>".$city."<br />";
$mailBodyForAdmin.= "<b>Навчальний заклад:&nbsp;</b>".$school."<br />";
$mailBodyForAdmin.= "<b>Email:&nbsp;</b>".$email."<br />";
$mailBodyForAdmin.= "<b>Телефон:&nbsp;</b>".$phone."<br />";
$mailBodyForAdmin.= "<b>Участь у хакатоні:&nbsp;</b>".$hakaton."<br />";
$mailBodyForAdmin.= "<b>Має проект:&nbsp;</b>".$project."<br />";
$mailBodyForAdmin.= "<b>Про проект:&nbsp;</b>".$aboutProject."<br />";

$mailBodyForAdmin.= "<br /><a href='vesnasoft.org/xprivate/protected/anketa.html'>Переглянути всіх учасників</a> (пароль для доступа у Артема)";

require_once 'lib/swift_required.php';
 
// Create the mail transport configuration
$transport = Swift_MailTransport::newInstance();
 
// Create the message
$messageForAdmin = Swift_Message::newInstance();
$messageForAdmin->setTo(array(
  // "smirnovanastya1602@gmail.com" => "Nastya",
  // "vesna@programming.kr.ua"=>"vesnasoft",
  "dmytro.bohdanov@gmail.com" => "Dmytro",
  "oliinyk.artem@gmail.com" => "Artem"
));
$messageForAdmin->setContentType("text/html");
$messageForAdmin->setSubject("Vesna-Soft new participant");
$messageForAdmin->setBody($mailBodyForAdmin);
$messageForAdmin->setFrom("vesna@programming.kr.ua", "Vesna-Soft");
 
// Send the email
$mailer = Swift_Mailer::newInstance($transport);
$mailer->send($messageForAdmin);

// ====================================================

$mailForUser = [
	"hakaton" => "
		Доброго дня!
		<br><br>

		Дякуємо за реєстрацію на Vesna-Soft 2015!<br>
		Чекаємо на тебе 23 травня 2015 року о 10:00 за адресою пров. Василівський, 10, 5й поверх.
		<br><br>

		З повагою,<br>
		Команда Vesna-Soft 2015<br>
		------------------------------<br>
		<a href='http://vesnasoft.org'>vesnasoft.org</a> | <a href='https://vk.com/vesna_soft'>vk</a>
	",
	"project" => "
		Доброго дня! 
		<br><br>

		Дякуємо за реєстрацію на Vesna-Soft 2015!<br>
		Ми зв’яжемось з тобою найближчим часом аби узгодити всі деталі презентації твого проекту.
		<br><br>

		З повагою,<br>
		Команда Vesna-Soft 2015<br>
		-------------------------------<br>
		<a href='http://vesnasoft.org'>vesnasoft.org</a> | <a href='https://vk.com/vesna_soft'>vk</a>
	",
	"hak_proj" =>"
		Доброго дня!
		<br><br>

		Дякуємо за реєстрацію на Vesna-Soft 2015!<br>
		Чекаємо на тебе 23 травня 2015 року о 10:00 за адресою пров. Василівський, 10, 5й поверх.
		<br><br>

		Найближчим часом ми зв’яжемось з тобою аби узгодити всі деталі презентації твого проекту.
		<br><br>

		З повагою,<br>
		Команда Vesna-Soft 2015<br>
		-------------------------------<br>
		<a href='http://vesnasoft.org'>vesnasoft.org</a> | <a href='https://vk.com/vesna_soft'>vk</a>
	"
];

if($hakaton == "true" && $project == "true") {
	$mailBodyForUser = $mailForUser['hak_proj'];
}
else if($hakaton == "true") {
	$mailBodyForUser = $mailForUser['hakaton'];
}
else if($project == "true") {
	$mailBodyForUser = $mailForUser['project'];
}
else {
	exit;
}
 
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

