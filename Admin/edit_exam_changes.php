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
$state = $_POST["state"];
$id = $_POST["id"];

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
	
	$sql = $db->prepare("update exam
						set quarter='$quarter', date='$date',
						location='$location', state='$state',
						passing_grade='$pass_rate', duration='$duration',
						start_time='$time', cutoff='$cutoff'
						where id='$id';");
	
	$sql->execute();
	//$id = $db->lastInsertId();
	if(isset($_POST["category1"]))
	{
		$cat1 = $_POST["category1"];
		$catId = $_POST["catId1"];
		$sql = $db->prepare("update exam_category
							set category_id='$cat1', exam_id='$id',
							possible='$points1'
							where exam_category_id = '$catId'");
		$sql->execute();
		$tempId1 = $_POST['assigned1'];
		$tempId2 = $_POST['assigned2'];
		$sql = $db->prepare("update assigned_graders
							set exam_cat_id='$catId', grader_id='$grader1'
							where assigned_exam_id = '$tempId1'");
		
		$sql->execute();
		
		$sql = $db->prepare("update assigned_graders
							set exam_cat_id='$catId', grader_id='$grader2'
							where assigned_exam_id = '$tempId2'");
		
		$sql->execute();
	}
	if(isset($_POST["category2"]))
	{	
		$cat1 = $_POST["category2"];
		$catId = $_POST["catId2"];
		$sql = $db->prepare("update exam_category
							set category_id='$cat1', exam_id='$id',
							possible='$points2'
							where exam_category_id = '$catId'");
		$sql->execute();
		$tempId1 = $_POST['assigned3'];
		$tempId2 = $_POST['assigned4'];
		$sql = $db->prepare("update assigned_graders
							set exam_cat_id='$catId', grader_id='$grader3'
							where assigned_exam_id = '$tempId1'");
		
		$sql->execute();
		
		$sql = $db->prepare("update assigned_graders
							set exam_cat_id='$catId', grader_id='$grader4'
							where assigned_exam_id = '$tempId2'");
		
		$sql->execute();
	}
	if(isset($_POST["category3"]))
	{
		$cat1 = $_POST["category3"];
		$catId = $_POST["catId3"];
		$sql = $db->prepare("update exam_category
							set category_id='$cat1', exam_id='$id',
							possible='$points3'
							where exam_category_id = '$catId'");
		$sql->execute();
		$tempId1 = $_POST['assigned5'];
		$tempId2 = $_POST['assigned6'];
		$sql = $db->prepare("update assigned_graders
							set exam_cat_id='$catId', grader_id='$grader5'
							where assigned_exam_id = '$tempId1'");
		
		$sql->execute();
		
		$sql = $db->prepare("update assigned_graders
							set exam_cat_id='$catId', grader_id='$grader6'
							where assigned_exam_id = '$tempId2'");
		
		$sql->execute();
	}
	if(isset($_POST["category4"]))
	{
		$cat1 = $_POST["category4"];
		$catId = $_POST["catId4"];
		$sql = $db->prepare("update exam_category
							set category_id='$cat1', exam_id='$id',
							possible='$points4'
							where exam_category_id = '$catId'");
		$sql->execute();
		$tempId1 = $_POST['assigned7'];
		$tempId2 = $_POST['assigned8'];
		$sql = $db->prepare("update assigned_graders
							set exam_cat_id='$catId', grader_id='$grader7'
							where assigned_exam_id = '$tempId1'");
		
		$sql->execute();
		
		$sql = $db->prepare("update assigned_graders
							set exam_cat_id='$catId', grader_id='$grader8'
							where assigned_exam_id = '$tempId2'");
		
		$sql->execute();
	}

}
catch(Exception $e)
{
	echo "SOMETHING WRONG" ."<br>". $e->getMessage();
}

$db = null;
header("Location: admin_home.php");
exit;

?>