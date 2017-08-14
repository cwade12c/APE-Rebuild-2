<?php
/**
 * Internal functions for exams
 * TODO: determine where to place exactly
 *
 * @author         Mathew McCain
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Exams
 */

/**
 * @author  Curran Higgins
 */
function getUpcomingExams()
{
    $sql = executeQuery("SELECT * FROM exams");
    $result = getQueryResults($sql);

    $currentDate = date('Y-m-d G:i:s');
    $currentDate = date_create($currentDate);
    $upcomingApes = array();

    foreach ($result as &$value) {
        $examDate = date_create($value["start"]);
        $differenceInTime = date_diff($examDate, $currentDate);

        if ($differenceInTime->invert > 0) {
            array_push($upcomingApes, $value);
        }
    }

    if (count($upcomingApes) > 0) {
        foreach ($upcomingApes as &$value) {
            displayExam($value);
        }
    }
}

/**
 * @author  Curran Higgins
 *
 * @param $exam
 */
function displayExam($exam)
{

    $id = $exam["location_id"];

    $sql = executeQuery(
        "SELECT * FROM locations WHERE (`id` = :id);", array(array("id", $id))
    );
    $result = getQueryResults($sql);
    $result = $result[0];

    $examStart = $exam["start"];
    $examLocation = $result["name"];
    $examReservedSeats = $result["reserved_seats"];
    $examLimitedSeats = $result["limited_seats"];
    $examPassingGrade = $exam["passing_grade"];


    echo <<< EOT
    
<div>
    <h2>Exam: $examStart</h2>
    <p><span style='font-weight:bold;'>Location:</span> $examLocation</p>
    <p><span style='font-weight:bold;'>Available Seats:</span> $examLimitedSeats</p>
    <p><span style='font-weight:bold;'>Reserved Seats:</span> $examReservedSeats</p>
    <p><span style='font-weight:bold;'>Passing Grade:</span> $examPassingGrade</p>
</div>
<hr />
EOT;
}