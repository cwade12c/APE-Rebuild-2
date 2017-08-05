<?php
include "creds.php";
$report = $_POST["reportName"];

try
{
	$db = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$sql = $db->prepare("insert into report(name)
						values ('$report');");
	
	$sql->execute();
	$id = $db->lastInsertId();
	if(isset($_POST["ewuid"]))
	{
		$sql = $db->prepare("insert into report_rows(report_id, type_id)
							values ('$id', 1);");
		$sql->execute();
	}
	if(isset($_POST["first_name"]))
	{
		$sql = $db->prepare("insert into report_rows(report_id, type_id)
							values ('$id', 2);");
							
		$sql->execute();
	}
	if(isset($_POST["last_name"]))
	{
		$sql = $db->prepare("insert into report_rows(report_id, type_id)
							values ('$id', 3);");
		$sql->execute();
	}
	if(isset($_POST["email"]))
	{
		$sql = $db->prepare("insert into report_rows(report_id, type_id)
							values ('$id', 4);");
		$sql->execute();
	}
	if(isset($_POST["pass_fail"]))
	{
		$sql = $db->prepare("insert into report_rows(report_id, type_id)
							values ('$id', 5);");
		$sql->execute();
	}
	if(isset($_POST["score"]))
	{
		$sql = $db->prepare("insert into report_rows(report_id, type_id)
							values ('$id', 6);");
		$sql->execute();
	}
	


}
catch(Exception $e)
{
	echo "SOMETHING WRONG" ."<br>". $e->getMessage();
}

$db = null;
header("Location: admin_create_report.php");
exit;

?>