<!DOCTYPE html>
<html>
	<img src="../img/ewu_logo.png" alt="EWU Logo">
	<?php 
	include "admin_navbar.php";
	include "../credentials.php";
	
	$id = $_POST["id"];
	$query = $connection->prepare("Select * from student where EWU_ID = $id");
	$query->execute();
	
	$result = $query->fetch(PDO::FETCH_ASSOC);
	$connection = null;
	
	?>
	<head>
		<title>Student</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="getStudentCompletedTable.js"></script>
	</head>
	<body>
		<input type="hidden" id="studentId" value="<?php echo $_POST["id"] ?>"/>
		<form>
		  <fieldset>
			<legend>Create User</legend>
			Last Name: <?php echo $result['l_name']?>
			<br>
			First Name: <?php echo $result['f_name']?>
			<br>
			EWU ID: <?php echo $result['EWU_ID']?>
			<br>
			Email: <?php echo $result['email']?>
			<br>
			State: <input type="text" name="lname" size="40" value="<?php echo $result['state']?>">
			<br>
			<!--<input type="submit" value="Edit User -->
			</fieldset>
		</form>

		<table id='completedTable' BORDER='1'>
		</table>

		<table id='detailsTable' BORDER='1'>
		</table>
	</body>
</html>