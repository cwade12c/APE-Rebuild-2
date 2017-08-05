<!DOCTYPE html>
<html>
	<img src="../img/ewu_logo.png" alt="EWU Logo">
	<?php include "admin_navbar.php";
			include "creds.php";?>
	<head>
		<title>Create APE</title>
	</head>
	<body>
		  <fieldset>
		  
			<legend>Create APE</legend>
			<form method="post" action="create_ape.php">
			Quarter: 
					  <select name="quarter">
						<?php
							try
							{
								$db = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
								$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
								
								$sql = $db->prepare("select * from quarter");
								
								$sql->execute();
								
								$res = $sql->fetchAll(PDO::FETCH_ASSOC);
								
								foreach($res as $val)
								{
								
									?>
									<option value=<?php echo $val['id'];?>><?php echo $val['name'];?></option>
									<?php
								}
							}
							catch(Exception $e)
							{
								echo "SOMETHING WRONG" ."<br>". $e->getMessage();
							}
							
							$db = null;
								?>
					  </select>
					
			<br><br>
			<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
			<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
			<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
			<script>
			  $( function() {
				$( "#date" ).datepicker({
				dateFormat: "yy-mm-dd"});
			  } );
			</script>
			Date: <input id="date" type="text" name="start_date" required>
			<script>
				n =  new Date();
				y = n.getFullYear();
				m = n.getMonth() + 1;
				d = n.getDate();
				document.getElementById("date").value = y + "-" + m + "-" + d;
			</script>
			<br><br>
			<link rel="stylesheet" href="./timepicker/jquery.timepicker.css">
			<script src="./timepicker/jquery.timepicker.min.js"></script>
			<script>
				$(function(){
				$('#time').timepicker();
				});
			</script>
			Start Time: <input id="time" type="text" name="start_time" value="2:00pm" required>
			<br><br>
			Duration: <input type="number" name="duration" min="1" max="10" value=4 required>
			<br>
			(Hours)
			<br><br>
			Register Deadline: <input type="number" name="deadline" min="2" max="24" value=2 required>
			<br>
			(Hours Prior)
			<br><br>
			Pass Grade: <input type="number" name="pass" min="50" max="100" value=80 required>
			<br><br>
			Location:
					  <select name="location">
						<?php
							try
							{
								$db = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
								$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
								
								$sql = $db->prepare("select * from location");
								
								$sql->execute();
								
								$res = $sql->fetchAll(PDO::FETCH_ASSOC);
								
								foreach($res as $val)
								{
								
									?>
									<option value=<?php echo $val['id'];?>><?php echo $val['name']." Seats: ".$val['seats'];?></option>
									<?php
								}
							}
							catch(Exception $e)
							{
								echo "SOMETHING WRONG" ."<br>". $e->getMessage();
							}
							
							$db = null;
								?>
					  </select>
			<br><br>
			Categories and Points Possible: <br>	
			 <input type="checkbox" name="category1" value="1" checked> Recursion: 
			 <input type="number" name="points1" min="1" max="100" value="20"><br>
			 <input type="checkbox" name="category2" value="2" checked> Linked List: 
			 <input type="number" name="points2" min="1" max="100" value="20"><br>
			 <input type="checkbox" name="category3" value="3" checked> General: 
			 <input type="number" name="points3" min="1" max="100" value="30"><br>
			 <input type="checkbox" name="category4" value="4" checked> Data Abstraction: 
			 <input type="number" name="points4" min="1" max="100" value="30"><br>
			<br>
			Graders:<br>
			Recursion: 
							  <select name="graders1">
						<?php
							try
							{
								$db = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
								$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
								
								$sql = $db->prepare("select * from grader");
								
								$sql->execute();
								
								$res = $sql->fetchAll(PDO::FETCH_ASSOC);
								
								foreach($res as $val)
								{
								
									?>
									<option value=<?php echo $val['EWU_ID']; ?>><?php echo $val['l_name'];?></option>
									<?php
								}
							}
							catch(Exception $e)
							{
								echo "SOMETHING WRONG" ."<br>". $e->getMessage();
							}
							
							$db = null;
								?>
							  </select>

							  <select name="graders2">
						<?php
							try
							{
								$db = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
								$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
								
								$sql = $db->prepare("select * from grader");
								
								$sql->execute();
								
								$res = $sql->fetchAll(PDO::FETCH_ASSOC);
								
								foreach($res as $val)
								{
								
									?>
									<option value=<?php echo $val['EWU_ID']; ?>><?php echo $val['l_name'];?></option>
									<?php
								}
							}
							catch(Exception $e)
							{
								echo "SOMETHING WRONG" ."<br>". $e->getMessage();
							}
							
							$db = null;
								?>
							  </select>

			<br>
			Linked List: 
							  <select name="graders3">
						<?php
							try
							{
								$db = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
								$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
								
								$sql = $db->prepare("select * from grader");
								
								$sql->execute();
								
								$res = $sql->fetchAll(PDO::FETCH_ASSOC);
								
								foreach($res as $val)
								{
								
									?>
									<option value=<?php echo $val['EWU_ID']; ?>><?php echo $val['l_name'];?></option>
									<?php
								}
							}
							catch(Exception $e)
							{
								echo "SOMETHING WRONG" ."<br>". $e->getMessage();
							}
							
							$db = null;
								?>
							  </select>
							  <select name="graders4">
						<?php
							try
							{
								$db = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
								$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
								
								$sql = $db->prepare("select * from grader");
								
								$sql->execute();
								
								$res = $sql->fetchAll(PDO::FETCH_ASSOC);
								
								foreach($res as $val)
								{
								
									?>
									<option value=<?php echo $val['EWU_ID']; ?>><?php echo $val['l_name'];?></option>
									<?php
								}
							}
							catch(Exception $e)
							{
								echo "SOMETHING WRONG" ."<br>". $e->getMessage();
							}
							
							$db = null;
								?>
							  </select>
			<br>
			General:
							  <select name="graders5">
						<?php
							try
							{
								$db = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
								$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
								
								$sql = $db->prepare("select * from grader");
								
								$sql->execute();
								
								$res = $sql->fetchAll(PDO::FETCH_ASSOC);
								
								foreach($res as $val)
								{
								
									?>
									<option value=<?php echo $val['EWU_ID']; ?>><?php echo $val['l_name'];?></option>
									<?php
								}
							}
							catch(Exception $e)
							{
								echo "SOMETHING WRONG" ."<br>". $e->getMessage();
							}
							
							$db = null;
								?>
							  </select>
							  <select name="graders6">
						<?php
							try
							{
								$db = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
								$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
								
								$sql = $db->prepare("select * from grader");
								
								$sql->execute();
								
								$res = $sql->fetchAll(PDO::FETCH_ASSOC);
								
								foreach($res as $val)
								{
								
									?>
									<option value=<?php echo $val['EWU_ID']; ?>><?php echo $val['l_name'];?></option>
									<?php
								}
							}
							catch(Exception $e)
							{
								echo "SOMETHING WRONG" ."<br>". $e->getMessage();
							}
							
							$db = null;
								?>
							  </select>
			<br>
			Data Abstraction:
							  <select name="graders7">
						<?php
							try
							{
								$db = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
								$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
								
								$sql = $db->prepare("select * from grader");
								
								$sql->execute();
								
								$res = $sql->fetchAll(PDO::FETCH_ASSOC);
								
								foreach($res as $val)
								{
								
									?>
									<option value=<?php echo $val['EWU_ID']; ?>><?php echo $val['l_name'];?></option>
									<?php
								}
							}
							catch(Exception $e)
							{
								echo "SOMETHING WRONG" ."<br>". $e->getMessage();
							}
							
							$db = null;
								?>
							  </select>
							  <select name="graders8">
						<?php
							try
							{
								$db = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
								$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
								
								$sql = $db->prepare("select * from grader");
								
								$sql->execute();
								
								$res = $sql->fetchAll(PDO::FETCH_ASSOC);
								
								foreach($res as $val)
								{
								
									?>
									<option value=<?php echo $val['EWU_ID']; ?>><?php echo $val['l_name'];?></option>
									<?php
								}
							}
							catch(Exception $e)
							{
								echo "SOMETHING WRONG" ."<br>". $e->getMessage();
							}
							
							$db = null;
								?>
							  </select>
			<br><br>
			
			<input type="submit" value="Create APE">
			</form>
		  </fieldset>
		
		
	</body>
</html>