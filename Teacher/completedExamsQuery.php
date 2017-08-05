<?php
	include 'creds.php';
	$data = array();
	$idStudent = $_POST["student"];
	
	try {
		$db = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$stmt = $db->prepare("SELECT g.exam_id, g.student_id, g.grade, g.passed, g.possible, q.name AS quarter, l.name AS location, e.date, e.duration
		FROM ape_database.exam_grades g
		INNER JOIN ape_database.student s
		ON g.student_id=s.EWU_ID
		INNER JOIN ape_database.exam e
		ON e.id=g.exam_id
		INNER JOIN ape_database.quarter q
		ON q.id=e.quarter
		INNER JOIN ape_database.location l
		ON e.location=l.id
		where g.student_id = " . $idStudent); 
		$stmt->execute();
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		$x = 0;
		while($row = $stmt->fetch())
		{
			$data[$x] = $row;
			$x++;
		}
		
		$db = null;
		echo json_encode($data);
	}catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
?>