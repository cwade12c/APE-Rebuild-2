<?php
/**
 * Functions for interacting with exams in database
 * e.g. assigning seats, getting registered students, registering students
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Database
 */

/**
 * Register student for an exam
 *
 * @param int    $examID    Exam ID
 * @param string $studentID Student ID
 */
function registerStudentForExam(int $examID, string $studentID)
{
    // validate info
    validateStudentID($studentID);
    validateRegistrationStateIs($studentID, STUDENT_STATE_REGISTERED);
    validateExamAllowsRegistration($examID);

    // register, assign seat
    registerStudentForExamQuery($examID, $studentID);
    assignExamSeat($examID, $studentID);
}

/**
 * Deregister from an exam
 *
 * @param int    $examID    Exam ID
 * @param string $studentID Student ID
 */
function deregisterStudentFromExam(int $examID, string $studentID)
{
    // validate info
    validateStudentID($studentID);
    validateRegistrationStateIs($studentID, STUDENT_STATE_REGISTERED);
    validateExamAllowsRegistration($examID);

    deregisterStudentFromExamQuery($examID, $studentID);
}

/**
 * Get registration state of a student
 *
 * @param string $studentID Student ID
 *
 * @return int              Registration state
 */
function getRegistrationState(string $studentID)
{
    validateStudentID($studentID);

    return getRegistrationStateQuery($studentID);
}

/**
 * Set registration state of a student
 *
 * @param string $studentID Student ID
 * @param int    $state     Registration state
 */
function setRegistrationState(string $studentID, int $state)
{
    validateStudentID($studentID);
    validateRegistrationState($state);

    setRegistrationStateQuery($studentID, $state);
}

/**
 * Get the number of open seats for an exam
 *
 * @param int $examID Exam ID
 *
 * @return int        Amount of open seats
 */
function getOpenSeatCount(int $examID)
{
    // TODO: populate
    return 0;
}

/**
 * Get the total seat count for an exam
 *
 * @param int $examID Exam ID
 *
 * @return int        total seat count
 */
function getTotalExamSeatCount(int $examID)
{
    // TODO: populate
    return 0;
}

/**
 * Get the total seat count for a location
 *
 * @param int $locationID Location ID
 *
 * @return int            total seat count
 */
function getTotalLocationSeatCount(int $locationID)
{
    // TODO: populate
    return 0;
}

// assign student to room/seat
// randomly assign all students for given exam (room, seat)
// get students registered
// get all assigned seats

/**
 * Check if student is assigned a seat
 *
 * @param int    $examID    Exam ID
 * @param string $studentID Student ID
 *
 * @return bool             If student is assigned a seat
 */
function seatAssigned(int $examID, string $studentID)
{
    // check if student is registered for an exam
    // check if student is assigned a room/seat
    // TODO: populate
    return false;
}

/**
 * Assign a student to a seat in an exam
 *
 * @param int    $examID    Exam ID
 * @param string $studentID Student ID
 */
function assignExamSeat(int $examID, string $studentID)
{
    // check open seat count
    // randomly select a room
    //
    // randomly select a seat
    // if filled, redo seat
    //
    // transaction may be necessary
    // TODO: populate, figure out efficient way
}

/**
 * Undo seat assignment for student/exam
 *
 * @param int    $examID    Exam ID
 * @param string $studentID Student ID
 */
function deassignSeat(int $examID, string $studentID)
{
    // TODO: populate
}

/**
 * Reset seat assignments for an exam
 *
 * @param int $examID Exam ID
 */
function resetSeatAssignments(int $examID)
{
    // get all currently registered students
    // un-assign all seating
    // assign all seating
    // transaction may be necessary for all queries
    // TODO: populate
}

/**
 * Attempt to find an open seat for an exam
 * Not intended for outside use
 * Only finds an open seat, does not assign
 *
 * @param int $examID Exam ID
 *
 * @return array      The seating found as an array
 *                      'id' => room ID
 *                      'seat' => seat #
 *
 * @throws InvalidArgumentException When no open seat is available
 */
function findRandomOpenSeat(int $examID)
{
    // get exam information and rooms
    $examInfo = getExamInformation($examID);
    $examLocationID = $examInfo['location_id'];
    $examRooms = getLocationRooms($examLocationID);

    $openRooms = array();

    // search for rooms w/ space
    foreach ($examRooms as $room) {
        // get all student seating
        $seating = getExamRoomSeating($examID, $room['id']);
        // check for an open seat
        if (count($seating) >= $room['seats']) {
            continue;
        }
        array_push($openRooms, $room);
    }

    // select random room, random seat
    $room = array_rand($openRooms);
    $openSeats = getOpenSeatNumbers($room, $room['seats']);
    $seat = array_rand($openSeats);

    return array('id' => $room['id'], 'seat' => $seat);
}

/**
 * Helper function for various exam interactions functions
 * Not intended for outside use
 * Get an array of open seat numbers
 *
 * @param array $seating    Array of seating, element format
 *                          'student_id'/'id' => student ID
 *                          'seat' => seat number
 * @param int   $max        Max number of seats
 *
 * @return array            Array of open seat numbers, sorted
 */
function getOpenSeatNumbers(array $seating, int $max)
{
    // determine seat numbers used
    $seatNumbers = array();
    foreach ($seating as $seat) {
        array_push($seatNumbers, $seat['seat']);
    }

    // determine open seats
    $openSeatNumbers = array();
    for($i = 1; $i <= $max; $i++) {
        if (!in_array($i, $seatNumbers)) {
            array_push($openSeatNumbers, $i);
        }
    }

    return $openSeatNumbers;
}

// TODO: function to check for duplicate seating ?

/**
 * Find any exam seating that is invalid
 * Which means room_id=null or seat=0
 * This function would just serve as a redundancy/error catcher
 * TODO: determine if should attempt seating assignments
 *
 * @param int $examID
 */
function checkForInvalidSeating(int $examID)
{
    // TODO: populate
}