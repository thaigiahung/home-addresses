<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	require_once('../libs/HomeAddressesHelper.php');

	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];  
	$address = $_POST['address'];
	$lat = $_POST['lat'];
	$lng = $_POST['lng'];

	if(!$firstName || !$lastName || !$address || !$lat || !$lng )
	{
		$data = array(
			"status" => 0, 
			"message" => "Missing params!"
		);
	}
	else
	{		
		$sql = "INSERT INTO `address`(`first_name`, `last_name`, `address`, `lat`, `lng`) 
						VALUES ('$firstName', '$lastName', '$address', $lat, $lng)";
        $result = HomeAddressesHelper::ExecuteQuery($sql);

        if(!$result)
        {
        	$data = array(
        		"status" => 0, 
        		"message" => "Can't add address!"
        	);
        }
        else
        {
        	$data = array(
        		"status" => 1, 
        		"message" => "Success!"
        	);
        }
	}
	echo json_encode($data);
?>