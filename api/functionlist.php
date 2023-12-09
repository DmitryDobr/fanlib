<?php

    require './functions/FunctionsWork.php';
	require './functions/FunctionsAuthors.php';
	require './functions/FunctionsAuthorWorks.php';
	require './functions/FunctionsRead.php';
    require './functions/FunctionsFandom.php';
    require './functions/FunctionsCharacters.php';

    require './Authentification/Auth.php';


    $list = array(
        "works/new" => GetIDNewWorks(), 
        "works/one/" => GetWorkByID(),
        "users/new" => GetNewAuthors(),
    )


?>