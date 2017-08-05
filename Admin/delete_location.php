<?php
	include "creds.php";
	$id = $_POST['location'];
	try
	{
		$db = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$sql = $db->prepare("delete from location where id = '$id'");
		
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