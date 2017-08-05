<?php
	include 'creds.php';
	$data = array();
	$idStudent = $_POST["student"];
	
	try {
		$stmt = $conn->prepare("SELECT state FROM student WHERE EWU_ID='".$idStudent."'"); 
		$stmt->execute();
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		while($row = $stmt->fetch())
		{				
			$data = $row['state'];
		}
		
		$conn = null;
		echo json_encode($data);
	}catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
?>