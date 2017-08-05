<?php
	include 'creds.php';
	
	$id = $_POST["id"];
	$idStudent = $_POST["student"];
	$sql = "DELETE FROM exam_roster WHERE student_id = '".$idStudent."' AND exam_id = '".$id."'";
	 	
	try{
		// Prepare statement
		$stmt = $conn->prepare($sql);
		// execute the query
		$stmt->execute();
		echo "Updated record. <br>";
	}catch(PDOException $e){
		echo $sql . "<br>" . $e->getMessage();
	}
	
	//header("Location: studentp1ND.html");
    //exit;
	
?>