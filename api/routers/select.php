<?php

// приведение массива php в массив постгреса
function ConvertToPostgresArray($values) {
    $strArray = [];
    foreach ($values as $value) {
        if (is_int($value) || is_float($value)) {
            // число преобразуем как strval().
            $str = strval($value);
        } else if (is_string($value)) {
            // для строк делаем.
            $value = str_replace('\\', '\\\\', $value);
            $value = str_replace('"', '\\"', $value);
            $str = '"' . $value . '"';
        } else if (is_bool($value)) {
            // bool в PostgreSQL constant.
            $str = $value ? 'TRUE' : 'FALSE';
        } else if (is_null($value)) {
            // null в PostgreSQL constant.
            $str = 'NULL';
        } else {
            throw new Exception('Unsupported data type encountered.');
        }
        $strArray[] = $str;
    }
    return '{' . implode(',', $strArray) . '}';
}

// функции выбора работ
function n_SelectWorks($db, $params) {
    $pagelen = 4;
    $pagecount = 0;

    $type = $params['type'];
    $page = $params['page'];
    $query = '';
    
    if ($type == "new") { // новые работы
        $query = 'SELECT COUNT(*) as co FROM "public"."WORK"';
        $count = pg_fetch_assoc(pg_query($db, $query))['co'];
        $pagecount = ceil($count / $pagelen); // посчитали число страниц, которое может быть

        if ($count < $pagelen)
            $pagecount = 1;

        $query = 'SELECT work_id FROM "public"."WORK" ORDER BY update_time DESC LIMIT '.($pagelen).' OFFSET '.(string)($pagelen*$page).';';    
    }

    if ($type == "completed") { // завершенные работы
        $query = 'SELECT COUNT(*) as co FROM "public"."WORK" WHERE "WORK_STATUS" = 2';
        $count = pg_fetch_assoc(pg_query($db, $query))['co'];
        $pagecount =  ceil($count / $pagelen); // посчитали число страниц, которое может быть
        if ($count < $pagelen)
            $pagecount = 1;

        $query = 'SELECT work_id FROM "public"."WORK" WHERE "WORK_STATUS" = 2 ORDER BY update_time DESC LIMIT '.$pagelen.' OFFSET '.($pagelen*$page).';';    
    }

    if ($type == "byAuthor") { // по id автора работы
        $user_id = $params['user_id'];

        $query = 'SELECT COUNT(*) as co FROM "public"."WORK" WHERE user_id = '.$user_id;
        $count = pg_fetch_assoc(pg_query($db, $query))['co'];
        $pagecount = ceil($count / $pagelen); // посчитали число страниц, которое может быть
        if ($count < $pagelen)
            $pagecount = 1;

        $query = 'SELECT work_id FROM "public"."WORK" WHERE user_id = '.$user_id.'ORDER BY work_id DESC LIMIT '.$pagelen.' OFFSET '.(string)($pagelen*$page).';';
    }

    $result = pg_query($db, $query);
    
    $full_result = [];
    $full_result += array("pagecount" => $pagecount);

    if (pg_num_rows($result) > 0) {
        $result_list = [];

        while ($answer = pg_fetch_assoc($result)) {
            $result_list[] = $answer;
        }

        $full_result += array("works" => $result_list);
        $full_result += array("status" => true);
        echo json_encode($full_result);
    }
    else {
        // http_response_code(404);

		$res = [
			"status" => false,
			"message" => "No post"
		];
		
		echo json_encode($res);
    }
    
    
}

// полуить инфу по работе по id работы
function n_GetWorkInfoByID($db, $params) {
    $WorkID = $params['work_id'];

    // ЗАПРОС ИНФЫ О РАБОТЕ И ИМЕНИ АВТОРА
    $querry = 'WITH TempUser AS (SELECT user_id, nickname FROM "public"."USER")';
    $querry = $querry . ' SELECT * FROM "public"."WORK" LEFT JOIN TempUser tm USING(user_id)';
    $querry = $querry . ' WHERE work_id = '.$WorkID.'';

    $result = pg_query($db, $querry);

    if (pg_num_rows($result) > 0) {
        $result_list = [];

        while ($answer = pg_fetch_assoc($result)) {
            $result_list[] = $answer;
        }

        // ЗАПРОС СПИСКА ГЛАВ РАБОТЫ
        $querry = 'SELECT chapter_id, chapter_name FROM "public"."CHAPTER" WHERE work_id = '.$WorkID.' ORDER BY chapter_id';
        $result = pg_query($db, $querry);
        $chapter_list = [];

        $num = 0;
        while ($answer = pg_fetch_assoc($result)) {
            $answer += array("num" => $num);
            $chapter_list[] = $answer;
            $num++;
        }

        // ОБЪЕДИНЕНИЕ АССОЦИАТИВНЫХ МАССИВОВ
        $result_list[0] = $result_list[0] + array("Chapters" => $chapter_list);
            
        echo json_encode($result_list);
    }
    else {
		http_response_code(404);

		$res = [
			"status" => false,
			"message" => "Error work not found"
		];
		
		echo json_encode($res);
	}

    // Функция возвращает все поля одной работы
    // и список, состоящий из ID и названий глав, входящих
    // в данную работу
}

