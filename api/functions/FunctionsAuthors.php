<?php

// получение новозарегистрированных авторов
function GetNewAuthors($db){
    $result = pg_query($db, 'SELECT user_id, nickname FROM "public"."USER" ORDER BY user_id DESC LIMIT 5' );
	
    $result_list = [];

    while ($answer = pg_fetch_assoc($result)) {
		$result_list[] = $answer;
	}
	
	echo json_encode($result_list);
}

// получение информации об одном авторе
function GetOneAuthor($db, $UserID, $withDate){
    // $result;

    if (!$withDate)
        $result = pg_query($db, 'SELECT user_id, nickname, about FROM "public"."USER" WHERE "user_id" = '.$UserID.'');
    else
        $result = pg_query($db, 'SELECT user_id, nickname, about, birth FROM "public"."USER" WHERE "user_id" = '.$UserID.'');

    if (pg_num_rows($result) > 0)
    {
        $result = pg_fetch_assoc($result);
	
		echo json_encode($result);
    }
    else {
        http_response_code(404);
		
		$res = [
			"status" => false,
			"message" => "Author not found"
		];
		
		echo json_encode($res);
    }
}

function UpdateuserInfo($db, $UserId, $about, $birth, $nickname) {

    $query = 'UPDATE "public"."USER" SET "birth" = $1, "about" = $2, "nickname" = $3 WHERE user_id= '.$UserId.'';
    $result = pg_query_params($db, $query, array($birth, $about, $nickname));


    $state = pg_result_error($result);  //  отлов ошибок выполнения запроса

    if (empty($state))
    {
        $result_list = ["status" => true,
                        "message" => "Update complete"];
        echo json_encode($result_list);
    }
    else
    {
        $result_list = ["status" => false,
                        "message" => $state];
        echo json_encode($result_list);
    }

    // UPDATE "USER" SET
    // "user_id" = '2',
    // "birth" = '1999-01-01',
    // "about" = 'Привет :)
    // Пишу детективные истории. В основном про расследование убийств. В данный момент пишу произведение "Второе письмо"',
    // "email" = 'JohnDoe@mail.ru',
    // "password" = '827ccb0eea8a706c4c34a16891f84e7b',
    // "nickname" = 'John Doe'
    // WHERE (("user_id" = '2'));
}



?>