<?php
	$serverName='localhost';
	$user='root;
	$pass='bB*-7Q7p$M4';
	$database='test/test_new_ape_database;

	$connection = new PDO("mysql:host=$serverName;dbname=$database", $user, $pass);
	$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
