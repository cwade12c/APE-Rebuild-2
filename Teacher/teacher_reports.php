<!DOCTYPE html>
<html>
	<img src="../img/ewu_logo.png" alt="EWU Logo">
	<?php include "teacher_navbar.php";?>
	<head>
		<title>teacher Reports</title>
	</head>
	<body>
	<fieldset>
		<legend>Generate Report</legend>
		
		<form method="post" action="generate_report.php">
		Select APE:
		<select name="exam">
	<?php
		include "creds.php";

		try
		{
			$db = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			$sql = $db->prepare("select exam.id, quarter, date, state, quarter.id as quarter_id, name
									from exam inner join quarter on exam.quarter = quarter.id 
									join in_class_exams  ON in_class_exams.exam_id = exam.id
									where teacher_id = 3586832");
			
			$sql->execute();
			
			$res = $sql->fetchAll(PDO::FETCH_ASSOC);
			
			foreach($res as $val)
			{
				?>
				<option value=<?php echo $val["id"];?>><?php echo $val['name'] . " ". $val['date'];?></option>
				<?php
			}
			?>
			<?php
			
		}
		catch(Exception $e)
		{
			echo "SOMETHING WRONG" ."<br>". $e->getMessage();
		}
		
		$db = null;
		
	?>
			</select>
			<br>
		<!--Saved Report Types:
				<select>
	<?php
	/*
		try
		{
			$db = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			$sql = $db->prepare("select * from report");
			
			$sql->execute();
			
			$res = $sql->fetchAll(PDO::FETCH_ASSOC);
			
			foreach($res as $val)
			{
				?>
				<option><?php echo $val['name'];?></option>
				<input id = "reportid" type="hidden" value = <?php echo $val['id'];?>>
				<?php
			}

			
		}
		catch(Exception $e)
		{
			echo "SOMETHING WRONG" ."<br>". $e->getMessage();
		}
		
		$db = null;
		*/
	?>
		</select>
					
		<form action="./teacher_create_report.php">
			<input type="submit" value="Create New Type">
		</form>
		-->
		<?php
		/*
		try
		{
			$db = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			$sql = $db->prepare("select * from report r
								inner join report_rows rr
								on r.id = rr.report_id
								where id ='$id'");
			
			$sql->execute();
			
			$res = $sql->fetchAll(PDO::FETCH_ASSOC);
			*/
		?>
		
		
		
		<br>
		<input name="ewuid" type="checkbox" value="1" checked> EWU ID<br><br>
		<input name="first_name" type="checkbox" value="1" checked> First Name<br><br>
		<input name="last_name" type="checkbox" value="1" checked> Last Name<br><br>
		<input name="email" type="checkbox" value="1" checked> Email<br><br>
		<input name="pass" type="checkbox" value="1" checked> Pass/Fail<br><br>
		<input name="score" type="checkbox" value="1" checked> Total Score<br><br>
		<input name="category_score" type="checkbox" value="1" checked> Category Scores<br>
		<br>
		<input type="submit" value="Generate Report">
		</form>
		</fieldset>
		<?php
		/*
		try
		{
			$db = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			$sql = $db->prepare("select * from report r
								inner join report_rows rr
								on r.id = rr.report_id
								where id ='$id'");
			
			$sql->execute();
			
			$res = $sql->fetchAll(PDO::FETCH_ASSOC);
		<script>
		$(document).ready(main);
		function main(){
			console.log("MAIN METHOD");
			//var reportstuff = <?php echo json_encode($res);?>;
			//console.log(reportstuff);
			//TODO
		}

		</script>
		catch(Exception $e)
		{
			echo "SOMETHING WRONG" ."<br>". $e->getMessage();
		}
		$db = null;
		*/
		?>
		
	</body>
</html>