// получить инфу по главе по id главы и работы
function n_GetChapterInfoById($db,$params) {

    $chapterId  = $params['chapterId'];
    $WorkId     = $params['WorkId'];

    $querry = 'SELECT * FROM "public"."CHAPTER" WHERE chapter_id = '.$chapterId.' AND work_id = '.$WorkId.'';
    $result = pg_query($db, $querry);

    $result_list = [];

    while ($answer = pg_fetch_assoc($result)) 
    {
        $result_list[] = $answer;
    }

    if (count($result_list) > 0)
    {
        echo json_encode($result_list);
    }
    else {
        http_response_code(404);

		$res = [
			"status" => false,
			"message" => "Error chapter with this id in work with this id not found"
		];
		
		echo json_encode($res);
    }
    // Функция проверяет, есть ли в работе с номером WorkId глава с номером chapterId
    // если такая глава имеется => возвращаем ответом текст главы
    // иначе => возвращаем ошибку
}

// получить информацию о главах работы отдельным запросом
function n_GetChaptersByWorkId($db, $params) {

    $WorkID = $params['WorkID'];

    $querry = 'SELECT chapter_id, chapter_name FROM "public"."CHAPTER" WHERE work_id = '.$WorkID.' ORDER BY chapter_number DESC';
    $result = pg_query($db, $querry);

    $result_list = [];

    while ($answer = pg_fetch_assoc($result)) {
		$result_list[] = $answer;
	}
	
	echo json_encode($result_list);
}

// получить список оставленных комментариев к работе
function n_GetWorkCommentsById($db, $params) {
    
    $WorkID = $params['WorkID'];

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

// получить вообще всю информацию об ОДНОЙ работе по ключу
function n_GetWorkAllInfoById($db, $params) {

    $WorkID = $params['WorkID'];

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
    $result = pg_query_params($db, $querry, [ConvertToPostgresArray($char_id)]);
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

// получение информации об одном авторе
function n_GetOneAuthor($db, $params) {
    // $result;
    $UserID = $params['UserID'];
    $result = pg_query($db, 'SELECT user_id, nickname, about FROM "public"."USER" WHERE "user_id" = '.$UserID.'');

    if (pg_num_rows($result) > 0) {
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

function n_GetAuthors($db, $params){
    $type = $params['type'];

    if ($type === 'new')
    {
        $result = pg_query($db, 'SELECT user_id, nickname FROM "public"."USER" ORDER BY user_id DESC' );
    }
	
    $result_list = [];

    while ($answer = pg_fetch_assoc($result)) {
		$result_list[] = $answer;
	}
	
	echo json_encode($result_list);
}

function n_GetFandomInfoById($db, $params) {

    $FandomId = $params['FandomId'];
    // информация по фандому по его идентификатору
    // -------------------------------------------------------------------------------------------
    $result = pg_query($db, 'SELECT * FROM "public"."FANDOM" WHERE fandom_id = '.$FandomId);

    if (pg_num_rows($result) > 0)
    {
        $result_list = [];

        while ($answer = pg_fetch_assoc($result)) {
            $result_list[] = $answer;
        }
        
        echo json_encode($result_list);
    }
    else
    {
        http_response_code(404);

        $res = [
			"status" => false,
			"message" => "Not found"
		];
		
		echo json_encode($res);
    }
}

$selectFunctions = [
    'works' => 'n_SelectWorks',
    'work' => 'n_GetWorkInfoByID',
    'workfull' => 'n_GetWorkAllInfoById',
    'workchapters' => 'n_GetChaptersByWorkId',
    'chapter' => 'n_GetChapterInfoById',
    'comments' => 'n_GetWorkCommentsById',
    'author' => 'n_GetOneAuthor',
    'authors' => 'n_GetAuthors',
    'fandom' => 'n_GetFandomInfoById',
];

function route($db, $params, $key) {
    global $selectFunctions;
    if (array_key_exists($key, $selectFunctions)){
        $selectFunctions[$key]($db, $params);
        return True;
    }
    else
    {
        return False;
    }
}



?>