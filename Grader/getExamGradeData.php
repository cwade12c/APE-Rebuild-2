<?php
	include "../credentials.php";
	$data = array();
	$assignedId = $_POST["assignedId"];
	$graderId = $_POST["graderId"];
	
	try {
		$stmt = $connection->prepare("select er.seat_num as seat_num, c.name as category_name, ec.possible as possible, cg.grade as grade, er.student_id as student_id
										from assigned_graders as ag 
										JOIN exam_category as ec on ag.exam_cat_id = ec.exam_category_id 
										JOIN category as c on c.id = ec.category_id 
										join exam as e on ec.exam_id = e.id 
										JOIN exam_roster as er on er.exam_id = e.id 
										left join category_grades as cg on cg.student_id = er.student_id and cg.assigned_exam_id = ag.assigned_exam_id
									where ag.grader_id = ".$graderId." and ag.assigned_exam_id = ".$assignedId." ORDER BY seat_num"); 
		
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