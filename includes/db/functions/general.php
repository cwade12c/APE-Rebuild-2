<?php
/**
 * General, helper functions
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Database
 */

// TODO: move more general validation functions to another set
// (outside just db functions)

// TODO: some sequences of verifications get a little confusing
/// lots of similar verifications, but lots of differences
/// could figure out a simpler way of handling strings of validations

/**
 * Validates all arguments related to exam attributes
 * Argument exception will be thrown if any issues found
 *
 * @param DateTime $start
 * @param DateTime $cutoff
 * @param int      $minutes
 * @param int      $passingGrade
 * @param array    $categories
 * @param int      $locationID
 * @param bool     $inClass
 * @param string   $teacherID
 */
function validateExamAttributes(DateTime $start, DateTime $cutoff, int $minutes,
    int $passingGrade, array $categories, int $locationID,
    bool $inClass = false, string $teacherID = ""
) {
    validateDates($start, $cutoff);
    validateExamLength($minutes);
    validateLocationID($locationID);
    if ($inClass) {
        validateTeacherID($teacherID);
    }
    validateExamCategories($passingGrade, $categories);
}

/**
 * Checks if start and cutoff datetimes for an exam are valid
 * if any issues are found, an invalid argument exception is thrown.
 * Helper method for exam functions.
 *
 * @param DateTime $start  start datetime
 * @param DateTime $cutoff cutoff datetime
 */
function validateDates(DateTime $start, DateTime $cutoff)
{
    // null check
    if ($start == null || $cutoff == null) {
        throw new InvalidArgumentException('null date');
    }

    $unixStart = $start->getTimestamp();
    $unixCutoff = $cutoff->getTimestamp();
    // check that start datetime is before/on cutoff datetime
    if ($unixStart < $unixCutoff) {
        throw new InvalidArgumentException(
            'Start datetime before registration cutoff datetime'
        );
    }

    $unixNow = (new DateTime())->getTimestamp();
    // check that start datetime is before current datetime
    if ($unixStart < $unixNow) {
        throw new InvalidArgumentException(
            'Start datetime set before current datetime'
        );
    }

    // TODO: max time difference (between start and cutoff, config) ?
}

/**
 * Checks if exam length (in minutes) is valid
 * Throws argument exceptions if there is an issue
 *
 * @param int $length exam length
 */
function validateExamLength(int $length)
{
    if ($length <= 0) {
        throw new InvalidArgumentException('Invalid exam length: ' . $length);
    }
    // TODO: check config max length value (minutes)
}

/**
 * Checks if a location ID is valid
 * Throws argument exceptions if there is an issue
 * Does not check if location w/ ID exists
 *
 * @param int $id location ID
 */
function validateLocationID(int $id)
{
    if ($id <= 0) {
        throw new InvalidArgumentException('Invalid location id: ' . $id);
    }
}

/**
 * Checks if location w/ ID exists
 * Throws argument exception if there is an issue
 *
 * @param int $id location ID
 */
function validateLocationIDExists(int $id)
{
    if (!locationExists($id)) {
        throw new InvalidArgumentException("Location({$id}) does not exist");
    }
}

/**
 * Checks if location name is valid
 * Throws argument exception if there is an issue
 * Does not check if location w/ name exists
 *
 * @param string $name location name
 */
function validateLocationName(string $name)
{
    // TODO: character set validation, whitespace
    // TODO: string length validation
}

/**
 * Check that no location uses the given name
 * Throws argument exception if there is an issue.
 *
 * @param string $name Location name
 */
function validateLocationNameDoesNotExist(string $name)
{
    if (locationNameExists($name)) {
        throw new InvalidArgumentException("Location(\"{$name}\") exists");
    }
}

/**
 * Check if the new name is a change from the current name
 * Then checks if another location uses the same name
 * If duplicate is found, then an argument exception is thrown
 *
 * @param int    $id   Location ID
 * @param string $name New/current location name
 */
function validateLocationNameChange(int $id, string $name)
{
    $info = getLocationInformation($id);
    if (strcmp($name, $info['name']) != 0) {
        validateLocationNameDoesNotExist($name);
    }
}

/**
 * Checks if a room ID is valid
 * Throws argument exceptions if there is an issue
 * Does not check if room w/ ID exists
 *
 * @param int $id room ID
 */
function validateRoomID(int $id)
{
    if ($id <= 0) {
        throw new InvalidArgumentException('Invalid room id: ' . $id);
    }
}

