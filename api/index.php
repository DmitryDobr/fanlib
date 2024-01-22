<?php
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Headers: *, Authorization');
	header('Access-Control-Allow-Methods: *');
	header('Access-Control-Allow-Credentials: true');

	// сюда приходят все запросы. И все будет обрабатываться здесь
	header('Content-Type: application/json; charset=utf-8');
    
	require 'pgConnect.php';

    // Получение данных из тела запроса
    function getFormData($method) {
        // GET или POST: данные возвращаем как есть
        if ($method === 'GET') return $_GET;
        if ($method === 'POST') {
            if (count($_POST) != 0)
                return $_POST;
            else
                return $_GET;
        }

        // PUT, PATCH или DELETE
        $data = array();
        $exploded = explode('&', file_get_contents('php://input'));

        foreach($exploded as $pair) {
            $item = explode('=', $pair);
            if (count($item) == 2) {
                $data[urldecode($item[0])] = urldecode($item[1]);
            }
        }

        return $data;
    }

	$method = $_SERVER['REQUEST_METHOD'];
    
    // Получаем данные из тела запроса
    $formData = getFormData($method);
	
	$q = $_GET['q'];
	$params = explode('/' , $q);
	
	$type = $params[0];
	$querryType = $params[1];

	$statusFlag = false;
	
    include_once './routers/' . $type . '.php';
    $statusFlag = route($db, $formData, $querryType);

	
	if (!$statusFlag) {
		http_response_code(404);

		$res = [
			"status" => false,
			"message" => "Error addres"
		];
		
		echo json_encode($res);
	}
?>

