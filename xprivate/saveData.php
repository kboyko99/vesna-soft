<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'db.php';

if( !isset($_POST['fio']) || !isset($_POST['age']) || !isset($_POST['email']) || !isset($_POST['phone']) || !isset($_POST['hackathon']) || !isset($_POST['project']) || !isset($_POST['aboutProject']) ) {
	exit;
}

$fio = htmlspecialchars(strip_tags($_POST['fio']), ENT_QUOTES);
$age = htmlspecialchars(strip_tags($_POST['age']), ENT_QUOTES);
$email = htmlspecialchars(strip_tags($_POST['email']), ENT_QUOTES);
$phone = htmlspecialchars(strip_tags($_POST['phone']), ENT_QUOTES);
$city = htmlspecialchars(strip_tags($_POST['city']), ENT_QUOTES);
$hackathon = htmlspecialchars(strip_tags($_POST['hackathon']), ENT_QUOTES) == 'true';
$project = htmlspecialchars(strip_tags($_POST['project']), ENT_QUOTES) == 'true';
$exhibition = htmlspecialchars(strip_tags($_POST['exhibition']), ENT_QUOTES) == 'true';
$guest = htmlspecialchars(strip_tags($_POST['guest']), ENT_QUOTES) == 'true';
mb_internal_encoding("UTF-8");

$aboutProject = mb_substr(htmlspecialchars(strip_tags($_POST['aboutProject']), ENT_QUOTES), 0, 550);
$projectTitle = mb_substr(htmlspecialchars(strip_tags($_POST['projectTitle']), ENT_QUOTES), 0, 550);
$project_category = mb_substr(htmlspecialchars(strip_tags($_POST['project_category']), ENT_QUOTES), 0, 550);
$date = date("d.m.y")."<br>".date("H:i");
$memberOneExist = false;
$memberTwoExist = false;
$memberThreeExist = false;
$hackathon_key = htmlspecialchars(strip_tags($_POST['hackathon_key']), ENT_QUOTES);
$team_name = htmlspecialchars(strip_tags($_POST['teamName']), ENT_QUOTES);
$company = htmlspecialchars(strip_tags($_POST['company']), ENT_QUOTES);
$exhibition_product = mb_substr(htmlspecialchars(strip_tags($_POST['exhibition_product']), ENT_QUOTES), 0, 550);
$interactive_element = mb_substr(htmlspecialchars(strip_tags($_POST['interactive_element']), ENT_QUOTES), 0, 550);
$exhibition_needs = mb_substr(htmlspecialchars(strip_tags($_POST['exhibition_needs']), ENT_QUOTES), 0, 550);

$file_name = 'protected/backup_participants.json';
$inp = @file_get_contents($file_name);
$tempArray = json_decode($inp);
array_push($tempArray, json_encode($_POST));
$jsonData = json_encode($tempArray, JSON_PRETTY_PRINT);
file_put_contents($file_name, $jsonData);

if(isset($_POST['memberNameOne']) && $_POST['memberNameOne'] != ''){
  $memberOneExist = true;
  $memberNameOne = htmlspecialchars(strip_tags($_POST['memberNameOne']), ENT_QUOTES);
  $memberAgeOne = htmlspecialchars(strip_tags($_POST['memberAgeOne']), ENT_QUOTES);
  $memberEmailOne = htmlspecialchars(strip_tags($_POST['memberEmailOne']), ENT_QUOTES);
  $memberPhoneOne = htmlspecialchars(strip_tags($_POST['memberPhoneOne']), ENT_QUOTES);
}
if(isset($_POST['memberNameTwo'])  && $_POST['memberNameTwo'] != ''){
  $memberTwoExist = true;
  $memberNameTwo =  htmlspecialchars(strip_tags($_POST['memberNameTwo']), ENT_QUOTES);
  $memberAgeTwo =  htmlspecialchars(strip_tags($_POST['memberAgeTwo']), ENT_QUOTES);
  $memberEmailTwo =  htmlspecialchars(strip_tags($_POST['memberEmailTwo']), ENT_QUOTES);
  $memberPhoneTwo = htmlspecialchars(strip_tags($_POST['memberPhoneTwo']), ENT_QUOTES);
}
if(isset($_POST['memberNameThree'])  && $_POST['memberNameThree'] != ''){
  $memberThreeExist = true;
  $memberNameThree =  htmlspecialchars(strip_tags($_POST['memberNameThree']), ENT_QUOTES);
  $memberAgeThree =  htmlspecialchars(strip_tags($_POST['memberAgeThree']), ENT_QUOTES);
  $memberEmailThree =  htmlspecialchars(strip_tags($_POST['memberEmailThree']), ENT_QUOTES);
  $memberPhoneThree = htmlspecialchars(strip_tags($_POST['memberPhoneThree']), ENT_QUOTES);
}

