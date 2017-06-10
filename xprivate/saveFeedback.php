<?php 

if( !isset($_POST['find']) || !isset($_POST['raty']) || !isset($_POST['opinion']) ) {
	exit;
}

$find = htmlspecialchars(strip_tags($_POST['find']), ENT_QUOTES);
$raty = htmlspecialchars(strip_tags($_POST['raty']), ENT_QUOTES);
mb_internal_encoding("UTF-8");
$opinion = mb_substr(htmlspecialchars(strip_tags($_POST['opinion']), ENT_QUOTES), 0, 550);
$date = date("d.m.y")." ".date("H:i");

$newAnketa = [
	"date" => $date,
	"find" => $find,
	"raty" => $raty,
	"opinion" => $opinion
];

// $backup = json_encode($newAnketa);
// $microtime = microtime(true)*10000;
// file_put_contents("./protected/feedback_backup/fb_$microtime.json", $backup);

$anketa = file_get_contents('./protected/feedback.json');
$anketa = json_decode($anketa);
if(gettype($anketa) == "array") {
	array_push($anketa, $newAnketa);
	$anketa = json_encode($anketa);
	file_put_contents('./protected/feedback.json', $anketa);
}
else {
	echo "ERROR: wrong type of feedback.json file: ".gettype($anketa);
}