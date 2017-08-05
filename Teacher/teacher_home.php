<!DOCTYPE html>
<html>
	<img src="../img/ewu_logo.png" alt="EWU Logo">
	<?php include "teacher_navbar.php";
		include "creds.php"?>
	<head>
		<title>teacher Home</title>
	</head>
	<body>
	  <fieldset>
		<legend>Hidden</legend>
		<table border="1" id="tableHidden">
		<tbody>
		<tr>
		<th>Quarter</th><th>Date</th><th>Edit</th>
		</tr>
		<?php

			try
			{
				$db = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				$sql = $db->prepare("select exam.id, quarter, date, state, quarter.id as quarter_id, name
									from exam inner join quarter on exam.quarter = quarter.id 
									join in_class_exams  ON in_class_exams.exam_id = exam.id
									where state='1' and teacher_id = 3586832");
				
				$sql->execute();
				
				$res = $sql->fetchAll(PDO::FETCH_ASSOC);
				
				foreach($res as $val)
				{
					?>
					<tr>
					<td><?php echo $val['name'];?></td>
					<td><?php echo $val['date'];?></td>
					<td><form action="edit_exam.php" method="post">
						<input type="hidden" name='id' value="<?php echo $val['id']; ?>" >
						<input type="submit" value="View">
						</form>
					</tr>
					<?php
				}
				?>
				</tbody>
				</table>
				<?php
				
			}
			catch(Exception $e)
			{
				echo "SOMETHING WRONG" ."<br>". $e->getMessage();
			}
			
			$db = null;
			
		?>
		</tbody>
		</table>
	  </fieldset>
	
	  <fieldset>
		<legend>Open For Registration</legend>
		<table border="1" id="tableHidden">
		<tbody>
		<tr>
		<th>Quarter</th><th>Date</th><th>Edit</th>
		</tr>
		<?php
			include "creds.php";

			try
			{
				$db = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				$sql = $db->prepare("select exam.id, quarter, date, state, quarter.id as quarter_id, name
									from exam inner join quarter on exam.quarter = quarter.id 
									join in_class_exams  ON in_class_exams.exam_id = exam.id
									where state='2' and teacher_id = 3586832");
				
				$sql->execute();
				
				$res = $sql->fetchAll(PDO::FETCH_ASSOC);
				
				foreach($res as $val)
				{
					?>
					<tr>
					<td><?php echo $val['name'];?></td>
					<td><?php echo $val['date'];?></td>
					<td><form action="edit_exam.php" method="post">
						<input type="hidden" name='id' value="<?php echo $val['id']; ?>" >
						<input type="submit" value="View">
						</form>
					</tr>
					<?php
				}
				?>
				</tbody>
				</table>
				<?php
				
			}
			catch(Exception $e)
			{
				echo "SOMETHING WRONG" ."<br>". $e->getMessage();
			}
			
			$db = null;
			
		?>
		</tbody>
		</table>
	  </fieldset>

	  <fieldset>
		<legend>In Progress</legend>
		<table border="1" id="tableHidden">
		<tbody>
		<tr>
		<th>Quarter</th><th>Date</th><th>Edit</th>
		</tr>
		<?php
			include "creds.php";

			try
			{
				$db = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				$sql = $db->prepare("select exam.id, quarter, date, state, quarter.id as quarter_id, name
									from exam inner join quarter on exam.quarter = quarter.id 
									join in_class_exams  ON in_class_exams.exam_id = exam.id
									where state='3' and teacher_id = 3586832");
				
				$sql->execute();
				
				$res = $sql->fetchAll(PDO::FETCH_ASSOC);
				
				foreach($res as $val)
				{
					?>
					<tr>
					<td><?php echo $val['name'];?></td>
					<td><?php echo $val['date'];?></td>
					<td><form action="edit_exam.php" method="post">
						<input type="hidden" name='id' value="<?php echo $val['id']; ?>" >
						<input type="submit" value="View">
						</form>
					</tr>
					<?php
				}
				?>
				</tbody>
				</table>
				<?php
				
			}
			catch(Exception $e)
			{
				echo "SOMETHING WRONG" ."<br>". $e->getMessage();
			}
			
			$db = null;
			
		?>
		</tbody>
		</table>
	  </fieldset>
	
	  <fieldset>
		<legend>Grading</legend>
		<table border="1" id="tableHidden">
		<tbody>
		<tr>
		<th>Quarter</th><th>Date</th><th>Edit</th>
		</tr>
		<?php
			include "creds.php";

			try
			{
				$db = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				$sql = $db->prepare("select exam.id, quarter, date, state, quarter.id as quarter_id, name
									from exam inner join quarter on exam.quarter = quarter.id 
									join in_class_exams  ON in_class_exams.exam_id = exam.id
									where state='4' and teacher_id = 3586832");
				
				$sql->execute();
				
				$res = $sql->fetchAll(PDO::FETCH_ASSOC);
				
				foreach($res as $val)
				{
					?>
					<tr>
					<td><?php echo $val['name'];?></td>
					<td><?php echo $val['date'];?></td>
					<td><form action="edit_exam.php" method="post">
						<input type="hidden" name='id' value=<?php echo $val['id']; ?>>
						<input type="submit" value="View">
						</form>
					</td>
					</tr>
					<?php
				}
				?>
				</tbody>
				</table>
				<?php
				
			}
			catch(Exception $e)
			{
				echo "SOMETHING WRONG" ."<br>". $e->getMessage();
			}
			
			$db = null;
			
		?>
		</tbody>
		</table>
	  </fieldset>
	</body>
</html>