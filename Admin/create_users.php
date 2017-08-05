<?php
include "creds.php";
$lname = $_POST["lname"];
$fname = $_POST["fname"];
$id = $_POST["EWUID"];
$email = $_POST["email"];
$type = $_POST["account_types"];
try
{
	$db = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	if($type === "student")
	{
		$sql = $db->prepare("insert into student(EWU_ID, l_name, f_name, email, state)
							values('$id','$lname','$fname','$email', 'ready');");
		
		$sql->execute();
	}
	else
	{
		$sql = $db->prepare("insert into $type(EWU_ID, email, f_name, l_name)
							values ('$id','$email','$fname','$lname');");
		
		$sql->execute();
	}


}
catch(Exception $e)
{
	echo "SOMETHING WRONG" ."<br>". $e->getMessage();
}

$db = null;
header("Location: admin_create_users.php");
exit;

?>