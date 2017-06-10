<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'db.php';
// $email = $_GET['email'];


// mail($email, $subject, $message, $headers);

// exit;
if( !isset($_POST['fio']) || !isset($_POST['age']) || !isset($_POST['email']) || !isset($_POST['phone']) || !isset($_POST['state'])) {
	// echo $_POST['fio'] . ' ' . $_POST['age'] .' ' . $_POST['email'] . ' ' . $_POST['phone'] . ' ' . $_POST['state'];
	exit;
}

$fio = htmlspecialchars(strip_tags($_POST['fio']), ENT_QUOTES);
$age = htmlspecialchars(strip_tags($_POST['age']), ENT_QUOTES);
$email = htmlspecialchars(strip_tags($_POST['email']), ENT_QUOTES);
$phone = htmlspecialchars(strip_tags($_POST['phone']), ENT_QUOTES);
$state = htmlspecialchars(strip_tags($_POST['state']), ENT_QUOTES);
$hakaton = htmlspecialchars(strip_tags($_POST['hakaton']), ENT_QUOTES);
$project = htmlspecialchars(strip_tags($_POST['project']), ENT_QUOTES);
mb_internal_encoding("UTF-8");

$teamId = -1;
$memberOneExist = false;
$memberTwoExist = false;

if(isset($_POST['memberNameOne']) && $_POST['memberNameOne'] != ''){
  $memberOneExist = true;
  $memberNameOne =  htmlspecialchars(strip_tags($_POST['memberNameOne']), ENT_QUOTES);
  $memberAgeOne =  htmlspecialchars(strip_tags($_POST['memberAgeOne']), ENT_QUOTES);
  $memberEmailOne =  htmlspecialchars(strip_tags($_POST['memberEmailOne']), ENT_QUOTES);
}

if(isset($_POST['memberNameTwo']) && $_POST['memberNameTwo'] != ''){
  $memberTwoExist = true;
  $memberNameTwo =  htmlspecialchars(strip_tags($_POST['memberNameTwo']), ENT_QUOTES);
  $memberAgeTwo =  htmlspecialchars(strip_tags($_POST['memberAgeTwo']), ENT_QUOTES);
  $memberEmailTwo =  htmlspecialchars(strip_tags($_POST['memberEmailTwo']), ENT_QUOTES);
}

$subject = 'Дякуємо за заповнення анкети';

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: vesna@programming.kr.ua". "\r\n";
// $headers .= 'Cc: ' .' <firstyuriy@gmail.com>';

