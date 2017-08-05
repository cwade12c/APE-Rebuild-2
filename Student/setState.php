<?php
	include 'creds.php';
	$data = array();
	$idStudent = $_POST["student"];
	$state = $_POST["theState"];
	
	try {
		$stmt = $conn->prepare("UPDATE student SET state='".$state."' WHERE EWU_ID='".$idStudent."'"); 
		$stmt->execute();
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		$conn = null;
		echo json_encode($data);
	}catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
?>