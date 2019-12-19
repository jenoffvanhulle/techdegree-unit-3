<?php

/*
Connection to the database. If it's succesful we have an object $db that connects to the database. If it fails a message will be displayed and the program will end
*/

try {
	$db = new PDO("sqlite:".__DIR__."/journal.db");
	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
	echo $e->getMessage();
	exit;
}