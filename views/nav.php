<?php

function displayAdminNavigation()
{
    echo "<a href=\"create_ape.php\">Create APE</a>";
    echo "<a href=\"archived.php\">View Archived APEs</a>";
    echo "<a href=\"users.php\">Manage Users</a>";
    echo "<a href=\"locations.php\">Locations</a>";
    echo "<a href=\"reports.php\">Reports</a>";
}

function displayTeacherNavigation()
{
    echo "<a href=\"create_ape.php\">Create APE</a>";
    echo "<a href=\"archived.php\">View Archived APEs</a>";
    echo "<a href=\"reports.php\">Reports</a>";
}

?>

<nav>
    <a href="home.php">Home</a>
    <? if (accountIsAdmin($_SESSION['ewuId']) || DEBUG) {
        displayAdminNavigation();
    } ?>
    <? if (accountIsTeacher($_SESSION['ewuId']) || DEBUG) {
        displayTeacherNavigation()();
    } ?>
    <a href="logout.php">Logout</a>
</nav>