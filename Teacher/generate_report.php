<!DOCTYPE html>
<html>
	<img src="../img/ewu_logo.png" alt="EWU Logo">
	<?php include "teacher_navbar.php"; include "creds.php";
	
	$db = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$query = "SELECT";
	$hasPrev = false;
	
	if(isset($_POST["ewuid"])) {
		$query = $query . " s.EWU_ID";
		$hasPrev = true;
	}
	
	if(isset($_POST["first_name"])) {
		if($hasPrev) {
			$query = $query . ",";
		}
		$query = $query . " s.f_name";
		$hasPrev = true;
	}
	
	if(isset($_POST["last_name"])) {
		if($hasPrev) {
			$query = $query . ",";
		}
		$query = $query . " s.l_name";
		$hasPrev = true;
	}
	
	if(isset($_POST["email"])) {
		if($hasPrev) {
			$query = $query . ",";
		}
		$query = $query . " s.email";
		$hasPrev = true;
	}
	
	if(isset($_POST["pass"])) {
		if($hasPrev) {
			$query = $query . ",";
		}
		$query = $query . " eg.passed";
		$hasPrev = true;
	}
	
	if(isset($_POST["score"])) {
		if($hasPrev) {
			$query = $query . ",";
		}
		$query = $query . " eg.grade";
		$hasPrev = true;
	}
	
	$query = $query . " from exam as e join exam_roster as er on e.id = er.exam_id
			join student as s on s.EWU_ID = er.student_id
			left join exam_grades as eg on eg.exam_id = e.id and eg.student_id = s.EWU_ID
			where e.id = " . $_POST["exam"];

	$sql = $db->prepare($query);
	$sql->execute();
			
	$res = $sql->fetchAll(PDO::FETCH_ASSOC);
	
	
	foreach($res as $val) {
		$hasPrev = false;

		if(isset($_POST["ewuid"])) {
			echo $val["EWU_ID"];
			$hasPrev = true;
		}
		
		if(isset($_POST["first_name"])) {
			if($hasPrev) {
				echo ", ";
			}
			echo $val["f_name"];
			$hasPrev = true;
		}
		
		if(isset($_POST["last_name"])) {
			if($hasPrev) {
				echo ", ";
			}
			echo $val["l_name"];
			$hasPrev = true;
		}
		
		if(isset($_POST["email"])) {
			if($hasPrev) {
				echo ", ";
			}
			echo $val["email"];
			$hasPrev = true;
		}
		
		if(isset($_POST["pass"])) {
			if($hasPrev) {
				echo ", ";
			}
			$passed = $val["passed"];
			if($passed == null) {
				echo "null";
			}
			elseif($passed == 0){
				echo "false";
			}
			else {
				echo "true";
			}
			$hasPrev = true;
		}
		
		if(isset($_POST["score"])) {
			if($hasPrev && $val["grade"] != null) {
				echo ", ";
			}
			echo $val["grade"];
			$hasPrev = true;
		}

		echo "<br>";
	}

	$db = null;
	
	
	
	
	
	?>

	
<html>