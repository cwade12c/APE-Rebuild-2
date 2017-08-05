<?php
	include "creds.php";
	$room = $_POST['room'];
	$seats = $_POST['seats'];
	try
	{
		$db = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$sql = $db->prepare("insert into location(name, seats)
							values('$room', '$seats')");
		
		$sql->execute();
		
		
	}
	catch(Exception $e)
	{
		echo "SOMETHING WRONG" ."<br>". $e->getMessage();
	}
	
	$db = null;
	header("Location: admin_location.php");
	exit;
?>