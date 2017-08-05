<!DOCTYPE html>
<html>
	<img src="../img/ewu_logo.png" alt="EWU Logo">
	<?php include "admin_navbar.php";?>
	<head>
		<title>Users</title>
	</head>
	<body>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="admin_users.js"></script>
		<br>
		Search Users:
		<input id="searchBar" type="text" name="search">
		<input id="searchBtn" type="button" value="Search">
		
		<h3>Students</h3>
		<table border="1" id="student_tbl">
		<tbody>
		<tr>
		<th>EWU ID</th><th>Last Name</th><th>First Name</th><th>Email</th><th>State</th>
		</tr>
		</tbody>
		</table>
		
		<h3>Graders</h3>
		<table border="1" id="grader_tbl">
		<tbody>
		<tr>
		<th>EWU ID</th><th>Last Name</th><th>First Name</th><th>Email</th>
		</tr>
		</tbody>
		</table>
		
		<h3>Teachers</h3>
		<table border="1" id="teacher_tbl">
		<tbody>
		<tr>
		<th>EWU ID</th><th>Last Name</th><th>First Name</th><th>Email</th>
		</tr>
		</tbody>
		</table>
	</body>
</html>