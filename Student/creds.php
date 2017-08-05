<?php
 	ini_set('display_errors','On');

	$host = 'localhost';
	$user = 'root';
	$password = 'bB*-7Q7p$M4';
	$db = 'ape_database';


	try {
		$conn = new PDO("mysql:host=$host;dbname=$db", $user, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//echo "Connected successfully <br>"; 
	}catch(PDOException $e){
		echo 'Connection failed: ' . $e->getMessage();
	}
?>