/**
 * Checks if room w/ ID exists
 * Throws argument exception if there is an issue
 *
 * @param int $id room ID
 */
function validateRoomIDExists(int $id)
{
    if (!roomExists($id)) {
        throw new InvalidArgumentException("Room({$id}) does not exist");
    }
}

/**
 * Checks if room name is valid
 * Throws argument exception if there is an issue
 * Does not check if room w/ name exists
 *
 * @param string $name room name
 */
function validateRoomName(string $name)
{
    // TODO: character set validation, whitespace
    // TODO: string length validation
}

/**
 * Check that no room uses the given name
 * Throws argument exception if there is an issue.
 *
 * @param string $name Room name
 */
function validateRoomNameDoesNotExist(string $name)
{
    if (roomNameExists($name)) {
        throw new InvalidArgumentException("Room(\"{$name}\") exists");
    }
}

/**
 * Validates a name change for a room
 * Checks if name changes from current name,
 * then checks if new name is valid and does not exist.
 * Throws argument exception if there is an issue
 *
 * @param int    $id
 * @param string $name
 */
function validateRoomNameChange(int $id, string $name)
{
    $info = getRoomInformation($id);
    if (strcmp($name, $info['name']) != 0) {
        validateRoomNameDoesNotExist($name);
    }
}

/**
 * Validates the number of seats is valid for a location/room entry
 * Throws argument exception if there is an issue
 *
 * @param int $seats
 */
function validateSeatCount(int $seats)
{
    if ($seats <= 0) {
        throw new InvalidArgumentException("Invalid seat count({$seats})");
    }
}

/**
 * Validate information for updating the location room or seating info
 *
 * @param int   $seatsReserved  number of reserved seats
 * @param int   $limitedSeats   limit of seats separate from room seats total
 * @param array $rooms          array of rooms
 *                              element format:
 *                              array(
 *                              'id' => room id,
 *                              'seats' => overridden number of seats
 *                              )
 */
function validateLocationRooms(int $seatsReserved, int $limitedSeats,
    array $rooms
) {
    // TODO: populate
}

/**
 * Validate it is safe to delete the given room
 *
 * @param int $id Room ID
 */
function validateRoomIDSafeDelete(int $id)
{
    validateRoomIDExists($id);
    // TODO: populate
}

/**
 * Validate it is safe to edit the room to given seat count
 *
 * @param int $id
 */
function validateRoomIDSafeEdit(int $id, int $seats)
{
    validateRoomIDExists($id);
    validateSeatCount($seats);

    // TODO: populate
}

/**
 * Checks if account ID is valid
 * Throws argument exception if not valid
 *
 * @param string $id
 */
function validateAccountID(string $id)
{
    // TODO: validate is account id, exists
}

/**
 * Check if student ID is valid
 * Throws argument exceptions if there is an issue
 *
 * @param string $studentID Student ID
 */
function validateStudentID(string $studentID)
{
    // TODO: validate
}

/**
 * Checks if teacher ID is valid
 * Throws argument exceptions if there is an issue
 *
 * @param string $teacherID
 */
function validateTeacherID(string $teacherID)
{
    // TODO: validate is account id, validate teacher id exists
    /// throw exception if not valid
}

/**
 * Validate the given category ID is valid
 * Throws argument exception if there is an issue
 *
 * @param int $id
 */
function validateCategoryID(int $id)
{
    // TODO: validate category id, exists
    /// throw exception if not valid
}

/**
 * Validate registration for an exam
 * Throws argument exception if there is an issue
 *
 * @param int    $examID    Exam ID
 * @param string $studentID Student ID
 */
function validateRegistration(int $examID, string $studentID)
{
    // validate information
    validateExamID($examID);
    validateStudentID($studentID);

    // check registration state
    $currentState = getRegistrationState($studentID);
    if ($currentState != STUDENT_STATE_READY) {
        // throw exception
        throw new InvalidArgumentException(
            sprintf(
                "Invalid student(%s) registration state(%d), cannot register",
                $studentID,
                $currentState
            )
        );
    }

    // check exam state
    // check if state allows registration
    $examState = getExamState($examID);
    if (!doesExamStateAllowRegistration($examState)) {
        // throw exception
        throw new InvalidArgumentException(
            sprintf(
                "Invalid exam state(%s), does not allow registration",
                $examState
            )
        );
    }
}

/**
 * Validate de-registration from an exam
 * Throws argument exception if there is an issue
 *
 * @param int    $examID    Exam ID
 * @param string $studentID Student ID
 */
