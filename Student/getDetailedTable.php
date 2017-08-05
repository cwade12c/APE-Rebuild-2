<?php
	include 'creds.php';
	$data = array();
	$idStudent = $_POST["id"];
	$idExam = $_POST["exam"];
	
	try {
		$stmt = $conn->prepare("SELECT g.grade, c.name AS category, e.possible AS possible
		FROM category_grades g
		INNER JOIN assigned_graders a
		ON a.assigned_exam_id=g.assigned_exam_id
		INNER JOIN exam_category e
		ON e.exam_category_id =a.exam_cat_id
		INNER JOIN category c
		ON c.id=e.category_id
		WHERE g.student_id='".$idStudent."' AND e.exam_id='".$idExam."'"); 
		$stmt->execute();
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		while($row = $stmt->fetch())
		{
			$data[] = $row;
		}
		$conn = null;
		echo json_encode($data);
	}catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
?>