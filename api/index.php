<?php

	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Headers: *, Authorization');
	header('Access-Control-Allow-Methods: *');
	header('Access-Control-Allow-Credentials: true');

	// сюда приходят все запросы. И все будет обрабатываться здесь
	header('Content-Type: application/json; charset=utf-8');
    
	require 'pgConnect.php';

	require './functions/FunctionsWork.php';
	require './functions/FunctionsAuthors.php';
	require './functions/FunctionsAuthorWorks.php';
	require './functions/FunctionsRead.php';
    require './functions/FunctionsFandom.php';



    // "оптимизированный код"
    require './Authentification/newAuth.php';

    require './newFunctions/FunctionsUpdate.php';
    require './newFunctions/FunctionsInsert.php';
    require './newFunctions/FunctionsSelect.php';
    require './newFunctions/FunctionsDelete.php';

    require './newFunctions/FunctionsCharacters.php';
	


	// $method = $_SERVER['REQUEST_METHOD'];
	
	$q = $_GET['q'];
	$params = explode('/' , $q);
	
	$type = $params[0];
	
	$statusFlag = false;
	
	// "http://fanlib-api.ru/works/"


	if ($type == "works"){
		// запросы на вывод общего списка работ
		$SelectionType = $params[1];

		// echo $SelectionType;
		if (isset($SelectionType))
		{
            // новые работы
			if ($SelectionType == "new"){
				$statusFlag = true;
				GetIDNewWorks($db); // запрос на вывод списка ID новых добавленных работ
                return;
			}

            // одна работа
			if ($SelectionType == "one"){
				$statusFlag = true;
				$WorkID = $params[2];

                if (!isset($params[3]))
                {
				    GetWorkByID($db, $WorkID); // запрос на вывод инфы об одной работе
                    return;
                }
                else
                {
                    if ($params[3] == "chapters")
                        GetChaptersByWorkId($db, $WorkID);
                        return;
                }
			}

            // работы написанные в рамках фандома
            // if ($SelectionType == "fromFandom"){

            //     if (isset($params[2]))
            //     {
            //         $statusFlag = true;
            //         $FandomID = $params[2];
            //         echo $SelectionType.$FandomID;
            //         return;
            //     }
            // }

            // автор хочет получить расширенный доступ к работе
            if ($SelectionType == "onAuthor") {
                $statusFlag = true;
				$WorkID = $_GET['work_id'];
                $UserID = $_GET['user_id'];

                WorkAuthorExist($db, $UserID , $WorkID); // запрос автором информации о работе
                return;
            }
		}
	}
	else if ($type == "AuthorWorks"){
		// Запросы на вывод работ конкретного автора

		$UserID = $_GET['user_id'];
		$SelectionType = $params[1];

		// http://fanlib-api.ru/AuthorWorks/id
		if (isset($UserID))
		{
			// вывод последних работ автора
			if ($SelectionType == "last"){
				$statusFlag = true;
				GetAuthorWorksLast($db, $UserID);
                return;
			}
		}

	}
	else if ($type == "users"){
		// запросы на вывод пользователей
		$SelectionType = $params[1];

		if ($SelectionType == "new")
		{
			$statusFlag = true;
			GetNewAuthors($db); // запрос на вывод списка новых авторов
            return;
		}

		if ($SelectionType == "one")
		{
			$statusFlag = true;
			$UserID = $params[2];
			GetOneAuthor($db,$UserID,false); // запрос на вывод списка новых авторов
            return;
		}
	}
	else if ($type == "read"){
		// запросы на получение информации о фанфике с главами

		$WorkId = intval($params[1]);

		if (isset($WorkId))
		{
			$statusFlag = true;

            if (count($params) > 2)
                $secondType = $params[2];
            
            if (!isset($secondType)){
			    GetWorkInfoByID($db, $WorkId);
                return;
            }
            else {
                if ($secondType == "chapter")
                {
                    $ChapterId = $params[3];
                    GetChapterInfoById($db, $ChapterId, $WorkId);
                    return;
                }
                else
                {
                    GetWorkCommentsById($db, $WorkId);
                    return;
                }
            }
		}
	}
    else if ($type == "fandom"){

        $SelectionType = $params[1];

        if (str_contains($SelectionType, "search")){
            $statusFlag = true;
            // echo $_GET['name']; // Outputs: value1
            SearchForFandom($db, $_GET['name']);
            return;
            // echo $SelectionType;
        }

        if ($SelectionType == "one"){
            $FandomId = $params[2];

            if (!isset($params[3]))
            {
                $statusFlag = true;
                GetFandomInfoById($db, $FandomId);
            }            
        }
    }
    else if ($type == "characters"){
        $SelectionType = $params[1];

        if (isset($SelectionType))
        {
            $key = ''.$type.'/'.$SelectionType.'';

            if (array_key_exists($key, $characterFunctions))
            {
                $characterFunctions[$key]($db, $_GET);
                $statusFlag = true;
                return;
            }
        }
    }
    
    else if ($type == "update") {
        $UpdateType = $params[1];

        if (isset($UpdateType))
        {
            $key = ''.$type.'/'.$UpdateType.'';

            if (array_key_exists($key, $updateFunctions))
            {
                $updateFunctions[$key]($db, $_GET);
                $statusFlag = true;
                return;
            }
        }
    }
    else if ($type == "insert") {
        $InsertType = $params[1];

        if (isset($InsertType))
        {
            $key = ''.$type.'/'.$InsertType.'';

            if (array_key_exists($key, $insertFunctions))
            {
                $insertFunctions[$key]($db, $_GET);
                $statusFlag = true;
            }
        }
    }
    else if ($type == "user"){
        $QuerryType = $params[1];

        if (isset($QuerryType))
        {
            $key = ''.$type.'/'.$QuerryType.'';

            if (array_key_exists($key, $loginFunctions))
            {
                $loginFunctions[''.$type.'/'.$QuerryType.'']($db, $_GET);
                $statusFlag = true;
            }
        }
    }
    else if ($type == "studio") {
        $QuerryType = $params[1];

        if (isset($QuerryType))
        {
            $key = ''.$type.'/'.$QuerryType.'';

            if (array_key_exists($key, $StudioselectFunctions))
            {
                $StudioselectFunctions[''.$type.'/'.$QuerryType.'']($db, $_GET);
                $statusFlag = true;
            }
        }
    }
    else if ($type == "delete") {
        $QuerryType = $params[1];

        if (isset($QuerryType))
        {
            $key = ''.$type.'/'.$QuerryType.'';

            if (array_key_exists($key, $deleteFunctions))
            {
                $deleteFunctions[''.$type.'/'.$QuerryType.'']($db, $_GET);
                $statusFlag = true;
            }
        }
    }
    else if ($type == 'select') {
        $QuerryType = $params[1];

        if (isset($QuerryType))
        {
            $key = ''.$type.'/'.$QuerryType.'';

            if (array_key_exists($key, $selectFunctions))
            {
                $selectFunctions[''.$type.'/'.$QuerryType.'']($db, $_GET);
                $statusFlag = true;
            }
        }
    }
	


	
	if (!$statusFlag) {
		http_response_code(404);

		$res = [
			"status" => false,
			"message" => "Error addres"
		];
		
		echo json_encode($res);
	}
?>