function validateDeregistration(int $examID, string $studentID)
{
    // validate information
    validateExamID($examID);
    validateStudentID($studentID);

    // check registration state
    $currentState = getRegistrationState($studentID);
    if ($currentState != STUDENT_STATE_REGISTERED) {
        // throw exception
        throw new InvalidArgumentException(
            sprintf(
                "Invalid student(%s) registration state(%d), not registered",
                $studentID,
                $currentState
            )
        );
    }

    // check exam state
    // if does not allow registration, then does not allow de-registration
    $examState = getExamState($examID);
    if (!doesExamStateAllowRegistration($examState)) {
        // throw exception
        throw new InvalidArgumentException(
            sprintf(
                "Invalid exam state(%s), does not allow de-registration",
                $examState
            )
        );
    }
}

/**
 * Validate the given exam ID is valid
 * Throws argument exception if there is an issue
 *
 * @param int $id
 */
function validateExamID(int $id)
{
    // TODO: validate exam id, exists
}

/**
 * Checks if list of categories and passing grade is valid for an exam
 * Throws argument exceptions if there is an issue
 *
 * @param int   $passingGrade
 * @param array $categories
 */
function validateExamCategories(int $passingGrade, array $categories)
{
    $keys = array();
    $totalPoints = 0;
    foreach ($categories as $i => $category) {
        // TODO: wrap up operation into another function
        /// catch exceptions, wrap exception w/ message about index value

        // validate entry types
        validateKeysExist($category, array('id', 'points'));
        validateIsInt($category['id'], "category id (index {$i})");
        validateIsInt($category['points'], "category points (index {$i})");
        // validate values
        validateCategoryID($category['id']);
        if ($category['points'] <= 0) {
            throw new InvalidArgumentException(
                "Invalid points value ({$category['points']}) <= 0, (index {$i})"
            );
        }
        // validate category ID is not duplicated
        if (in_array($category['id'], $keys)) {
            throw new InvalidArgumentException(
                "Duplicate category id (index {$i})"
            );
        }
        // pull values
        $totalPoints += $category['points'];
        array_push($keys, $category['id']);
    }

    // validate points reachable
    if ($passingGrade <= 0) {
        throw new InvalidArgumentException(
            "Passing grade ({$passingGrade}) <= 0"
        );
    }
    if ($passingGrade > $totalPoints) {
        throw new InvalidArgumentException(
            "Passing grade ({$passingGrade}) not reachable from calculated total ({$totalPoints})"
        );
    }

}

/**
 * Checks that the given array has the given set of keys
 * Throws argument exception if key does not exist
 *
 * @param array $arr
 * @param array $keys
 */
function validateKeysExist(array $arr, array $keys)
{
    foreach ($keys as $key) {
        if (!array_key_exists($key, $arr)) {
            throw new InvalidArgumentException("Key '{$key}' does not exist");
        }
    }
}

/**
 * Validate the given value is a type of string
 *
 * @param        $value
 * @param string $msg String for portion of exception message
 */
function validateIsString($value, string $msg)
{
    validateIsType($value, 'string', $msg);
}

/**
 * Validate given value is a type of integer
 *
 * @param        $value
 * @param string $msg String for portion of exception message
 */
function validateIsInt($value, string $msg)
{
    validateIsType($value, 'integer', $msg);
}

/**
 * Validate that a value is of a given type
 * Throws exception if type does not match
 *
 * @param        $value
 * @param string $type Value type as a string
 *                     See http://php.net/manual/en/function.gettype.php
 * @param string $msg  String for the 'what' portion of the exception message
 */
function validateIsType($value, string $type, string $msg)
{
    $actualType = gettype($value);
    if (strcasecmp($actualType, $type) != 0) {
        throw new InvalidArgumentException(
            "Expected {$type} value type for ({$msg})"
        );
    }
}

/**
 * Gets details about a table attribute
 *
 * @param string $tableName     name of table
 * @param string $attributeName name of attribute
 *
 * @return mixed                associative array of result
 *                              'DATA_TYPE' => mysql datatype as string
 *                              'CHARACTER_MAXIMUM_LENGTH' => max string length
 *                                  if applicable, else null
 *                              'NUMERIC_PRECISION' => number precision
 *                                  if applicable, else null
 *                              if table/attribute does not exist,
 *                                  false is returned
 */
function getTableAttributeDetails(string $tableName, string $attributeName)
{
    return getTableAttributeDetailsQuery($tableName, $attributeName);
    // TODO: check return to see if valid
    /// if false, no records were found
}