<?php

// удаление главы из БД
function n_DeleteChapter($db, $params) {
    $UserId     = $params['user_id'];
    $WorkId     = $params['work_id'];
    $ChapterId  = $params['chapter_id'];

    $querry = 'DELETE FROM "public"."CHAPTER" WHERE chapter_id = '.$ChapterId.' AND work_id = '.$WorkId.';';
    $result = pg_query($db, $querry);

    $state = pg_result_error($result);  //  отлов ошибок выполнения запроса

    if (empty($state)) {
        $result_list = ["status" => true,
                        "message" => "Delete complete"];
        echo json_encode($result_list);
    }
    else {
        $result_list = ["status" => false,
                        "message" => $state];
        echo json_encode($result_list);
    }
}

// удаление работы из БД
function n_DeleteWork($db, $params) {
    $UserId     = $params['user_id'];
    $WorkId     = $params['work_id'];

    $querry = 'DELETE FROM "public"."WORK" WHERE user_id = '.$UserId.' AND work_id = '.$WorkId.';';
    $result = pg_query($db, $querry);

    $state = pg_result_error($result);  //  отлов ошибок выполнения запроса

    if (empty($state)) {
        $result_list = ["status" => true,
                        "message" => "Delete complete"];
        echo json_encode($result_list);
    }
    else {
        $result_list = ["status" => false,
                        "message" => $state];
        echo json_encode($result_list);
    }
}

// удаление записи-связки коллекция-к-работе
function n_DeleteWCollection($db, $params) {
    $CWid = $params['id'];
    $user_id = $params['user_id'];

    $querry = 'SELECT col.id_user FROM "public"."COLLECTION" AS col ';
    $querry = $querry . 'INNER JOIN "public"."COLLECTION-TO-WORK" AS coltw ';
    $querry = $querry . 'ON coltw.id_collection = col.collection_id ';
    $querry = $querry . 'WHERE coltw."COLL_WORK_id" = '.$CWid.';';
    $result = pg_query($db, $querry);

    if (pg_fetch_assoc($result)["id_user"] == $user_id) {
        $querry = 'DELETE FROM "public"."COLLECTION-TO-WORK" WHERE "COLL_WORK_id" = '.$CWid.';';
        $result = pg_query($db, $querry);

        $state = pg_result_error($result);  //  отлов ошибок выполнения запроса

        if (empty($state)) {
            $result_list = ["status" => true,
                            "message" => "Delete complete"];
            echo json_encode($result_list);
        }
        else {
            $result_list = ["status" => false,
                            "message" => $state];
            echo json_encode($result_list);
        }
    }
    else {
        $result_list = ["status" => true,
                        "message" => "Delete cannot be provided"];
        echo json_encode($result_list);        
    }
}

$deleteFunctions = [
    'work' => 'n_DeleteWork',
    'chapter' => 'n_DeleteChapter',
    'workCollection' => 'n_DeleteWCollection',
];

function route($db, $params, $key) {
    global $deleteFunctions;
    if (array_key_exists($key, $deleteFunctions)){
        $deleteFunctions[$key]($db, $params);
    }
}



?>