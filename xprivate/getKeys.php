<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
	$data = json_decode(file_get_contents("keys.json"), true);
	$res = array();
    include 'db.php';
    foreach( $data['keys'] as $key){
//        $appears = mysqli_num_rows($con->query("SELECT id FROM `vesnasoft_participants` WHERE `hackathon_key` = '$key'"));
//         if($appears < 3)
            array_push($res, $key);
    }
    shuffle($res);

	echo json_encode($res);