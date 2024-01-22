<?php

// рекурсивная функция, при помощи исходной строки в ловеркейсе, индекса и ссылки на массив в итоге в массиве скопятся все комбинации
// строки из больших и маленьких букв, т.е. по строке аb вернет [ab, Ab, aB, AB] или что-то около того
function &n_traverse($S, $idx, &$c) {
    if ($idx == mb_strlen($S)) {
        array_push($c,  $S);
        return;
    }
    n_traverse($S, $idx + 1, $c);
    $tt = mb_substr($S, $idx , 1);
    $T = mb_substr($S, $idx , 1);
    $T = mb_strtoupper($T, "UTF-8");
    $T = mb_convert_encoding($T, "UTF-8");

    n_traverse(str_replace($tt, $T, $S), $idx + 1, $c);
    return;
}

function n_perebor($SearchString) {
    $c = [];
    n_traverse(mb_strtolower($SearchString,"UTF-8"), 0, $c);
    return $c;
}


function n_SearchForFandom($db, $params) {

    $SearchString = $params['name'];
    $flag_withzero = true;
    if (isset($params['exclude']))
    {
        $flag_withzero = false;
    }

    $srch = n_perebor($SearchString);

    $result_list = [];

    // для каждой найденной строки делаем запрос на поиск
    foreach ($srch as $value) {
        $result = '';

        if ($flag_withzero)
            $result = pg_query($db, 'SELECT fandom_id, name FROM "public"."FANDOM" WHERE name LIKE \'%'.$value.'%\' LIMIT 5');
        else
            $result = pg_query($db, 'SELECT fandom_id, name FROM "public"."FANDOM" WHERE name LIKE \'%'.$value.'%\' AND fandom_id <> 0 LIMIT 5');

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

    if (count($result_list) > 0) {
        echo json_encode($result_list);
    }
    else {
        $res = [
            "status" => false,
            "message" => "Not found"
        ];

        echo json_encode($res);
    }
}


$searchFunctions = [
    'fandom' => 'n_SearchForFandom',
];

function route($db, $params, $key) {

    // $SearchString = $params['name'];

    global $searchFunctions;
    if (array_key_exists($key, $searchFunctions)){
        $searchFunctions[$key]($db, $params);
        return True;
    }
    else
    {
        return False;
    }
}

?>