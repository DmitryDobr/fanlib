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

// работа автора сузествует? => отправляем инфу
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


    if (nn_CheckWorkAuthor($db, $UserID, $WorkId)) {
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

    $querry = 'SELECT collection_id as id, name  FROM "public"."COLLECTION" WHERE id_user = $1 AND collection_id = $2;';
    $result = pg_query_params($db, $querry, array($user_id, $collection_id));
    
    
    if (pg_num_rows($result) > 0) {
        $result_list = [];

        while ($answer = pg_fetch_assoc($result)) {
            $answer += array("status" => true);

            $current_collection_id = $answer['id'];

            $querry1 = 'SELECT count(ctw.work_id) as count FROM "public"."COLLECTION" as col INNER JOIN "public"."COLLECTION-TO-WORK" as ctw ';
            $querry1 = $querry1 . 'ON col.collection_id = ctw.id_collection ';
            $querry1 = $querry1 . 'WHERE ctw.id_collection = $1;';
            $result1 = pg_query_params($db, $querry1, array($current_collection_id));

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

    $querry = 'SELECT collection_id as id, name  FROM "public"."COLLECTION" WHERE id_user = $1 AND collection_id= $2';
    $result = pg_query_params($db, $querry, array($user_id, $collection_id));

    if (pg_num_rows($result) > 0) {
        $querry = 'SELECT coltw.work_id, coltw."COLL_WORK_id" FROM "public"."COLLECTION" AS col ';
        $querry = $querry . 'INNER JOIN "public"."COLLECTION-TO-WORK" AS coltw ';
        $querry = $querry . 'ON coltw.id_collection = col.collection_id ';
        $querry = $querry . 'WHERE col.collection_id = $1 AND col.id_user = $2 ORDER BY coltw.work_id DESC;';

        $result = pg_query_params($db, $querry, array($collection_id, $user_id));

        $state = pg_result_error($result);  //  отлов ошибок выполнения запроса

        if (empty($state)) {
            $result_list = [];

            while ($answer = pg_fetch_assoc($result)) {
                $result_list[] = $answer;
            }
            
            if (count($result_list) > 0) {
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
    else {
        $result_list = ["status" => false,
                        "message" => "cant get user"];
        echo json_encode($result_list);
    }
    
}

// получение работ автора
function n_getAllWorks($db, $params) {
    $UserID = $params['user_id'];

    $querry = 'SELECT work_id FROM "public"."WORK" WHERE user_id = $1 ORDER BY work_id DESC';
    $result = pg_query_params($db, $querry, array($UserID));

    // echo pg_num_rows($result);

    if (pg_num_rows($result) > 0) {
        $result_list = [];

        while ($answer = pg_fetch_assoc($result)) {
            $result_list[] = $answer;
        }
	
		echo json_encode($result_list);
    }
    else {
		$res = [
			"message" => "No post"
		];
		
		echo json_encode($res);
    }
}

$StudioselectFunctions = [
    'work' => 'n_WorkAuthorExist',
    'chapter' => 'n_ChapterAuthorExist',
    'collections' => 'n_UserCollections',
    'collection' => 'n_UserCollection',
    'collectionWorks' => 'n_CollectionWorks',
    'allworks' => 'n_getAllWorks',
];

function route($db, $params, $key) {
    global $StudioselectFunctions;
    if (array_key_exists($key, $StudioselectFunctions)){
        $StudioselectFunctions[$key]($db, $params);
        return True;
    }
    else
    {
        return False;
    }
}

?>