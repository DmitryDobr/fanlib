<?php

function n_GetCharactersFromFandomById($db, $params) {
    $FandomId = $params['fandom_id'];

    $query = 'SELECT ctf.character_id, ch.character_name ';
    $query = $query . 'FROM "public"."CHARACTER-TO-FANDOM" ctf INNER JOIN "public"."CHARACTER" ch ';
    $query = $query . 'ON ctf.character_id = ch.character_id WHERE ctf.fandom_id = '.$FandomId.' AND ctf.fandom_id > 0';

    // echo $query;

    $result = pg_query($db, $query);

    if (pg_num_rows($result) > 0) {
        $result_list = [];

        while ($answer = pg_fetch_assoc($result)) {
            $result_list[] = $answer;
        }
        
        echo json_encode($result_list);
    }
    else {
        http_response_code(404);

        $res = [
			"status" => false,
			"message" => "Not found"
		];
		
		echo json_encode($res);
    }


    // ПОЛУЧИТЬ СПИСОК ПЕРСОНАЖЕЙ, ПРИПИСАННЫХ К ФАНДОМУ

    // SQL запрос:
    // SELECT ctf.character_id, ch.character_name
    // FROM "public"."CHARACTER-TO-FANDOM" ctf INNER JOIN "public"."CHARACTER" ch
    // ON ctf.character_id = ch.character_id WHERE ctf.fandom_id = 1;
}

$characterFunctions = [
    'fromfandom' => 'n_GetCharactersFromFandomById',
];


function route($db, $params, $key) {
    
    global $characterFunctions;
    if (array_key_exists($key, $characterFunctions)){
        $characterFunctions[$key]($db, $params);
    }
}



?>