if($memberOneExist && $hackathon){
  //add first member to db
  	$stmt = $con->prepare("INSERT INTO `vesnasoft_participants` (`name_surname` ,`age` ,`email`, `city`, `phone`, `hackathon_key`, `team_name`) VALUES (?, ?, ?, ?, ?, ?, ?);");
	$stmt->bind_param("sississ", $memberNameOne,  $memberAgeOne,  $memberEmailOne, $city, $memberPhoneOne, $hackathon_key, $team_name);
	$stmt->execute();
  if($memberTwoExist){
    //add second member to db
        $stmt = $con->prepare("INSERT INTO `vesnasoft_participants` (`name_surname` ,`age` ,`email`, `city`, `phone`, `hackathon_key`, `team_name`) VALUES (?, ?, ?, ?, ?, ?, ?);");
		$stmt->bind_param("sississ", $memberNameTwo,  $memberAgeTwo,  $memberEmailTwo, $city, $memberPhoneTwo, $hackathon_key, $team_name);
		$stmt->execute();
  }
  if($memberThreeExist){
    //add second member to db
		$stmt = $con->prepare("INSERT INTO `vesnasoft_participants` (`name_surname` ,`age` ,`email`, `city`, `phone`, `hackathon_key`, `team_name`)VALUES (?, ?, ?, ?, ?, ?, ?);");
		$stmt->bind_param("sississ", $memberNameThree,  $memberAgeThree,  $memberEmailThree, $city, $memberPhoneThree, $hackathon_key, $team_name);
		$stmt->execute();
  }
}
//add person that filled form
if(! $hackathon){
    if($exhibition){
        $stmt = $con->prepare("INSERT INTO `vesnasoft_participants` (`name_surname` ,`age` ,`email`, `city`, `phone` ,`company` ,`exhibition_product` ,`interactive_element`, `exhibition_needs`)VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);");
        $stmt->bind_param("sississss", $fio,  $age,  $email, $city, $phone, $company,  $exhibition_product, $interactive_element, $exhibition_needs);
    } else if($project){
        $stmt = $con->prepare("INSERT INTO `vesnasoft_participants` (`name_surname` ,`age` ,`email`, `city`, `phone` ,`project_title` ,`project_description`, `project_category`)VALUES (?, ?, ?, ?, ?, ?, ?, ?);");
        $stmt->bind_param("sississs", $fio,  $age,  $email, $city, $phone, $projectTitle,  $aboutProject, $project_category);
    } else if($guest){
             $stmt = $con->prepare("INSERT INTO `vesnasoft_participants` (`name_surname` ,`age` ,`email`, `city`, `phone` ,`guest` )VALUES (?, ?, ?, ?, ?, ?);");
             $stmt->bind_param("sissis", $fio,  $age, $email, $city, $phone, $guest);
         }
     }
else{
    if($project){
        $stmt = $con->prepare("INSERT INTO `vesnasoft_participants` (`name_surname` ,`age` ,`email`, `city` , `phone` ,`project_title` ,`project_description`, `project_category`, `hackathon_key`)VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);");
        $stmt->bind_param("sississss", $fio,  $age,  $email, $city, $phone, $projectTitle,  $aboutProject, $project_category, $hackathon_key);
    }else{
        $stmt = $con->prepare("INSERT INTO `vesnasoft_participants` (`name_surname` ,`age` ,`email`, `city` , `phone` ,`hackathon_key`, `team_name` )VALUES (?, ?, ?, ?, ?, ?, ?);");
        $stmt->bind_param("sississ", $fio,  $age,  $email, $city, $phone, $hackathon_key, $team_name);
    }
}
$stmt->execute();

$to = 'vesna@programming.kr.ua';
$subject = 'Новый учасник';

$message = "";
$message.= "<b>Новий учасник:&nbsp;</b>".$fio."<br />";

