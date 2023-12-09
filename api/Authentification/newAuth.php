<?php

function n_CheckUser($db, $params){
    // echo $login.$password;

    $login = $params['email'];
    $password = $params['password'];

    $result = pg_query($db, 'SELECT user_id, nickname FROM "public"."USER" WHERE email=\''.$login.'\' AND password= \''.md5($password).'\'');
    // $result = pg_query($db, 'SELECT user_id, nickname FROM "public"."USER" WHERE email=\''.$login.'\'');

    if (pg_num_rows($result) == 1)
    {
        $result_list = pg_fetch_assoc($result);
        $result_list += array("status" => true);

        // while ($answer = pg_fetch_assoc($result)) {
        //     $result_list[] = $answer;
        // }
        
        echo json_encode($result_list);
    }
    else
    {
        $result_list = array("status" => false,"Error" => "Cant find user");
                        
        echo json_encode($result_list);
    }
}

function n_registerUser($db, $params) {

    $login = $params['email'];
    $password = $params['password'];
    $nickname = $params['nickname'];


    $result = pg_query($db, 'SELECT user_id, nickname FROM "public"."USER" WHERE email=\''.$login.'\'');

    if (pg_num_rows($result) > 0) {
        $result_list = ["status" => false,
                        "Message" => "Email already used"];
        echo json_encode($result_list);
    }
    else {
        $result = pg_query($db, 'SELECT user_id FROM "public"."USER" ORDER BY user_id DESC LIMIT 1');

        $new_id = pg_fetch_assoc($result)['user_id'] + 1;


        // добавление пользователя в базу данных
        $query = 'INSERT INTO "public"."USER" (user_id, birth, about, email, password, nickname) VALUES ($1, $2, $3, $4, $5, $6)';
	    $result = pg_query_params($db, $query, array($new_id, NULL, NULL,$login, md5($password), $nickname));

        $state = pg_result_error($result);  //  отлов ошибок выполнения запроса

        if (empty($state))
        {
            $result_list = ["status" => true,
                            "Message" => "Register complete"];
            echo json_encode($result_list);
        }
        else
        {
            $result_list = ["status" => false,
                            "Message" => $state];
            echo json_encode($result_list);
        }
    }
}

// получение информации об одном авторе
function n_GetOneAuthor($db, $params){

    $withDate = true;
    $UserID = $params['user_id'];

    $result;

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

function n_UpdateuserInfo($db, $params) {

    $UserId     = $params['user_id'];
    $about      = $params['about'];
    $birth      = $params['birth'];
    $nickname   = $params['nickname'];



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



$loginFunctions = [
    'user/login' => 'n_CheckUser',
    'user/register' => 'n_registerUser',
    'user/userinfo' => 'n_GetOneAuthor',
    'user/updateuserinfo' => 'n_UpdateuserInfo',
]


?>