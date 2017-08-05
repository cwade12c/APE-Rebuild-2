<!DOCTYPE html>
<html>
	<?php include "teacher_navbar.php";?>
	<head>
		<title>Create APE</title>
	</head>
	<body>
		<form>
		  <fieldset>
			<legend>Create APE</legend>
			Quarter: <form>
					  <select name="quarters">
						<option value="winter">Winter</option>
						<option value="spring">Spring</option>
						<option value="summer">Summer</option>
						<option value="fall">Fall</option>
					  </select>
					</form>
			<br><br>
			Date: <input type="date" name="start_date"><!--Doesn't support IE or FF-->
			<br><br>
			Start Time: <input type="time" name="start_time">
			<br><br>
			Duration: <input type="number" name="duration" min="1" max="10">
			<br>
			(Hours)
			<br><br>
			Register Deadline: <input type="number" name="deadline" min="2" max="24">
			<br>
			(Hours Prior)
			<br><br>
			Pass Grade: <input type="number" name="deadline" min="70" max="90">
			<br><br>
			Location:<form>
					  <select name="location">
						<option value="loc1">from Database</option>
						<option value="loc2">EX: CEB 207</option>
					  </select>
					</form>
			<br>
			Categories: <form>
						 <input type="checkbox" name="category" value="data_abstraction"> Data Abstraction<br>
						 <input type="checkbox" name="category" value="general"> General<br>
						 <input type="checkbox" name="category" value="linked_list"> Linked List<br>
						 <input type="checkbox" name="category" value="recursion"> Recursion
						 </form>
			<br><br>
			Graders:<br>
			Data Abstraction: <form>
							  <select name="graders1">
								<option value="grader1">from Database</option>
								<option value="grader2">EX: Stu</option>
							  </select>
							</form>
							<form>
							  <select name="graders1">
								<option value="loc1">from Database</option>
								<option value="loc2">EX: Dan</option>
							  </select>
							</form>
			<br>
			General: <form>
							  <select name="graders1">
								<option value="grader1">from Database</option>
								<option value="grader2">EX: Stu</option>
							  </select>
							</form>
							<form>
							  <select name="graders1">
								<option value="loc1">from Database</option>
								<option value="loc2">EX: Dan</option>
							  </select>
							</form>
			<br>
			Linked List: <form>
							  <select name="graders1">
								<option value="grader1">from Database</option>
								<option value="grader2">EX: Stu</option>
							  </select>
							</form>
							<form>
							  <select name="graders1">
								<option value="loc1">from Database</option>
								<option value="loc2">EX: Dan</option>
							  </select>
							</form>
			<br>
			Recursion: <form>
							  <select name="graders1">
								<option value="grader1">from Database</option>
								<option value="grader2">EX: Stu</option>
							  </select>
							</form>
							<form>
							  <select name="graders1">
								<option value="loc1">from Database</option>
								<option value="loc2">EX: Dan</option>
							  </select>
							</form>
			<br>
			
			<input type="submit" value="Upload CSV File">
			<input type="submit" value="Create APE">
		  </fieldset>
		</form>
		
	</body>
</html>