<?php

// приведение массива php в массив постгреса
function toPostgresArray($values) {
    $strArray = [];
    foreach ($values as $value) {
        if (is_int($value) || is_float($value)) {
            // For integers and floats, we can simply use strval().
            $str = strval($value);
        } else if (is_string($value)) {
            // For strings, we must first do some text escaping.
            $value = str_replace('\\', '\\\\', $value);
            $value = str_replace('"', '\\"', $value);
            $str = '"' . $value . '"';
        } else if (is_bool($value)) {
            // Convert the boolean value into a PostgreSQL constant.
            $str = $value ? 'TRUE' : 'FALSE';
        } else if (is_null($value)) {
            // Convert the null value into a PostgreSQL constant.
            $str = 'NULL';
        } else {
            throw new Exception('Unsupported data type encountered.');
        }
        $strArray[] = $str;
    }
    return '{' . implode(',', $strArray) . '}';
}


// поиск из всех работ самых новых
function GetNewWorks($db) {
    $result = pg_query($db, 'SELECT * FROM "public"."WORK" ORDER BY update_time DESC LIMIT 5 OFFSET 0' );
	
    $result_list = [];

    while ($answer = pg_fetch_assoc($result)) {
		$result_list[] = $answer;
	}
	
	echo json_encode($result_list);
}

// получить только ID самых новых работ
function GetIDNewWorks($db){
    $result = pg_query($db, 'SELECT work_id FROM "public"."WORK" ORDER BY update_time DESC LIMIT 5 OFFSET 0' );

    $result_list = [];

    while ($answer = pg_fetch_assoc($result)) {
        $result_list[] = $answer;
    }
	
	echo json_encode($result_list);
}

// получить информацию об ОДНОЙ работе по ключу
function GetWorkByID($db, $WorkID) {

    // ОСНОВНАя ИНФОРМАЦИя О РАБОТЕ
    $querry = 'WITH TempUser AS (SELECT user_id, nickname FROM "public"."USER")';
    $querry = $querry . ' SELECT * FROM "public"."WORK" LEFT JOIN TempUser tm USING(user_id)';
    $querry = $querry . ' WHERE work_id = '.$WorkID.'';

    $result = pg_query($db, $querry);

    $result_list = [];
    while ($answer = pg_fetch_assoc($result)) {
        $result_list[] = $answer;
    }

    // --------------------------------------------------------------
    // ИНФОРМАЦИя О ПЕРСОНАЖАХ В РАБОТЕ
    $querry = 'SELECT ctw.character_id, ch.character_name FROM "public"."CHARACTER-TO-WORK" as ctw ';
    $querry = $querry . 'LEFT JOIN "public"."CHARACTER" as ch ON ctw.character_id=ch.character_id WHERE ctw.work_id = '.$WorkID.'';
    
    // echo $querry;

    $result = pg_query($db, $querry);

    $char_list = []; // список на присоединение к результирующему набору
    $char_id = []; // список с id персонажей для дальнейших действий
    while ($answer = pg_fetch_assoc($result)) {
        $char_list[] = $answer;
        $char_id[] = $answer['character_id'];
        // echo $answer['character_id'];
    }
    
    $result_list[0] += array("characters" => $char_list);

    // --------------------------------------------------------------
    // ИНФОРМАЦИя О ФАНДОМАХ, ИСПОЛЬЗУЕМЫХ В РАБОТЕ
    $querry = 'SELECT DISTINCT f.name, f.fandom_id FROM "public"."CHARACTER-TO-FANDOM" as ctw ';
    $querry = $querry . 'LEFT JOIN "public"."FANDOM" as f ON f.fandom_id = ctw.fandom_id ';
    $querry = $querry . 'WHERE ctw.character_id = ANY ($1)';

    $fand_list = []; // список фандомов
    $result = pg_query_params($db, $querry, [toPostgresArray($char_id)]);
    while ($answer = pg_fetch_assoc($result)) {
        $fand_list[] = $answer;
    }

    $result_list[0] += array("fandom" => $fand_list);
        
    echo json_encode($result_list);
    
    // --------------------------------------------------------------
    // Получение информации о работе
    // получить поля работы, объединить с полем имени автора
    // 
    // SQL запрос
        //  WITH TempUser
        //  AS (SELECT user_id, nickname FROM "public"."USER")
        //  SELECT * FROM "public"."WORK"
        //  LEFT JOIN TempUser tm USING(user_id)
        //  WHERE work_id = 0
    // ---------------------------------------------------------------
    // Получение списка персонажей, используемых в работе
    //
    // SQL запрос
        // SELECT ctw.character_id, ch.character_name FROM "public"."CHARACTER-TO-WORK" as ctw 
        // LEFT JOIN "public"."CHARACTER" ch ON ctw.character_id=ch.character_id WHERE ctw.work_id=1
    // ---------------------------------------------------------------
    // Получение списка фандомов, задействованных в работе
    //
    // SQL запрос
        // SELECT DISTINCT f.name FROM "public"."CHARACTER-TO-FANDOM" as ctw 
        // LEFT JOIN "public"."FANDOM" as f ON f.fandom_id = ctw.fandom_id
        // WHERE ctw.character_id IN (7,8,9)
}

// получить информацию о главах работы отдельным запросом
function GetChaptersByWorkId($db, $WorkID) {
    $querry = 'SELECT chapter_id, chapter_name FROM "public"."CHAPTER" WHERE work_id = '.$WorkID.' ORDER BY chapter_id DESC';
    $result = pg_query($db, $querry);

    $result_list = [];

    while ($answer = pg_fetch_assoc($result)) {
		$result_list[] = $answer;
	}
	
	echo json_encode($result_list);
}

// получить список оставленных комментариев к работе
function GetWorkCommentsById($db, $WorkID) {
    
    $querry = 'SELECT us.nickname, comm.text, comm.comment_id, comm.id_user ';
    $querry = $querry . 'FROM "public"."COMMENT" as comm LEFT JOIN "public"."USER" as us ';
    $querry = $querry . 'ON comm.id_user = us.user_id WHERE comm.id_work='.$WorkID.' ORDER BY comm.comment_id DESC;';

    $result = pg_query($db, $querry);

    $result_list = [];

    while ($answer = pg_fetch_assoc($result)) {
		$result_list[] = $answer;
	}
	
	echo json_encode($result_list);

    // ЗАПРОС
    // SELECT us.nickname, comm.text, comm.comment_id, comm.id_user 
    // FROM "public"."COMMENT" as comm LEFT JOIN "public"."USER" as us 
    // ON comm.id_user = us.user_id WHERE comm.id_work=0;
}

// добавление комментария от имени пользователя к работе
function AddComment($db, $params) {

    $result = pg_query($db, 'SELECT comment_id FROM "public"."COMMENT" ORDER BY comment_id DESC LIMIT 1');

    $new_id = pg_fetch_assoc($result)['comment_id'] + 1;
    $params[] = $new_id;
    
    $query = 'INSERT INTO "public"."COMMENT" (id_user, id_work, text, comment_id) VALUES ($1, $2, $3, $4)';
    $result = pg_query_params($db, $query, $params);

    $state = pg_result_error($result);  //  отлов ошибок выполнения запроса

    if (empty($state))
    {
        $result_list = ["status" => true,
                        "message" => "Comment add complete"];
        echo json_encode($result_list);
    }
    else
    {
        $result_list = ["status" => false,
                        "message" => $state];
        echo json_encode($result_list);
    }
}

?>