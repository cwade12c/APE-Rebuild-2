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
 * Query to get the list exam IDs the student is registered for
 *
 * @param string $studentID Student ID
 *
 * @return array            List of exam IDs
 */
function getExamsRegisteredForQuery(string $studentID)
{
    $query = "SELECT `exam_id` FROM `exam_roster` "
        . " WHERE `student_id`=:id";
    $sql = executeQuery(
        $query, array(array(':id', $studentID, PDO::PARAM_STR))
    );
    return getQueryResults($sql);
}

/**
 * Query to pull all registrations on the exam roster
 *
 * @param int $examID Exam ID
 *
 * @return array      Array of registrations, element format
 *                        'student_id' => student ID
 *                        'room_id' => room ID
 *                        'seat' => room seat
 *                    NOTE: if room_id=null or seat=0
 *                          then no seat is set
 */
function getAllExamRegistrationsQuery(int $examID)
{
    $query = "SELECT `student_id`, `room_id`, `seat` FROM `exam_roster` "
        . " WHERE `exam_id`=:id";
    $sql = executeQuery(
        $query, array(array(':id', $examID, PDO::PARAM_INT))
    );
    return getQueryResults($sql);
}

/**
 * Check if student registered for a given exam
 *
 * @param int    $examID    Exam ID
 * @param string $studentID Student ID
 *
 * @return bool             If student is registered for
 */
function isStudentRegisteredForQuery(int $examID, string $studentID)
{
    $query = "SELECT (:studentID IN ( "
        . "SELECT `student_id` FROM `exam_roster` WHERE `exam_id` = :examID"
        . " )) AS `registered_for`";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':studentID', $studentID, PDO::PARAM_STR))
    );

    return getQueryResult($sql);
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
    $query = "SELECT `state` FROM `student_registrations` "
        . " WHERE `id` = :studentID";
    $sql = executeQuery(
        $query, array(array(':studentID', $studentID, PDO::PARAM_STR))
    );

    return getQueryResult($sql);
}

/**
 * Query to set a student's registration state
 *
 * @param string $studentID Student ID
 * @param int    $state     Registration state
 */
function setRegistrationStateQuery(string $studentID, int $state)
{
    $query = "INSERT INTO `student_registrations`(`id`,`state`) "
        . " VALUES (:studentID, :state) "
        . " ON DUPLICATE KEY UPDATE `state`=:state";
    $sql = executeQuery(
        $query, array(array(':studentID', $studentID, PDO::PARAM_STR),
                      array(':state', $state, PDO::PARAM_INT))
    );
}

/**
 * Query to register a student for an exam
 *
 * @param int    $examID    Exam ID
 * @param string $studentID Student ID
 */
function registerStudentForExamQuery(int $examID, string $studentID)
{
    $query = "INSERT INTO `exam_roster`(`exam_id`,`student_id`) "
        . " VALUES (:examID, :studentID) ";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':studentID', $studentID, PDO::PARAM_STR))
    );
}

/**
 * Query to remove registration of student from an exam
 *
 * @param int    $examID    Exam ID
 * @param string $studentID Student ID
 */
function deregisterStudentFromExamQuery(int $examID, string $studentID)
{
    $query = "DELETE FROM `exam_roster` "
        . " WHERE `exam_id` = :examID AND `student_id` = :studentID";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':studentID', $studentID, PDO::PARAM_STR))
    );
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
 */
function getAssignedSeatQuery(string $studentID, int $examID)
{
    $query = "SELECT `room_id`, `seat` FROM `exam_roster` "
        . " WHERE `exam_id` = :examID AND `student_id` = :studentID";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':studentID', $studentID, PDO::PARAM_STR))
    );

    return getQueryResultRow($sql);
}

/**
 * Query to set the registration seating info
 * Assumes registration exists already
 *
 * @param string $studentID Student ID
 * @param int    $examID    Exam ID
 * @param int    $roomID    Room ID
 * @param int    $seat      Seat
 */
function setAssignedSeatQuery(string $studentID, int $examID, int $roomID,
    int $seat
) {
    $query = "UPDATE `exam_roster` "
        . " SET `room_id` = :roomID, `seat` = :seat "
        . " WHERE `exam_id` = :examID AND `student_id` = :studentID ";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':studentID', $studentID, PDO::PARAM_STR),
                      array(':roomID', $roomID, PDO::PARAM_INT),
                      array(':seat', $seat, PDO::PARAM_INT))
    );
}
