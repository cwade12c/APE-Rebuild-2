<DOCTYPE html>
<?php
	try
	{
		require_once('../config.php');
		
		$id = $_POST['id'];
	}
	
	catch(PDOException $e)
	{
		echo "Something went wrong: " . $e->getMessage();
	}
	?>