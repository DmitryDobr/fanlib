<?php
// проверить "Принадлежит" ли работа данному автору
function nn_CheckWorkAuthor($db, $UserID, $WorkId) {

    $querry = 'SELECT about, "WorkName", remark FROM "public"."WORK" WHERE user_id = '.$UserID.' AND work_id = '.$WorkId.'';
    $result = pg_query($db, $querry);

    if (pg_num_rows($result) > 0) {
        return true;
    }
    else {
        return false;
    }
}

function n_WorkAuthorExist($db, $params) {

    $UserID = $params['user_id'];
    $WorkId = $params['work_id'];

    if (nn_CheckWorkAuthor($db, $UserID, $WorkId))
    {
        $querry = 'SELECT about, "WorkName", remark FROM "public"."WORK" WHERE user_id = '.$UserID.' AND work_id = '.$WorkId.'';
        $result = pg_query($db, $querry);

        if (pg_num_rows($result) > 0) {
            $result_list = [];

            while ($answer = pg_fetch_assoc($result)) {
                $result_list[] = $answer;
            }
        
            echo json_encode($result_list);
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
    else {
        http_response_code(403);

            $res = [
                "status" => false,
                "message" => "Cant get user to edit"
            ];
            
            echo json_encode($res);
    }
    
}

function n_ChapterAuthorExist($db, $params) {
    
    $UserID     = $params['user_id'];
    $WorkId     = $params['work_id'];
    $ChapterId  = $params['chapter_id'];


    if (nn_CheckWorkAuthor($db, $UserID, $WorkId))
    {
        $querry = 'SELECT chapter_text, chapter_name FROM "public"."CHAPTER" WHERE chapter_id = '.$ChapterId.' AND work_id = '.$WorkId.'';
        $result = pg_query($db, $querry);

        if (pg_num_rows($result) > 0) {
            $result_list = [];

            while ($answer = pg_fetch_assoc($result)) {
                $result_list[] = $answer;
            }
        
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

function n_UserCollections($db, $params) {
    //  ЭТО НЕ НУЖНО!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    // НЕОБХОДИМО СГЕНЕРИРОВАТЬ ИНФОРМАЦИЮ О ПОЛЬЗОВАТЕЛЬСКОЙ КОЛЛЕКЦИИ
    // и ДОБАВИТЬ ID РАБОТ В КОЛЛЕКЦИИ
    //  => один элемент массива
    // все пользовательские коллекции => массив => JSON => отправить
    // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    // Просто вывести саму коллекцию и число работ в ней

    $user_id = $params['user_id'];

    $querry = 'SELECT collection_id as id, name  FROM "public"."COLLECTION" WHERE id_user = '.$user_id.'';
    $result = pg_query($db, $querry);
    
    
    if (pg_num_rows($result) > 0) {
        $result_list = [];

        while ($answer = pg_fetch_assoc($result)) {
            $answer += array("status" => true);

            $current_collection_id = $answer['id'];

            $querry1 = 'SELECT count(ctw.work_id) as count FROM "public"."COLLECTION" as col INNER JOIN "public"."COLLECTION-TO-WORK" as ctw ';
            $querry1 = $querry1 . 'ON col.collection_id = ctw.id_collection ';
            $querry1 = $querry1 . 'WHERE ctw.id_collection = '.$current_collection_id.';';
            $result1 = pg_query($db, $querry1);

            $assoc = pg_fetch_assoc($result1);
            // $collectionIdList = [];
            // if (pg_num_rows($result1) > 0) {
            //     while ($answer1 = pg_fetch_assoc($result1)) {
            //         $collectionIdList[] = $answer1;
            //     }
            // }
            // $answer += array("IdWorks" => $collectionIdList);
            
            $answer += array("count" => $assoc['count']);

            $result_list[] = $answer;
        }

        echo json_encode($result_list);
    }
    else {
        http_response_code(403);

        $res = [
            "status" => false,
            "message" => "No collections found"
        ];
        
        echo json_encode($res);
    }


    // СТАРЫЙ SQL ЗАПРОС
    // SELECT ctw.work_id FROM "public"."COLLECTION" as col INNER JOIN "public"."COLLECTION-TO-WORK" as ctw 
    // ON col.collection_id = ctw.id_collection 
    // WHERE ctw.id_collection = 3;
    
}


$selectFunctions = [
    'studio/work' => 'n_WorkAuthorExist',
    'studio/chapter' => 'n_ChapterAuthorExist',
    'studio/collections' => 'n_UserCollections',
]

?>