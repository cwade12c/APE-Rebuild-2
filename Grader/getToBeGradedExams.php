<?php
	include "../credentials.php";
	$data = array();
	$idGrader = $_POST["grader"];
	
	try {
		$stmt = $connection->prepare("SELECT g.assigned_exam_id as assignedId, c.name AS category, q.name AS quarter, e.date AS date, e.id as examID, e.duration as duration, e.start_time AS time, l.name as location
		FROM ape_database.assigned_graders g
		INNER JOIN ape_database.exam_category cg 
		ON g.exam_cat_id = cg.exam_category_id
        INNER JOIN ape_database.category c
        ON cg.category_id=c.id
        INNER JOIN ape_database.exam e 
        ON e.id =cg.exam_id
        INNER JOIN ape_database.quarter q
        ON e.quarter=q.id
        INNER JOIN ape_database.location l
        ON l.id=e.location
		WHERE g.grader_id='".$idGrader."' and g.submitted = 0
        ORDER BY e.id"); 
		
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