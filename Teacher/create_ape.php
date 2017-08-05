<?php
include "creds.php";
$quarter = $_POST["quarter"];
$date = $_POST["start_date"];
$time = $_POST["start_time"];
$duration = $_POST["duration"];
$location = $_POST["location"];
$cutoff = $_POST["deadline"];
$pass_rate = $_POST["pass"];
$cat1 = isset($_POST["category1"]);
$cat2 = isset($_POST["category2"]);
$cat3 = isset($_POST["category3"]);
$cat4 = isset($_POST["category4"]);
$points1 = $_POST["points1"];
$points2 = $_POST["points2"];
$points3 = $_POST["points3"];
$points4 = $_POST["points4"];
$grader1 = $_POST["graders1"];
$grader2 = $_POST["graders2"];
$grader3 = $_POST["graders3"];
$grader4 = $_POST["graders4"];
$grader5 = $_POST["graders5"];
$grader6 = $_POST["graders6"];
$grader7 = $_POST["graders7"];
$grader8 = $_POST["graders8"];

$AMPM = substr($time, strlen($time)-2, strlen($time));
$time = substr($time, 0, strlen($time)-2);
$hour = substr($time, 0, strlen($time)-3);
$minute = substr($time, strlen($time)-2, strlen($time));
if($AMPM === "pm" AND $hour != 12)
{
	$hour = $hour+12;
	$time = $hour.":".$minute;
}
try
{
	$db = new PDO("mysql:host=$server;dbname=$dbname", $user, $pass);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$sql = $db->prepare("insert into exam(quarter, date, location, state, passing_grade, duration, start_time, cutoff)
						values ('$quarter','$date','$location','1','$pass_rate','$duration','$time','$cutoff');");
	
	$sql->execute();
	
	$id = $db->lastInsertId();
	$sql = $db->prepare("insert into in_class_exams (exam_id, teacher_id) values ('$id', 3586832)");
	$sql->execute();
	
	if(isset($_POST["category1"]))
	{
		$cat1 = $_POST["category1"];
		$sql = $db->prepare("insert into exam_category(category_id, exam_id, possible) 
						values('$cat1', '$id', '$points1')");
		$sql->execute();
		$tempId = $db->lastInsertId();
		$sql = $db->prepare("insert into assigned_graders(exam_cat_id, grader_id)
							values('$tempId', '$grader1'),
								('$tempId', '$grader2')");
		$sql->execute();
	}
	if(isset($_POST["category2"]))
	{	
		$cat2 = $_POST["category2"];
		$sql = $db->prepare("insert into exam_category(category_id, exam_id, possible) 
						values('$cat2', '$id', '$points2')");
		$sql->execute();
		$tempId = $db->lastInsertId();
		$sql = $db->prepare("insert into assigned_graders(exam_cat_id, grader_id)
							values('$tempId', '$grader3'),
								('$tempId', '$grader4')");
		$sql->execute();
	}
	if(isset($_POST["category3"]))
	{
		$cat3 = $_POST["category3"];
		$sql = $db->prepare("insert into exam_category(category_id, exam_id, possible) 
						values('$cat3', '$id', '$points3')");
		$sql->execute();
		$tempId = $db->lastInsertId();
		$sql = $db->prepare("insert into assigned_graders(exam_cat_id, grader_id)
							values('$tempId', '$grader5'),
								('$tempId', '$grader6')");
		$sql->execute();
	}
	if(isset($_POST["category4"]))
	{
		$cat4 = $_POST["category4"];
		$sql = $db->prepare("insert into exam_category(category_id, exam_id, possible) 
						values('$cat4', '$id', '$points4')");
		$sql->execute();
		$tempId = $db->lastInsertId();
		$sql = $db->prepare("insert into assigned_graders(exam_cat_id, grader_id)
							values('$tempId', '$grader7'),
								('$tempId', '$grader8')");
		$sql->execute();
	}

}
catch(Exception $e)
{
	echo "SOMETHING WRONG" ."<br>". $e->getMessage();
}

$db = null;
header("Location: teacher_home.php");
exit;

?>