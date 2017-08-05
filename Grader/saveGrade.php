<?php
	include "../credentials.php";
	$data = array();
	$assignedId = $_POST["assignedId"];
	$studentId = $_POST["studentId"];
	$grade = $_POST["grade"];
	
	try {
		$stmt = $connection->prepare("INSERT INTO category_grades (assigned_exam_id, student_id, grade) VALUES (".$assignedId.", ".$studentId.", ".$grade.") ON DUPLICATE KEY UPDATE grade = ".$grade); 
		
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