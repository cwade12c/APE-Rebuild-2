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

/*
 * TODO: validate exam state allows registration/seating
 *      for relevant functions
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
    validateStudentID($studentID);

    $results = getExamsRegisteredForQuery($studentID);
    $exams = array_map(
        function ($row) {
            return $row['exam_id'];
        }, $results
    );

    return $exams;
}

/**
 * Get list of student IDs registered for an exam
 *
 * @param int $examID Exam ID
 *
 * @return array      List of student IDs
 */
function getExamRegistrations(int $examID)
{
    validateExamID($examID);

    $results = getAllExamRegistrationsQuery($examID);
    $studentIDs = array_map(
        function ($row) {
            return $row['student_id'];
        }, $results
    );

    return $studentIDs;
}

/**
 * Check if a student is registered for an exam
 *
 * @param int    $examID    Exam ID
 * @param string $studentID Student ID
 *
 * @return bool             If student is registered for
 */
function isStudentRegisteredFor(int $examID, string $studentID)
{
    validateExamID($examID);
    validateStudentID($studentID);

    return isStudentRegisteredForQuery($examID, $studentID);
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
 * Register student for an exam
 *
 * @param int    $examID    Exam ID
 * @param string $studentID Student ID
 */
function registerStudentForExam(int $examID, string $studentID)
{
    validateStudentID($studentID);
    validateRegistrationStateIs($studentID, STUDENT_STATE_READY);
    validateExamAllowsRegistration($examID);
    validateExamRoomAvailable($examID);

    registerStudentForExamQuery($examID, $studentID);

    setRegistrationState($studentID, STUDENT_STATE_REGISTERED);
}

/**
 * Force registers a student, bypasses any checks
 * Other than valid student/exam ID
 *
 * @param int    $examID
 * @param string $studentID
 */
function registerStudentForExamForced(int $examID, string $studentID)
{
    validateStudentID($studentID);
    validateExamID($examID);
    // TODO: validate registration state is not 'passed' ?
    // TODO: check if within grading exam state and update?

    registerStudentForExamQuery($examID, $studentID);

    setRegistrationState($studentID, STUDENT_STATE_REGISTERED);
}

/**
 * Deregister from an exam
 *
 * @param int    $examID    Exam ID
 * @param string $studentID Student ID
 */
function deregisterStudentFromExam(int $examID, string $studentID)
{
    validateStudentID($studentID);
    validateRegistrationStateIs($studentID, STUDENT_STATE_REGISTERED);
    validateExamAllowsRegistration($examID);
    validateStudentIsRegisteredFor($studentID, $examID);

    deregisterStudentFromExamQuery($examID, $studentID);

    refreshRegistrationStateFromDeregister($studentID);
}

/**
 * Helper function for deregisterStudentFromExam()
 * Used to determine the students registration state
 *
 * @param string $studentID Student ID
 */
function refreshRegistrationStateFromDeregister(string $studentID)
{
    $failures = getFailedExamCount($studentID);

    $newState = ($failures < MAX_FAILURES_BEFORE_BLOCK) ? STUDENT_STATE_READY
        : STUDENT_STATE_BLOCKED;
    setRegistrationState($studentID, $newState);
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
    validateExamID($examID);
    validateStudentIsRegisteredFor($studentID, $examID);

    return getAssignedSeatQuery($studentID, $examID);
}

/**
 * Internal function, should only be used on the state change
 * Assigns all seats for an exam
 *
 * @param int $examID Exam ID
 */
function assignExamSeats(int $examID)
{
    $students = getExamRegistrations($examID);

    $locationID = getExamLocationID($examID);
    $rooms = getLocationRooms($locationID);

    $seating = array();
    foreach($rooms as $room) {
        $seatingRoom = array();
        $seatingRoom['id'] = $room['id'];
        $seatingRoom['seats'] = range(1, $room['seats']);
        array_push($seating, $seatingRoom);
    }

    foreach($students as $studentID) {
        $roomKey = array_rand($seating);
        $room = &$seating[$roomKey];
        $roomID = $room['id'];

        $seats = &$room['seats'];
        $seatKey = array_rand($seats);
        $seat = $seats[$seatKey];

        setAssignedSeatQuery($studentID, $examID, $roomID, $seat);

        unset($seats[$seatKey]);
        if (count($seats) == 0) {
            unset($seating[$roomKey]);
        }
    }
}

/**
 * Internal function
 * Un-assigns all seats for an exam
 *
 * @param int $examID Exam ID
 */
function resetExamSeats(int $examID)
{
    $students = getExamRegistrations($examID);
    foreach ($students as $studentID) {
        setAssignedSeatQuery($studentID, $examID, null, null);
    }
}

/**
 * Get all assigned seats for an exam
 *
 * @param int $examID Exam ID
 *
 * @return array      Array of assigned seats, element format
 *                    'student_id' => student ID
 *                    'room_id' => room ID
 *                    'seat' => seat
 */
function getAssignedSeats(int $examID)
{
    validateExamID($examID);

    $registrations = getAllExamRegistrationsQuery($examID);

    // need to filter out seats that aren't correct
    foreach ($registrations as $i => $registration) {
        if (!isSeatSet($registration['room_id'], $registration['seat'])) {
            unset($registration[$i]);
        }
    }

    return $registrations;
}

/**
 * Get the number of assigned seats
 *
 * @param int $examID Exam ID
 *
 * @return int        Assigned seat count
 */
function getAssignedSeatCount(int $examID)
{
    // TODO: make query for

    return count(getAssignedSeats($examID));
}

/**
 * Get amount of registrations for an exam
 *
 * @param $examID Exam ID
 *
 * @return int    Number of registrations
 */
function getRegisteredCount($examID)
{
    // TODO: make query for

    return count(getAllExamRegistrationsQuery($examID));
}

/**
 * Find any exam seating that is invalid
 * Which means room_id=null or seat=0
 * This function would just serve as a redundancy/error checking
 *
 * @param int $examID Exam ID
 *
 * @return array      List of student IDs for invalid seating
 */
function checkForInvalidSeating(int $examID)
{
    validateExamID($examID);
    validateExamLocationAvailable($examID);

    $info = getExamInformation($examID);
    $locationID = $info['location_id'];

    $roster = getAllExamRegistrationsQuery($examID);
    $students = array();
    foreach ($roster as $registration) {
        $roomID = $registration['room_id'];
        $seat = $registration['seat'];

        if (!isSeatSet($roomID, $seat) || !locationHasRoom($roomID, $seat)
            || ($seat >= getLocationRoomSeats($locationID, $roomID))
        ) {
            array_push($students, $registration['student_id']);
        }
    }

    return $students;
}

/**
 * Check for any duplicate exam seating
 * Serves as redundancy/error checking
 *
 * @param int $examID Exam ID
 *
 * @return array        For duplicate seats found
 *                      Elements will be an array of duplicates
 *                      Each sub array's format
 *                      'room_id' => room id
 *                      'seat' => seat
 *                      'students' => array of student IDs
 */
function checkForDuplicateSeating(int $examID)
{
    validateExamID($examID);
    validateExamLocationAvailable($examID);

    $roster = getAllExamRegistrationsQuery($examID);

    $quickSeating = array();
    foreach ($roster as $registration) {
        $roomID = $registration['room_id'];
        $seat = $registration['seat'];
        $studentID = $registration['student_id'];

        if (!isSeatSet($roomID, $seat)) {
            continue;
        }

        if (!array_key_exists($roomID, $quickSeating)) {
            $newRoomArr = array();
            $newRoomArr[$seat] = array($studentID);
            $quickSeating[$roomID] = $newRoomArr;
            continue;
        }

        if (!array_key_exists($seat, $quickSeating[$roomID])) {
            $quickSeating[$roomID][$seat] = array($studentID);
        }

        array_push($quickSeating[$roomID][$seat], $studentID);
    }

    $duplicates = array();
    foreach ($quickSeating as $roomID => $roomSeats) {
        foreach ($roomSeats as $seat => $students) {
            if (count($students) > 1) {
                array_push(
                    $duplicates,
                    array('room_id'  => $roomID, 'seat' => $seat,
                          'students' => $students)
                );
            }
        }
    }

    return $duplicates;
}

/**
 * Get the max space for an exam
 * Does not take into account reserved seats
 *
 * @param int $examID Exam ID
 *
 * @return int        Max amount of seats
 */
function getMaxExamSpace(int $examID)
{
    $locationID = getExamLocationID($examID);
    return getLocationRoomsMaxSeats($locationID);
}

/**
 * Check if space is available to register.
 * Takes into account reserved seats.
 * Intended to be used for normal registration.
 *
 * @param int $examID Exam ID
 *
 * @return bool       If space available for registration
 */
function examRegistrationSpaceAvailable(int $examID)
{
    $info = getLocationInformation($examID);
    $maxSeats = getLocationRoomsMaxSeats($info['location_id']);
    $reservedSeats = $info['reserved_seats'];

    $maxSeats -= $reservedSeats;
    $registeredAmount = getAssignedSeatCount($examID);

    return ($registeredAmount < $maxSeats);
}

/**
 * Check if there is any seat space available for an exam.
 * Does not take the reserved seat count into account.
 * Intended to be used for emergency/forced registration
 *
 * @param int $examID Exam ID
 *
 * @return bool       If seat space available
 */
function examSeatSpaceAvailable(int $examID)
{
    $locationID = getExamLocationID($examID);
    $maxSeats = getLocationRoomsMaxSeats($locationID);

    $registeredAmount = getAssignedSeatCount($examID);

    return ($registeredAmount < $maxSeats);
}

/**
 * Check if given exam has a location available
 *
 * @param int $examID Exam ID
 *
 * @return bool       Location available
 */
function examLocationAvailable(int $examID)
{
    $info = getExamInformation($examID);
    return is_null($info['location_id']);
}

/**
 * Check if given seating means a seat is set
 *
 * @param int $roomID Room ID
 * @param int $seat   Seat
 *
 * @return bool       If set set
 */
function isSeatSet(int $roomID, int $seat)
{
    return !(is_null($roomID) || is_null($seat) || ($roomID <= 0)
        || ($seat <= 0));
    // TODO: check if seat number is within the room seat max
}