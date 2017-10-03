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
 * Gets a list of exam IDs the student is registered for.
 *
 * @param string $studentID Student ID
 *
 * @return array            List of exam IDs
 */
function getExamsRegisteredFor(string $studentID)
{
    // validate info
    validateStudentID($studentID);

    return getExamsRegisteredForQuery($studentID);
}

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

    // find open seat

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
    validateStudentIsRegisteredFor($studentID, $examID);

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
    // validate info
    validateStudentID($studentID);
    validateRegistrationState($state);

    setRegistrationStateQuery($studentID, $state);
}

/**
 * Get seating information for a student, exam registration
 *
 * @param string $studentID Student ID
 * @param int    $examID    Exam ID
 *
 * @return array            associate array, format
 *                          'room_id' => room id
 *                          'seat' => seat number
 *                          if room_id=null or seat=0, no seat assigned
 */
function getAssignedSeat(string $studentID, int $examID)
{
    // validate info
    validateExamID($examID);
    validateStudentIsRegisteredFor($studentID, $examID);

    return getAssignedSeatQuery($studentID, $examID);
}

/**
 * Get the number of open seats for an exam
 * Takes into account reserved seats and limited seats
 *
 * @param int $examID Exam ID
 *
 * @return int        Amount of open seats
 */
function getOpenSeatCount(int $examID)
{
    // get max room seats
    // get reserved seat count
    // get amount of open/assigned seats
    // return (max - reserved - assigned)
    // TODO: populate
    return 0;
}


function getAssignedSeatCount(int $examID)
{
    // validate info
    validateExamID($examID);

    $assignedSeats = array();
    return count($assignedSeats);
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
    $examInfo = getExamInformation($examID);
    return getLocationRoomsMaxSeats($examInfo['location_id']);
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
 * Get all seating for an exam/room
 *
 * @param int $examID   Exam ID
 * @param int $roomID   Room ID
 *
 * @return array        Array of seating in format
 *                      'student_id' => student id
 *                      'seat' => seat number
 */
function getExamRoomSeating(int $examID, int $roomID)
{
    // validate
    // query
    // TODO: populate
    return array();
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

    $openRoomsSeating = array();

    // search for rooms w/ space
    foreach ($examRooms as $room) {
        // get all student seating
        $seating = getExamRoomSeating($examID, $room['id']);
        // check for an open seat
        if (count($seating) >= $room['seats']) {
            continue;
        }
        array_push(
            $openRoomsSeating, array('room' => $room, 'seating' => $seating)
        );
    }

    // select random room, random seat
    $roomAndSeating = array_rand($openRoomsSeating);
    $room = $roomAndSeating['room'];
    $openSeats = getOpenSeatNumbers($roomAndSeating['seating'], $room['seats']);
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
    for ($i = 1; $i <= $max; $i++) {
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