<?php

function GetWorkInfoByID($db, $WorkID) {

    // ЗАПРОС ИНФЫ О РАБОТЕ И ИМЕНИ АВТОРА
    $querry = 'WITH TempUser AS (SELECT user_id, nickname FROM "public"."USER")';
    $querry = $querry . ' SELECT * FROM "public"."WORK" LEFT JOIN TempUser tm USING(user_id)';
    $querry = $querry . ' WHERE work_id = '.$WorkID.'';

    $result = pg_query($db, $querry);

    if (pg_num_rows($result) > 0)
    {
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

function GetChapterInfoById($db, $chapterId, $WorkId) {
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



?>