<?php
	include 'creds.php';
	$data = array();
	$idStudent = $_POST["student"];
	
	try {
		$stmt = $conn->prepare("SELECT g.exam_id, g.student_id, g.grade, g.passed, g.possible, g.student_id, q.name AS quarter, l.name AS location, e.date, e.duration
		FROM exam_grades g
		INNER JOIN student s
		ON g.student_id=s.EWU_ID
		INNER JOIN exam e
		ON e.id=g.exam_id
		INNER JOIN quarter q
		ON q.id=e.quarter
		INNER JOIN location l
		ON e.location=l.id"); 
		$stmt->execute();
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		$x = 0;
		while($row = $stmt->fetch())
		{	if($row["student_id"] == $idStudent)
			{
				$data[$x] = $row;
				$x++;
			}
		}
		
		$conn = null;
		echo json_encode($data);
	}catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
?>