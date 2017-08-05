<?php
	include "../credentials.php";
	$data = array();
	$assignedId = $_POST["assignedId"];
	
	try {
		$stmt = $connection->prepare("update assigned_graders set submitted = 1 where assigned_exam_id = ".$assignedId); 
		
		$stmt->execute();
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		while($row = $stmt->fetch())
		{	
			$data[] = $row;
		}
		
		
		$connection = null;
		echo json_encode($data);
	}catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
?>