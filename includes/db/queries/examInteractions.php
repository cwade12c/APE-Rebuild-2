<?php
/**
 * Query functions for exam interactions
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Database
 */

/**
 * Get registration info for a student and exam
 *
 * @param string $studentID Student ID
 * @param int    $examID    Exam ID
 *
 * @return array            Registration information, format
 *                          'room
 */
function getExamRegistrationInfoQuery(string $studentID, int $examID)
{
    // TODO: populate
    return array();
}

/**
 * Query to get the list exam IDs the student is registered for
 *
 * @param string $studentID Student ID
 *
 * @return array            List of exam IDs
 */
function getExamsRegisteredForQuery(string $studentID)
{
    // TODO: populate
    return array();
}

/**
 * Query to get a student's registration state
 *
 * @param string $studentID Student ID
 *
 * @return int              Registration state
 */
function getRegistrationStateQuery(string $studentID)
{
    // TODO: populate
    return 0;
}

/**
 * Query to set a student's registration state
 *
 * @param string $studentID Student ID
 * @param int    $state     Registration state
 */
function setRegistrationStateQuery(string $studentID, int $state)
{
    // TODO: populate
}

/**
 * Query to get the assigned seating for an exam registration
 *
 * @param string $studentID Student ID
 * @param int    $examID    Exam ID
 *
 * @return array            Associative array w/ seating information
 *                          'room_id' => room ID
 *                          'seat' => seat number
 *                          if room_id=null or seat=0, no seat assigned
 */
function getAssignedSeatQuery(string $studentID, int $examID)
{
    // TODO: populate
    return array();
}

/**
 * Query to register a student for an exam
 *
 * @param int    $examID    Exam ID
 * @param string $studentID Student ID
 */
function registerStudentForExamQuery(int $examID, string $studentID)
{
    // TODO: populate
}

/**
 * Query to remove registration of student from an exam
 *
 * @param int    $examID    Exam ID
 * @param string $studentID Student ID
 */
function deregisterStudentFromExamQuery(int $examID, string $studentID)
{
    // TODO: populate
}

/**
 * Query to pull all registrations on the exam roster
 *
 * @param int $examID Exam ID
 *
 * @return array        Array of registrations, element format
 *                          'student_id' => student ID
 *                          'room_id' => room ID
 *                          'seat' => room seat
 *                      NOTE: if room_id=null or seat=0
 *                            then no seat is set
 */
function getAllExamRegistrationsQuery(int $examID)
{
    // TODO: populate
    return array();
}

/**
 * Query to get all registrations for an exam ID
 * w/ seat assignments in a given room
 *
 * @param int $examID Exam ID
 * @param int $roomID Room ID
 *
 * @return array        Array of registrations, element format
 *                          'student_id' => student ID
 *                          'seat' => room seat number
 *                      NOTE:   if seat=0, then no seat is assigned
 *                              should be assumed to be an error
 */
function getExamRoomRegistrationsQuery(int $examID, int $roomID)
{
    // TODO: populate
    return array();
}