<?php

require_once('config.php');

if (userIsLoggedIn()) {
    redirect('home.php');
}

echo "<nav><a style='float:right;' href='login.php'>Login</a></nav><br><hr />";

getUpcomingExams();

?>