if($hackathon){
  $message.= "<b>Хоче на Хакатон</b><br/>";
  $message.= "<b>Команда: &nbsp;</b>".$team_name."<br />";
}
$message.= "<b>Вік:&nbsp;</b>".$age."<br />";
$message.= "<b>Email:&nbsp;</b>".$email."<br />";
$message.= "<b>Телефон:&nbsp;</b>".$phone."<br />";
$message.= "<b>Місто:&nbsp;</b>".$city."<br />";
if($project){
  $message.= "<b>Має проект:&nbsp;</b>".$projectTitle."<br />";
  $message.= "<b>Про проект:&nbsp;</b>".$aboutProject."<br />";
}
if($memberOneExist && $hackathon){
  $message.= "<b>Також зареєстрував:&nbsp;</b>".$memberNameOne. '. Вік - ' . $memberAgeOne . '. email - ' . $memberEmailOne ."<br />";
}
if($memberTwoExist && $hackathon){
  $message.= "<b>Також зареєстрував:&nbsp;</b>".$memberNameTwo. '. Вік - ' . $memberAgeTwo . '. email - ' . $memberEmailTwo ."<br />";
}
if($memberThreeExist && $hackathon){
  $message.= "<b>Також зареєстрував:&nbsp;</b>".$memberNameThree. '. Вік - ' . $memberAgeThree . '. email - ' . $memberEmailThree ."<br />";
}
if($exhibition){
    $message.="<b>Хоче взяти участь у виставці</b><br>";
    $message.="<b>Представляє компанію: &nbsp;".$company."</b><br>";
    $message.="<b>Показує отакий продукт: &nbsp;".$exhibition_product."</b><br>";
    if($interactive_element != '')
        $message.="<b>З інтерактивним елементом: &nbsp;".$interactive_element."</b><br>";
    if($exhibition_needs != '')
        $message.="<b>З нашого боку просить: &nbsp;".$exhibition_exhibition_needs."</b><br>";
}
if($guest){
    $message.="<b>Гість.</b>";
}

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: vesna@programming.kr.ua". "\r\n";
$headers .= 'Cc: ' .' <firstyuriy+vesnasoft@gmail.com>';
mail($to, $subject, $message, $headers);

//letters to person that filled form
$subject = 'Дякуємо за заповнення анкети';

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: vesna@programming.kr.ua". "\r\n";
// $headers .= 'Cc: ' .' <firstyuriy@gmail.com>';
if($hackathon){
    if($project){
        if($memberOneExist){
            $message = file_get_contents('emails/ProjectAndHackathonWithTeam.html');
            $message = str_replace("{{first_name}}", $fio, $message);
            $message = str_replace("{{second_name}}", ', '.$memberNameOne, $message);
            if($memberTwoExist){
                $message = str_replace("{{third_email}}", ', '.$memberNameTwo, $message);
                if($memberThreeExist){
                    $message = str_replace("{{fourth_email}}", ', '.$memberNameThree, $message);
                     mail($memberEmailThree, $subject, $message, $headers);
                }else
                    $message = str_replace("{{fourth_email}}", '', $message);
                mail($memberEmailTwo, $subject, $message, $headers);
            } else{
                $message = str_replace("{{third_email}}", '', $message);
                $message = str_replace("{{fourth_email}}", '', $message);
            }
            mail($email, $subject, $message, $headers);
            mail($memberEmailOne, $subject, $message, $headers);
        }else{
            $message = file_get_contents('emails/ProjectAndHackathonNoTeam.html');
            mail($email, $subject, $message, $headers);
        }
    }else{
        if($memberOneExist){
            $message = file_get_contents('emails/onlyHackathonWithTeam.html');
            $message = str_replace("{{first_name}}", $fio, $message);
            $message = str_replace("{{second_name}}", ', '.$memberNameOne, $message);
            if($memberTwoExist){
                $message = str_replace("{{third_email}}", ', '.$memberNameTwo, $message);
                if($memberThreeExist){
                    $message = str_replace("{{fourth_email}}", ', '.$memberNameThree, $message);
                    mail($memberEmailThree, $subject, $message, $headers);
                }
                else {
                    $message = str_replace("{{third_email}}", '', $message);
                    $message = str_replace("{{fourth_email}}", '', $message);
                }
                mail($memberEmailTwo, $subject, $message, $headers);
            }
            mail($email, $subject, $message, $headers);
            mail($memberEmailOne, $subject, $message, $headers);

        }else{
            $message = file_get_contents('emails/onlyHackathonNoTeam.html');
            mail($email, $subject, $message, $headers);
        }
    }
}else{
    if($project)
        $message = file_get_contents('emails/onlyProject.html');

    if($exhibition)
        $message = file_get_contents('emails/exhibition.html');

    if($guest)
        $message = file_get_contents('emails/guest.html');

    mail($email, $subject, $message, $headers);
}

