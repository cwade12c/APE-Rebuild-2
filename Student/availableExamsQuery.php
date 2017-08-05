<?php
	include 'creds.php';
	$data = array();
	$locations = array();
	$quarters = array();
	$temp = array();
	
	try {
		$stmt = $conn->prepare("SELECT id, quarter, date, location, start_time, duration FROM exam WHERE state='2'"); 
		$stmt->execute();
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		while($row = $stmt->fetch())
		{	
			$data[] = ['id'=>$row['id'], 'quarter'=>$row['quarter'], 'date'=>$row['date'], 'location'=>$row['location'], 'start_time'=>$row['start_time'], 'duration'=>$row['duration']];
		}
		
		$stmt = null;
		$result = null;
		$row = null;
		
		$stmt = $conn->prepare("SELECT id, name FROM quarter"); 
		$stmt->execute();
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		while($row = $stmt->fetch())
		{	
			$quarter[] = ['id'=>$row['id'], 'name'=>$row['name']];
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
		
		$temp = array($data, $quarter, $location);
		
		$conn = null;
		echo json_encode($temp);
	}catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
?>