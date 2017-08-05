<?php
	include "../credentials.php";
	
	$search = $_POST["search"];
	$table = $_POST["table"];
	
	$query = $connection->prepare("SELECT * FROM $table WHERE f_name LIKE '%$search%'");
	$query->execute();
	
	$result = $query->fetchAll(PDO::FETCH_ASSOC);
	$connection = null;
	echo json_encode($result);
?>