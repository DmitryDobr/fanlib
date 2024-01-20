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

        $result = pg_query($db, 'SELECT max(chapter_number) as mx FROM "public"."CHAPTER" WHERE work_id='.$params['work_id']);
        $new_number = pg_fetch_assoc($result)['mx'] + 1;

        $querry = 'INSERT INTO "public"."CHAPTER" (chapter_id, work_id, chapter_name, chapter_number) VALUES ($1, $2, $3, $4)';
    
        $result = pg_query_params($db, $querry, array($new_id, $params['work_id'], "Новая глава",$new_number));


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

// добавление новой работы
function n_AddWork($db, $params) {
    // определение параметров работы и персонажей, связанных с ней
    $characters =   json_decode(file_get_contents('php://input'), true);

    $user_id    =   $params['user_id'];
    $WorkName   =   $params['work_name'];
    $about      =   $params['about'];

    $result = pg_query($db, 'SELECT max("work_id") as max FROM "public"."WORK"');
    $new_id = pg_fetch_assoc($result)['max'] + 1;

    pg_query($db, "BEGIN") or die("Could not start transaction\n"); // старт транзакции

    $querry = 'INSERT INTO "public"."WORK" ("work_id", "user_id", "WorkName", "about", "WORK_STATUS", "update_time") VALUES ($1, $2, $3, $4, $5, $6)';
    $result1 = pg_query_params($db, $querry, array($new_id, $user_id, $WorkName, $about, 1, date('j-m-y')));
    
    if ($result1) { // получилось добавить в таблицу работ новую запись
        $flag = true;

        // для каждого персонажа в полученном списке
        foreach ($characters as $character) {
            $buf_result = pg_query($db, 'SELECT max(ctw."CHAR_WORK_id") as max FROM "public"."CHARACTER-TO-WORK" as ctw');
            $new_id_ctw = pg_fetch_assoc($buf_result)['max'] + 1; // определяем id

            // добавляем персонажа в связку персонаж-работа
            $querry_ctw = 'INSERT INTO "public"."CHARACTER-TO-WORK" ("CHAR_WORK_id", "character_id", "work_id") VALUES ($1, $2, $3)';
            $result2 =  pg_query_params($db, $querry_ctw, array($new_id_ctw, $character['id_char'], $new_id));

            // не удалось записаться в связку персонаж-работа
            if (!$result2) {
                $flag = false;
                pg_query($db, "ROLLBACK") or die("Transaction rollback failed\n");
                break;
            }
        }

        if ($flag) {
            // все хорошечно
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

// добавление работы в коллекцию
function n_AddWorkCollection($db, $params) {
    $flag_ok = false;

    $user_id        = $params['user_id'];
    $collection_id  = $params['collection_id'];
    $work_id        = $params['work_id'];

    $querry = 'SELECT collection_id as id FROM "public"."COLLECTION" WHERE id_user = '.$user_id.' AND collection_id ='.$collection_id.'';
    $result = pg_query($db, $querry);

    if (pg_num_rows(($result)) == 1) { // пользователь и коллекция связаны

        $querry = 'SELECT id_collection as id FROM "public"."COLLECTION-TO-WORK" WHERE id_collection ='.$collection_id.' AND work_id='.$work_id.'';
        $result = pg_query($db, $querry);

        if (pg_num_rows(($result)) == 0) // работа и коллекция не связаны => можно добавить работу в коллекцию
        {
            $result = pg_query($db, 'SELECT max("COLL_WORK_id") as max FROM "public"."COLLECTION-TO-WORK"');
            $new_id = pg_fetch_assoc($result)['max'] + 1;
            
            $querry = 'INSERT INTO "public"."COLLECTION-TO-WORK" ("COLL_WORK_id", "id_collection", "work_id") VALUES ($1, $2, $3)';
            $result1 = pg_query_params($db, $querry, array($new_id, $collection_id, $work_id));

            $state = pg_result_error($result1);  //  отлов ошибок выполнения запроса

            if (empty($state)) {

                $result_list = ["status" => true,
                                "message" => "Add complete"];
                echo json_encode($result_list);

                $flag_ok = true;
            }
        }
    }

    if (!$flag_ok) {
        $result_list = ["status" => false,
                        "message" => "failed to add"];
        echo json_encode($result_list);
    }
}

$insertFunctions = [
    'comment' => 'n_AddComment',
    'chapter' => 'n_AddChapter',
    'work' => 'n_AddWork',
    'workcollection' => 'n_AddWorkCollection',
];

function route($db, $params, $key) {
    global $insertFunctions;
    if (array_key_exists($key, $insertFunctions)){
        $insertFunctions[$key]($db, $params);
        return True;
    }
    else
    {
        return False;
    }
}



?>