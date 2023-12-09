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


$deleteFunctions = [
    'delete/work' => 'n_DeleteWork',
    'delete/chapter' => 'n_DeleteChapter',
]

?>