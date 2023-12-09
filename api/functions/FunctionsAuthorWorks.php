<?php

// Получить 5 последних работ автора
function GetAuthorWorksLast($db, $UserID){
    $querry = 'SELECT work_id FROM "public"."WORK" WHERE user_id = '.$UserID.'ORDER BY work_id DESC LIMIT 5';
    $result = pg_query($db, $querry);

    // echo pg_num_rows($result);

    if (pg_num_rows($result) > 0)
    {
        $result_list = [];

        while ($answer = pg_fetch_assoc($result)) {
            $result_list[] = $answer;
        }
	
		echo json_encode($result_list);
    }
    else {
		$res = [
			"message" => "No post"
		];
		
		echo json_encode($res);
    }
}

// проверить "Принадлежит" ли работа данному автору
function CheckWorkAuthor($db, $UserID, $WorkId) {
    $querry = 'SELECT about, "WorkName", remark FROM "public"."WORK" WHERE user_id = '.$UserID.' AND work_id = '.$WorkId.'';
    $result = pg_query($db, $querry);

    if (pg_num_rows($result) > 0) {
        return true;
    }
    else {
        return false;
    }
}

// то же самое, но для возвращения ответа на клиент
function WorkAuthorExist($db, $UserID, $WorkId) {


    $querry = 'SELECT about, "WorkName", remark FROM "public"."WORK" WHERE user_id = '.$UserID.' AND work_id = '.$WorkId.'';
    $result = pg_query($db, $querry);

    if (pg_num_rows($result) > 0) {
        $result_list = [];

        while ($answer = pg_fetch_assoc($result)) {
            $result_list[] = $answer;
        }
	
		echo json_encode($result_list);
    }
    else {
        http_response_code(403);

        $res = [
			"status" => false,
			"message" => "Cant get user to edit"
		];
		
		echo json_encode($res);
    }
}



?>