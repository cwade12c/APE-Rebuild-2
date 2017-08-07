<?php
require_once('../config.php');
enforceAuthentication();
?>

<!DOCTYPE html>
<html>
	<head>
		<title>APE Registration</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="studentData.js"></script>
		
	</head>
	<style>
	legend {
		display: block;
		padding-left: 2px;
		padding-right: 2px;
		border: none;
		font-size: 150%;
	}
	fieldset{
	  border-style: solid;
	  text-align: left;
	  display: inline-block;
	  width: 800px;
	  vertical-align:middle;
	}
	div{
		text-align:center;
		vertical-align:middle;
	}
	ul {
		list-style-type: none;
		margin: 0;
		padding: 0;
		overflow: hidden;
		background-color: #F00;
		width: 820px;
		margin: auto;
	}

	li {
		float: left;
	}

	li a {
		display: block;
		color: black;
		text-align: center;
		padding: 14px 16px;
		text-decoration: none;
	}

	li a:hover {
		background-color: white;
	}
	</style>
	<body bgcolor="#fdf5e6">
	<h1 align='center'>Student Home Page</h1>
	<ul>
	  <li style='float:right' ><a href='studentp2.php'>APE Info</a></li>
	  <li style='float:right'><a class='active' href='studentp1ND.php'>Home</a></li>
	</ul>
	<div>
		<fieldset style='border-color:#F00;'>
			<legend style='color:red;'> Important Messages </legend>
			<h2 style='color:red;'>YOU ARE NOT REGISTERED FOR AN APE! </h2><br>
			OR <br>
			Congratulations, you passed! <br>
		</fieldset>
	</div>
	<h3 align='center'>APE exam status, grades, and archives can be found here:</h3>
	<div> <!-- Completed APEs -->
		<fieldset>
			<legend> Completed APEs</legend>
			<h4>You have no comepleted APEs at this time. OR</h4>
			<table id='completedTable' BORDER='1' style="width:100%;">
								
			</table>
			<!-- Detailed Grades -->
			<div id='details'>
			<table id='detailsTable' BORDER='1'>
							
			</table>
			</div>
		</fieldset>
	</div>
	<div> <!-- Registered APEs -->
		<fieldset>
			<legend> Registered APEs</legend>
			<h4>You are not registered for any APEs at this time. OR</h4>
			<table id='registeredTable' BORDER='1' style="width:100%;">
								
			</table>
		</fieldset>
	</div>
	<div> <!-- Available APEs -->
		<fieldset>
			<legend> Available APEs</legend>
			<h4>There are no available APEs at this time. OR</h4>
			<table id='availableTable' BORDER='1' style="width:100%;">
								
			</table>
		</fieldset>
	</div>
		
		
	</body>
</html>


