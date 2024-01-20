<?php
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Headers: *, Authorization');
	header('Access-Control-Allow-Methods: *');
	header('Access-Control-Allow-Credentials: true');

	// сюда приходят все запросы. И все будет обрабатываться здесь
	header('Content-Type: application/json; charset=utf-8');
    
	require 'pgConnect.php';

	// $method = $_SERVER['REQUEST_METHOD'];
	
	$q = $_GET['q'];
	$params = explode('/' , $q);
	
	$type = $params[0];
	
	$statusFlag = false;
	

    $querryType = $params[1];
    include_once './routers/' . $type . '.php';
    $statusFlag = route($db, $_GET, $querryType);

	
	if (!$statusFlag) {
		http_response_code(404);

		$res = [
			"status" => false,
			"message" => "Error addres"
		];
		
		echo json_encode($res);
	}
?>

