<?php
	include 'creds.php';
	$temp = array();
	$qt = $_POST["quarter"];
	try {
		$stmt = $conn->prepare("SELECT id, name FROM quarter WHERE id='".$qt."'"); 
		$stmt->execute();
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		while($row = $stmt->fetch())
		{	
			$temp[] = ['id'=>$row['id'], 'name'=>$row['name']];
		}
		$conn = null;
		echo json_encode($temp);
	}catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
?>