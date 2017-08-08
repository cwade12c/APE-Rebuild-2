<?php

require_once('../config.php');
enforceAuthentication();

?>

<html>
<head>
<title>Home Page</title>
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="graderData.js"></script>
</head>

<style>
legend {
    display: block;
    padding-left: 2px;
    padding-right: 2px;
    border: none;
	font-size: 150%;
}
fieldset{
  border-style: solid;
  text-align: left;
  display: inline-block;
  width: 800px;
  vertical-align:middle;
}
div{
    text-align:center;
    vertical-align:middle;
}
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
		background-color: #F00;
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
}
table, th, td {
}
</style>

<body bgcolor="#fdf5e6">

<h1 align="center">Grader Home Page</h1>
<ul>
  <li style='float:right'><a class='active' href='grader1.php'>Home</a></li>
</ul>
<hr>
<div>
<fieldset style='border-color:#F00;'>
	<legend style='color:red;'> Important Messages </legend>
	You have <#> of APEs assigned to you. <br>
	Messages from Admin or assigner.<br>
</fieldset>
</div>
<hr>
<div>
<fieldset>
	<legend>APEs to Grade</legend>
	<p>Will be sorted by date completed. (Oldest to newest)</p>
	<p>Option to sort differently.</p>
	<div>
	<table id='tbgTable' BORDER='1' style="width:100%;">
								
	</table>
	</div>
			
			
			
</fieldset>
<br>
</div>
</body>
</html> 