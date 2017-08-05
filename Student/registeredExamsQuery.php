<?php
	include 'creds.php';
	$data = array();
	$exams = array();
	$locations = array();
	$temp = array();
	$idStudent = $_POST["student"];
	
	try {
		$stmt = $conn->prepare("SELECT exam_id, student_id FROM exam_roster WHERE student_id='".$idStudent."'"); 
		$stmt->execute();
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		while($row = $stmt->fetch())
		{				
			$ex = $conn->prepare("SELECT id, quarter, date, location, start_time, duration FROM exam WHERE id='".$row['exam_id']."'"); 
			
			$ex->execute();
			$result = $ex->setFetchMode(PDO::FETCH_ASSOC);
			
			while($theExam = $ex->fetch())
			{	
				$data[] = ['id'=>$theExam['id'], 'quarter'=>$theExam['quarter'], 'date'=>$theExam['date'], 'location'=>$theExam['location'], 'start_time'=>$theExam['start_time'], 'duration'=>$theExam['duration']];
			}
		}
		
		$stmt = null;
		$result = null;
		$row = null;
		
		$stmt = $conn->prepare("SELECT id, name FROM location"); 
		$stmt->execute();
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		while($row = $stmt->fetch())
		{	
			$location[] = ['id'=>$row['id'], 'name'=>$row['name']];
		}
		
		$temp = array($data, $location);
		
		$conn = null;
		echo json_encode($temp);
	}catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
?>