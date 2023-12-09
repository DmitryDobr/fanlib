<?php

// проверить "Принадлежит" ли работа данному автору
function nnn_CheckWorkAuthor($db, $UserID, $WorkId) {

    $querry = 'SELECT about, "WorkName", remark FROM "public"."WORK" WHERE user_id = '.$UserID.' AND work_id = '.$WorkId.'';
    $result = pg_query($db, $querry);

    if (pg_num_rows($result) > 0) {
        return true;
    }
    else {
        return false;
    }
}

// добавление комментария от имени пользователя к работе
function n_AddComment($db, $params) {

    $user_id    = $params['user_id'];
    $work_id    = $params['work_id'];
    $text       = $params['commenttext'];

    $result = pg_query($db, 'SELECT comment_id FROM "public"."COMMENT" ORDER BY comment_id DESC LIMIT 1');

    $new_id = pg_fetch_assoc($result)['comment_id'] + 1;
    
    $query = 'INSERT INTO "public"."COMMENT" (id_user, id_work, text, comment_id) VALUES ($1, $2, $3, $4)';
    $result = pg_query_params($db, $query, array($user_id, $work_id, $text, $new_id));

    $state = pg_result_error($result);  //  отлов ошибок выполнения запроса

    if (empty($state)) {
        $result_list = ["status" => true,
                        "message" => "Comment add complete"];
        echo json_encode($result_list);
    }
    else {
        $result_list = ["status" => false,
                        "message" => $state];
        echo json_encode($result_list);
    }
}

// добавление главы в работу
function n_AddChapter($db, $params) {
    // $params[]
    // [0] - user_id
    // [1] - work_id

    if (CheckWorkAuthor($db, $params['user_id'], $params['work_id']))
    {
        $result = pg_query($db, 'SELECT chapter_id FROM "public"."CHAPTER" ORDER BY chapter_id DESC LIMIT 1');
        $new_id = pg_fetch_assoc($result)['chapter_id'] + 1;


        $querry = 'INSERT INTO "public"."CHAPTER" (chapter_id, work_id, chapter_name) VALUES ($1, $2, $3)';
    
        $result = pg_query_params($db, $querry, array($new_id, $params['work_id'], "Новая глава"));



        $state = pg_result_error($result);  //  отлов ошибок выполнения запроса

        if (empty($state)) {
            $result_list = ["status" => true,
                            "message" => "Add chapter complete"];
            echo json_encode($result_list);
        }
        else {
            $result_list = ["status" => false,
                            "Message" => $state];
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

// добавление нрвой работы
function n_AddWork($db, $params) {

    // определение параметров работы и персонажей, связанных с ней
    $characters =   json_decode(file_get_contents('php://input'), true);

    $user_id    =   $params['user_id'];
    $WorkName   =   $params['work_name'];
    $about      =   $params['about'];

    $result = pg_query($db, 'SELECT max("work_id") as max FROM "public"."WORK"');
    $new_id = pg_fetch_assoc($result)['max'] + 1;

    pg_query($db, "BEGIN") or die("Could not start transaction\n");

    $querry = 'INSERT INTO "public"."WORK" ("work_id", "user_id", "WorkName", "about", "WORK_STATUS", "update_time") VALUES ($1, $2, $3, $4, $5, $6)';
    $result1 = pg_query_params($db, $querry, array($new_id, $user_id, $WorkName, $about, 1, date('j-m-y')));
    
    if ($result1) {
        $flag = true;

        foreach ($characters as $character)
        {
            $buf_result = pg_query($db, 'SELECT max(ctw."CHAR_WORK_id") as max FROM "public"."CHARACTER-TO-WORK" as ctw');
            $new_id_ctw = pg_fetch_assoc($buf_result)['max'] + 1;

            // echo $character['id_char'];
            $querry_ctw = 'INSERT INTO "public"."CHARACTER-TO-WORK" ("CHAR_WORK_id", "character_id", "work_id") VALUES ($1, $2, $3)';
            $result2 =  pg_query_params($db, $querry_ctw, array($new_id_ctw, $character['id_char'], $new_id));

            if (!$result2) // не удалось записаться в связку персонаж-работа
            {
                $flag = false;
                // echo "Rolling back transaction\n";
                pg_query($db, "ROLLBACK") or die("Transaction rollback failed\n");
                break;
            }
        }

        if ($flag)
        {
            pg_query($db, "COMMIT") or die("Transaction commit failed\n");

            $result_list = ["status" => true,
                "message" => "Add work complete"];
            echo json_encode($result_list);
        }
        else {
            $result_list = ["status" => false,
                            "message" => "failed transaction"];
            echo json_encode($result_list);
        }
    }
    else {
        // echo "Rolling back transaction\n";
        pg_query($db, "ROLLBACK") or die("Transaction rollback failed\n");

        $result_list = ["status" => false,
                        "message" => "failed transaction"];
        echo json_encode($result_list);
    }  
}

$insertFunctions = [
    'insert/comment' => 'n_AddComment',
    'insert/chapter' => 'n_AddChapter',
    'insert/work' => 'n_AddWork',
]




?>