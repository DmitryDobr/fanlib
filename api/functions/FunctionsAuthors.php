<?php

// получение новозарегистрированных авторов
function GetNewAuthors($db){
    $result = pg_query($db, 'SELECT user_id, nickname FROM "public"."USER" ORDER BY user_id DESC' );
	
    $result_list = [];

    while ($answer = pg_fetch_assoc($result)) {
		$result_list[] = $answer;
	}
	
	echo json_encode($result_list);
}

// получение информации об одном авторе
function GetOneAuthor($db, $UserID, $withDate){
    // $result;

    if (!$withDate)
        $result = pg_query($db, 'SELECT user_id, nickname, about FROM "public"."USER" WHERE "user_id" = '.$UserID.'');
    else
        $result = pg_query($db, 'SELECT user_id, nickname, about, birth FROM "public"."USER" WHERE "user_id" = '.$UserID.'');

    if (pg_num_rows($result) > 0)
    {
        $result = pg_fetch_assoc($result);
	
		echo json_encode($result);
    }
    else {
        http_response_code(404);
		
		$res = [
			"status" => false,
			"message" => "Author not found"
		];
		
		echo json_encode($res);
    }
}



?>