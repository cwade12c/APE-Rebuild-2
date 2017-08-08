<!DOCTYPE HTML>
<html>
	<img src="../img/ewu_logo.png" alt="EWU Logo">
	<?php include "admin_navbar.php";
		include "creds.php"?>
	<head>
		<title>Archived APEs</title>
	</head>
	
	<body>
	<input type="hidden" id="examId" value="<?php echo $_POST["id"] ?>"/>
	<fieldset id="fieldset">
	<legend>Archived APE Details</legend>
	<div id = "div">
	
	<input type="hidden" name='id' value="<?php echo $_POST['id']; ?>">
<?php

	$id = $_POST['id'];

	try
	{
		$db = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$sql = $db->prepare("select exam.id, quarter, date, location, 
							state, passing_grade, duration, start_time, cutoff, 
							quarter.id as quarter_id, quarter.name as quarter_name, 
							location.name as location_name
							from exam inner join quarter  
							on exam.quarter = quarter.id 
                            inner join location
                            on exam.location = location.id
							where exam.id='$id'");
		
		$sql->execute();
		
		$res = $sql->fetchAll(PDO::FETCH_ASSOC);
		
		foreach($res as $val)
		{
			?>
			Quarter: 
			<?php
				try
				{
					$sql = $db->prepare("select * from quarter");
					
					$sql->execute();
					
					$res1 = $sql->fetchAll(PDO::FETCH_ASSOC);
					
					foreach($res1 as $val1)
					{
					
						if($val['quarter_name']===$val1['name'])
						{
						
						echo $val1['name'];
						
						}

					}
				}
				catch(Exception $e)
				{
					echo "SOMETHING WRONG" ."<br>". $e->getMessage();
				}

					?>
			<br><br>

			Date: <?php echo $val['date'] ?>

			<br><br>
			Start Time: <?php echo $val['start_time'] . " <--- Need to fix format"?>
			<br><br>
			Duration: <?php echo $val['duration'] ?>
			<br>
			(Hours)
			<br><br>
			Register Deadline: <?php echo $val['cutoff'] ?>
			<br>
			(Hours Prior)
			<br><br>
			Pass Grade:<?php echo $val['passing_grade'] ?>
			<br><br>
			Location:
				<?php
					try
					{	
						$sql = $db->prepare("select * from location");
						
						$sql->execute();
						
						$res2 = $sql->fetchAll(PDO::FETCH_ASSOC);
						
						foreach($res2 as $val2)
						{
						
							if($val['location'] === $val2['id'])
							{
							
							 echo $val2['name']." Seats: ".$val2['seats'];
							
							}

						}
					}
					catch(Exception $e)
					{
						echo "SOMETHING WRONG" ."<br>". $e->getMessage();
					}
					
						?>
			  </select>
			<?php
			
		}
	}
	catch(Exception $e)
	{
		echo "SOMETHING WRONG" ."<br>". $e->getMessage();
	}
	
	$db = null;
	
	try
	{
		$db = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$sql = $db->prepare("select * from exam_category e
							inner join category c
							on e.category_id = c.id
							where exam_id='$id'");
		
		$sql->execute();
		
		$res = $sql->fetchAll(PDO::FETCH_ASSOC);
		?>
		<br><br>Categories and Points Possible: <br>	
		  Recursion: 
		 <input type="number" id="points1" name="points1" min="0" max="100" value="0" readonly><br>
		  Linked List: 
		 <input type="number" id="points2" name="points2" min="0" max="100" value="0" readonly><br>
		  General: 
		 <input type="number" id="points3" name="points3" min="0" max="100" value="0" readonly><br>
		  Data Abstraction: 
		 <input type="number" id="points4" name="points4" min="0" max="100" value="0" readonly><br>
		<br>
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src = getExamStudents.js></script>
		<script>
		$(document).ready(main);
		function main(){
			//console.log("MAIN METHOD");
			var cats = <?php echo json_encode($res);?>;
			console.log(cats);
			for(x = 0; x < cats.length; x++)
			{

				var id = cats[x].id;
				var value = cats[x].possible;
				//console.log(id);
				//document.getElementById("category"+id).checked = true;
				document.getElementById("points"+id).value = value;
				
				var input = document.createElement("input");
				input.setAttribute("type", "hidden");
				input.setAttribute("name", "catId"+id);
				input.setAttribute("value", cats[x].exam_category_id);
				document.getElementById("div").appendChild(input);
			}
		}

		</script>
		<?php



	}
	catch(Exception $e)
	{
		echo "SOMETHING WRONG" ."<br>". $e->getMessage();
	}

?>	
	Graders:<br>
	Recursion: 
					  <select id="graders1" name="graders1" disabled>
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

					  <select id="graders2" name="graders2" disabled>
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
					  <select id="graders3" name="graders3" disabled>
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
					  <select id="graders4" name="graders4" disabled>
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
					  <select id="graders5" name="graders5" disabled>
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
					  <select id="graders6" name="graders6" disabled>
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
					  <select id="graders7" name="graders7" disabled>
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
					  <select id="graders8" name="graders8" disabled>
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

						
				try
				{	
					$sql = $db->prepare("select * from assigned_graders a
										inner join grader g on a.grader_id = g.EWU_ID 
										inner join exam_category e on a.exam_cat_id = e.exam_category_id 
										where exam_id = '$id'");
					
					$sql->execute();
					
					$res = $sql->fetchAll(PDO::FETCH_ASSOC);
					
				}
				catch(Exception $e)
				{
					echo "SOMETHING WRONG" ."<br>". $e->getMessage();
				}
				$db=null;
						?>
					  </select>
					  
			<script>
			$(document).ready(main);
			function main(){
				//console.log("MAIN METHOD");
				var graders = <?php echo json_encode($res);?>;
				//console.log(graders);
				var cat1 = 1;
				var cat2 = 3;
				var cat3 = 5;
				var cat4 = 7;
				var count = 0;
				var num = 0;
				for(x = 0; x < graders.length; x++)
				{
					console.log(graders);
					var catId = graders[x].category_id;
					var ewuId = graders[x].EWU_ID;
					//console.log(catId);					
					if(catId==1)
					{
						num = cat1;
						//console.log(document.getElementById("graders"+num).options);
						//console.log(cat1);
						
						count = 0;
						while(document.getElementById("graders"+num).options[count].value != ewuId)
							count++;
						
						document.getElementById("graders"+num).options[count].selected = true;
						cat1++;
						var input = document.createElement("input");
						input.setAttribute("type", "hidden");
						input.setAttribute("name", "assigned"+num);
						input.setAttribute("value", graders[x].assigned_exam_id);
						document.getElementById("div").appendChild(input);
					}
					else if(catId==2)
					{
						num = cat2;
						//console.log(document.getElementById("graders"+num).options[cat2].value);
						//console.log(cat2);
						
						count = 0;
						while(document.getElementById("graders"+num).options[count].value != ewuId)
							count++;
						
						document.getElementById("graders"+num).options[count].selected = true;
						cat2++;
						var input = document.createElement("input");
						input.setAttribute("type", "hidden");
						input.setAttribute("name", "assigned"+num);
						input.setAttribute("value", graders[x].assigned_exam_id);
						document.getElementById("div").appendChild(input);
					}
					else if(catId==3)
					{
						num = cat3;
						//console.log(document.getElementById("graders"+num).options[cat3].value);
						//console.log(cat3);
						
						count = 0;
						while(document.getElementById("graders"+num).options[count].value != ewuId)
							count++;
						
						document.getElementById("graders"+num).options[count].selected = true;
						cat3++;
						var input = document.createElement("input");
						input.setAttribute("type", "hidden");
						input.setAttribute("name", "assigned"+num);
						input.setAttribute("value", graders[x].assigned_exam_id);
						document.getElementById("div").appendChild(input);
					}
					else
					{
						num = cat4;
						//console.log(document.getElementById("graders"+num).options[cat4].value);
						//console.log(cat4);
						
						count = 0;
						while(document.getElementById("graders"+num).options[count].value != ewuId)
							count++;
						
						document.getElementById("graders"+num).options[count].selected = true;
						cat4++;
						var input = document.createElement("input");
						input.setAttribute("type", "hidden");
						input.setAttribute("name", "assigned"+num);
						input.setAttribute("value", graders[x].assigned_exam_id);
						document.getElementById("div").appendChild(input);
					}					
					//console.log(document.getElementById("graders"+num).options[0].value);//.options[x].selected = true;
				}
			}
			
			</script>
			<form action = "admin_view_archived.php">
			<input type="submit" value="Cancel">
			</form>
			</div>
		  </fieldset>
		  <fieldset>
		  <legend>Registered Students</legend>

		  <table id='completedTable' BORDER='1' style="width:100%;">
								
			</table>
			<!-- Detailed Grades -->
			<div id='details'>
			<table id='detailsTable' BORDER='1'>
							
			</table>
			</div>
		  </fieldset>
	</body>
</html>	
