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

    $text = json_decode(file_get_contents('php://input'), true)[0]["chapter_text"];
    // echo file_get_contents('php://input');

    if (CheckWorkAuthor($db, $params['user_id'], $params['work_id'])) {
        $querry = 'UPDATE "public"."CHAPTER" SET "chapter_text" = $1, "chapter_name" = $2 WHERE "chapter_id" = '.$params['chapter_id'].'';
        $result = pg_query_params($db, $querry, array($text, $params['chapter_name']));

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
        http_response_code(403);

        $res = [
			"status" => false,
			"message" => "Cant get user to edit"
		];
		
		echo json_encode($res);
    }
}


$updateFunctions = [
    'update/work' => 'n_UpdateWork',
    'update/chapter' => 'n_UpdateChapter',
]





?>