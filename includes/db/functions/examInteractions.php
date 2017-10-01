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
function registerStudent(int $examID, string $studentID)
{
    validateRegistration($examID, $studentID);

    // register
    // assign seat
    // TODO: populate
}

/**
 * Deregister from an exam
 *
 * @param int    $examID    Exam ID
 * @param string $studentID Student ID
 */
function deregisterStudent(int $examID, string $studentID)
{
    validateDeregistration($examID, $studentID);

    // delete registration
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
    // TODO: populate
    return 0;
}

/**
 * Set registration state of a student
 *
 * @param string $studentID Student ID
 * @param int    $state     Registration state
 */
function setRegistrationState(string $studentID, int $state)
{
    // TODO: populate
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
function assignSeat(int $examID, string $studentID)
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
 * @param int $examID   Exam ID
 */
function resetSeatAssignments(int $examID)
{
    // get all currently registered students
    // un-assign all seating
    // assign all seating
    // transaction may be necessary for all queries
    // TODO: populate
}
