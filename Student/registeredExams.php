<?php
	include 'creds.php';
	
	echo '<table border="1" id="apes">';
	echo '<tr><th>Quarter</th><th>Date</th><th>Location</th><th>Duration</th><th>Actions</th></tr>';
	try {
		$idStudent = '692364';// this is the student currently logged in
		
		$stmt = $conn->prepare("SELECT exam_id FROM exam_roster WHERE student_id=".$idStudent.""); 
		$stmt->execute();
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
		
		while($row = $stmt->fetch())
		{		
			$examID = $row['exam_id'];
			
			$examStat = $conn->prepare("SELECT id, quarter, date, location, state, passing_grade, duration FROM exam WHERE id='".$examID."'");
			$examStat->execute();
			$examRes = $examStat->setFetchMode(PDO::FETCH_ASSOC); 
			$exam = $examStat->fetch();
			
			$locstat = $conn->prepare("SELECT name FROM location WHERE id='".$exam['location']."'"); 
			$locstat->execute();
			$location = $locstat->setFetchMode(PDO::FETCH_ASSOC); 
			$loc = $locstat->fetch()['name'];

			$quartstat = $conn->prepare("SELECT name FROM quarter WHERE id='".$exam['quarter']."'"); 
			$quartstat->execute();
			$quarter = $quartstat->setFetchMode(PDO::FETCH_ASSOC); 
			$quart = $quartstat->fetch()['name'];			?>
			<tr align="Center">
				<td>
					<?php echo $quart; ?>
				</td>
				<td>
					<?php echo $exam['date']; ?>
				</td>
				<td>
					<?php echo $loc; ?>
				</td>
				<td>
					<?php echo $exam['duration']." hours";
						  $id = $exam['id']; ?>
				</td>
				
				<form action = 'register.php' method = 'post'>
					<td>
						<input type='submit' value='Register' name='RegisterButton' >
						<input type="hidden" name="id" value="<?php echo $id;?>">
						<input type="hidden" name="student" value="<?php echo $idStudent;?>">
					</td>
				</form>
			</tr>
				<?php
		}
		}catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
	
?>