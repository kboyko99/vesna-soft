<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    include 'db.php';
    	
    $result = $con->query("SELECT * FROM `vesnasoft_participants`");
    $response = [];
    if ($result->num_rows > 0)
        while($row = $result->fetch_assoc())
	       array_push($response, $row);
        	                               
    echo json_encode($response);
