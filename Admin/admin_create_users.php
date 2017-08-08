<!DOCTYPE html>
<html>
	<img src="../img/ewu_logo.png" alt="EWU Logo">
	<?php include "admin_navbar.php";?>
	<head>
		<title>Create User</title>
	</head>
	<body>
		<form method = "POST" action="create_users.php">
		  <fieldset>
			<legend>Create User</legend>

			Last Name: <input type="text" name="lname" required>
			<br>
			First Name: <input type="text" name="fname" required>
			<br>
			EWU ID: <input type="text" name="EWUID" required>
			<br>
			Email: <input type="text" name="email" required>
			<br>
			Account Type: <form>
							  <select name="account_types">
								<option value="student">Student</option>
								<option value="grader">Grader</option>
								<option value="teacher">Teacher</option>
							  </select>
						</form>
			<br>
			<input type="submit" value="Create User">
			</fieldset>
		</form>
	</body>
</html>