switch ($state) {
	case 'alreadyHackathon':
		if( !isset($_POST['projectTitle']) || !isset($_POST['aboutProject']) )
			exit;

		$projectTitle =  htmlspecialchars(strip_tags($_POST['projectTitle']), ENT_QUOTES);
		$aboutProject =  htmlspecialchars(strip_tags($_POST['aboutProject']), ENT_QUOTES);

		$query = "UPDATE `vesnasoft_participants` SET  `project_title` =  '$projectTitle', `project_description` =  '$aboutProject' WHERE  `vesnasoft_participants`.`email` = '$email';";
		mysqli_query($con, $query);
		$participant = $con->query("SELECT * FROM `vesnasoft_participants` WHERE `email` = '$email'")->fetch_array(MYSQLI_ASSOC);

		if($participant['team_id'] == 0){
			$message = file_get_contents('emails/ProjectAndHackathonNoTeam.html');
			mail($email, $subject, $message, $headers);

		}else{
			$teammatesNames =  $con->query("SELECT `name_surname` FROM  `vesnasoft_participants` WHERE  `email` !=  '$email' AND  `team_id` = $participant[team_id] LIMIT 0 , 30");
			$stack = array();
			$i = 0;

			while ($row = mysqli_fetch_assoc($teammatesNames)) {
				$stack[$i] = $row['name_surname'];
				$i++;
			}
			$memberNameOne =  $stack[0];
			$memberNameTwo =  $stack[1];

			$message = file_get_contents('emails/ProjectAndHackathonWithTeam.html');
			$message = str_replace("{{first_name}}", $fio, $message);
			$message = str_replace("{{second_name}}", ', '.$memberNameOne, $message);
			if($memberTwoExist){
				$message = str_replace("{{third_email}}", ', '.$memberNameTwo, $message);
				mail($memberEmailTwo, $subject, $message, $headers);
			}else{
				$message = str_replace("{{third_email}}", '', $message);
			}
			mail($email, $subject, $message, $headers);
			mail($memberEmailOne, $subject, $message, $headers);
		}
		break;

	case 'alreadyProject':
		if($memberOneExist){
			//finding new team id
		  $maxTeamIdQuery = "SELECT MAX(`team_id`) AS LastTeamId FROM  `vesnasoft_participants`";
		  $maxIdResult = mysqli_query($con, $maxTeamIdQuery);
		  $row = mysqli_fetch_array( $maxIdResult );
		  $teamId = $row['LastTeamId'] + 1;

			//add first member to db
		  $addMemberQuery = "INSERT INTO `vesnasoft_participants` (`name_surname` ,`age` ,`email` ,`team_id`)VALUES ('$memberNameOne',  '$memberAgeOne',  '$memberEmailOne', '$teamId');";
		  mysqli_query($con, $addMemberQuery);

			$message = file_get_contents('emails/onlyHackathonWithTeam.html');
			$message = str_replace("{{first_name}}", $fio, $message);
			$message = str_replace("{{second_name}}", ', '.$memberNameOne, $message);
			if($memberTwoExist){
				//add second member to db if exist
				$message = str_replace("{{third_email}}", ', '.$memberNameTwo, $message);
				$addMemberQuery = "INSERT INTO `vesnasoft_participants` (`name_surname` ,`age` ,`email` ,`team_id`)VALUES ('$memberNameTwo',  '$memberAgeTwo',  '$memberEmailTwo', '$teamId');";
				mysqli_query($con, $addMemberQuery);
				mail($memberEmailTwo, $subject, $message, $headers);
			}else{
				$message = str_replace("{{third_email}}", '', $message);
			}
			mail($memberEmailOne, $subject, $message, $headers);

			//update participant data
			$query = "UPDATE `vesnasoft_participants` SET  `team_id` =  $teamId  WHERE  `vesnasoft_participants`.`email` = '$email'";
			mysqli_query($con, $query);

			$message = file_get_contents('emails/ProjectAndHackathonWithTeam.html');
			$message = str_replace("{{first_name}}", $fio, $message);
			$message = str_replace("{{second_name}}", ', '.$memberNameOne, $message);
			if($memberTwoExist){
				$message = str_replace("{{third_email}}", ', '.$memberNameTwo, $message);
			}else{
				$message = str_replace("{{third_email}}", '', $message);
			}
			mail($email, $subject, $message, $headers);
		}else{
			//participant go to hackathon with project and without team
			$query = "UPDATE `vesnasoft_participants` SET  `team_id` =  0  WHERE  `vesnasoft_participants`.`email` = '$email'";
			mysqli_query($con, $query);
			$message = file_get_contents('emails/ProjectAndHackathonNoTeam.html');
			mail($email, $subject, $message, $headers);
		}
		break;

	default:
		break;
}

$to      = 'dmytro.bohdanov@gmail.com';
$subject = 'Онoвлена інформація учасник';

$message = "";
$message.= "<b>Ім’я:&nbsp;</b>".$fio."<br />";
// if($hakaton){
  $message.= "<b>Хоче на Хакатон</b><br/>";
  $message.= "<b>Команда: &nbsp;</b>".$teamId."<br />";
// }
$message.= "<b>Вік:&nbsp;</b>".$age."<br />";
$message.= "<b>Email:&nbsp;</b>".$email."<br />";
$message.= "<b>Телефон:&nbsp;</b>".$phone."<br />";
$message.= "<b>Має проект:&nbsp;</b>".$projectTitle."<br />";
$message.= "<b>Про проект:&nbsp;</b>".$aboutProject."<br />";
if($memberOneExist){
  $message.= "<b>В його команді:&nbsp;</b>".$memberNameOne. '. Вік - ' . $memberAgeOne . '. email - ' . $memberEmailOne ."<br />";
}
if($memberTwoExist){
  $message.= "<b>В його команді:&nbsp;</b>".$memberNameTwo. '. Вік - ' . $memberAgeTwo . '. email - ' . $memberEmailTwo ."<br />";
}

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: vesna@programming.kr.ua". "\r\n";
$headers .= 'Cc: ' .' <firstyuriy@gmail.com>';

mail($to, $subject, $message, $headers);


 ?>
