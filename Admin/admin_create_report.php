<!DOCTYPE html>
<html>
	<?php include "admin_navbar.php";?>
	<head>
		<title>Admin Reports</title>
	</head>
	<body>
	<form>
		<fieldset>
			<legend>Create Report Type</legend>
			Report Type Name: <input type="text" name="reportName">
			<br><br>
			
			<div class="field"><input name="username" type="checkbox" value="1" /> Username</div>
				<label for="ewuid">&nbsp;</label> 
			<div class="field"><input name="ewuid" type="checkbox" value="1" checked="checked" /> EWU ID</div>

				<label for="first_name">&nbsp;</label> 
			<div class="field"><input name="first_name" type="checkbox" value="1" checked="checked" /> First Name</div>

				<label for="last_name">&nbsp;</label> 
			<div class="field"><input name="last_name" type="checkbox" value="1" checked="checked" /> Last Name</div>

				<label for="email">&nbsp;</label> 
			<div class="field"><input name="email" type="checkbox" value="1" /> Email</div>

				<label for="address">&nbsp;</label> 
			<div class="field"><input name="address" type="checkbox" value="1" /> Mailing Address</div>

				<label for="seat_id">&nbsp;</label> 
			<div class="field"><input name="seat_id" type="checkbox" value="1" checked="checked" /> Seat ID</div>

				<label for="attempts">&nbsp;</label> 
			<div class="field"><input name="attempts" type="checkbox" value="1" /> Number of Attempts</div>

				<label for="pass">&nbsp;</label> 
			<div class="field"><input name="pass" type="checkbox" value="1" checked="checked" /> Pass/Fail</div>

				<label for="state">&nbsp;</label> 
			<div class="field"><input name="state" type="checkbox" value="1" checked="checked" /> Student State</div>

				<label for="score">&nbsp;</label> 
			<div class="field"><input name="score" type="checkbox" value="1" checked="checked" /> Total Score</div>

				<label for="itemized">&nbsp;</label> 
			<div class="field"><input name="itemized" type="checkbox" value="1" /> Itemized Score</div>

				<label for="possible">&nbsp;</label> 
			<div class="field"><input name="possible" type="checkbox" value="1" /> Include Possible Score</div>

			<br>
			<input type="submit" value="Create Report Type">
		</fieldset>
	</form>
	</body>
</html>