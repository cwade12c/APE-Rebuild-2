<!DOCTYPE html>
<html>
	<img src="../img/ewu_logo.png" alt="EWU Logo">
	<?php include "admin_navbar.php";?>
	<head>
		<title>Admin Reports</title>
	</head>
	<body>
	<form method="post" action="create_report_type.php">
		<fieldset>
			<legend>Create Report Type</legend>
			Report Type Name: <input type="text" name="reportName" required>
			<br><br>
			<input name="ewuid" type="checkbox" value="1"> EWU ID<br><br>
			<input name="first_name" type="checkbox" value="1"> First Name<br><br>
			<input name="last_name" type="checkbox" value="1"> Last Name<br><br>
			<input name="email" type="checkbox" value="1"> Email<br><br>
			<input name="pass_fail" type="checkbox" value="1" > Pass/Fail<br><br>
			<input name="score" type="checkbox" value="1"> Total Score<br>
			<br>
			<input type="submit" value="Create Report Type">
		</fieldset>
	</form>
	</body>
</html>