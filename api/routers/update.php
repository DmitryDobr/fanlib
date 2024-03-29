<?php

// проверить "Принадлежит" ли работа данному автору
function n_CheckWorkAuthor($db, $UserID, $WorkId) {

    $querry = 'SELECT about, "WorkName", remark FROM "public"."WORK" WHERE user_id = '.$UserID.' AND work_id = '.$WorkId.'';
    $result = pg_query($db, $querry);

    if (pg_num_rows($result) > 0) {
        return true;
    }
    else {
        return false;
    }
}


function n_UpdateWork($db, $params) {

    if (n_CheckWorkAuthor($db, $params['user_id'], $params['work_id'])) {
        // UPDATE разрешен
        // date_default_timezone_set('UTC');
        // $today = date('j-m-y');

        $query = 'UPDATE "public"."WORK" SET "about" = $1, "remark" = $2, "WorkName" = $3, "update_time" = $4 WHERE work_id = '.$params['work_id'].'';
        $result = pg_query_params($db, $query, array($params['about'], $params['remark'], $params['name'], date('j-m-y')));

        $state = pg_result_error($result);  //  отлов ошибок выполнения запроса

        if (empty($state)) {
            $result_list = ["status" => true,
                            "message" => "Update complete"];
            echo json_encode($result_list);
        }
        else {
            $result_list = ["status" => false,
                            "message" => $state];
            echo json_encode($result_list);
        }
    }
    else {
        // UPDATE НЕ разрешен
        http_response_code(403);

        $res = [
			"status" => false,
			"message" => "Cant get user to edit"
		];
		
		echo json_encode($res);
    }
}

// обновление информации о главе
function n_UpdateChapter($db, $params) {
    // $params[]
    // [0] - user_id
    // [1] - work_id
    // [2] - chapter_id
    // [3] - chapter_name
    // [4] - chapter_number

    $text = json_decode(file_get_contents('php://input'), true)[0]["chapter_text"];
    // echo file_get_contents('php://input');
    
    // echo $_POST['user_id'];

    if (n_CheckWorkAuthor($db, $params['user_id'], $params['work_id'])) {
        $querry = 'UPDATE "public"."CHAPTER" SET "chapter_text" = $1, "chapter_name" = $2, "chapter_number" = $3 WHERE "chapter_id" = '.$params['chapter_id'].'';
        $result = pg_query_params($db, $querry, array($text, $params['chapter_name'], $params['chapter_number']));

        $state = pg_result_error($result);  //  отлов ошибок выполнения запроса

        $query = 'UPDATE "public"."WORK" SET "update_time" = $1 WHERE work_id = $2';
        $result = pg_query_params($db, $query, array(date('j-m-y'), $params['work_id']));
        
        if (empty($state)) {
            $result_list = ["status" => true,
                            "message" => "Update complete"];
            echo json_encode($result_list);
        }
        else {
            $result_list = ["status" => false,
                            "message" => $state];
            echo json_encode($result_list);
        }
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


$updateFunctions = [
    'work' => 'n_UpdateWork',
    'chapter' => 'n_UpdateChapter',
];

function route($db, $params, $key) {
    global $updateFunctions;
    if (array_key_exists($key, $updateFunctions)){
        $updateFunctions[$key]($db, $params);
        return True;
    }
    else
    {
        return False;
    }
}


?>