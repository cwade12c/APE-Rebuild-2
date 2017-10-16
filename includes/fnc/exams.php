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

class UpcomingExam
{
    private $start;
    private $location;
    private $reservedSeats;
    private $limitedSeats;
    private $passingGrade;

    public function __construct( $start, $location, $reserved, $limited, $passing )
    {
        $this->start = $start;
        $this->location = $location;
        $this->reservedSeats = $reserved;
        $this->limitedSeats = $limited;
        $this->passingGrade = $passing;
    }

    public function getStart()
    {
        return $this->start;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function getReservedSeats()
    {
        return $this->reservedSeats;
    }

    public function getLimitedSeats()
    {
        return $this->limitedSeats;
    }

    public function getPassingGrade()
    {
        return $this->passingGrade;
    }
}

/**
 * @author  Curran Higgins
 */
function getUpcomingExams()
{
    $sql = "SELECT * FROM `exams` WHERE `state` = :state";
    $query = executeQuery($sql, array(
        array(":state", EXAM_STATE_OPEN)
    ));
    $results = getQueryResults($query);

    $currentDate  = date('Y-m-d G:i:s');
    $currentDate  = date_create($currentDate);
    $upcomingApes = array();
    $upcomingApesWithFullDetails = array();

    foreach ($results as &$value) {
        $examDate         = date_create($value["start"]);
        $differenceInTime = date_diff($examDate, $currentDate);

        if ($differenceInTime->invert > 0) {
            array_push($upcomingApes, $value);
        }
    }

    if (count($upcomingApes) > 0) {
        foreach ($upcomingApes as &$value) {
            $id = $value["location_id"];

            $sql    = executeQuery(
                "SELECT * FROM locations WHERE (`id` = :id);", array(array("id", $id))
            );
            $result = getQueryResults($sql);
            $result = $result[0];

            $examStart         = $value["start"];
            $examLocation      = $result["name"];
            $examReservedSeats = $result["reserved_seats"];
            $examLimitedSeats  = $result["limited_seats"];
            $examPassingGrade  = $value["passing_grade"];

            $exam = new UpcomingExam($examStart, $examLocation, $examReservedSeats, $examLimitedSeats, $examPassingGrade);
            array_push($upcomingApesWithFullDetails, $exam);
        }
    }

    return $upcomingApesWithFullDetails;

/*    if (count($upcomingApes) > 0) {
        foreach ($upcomingApes as &$value) {
            displayExam($value);
        }
    }*/
}

/**
 * @author  Curran Higgins
 *
 * @param $exam
 */
function displayExam($exam)
{

    $id = $exam["location_id"];

    $sql    = executeQuery(
        "SELECT * FROM locations WHERE (`id` = :id);", array(array("id", $id))
    );
    $result = getQueryResults($sql);
    $result = $result[0];

    $examStart         = $exam["start"];
    $examLocation      = $result["name"];
    $examReservedSeats = $result["reserved_seats"];
    $examLimitedSeats  = $result["limited_seats"];
    $examPassingGrade  = $exam["passing_grade"];


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