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

// работа принадлежит автору?
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

// глава есть в работе автора?
function n_ChapterAuthorExist($db, $params) {
    
    $UserID     = $params['user_id'];
    $WorkId     = $params['work_id'];
    $ChapterId  = $params['chapter_id'];


    if (nn_CheckWorkAuthor($db, $UserID, $WorkId))
    {
        $querry = 'SELECT chapter_text, chapter_name, chapter_number FROM "public"."CHAPTER" WHERE chapter_id = '.$ChapterId.' AND work_id = '.$WorkId.'';
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

// вывод всех коллекций пользователя
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

// вывод одной коллекции пользователя
function n_UserCollection($db, $params) {

    $user_id        = $params['user_id'];
    $collection_id  = $params['collection_id'];

    $querry = 'SELECT collection_id as id, name  FROM "public"."COLLECTION" WHERE id_user = '.$user_id.' AND collection_id = '.$collection_id.'';
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

// вывести список работ, входящих в коллекцию (нужен только id работы)
function n_CollectionWorks($db, $params) {
    
    $collection_id = $params['collection_id'];
    $user_id = $params['user_id'];

    $querry = 'SELECT collection_id as id, name  FROM "public"."COLLECTION" WHERE id_user = '.$user_id.' AND collection_id= '.$collection_id.'';
    $result = pg_query($db, $querry);

    if (pg_num_rows($result) > 0)
    {
        $querry = 'SELECT coltw.work_id, coltw."COLL_WORK_id" FROM "public"."COLLECTION" AS col ';
        $querry = $querry . 'INNER JOIN "public"."COLLECTION-TO-WORK" AS coltw ';
        $querry = $querry . 'ON coltw.id_collection = col.collection_id ';
        $querry = $querry . 'WHERE col.collection_id = $1 AND col.id_user = $2 ORDER BY coltw.work_id DESC;';

        $result = pg_query_params($db, $querry, array($collection_id, $user_id));

        $state = pg_result_error($result);  //  отлов ошибок выполнения запроса

        if (empty($state))
        {
            $result_list = [];

            while ($answer = pg_fetch_assoc($result)) {
                $result_list[] = $answer;
            }
            
            if (count($result_list) > 0)
            {
                echo json_encode($result_list);
            }
            else {
                $result_list = ["status" => true,
                                "message" => 'no works'];
                echo json_encode($result_list);
            }
        }
        else {
            $result_list = ["status" => false,
                            "message" => $state];
            echo json_encode($result_list);
        }
    }
    else
    {
        $result_list = ["status" => false,
                        "message" => "cant get user"];
        echo json_encode($result_list);
    }
    
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
        $pagecount = round($count / $pagelen,0,PHP_ROUND_HALF_UP); // посчитали число страниц, которое может быть

        if ($count < $pagelen)
            $pagecount = 1;

        $query = 'SELECT work_id FROM "public"."WORK" ORDER BY work_id DESC LIMIT '.$pagelen.' OFFSET '.(string)($pagelen*$page).';';    
    }

    if ($type == "completed") { // завершенные работы
        $query = 'SELECT COUNT(*) as co FROM "public"."WORK" WHERE "WORK_STATUS" = 2';
        $count = pg_fetch_assoc(pg_query($db, $query))['co'];
        $pagecount = round($count / $pagelen,0,PHP_ROUND_HALF_UP); // посчитали число страниц, которое может быть
        if ($count < $pagelen)
            $pagecount = 1;

        $query = 'SELECT work_id FROM "public"."WORK" WHERE "WORK_STATUS" = 2 ORDER BY work_id DESC LIMIT '.$pagelen.' OFFSET '.(string)($pagelen*$page).';';    
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
    }
    
    echo json_encode($full_result);
}

$selectFunctions = [
    'select/works' => 'n_SelectWorks',
];

$StudioselectFunctions = [
    'studio/work' => 'n_WorkAuthorExist',
    'studio/chapter' => 'n_ChapterAuthorExist',
    'studio/collections' => 'n_UserCollections',
    'studio/collection' => 'n_UserCollection',
    'studio/collectionWorks' => 'n_CollectionWorks',
];

?>