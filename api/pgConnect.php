<?php

	$host           =   "host = 127.0.0.1";
	$port           =   "port = 5432";
	$dbname         =   "dbname = Fanfiki";
	$credentials    =   "user = postgres password = postgres";

	$db = pg_connect("$host $port $dbname $credentials");


?>