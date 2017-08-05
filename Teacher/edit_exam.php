<!DOCTYPE HTML>
<html>
	<img src="../img/ewu_logo.png" alt="EWU Logo">
	<?php include "teacher_navbar.php";
		include "creds.php"?>
	<head>
		<title>Edit APE</title>
	</head>
	<body>

	<fieldset id="fieldset">
	<legend>Edit APE</legend>
	<form method="post" action="edit_exam_changes.php">
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
		  <select name="quarter">
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
						?>
						<option selected="selected" value=<?php echo $val1['id'];?>><?php echo $val1['name'];?></option>
						<?php
						}
						else
						{
						?>
						<option value=<?php echo $val1['id'];?>><?php echo $val1['name'];?></option>
						<?php
						}
					}
				}
				catch(Exception $e)
				{
					echo "SOMETHING WRONG" ."<br>". $e->getMessage();
				}

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
			Date: <input id="date" type="text" name="start_date" value=<?php echo $val['date'] ?> required>

			<br><br>
			<link rel="stylesheet" href="./timepicker/jquery.timepicker.css">
			<script src="./timepicker/jquery.timepicker.min.js"></script>
			<script>
				$(function(){
				$('#time').timepicker();
				});
			</script>
			Start Time: <input id="time" type="text" name="start_time" value=<?php echo $val['start_time'] ?> required>
			<br><br>
			Duration: <input type="number" name="duration" min="1" max="10" value=<?php echo $val['duration'] ?> required>
			<br>
			(Hours)
			<br><br>
			Register Deadline: <input type="number" name="deadline" min="2" max="24" value=<?php echo $val['cutoff'] ?> required>
			<br>
			(Hours Prior)
			<br><br>
			Pass Grade: <input type="number" name="pass" min="50" max="100" value=<?php echo $val['passing_grade'] ?> required>
			<br><br>
			Location:
			  <select name="location">
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
							?>
							<option selected="selected" value=<?php echo $val2['id'];?>><?php echo $val2['name']." Seats: ".$val2['seats'];?></option>
							<?php
							}
							else
							{
							?>
							<option value=<?php echo $val2['id'];?>><?php echo $val2['name']." Seats: ".$val2['seats'];?></option>
							<?php
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
		 <input type="checkbox" id="category1" name="category1" value="1" > Recursion: 
		 <input type="number" id="points1" name="points1" min="1" max="100" value="20"><br>
		 <input type="checkbox" id="category2" name="category2" value="2" > Linked List: 
		 <input type="number" id="points2" name="points2" min="1" max="100" value="20"><br>
		 <input type="checkbox" id="category3" name="category3" value="3" > General: 
		 <input type="number" id="points3" name="points3" min="1" max="100" value="30"><br>
		 <input type="checkbox" id="category4" name="category4" value="4" > Data Abstraction: 
		 <input type="number" id="points4" name="points4" min="1" max="100" value="30"><br>
		<br>
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
				document.getElementById("category"+id).checked = true;
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
					  <select id="graders1" name="graders1">
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

					  <select id="graders2" name="graders2">
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
					  <select id="graders3" name="graders3">
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
					  <select id="graders4" name="graders4">
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
					  <select id="graders5" name="graders5">
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
					  <select id="graders6" name="graders6">
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
					  <select id="graders7" name="graders7">
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
					  <select id="graders8" name="graders8">
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
			<br><br>
			Exam State:
				<select id="state" name="state">
				<?php
					try
					{
						$db = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
						$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						
						$sql = $db->prepare("select * from exam_state");
						
						$sql->execute();
						
						$res = $sql->fetchAll(PDO::FETCH_ASSOC);
						
						foreach($res as $val)
						{
						
							?>
							<option value=<?php echo $val['id']; ?>><?php echo $val['state'];?></option>
							<?php
						}
					}
					catch(Exception $e)
					{
						echo "SOMETHING WRONG" ."<br>". $e->getMessage();
					}
					
						?>
					</select>
			<br><br>
				<?php	
				try
				{	
					$sql = $db->prepare("select state from exam where id = '$id'");
					
					$sql->execute();
					
					$res = $sql->fetchAll(PDO::FETCH_ASSOC);
					
				}
				catch(Exception $e)
				{
					echo "SOMETHING WRONG" ."<br>". $e->getMessage();
				}
				$db=null;
				?>
			<script>
			$(document).ready(main);
			function main(){
				//console.log("MAIN METHOD");
				var exam = <?php echo json_encode($res);?>;
				var state = exam[0].state;
				console.log(document.getElementById("state").options[0].selected);
				console.log(state);
				document.getElementById("state").options[state-1].selected = true;
				
			}
			</script>
			<input type="submit" value="Save Changes">
			</form>
			<form action = "teacher_home.php">
			<input type="submit" value="Cancel">
			</form>
			</div>
			
		  </fieldset>
	</body>
</html>