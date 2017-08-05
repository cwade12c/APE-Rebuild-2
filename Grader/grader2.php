<html>
<head>
<title>Grading Page</title>
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="examGradeData.js"></script>
</head>

<style>
div{
    text-align:center;
    vertical-align:middle;
	width: 800px;
	margin: 0 auto;
}
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #ff4d4d;
	width: 820px;
	margin: auto;
}

li {
    float: left;
}

li a {
    display: block;
    color: black;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}

li a:hover {
    background-color: white;
}

th, td {
    text-align: left;
	padding: 8px;
}
table, th, td {
    border: 2px solid black;
    border-collapse: collapse;
    text-align: center; 
	margin: 0 auto;
}
</style>

<body bgcolor="#fdf5e6">

<input type="hidden" id="examCatId" value="<?php echo $_POST["assignedId"] ?>"/>
<input type="hidden" id="graderId" value="<?php echo $_POST["graderId"] ?>"/>

<h1 align='center'>APE Grades Page</h1>
<ul>
  <li style='float:right'><a class='active' href='../Ape.html'>Back To Landing Page</a></li>
  <li style='float:right'><a class='active' href='grader1.php'>Home</a></li>
</ul>
<hr>
<div>
	<table id='gradesTable' BORDER='1' style="width:100%;">
		
	</table>
	<br>
	<input type='button' value="Save Grades" id="saveBtn" style='float: Center;'>
	<input type='button' onclick="location.href = 'grader1.html';"  value="Back" style='float: Center;'>
<br>
</div>
</body>
</html>