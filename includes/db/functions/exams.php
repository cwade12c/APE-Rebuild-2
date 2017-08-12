<?php
/**
 * Functions for exams in database
 *
 * @author		Mathew McCain
 * @author      Curran Higgins
 * @category	APE
 * @package		APE_includes
 * @subpackage	Database
 */

// refresh/check exam state
// set exam state
// finalize exam

// create in class exam
// assign teacher(s)
// get teacher
// search exams by in class/teacher
// get non-archived exams for teacher



// set location for exam
// get location for exam
// update location for exam
/// check if seat count difference will cause an issue (block, return false)
/// randomize rooms/seats

// updating exam information
/// date/times, passing grade

// get all exams
function getAllExams() {
    return getExams();
}

// get all archived exams
function getArchivedExams() {}

// get all non-archived exams
function getNonArchivedExams() {}

// get all finalizing exams
function getFinalizingExams() {}

// get all grading exams

// get all upcoming exams

// get exams by state
function getExams(int $state = 0, bool $inClass = false) {}

// in class flag ?

// search exams
/// state, date/time (quarter?), in class

// get exam information
function getExamInformation(int $id) {}

// create exam
function createExam() {}

// update exam

// update exam location
/// re-assign seats

// add/remove category for exam
/// check state, check for assigned graders
// get categories for exam















// TODO: not integral to db backend, move to another set
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

// TODO: not integral to db backend, move to another set
function displayExam($exam)
{

    $id = $exam["location_id"];

    $sql = executeQuery("SELECT * FROM locations WHERE (`id` = :id);", array(array("id", $id)));
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