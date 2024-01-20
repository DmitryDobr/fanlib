<?php

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

        $query = 'SELECT work_id FROM "public"."WORK" ORDER BY update_time DESC LIMIT '.$pagelen.' OFFSET '.(string)($pagelen*$page).';';    
    }

    if ($type == "completed") { // завершенные работы
        $query = 'SELECT COUNT(*) as co FROM "public"."WORK" WHERE "WORK_STATUS" = 2';
        $count = pg_fetch_assoc(pg_query($db, $query))['co'];
        $pagecount = round($count / $pagelen,0,PHP_ROUND_HALF_UP); // посчитали число страниц, которое может быть
        if ($count < $pagelen)
            $pagecount = 1;

        $query = 'SELECT work_id FROM "public"."WORK" WHERE "WORK_STATUS" = 2 ORDER BY update_time DESC LIMIT '.$pagelen.' OFFSET '.(string)($pagelen*$page).';';    
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
    'works' => 'n_SelectWorks',
];

function route($db, $params, $key) {
    global $selectFunctions;
    if (array_key_exists($key, $selectFunctions)){
        $selectFunctions[$key]($db, $params);
    }
}



?>