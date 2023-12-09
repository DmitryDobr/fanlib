<?php

$b = [];

// поиск фандомов по названию
function SearchForFandom($db, $SearchString){

    function traverse($S, $idx) {
        if ($idx == mb_strlen($S)) {
            array_push($GLOBALS['b'],  $S);
            return;
        }
        traverse($S, $idx + 1);
        $tt = mb_substr($S, $idx , 1);

        $T = mb_substr($S, $idx , 1);
        
        $T = mb_strtoupper($T, "UTF-8");

        $T = mb_convert_encoding($T, "UTF-8");

        traverse(str_replace($tt, $T, $S), $idx + 1);
        return;
    }

    traverse(mb_strtolower($SearchString,"UTF-8"), 0);

    // for ($i = 0; $i < count($GLOBALS['b']); $i++)
    // {
    //     echo $GLOBALS['b'][$i].' ';
    // }
    // поиск Фандомов
    // ------------------------------------------------------------------------------------------------------------------------------
    // $result = pg_query($db, 'SELECT fandom_id, name FROM "public"."FANDOM" WHERE name LIKE \'%'.mb_strtolower($SearchString,"UTF-8").'%\' LIMIT 5');
    // $result1 = pg_query($db, 'SELECT fandom_id, name FROM "public"."FANDOM" WHERE name LIKE \'%'.mb_strtoupper($SearchString,"UTF-8").'%\' LIMIT 5');
    $result_list = [];

    foreach ($GLOBALS['b'] as $value) {
        $result = pg_query($db, 'SELECT fandom_id, name FROM "public"."FANDOM" WHERE name LIKE \'%'.$value.'%\' LIMIT 5');

        if (pg_num_rows($result) > 0) {
            while ($answer = pg_fetch_assoc($result)) {
                $answer += array("status" => true);
                if (!in_array($answer, $result_list))
                {
                    $result_list[] = $answer;
                }
            }
        }
    }

    if (count($result_list) > 0)
    {
        echo json_encode($result_list);
    }
    else
    {
        $res = [
            "status" => false,
            "message" => "Not found"
        ];
    }


    // if (pg_num_rows($result) > 0) {
    //     $result_list = [];

    //     while ($answer = pg_fetch_assoc($result)) {
    //         $answer += array("status" => true);
    //         $result_list[] = $answer;
    //     }

    //     while ($answer = pg_fetch_assoc($result1)) {

    //         $answer += array("status" => true);
    //         if (!in_array($answer, $result_list))
    //         {
                
    //             $result_list[] = $answer;
    //         }
    //     }
        
    //     echo json_encode($result_list);
    // }
    // else
    // {
    //     $res = [
	// 		"status" => false,
	// 		"message" => "Not found"
	// 	];
		
	// 	echo json_encode($res);
    // }
}

function GetFandomInfoById($db, $FandomId) {

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

function CalculateFandomWorksCount($db) {
    // по id фандома высчитывает количество работ, написанных в данном фандоме
    // возвращает название фандома, id и число работ

    // через связь работа->персонаж получаем количество уникальных номеров работ
    // с данными персонажами данного фандома
    // (т.к. в работе может использоваться несколько персонажей из 1 фандома)
    // после чего через связь персонаж->фандом узнаем номер фандома
    // --------------------------------------------------------------------------------------







    // --------------------------------------------------------------------------------------
    // Запрос
    // WITH tempf AS (SELECT COUNT(DISTINCT ctw.work_id) as workcount , ctf.fandom_id
    // FROM "public"."CHARACTER-TO-FANDOM" ctf LEFT JOIN "public"."CHARACTER-TO-WORK" ctw
    // ON ctf.character_id = ctw.character_id GROUP BY ctf.fandom_id) 
    // SELECT f.name, f.fandom_id, tempf.WorkCount FROM "public"."FANDOM" as f 
    // LEFT JOIN tempf USING(fandom_id) ORDER BY workcount DESC;

    //// старый запрос
    // SELECT COUNT(DISTINCT ctw.work_id) , ctf.fandom_id
    // FROM "public"."CHARACTER-TO-FANDOM" ctf LEFT JOIN "public"."CHARACTER-TO-WORK" ctw
    // ON ctf.character_id = ctw.character_id GROUP BY ctf.fandom_id;
}

?>