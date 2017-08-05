<DOCTYPE html>
<?php
	try
	{
		include "creds.php";
		
		$id = $_POST['id'];
	}
	
	catch(PDOException $e)
	{
		echo "Something went wrong: " . $e->getMessage();
	}
	?>