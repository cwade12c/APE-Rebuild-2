<html>
<head>
<style>
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #EF0F0F;
}

li {
    float: left;
}

li a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}

li a:hover:not(.active) {
    background-color: #A90A0A;
}

.active {
    background-color: #4CAF50;
}
</style>
</head>
	<body>
		<div id="NavBar">
			<ul>
				<li><a href="admin_home.php">APE Home</a></li>
				<li><a href="admin_create_ape.php">Create APE</a></li>
				<li><a href="admin_view_archived.php">View Archived APEs</a></li>
				<li><a href="admin_users.php">Users</a></li>
				<li><a href="admin_create_users.php">Create Users</a></li>
				<li><a href="admin_location.php">Locations</a></li>				
				<li><a href="admin_reports.php">Generate Report</a></li>
				<!--<li><a href="admin_create_report.php">Create Report Type</a></li>-->
			<ul>
		</div>
	</body>
</html>