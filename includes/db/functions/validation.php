<?php
/**
 * Validation functions used for backend functions
 * Mainly only ones that are used by the frontend
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
    // really only want to check if lowering the seat amount
    // then checking the currently assigned seats
}

/**
 * Checks if account ID is valid
 *
 * @param string $id
 */
function validateAccountID(string $id)
{
    if (!validID($id) && !validTempID($id)) {
        throw new InvalidArgumentException(
            "Account ID is not a valid format", ERROR_CODE_ARG
        );
    }
}

/**
 * Validate the given account exists
 *
 * @param string $id
 *
 * @throws InvalidArgumentException
 */
function validateAccountExists(string $id)
{
    validateAccountID($id);
    if (!accountExists($id)) {
        throw new InvalidArgumentException(
            "Account ID does not exist", ERROR_CODE_ARG
        );
    }
}

/**
 * Validate the account id is a temp id
 * and the account exists
 *
 * @param string $id
 */
function validateTempExists(string $id)
{
    if (!validTempID($id)) {
        throw new InvalidArgumentException(
            "Account ID ({$id}) is not a valid temp ID"
        );
    }
    validateAccountExists($id);
}

/**
 * Validate an account has the given type set
 *
 * @param string $id
 * @param int    $type
 *
 * @throws InvalidArgumentException
 */
function validateAccountHasType(string $id, int $type)
{
    if (!accountTypeHas($id, $type)) {
        throw new InvalidArgumentException(
            "Account ID({$id}) does not have type({$type})", ERROR_CODE_ARG
        );
    }
}

/**
 * Validates that at least one field is available for a temp student
 * And any given value is valid
 *
 * @param string|null $firstName
 * @param string|null $lastName
 * @param string|null $email
 *
 * @throws  InvalidArgumentException
 */
function validateTempStudentFields(string $firstName = null,
    string $lastName = null, string $email = null
) {
    if (is_null($firstName) && is_null($lastName) && is_null($email)) {
        throw new InvalidArgumentException(
            "At least one identification field is required for a temp student (first name, last name or email)",
            ERROR_CODE_ARG
        );
    }

    if (!is_null($firstName)) {
        validateAccountName("first", $firstName);
    }
    if (!is_null($lastName)) {
        validateAccountName("last", $lastName);
    }
    if (!is_null($email)) {
        validateAccountEmail($email);
    }
}

/**
 * Validate all fields for an account
 *
 * @param string $id
 * @param string $firstName
 * @param string $lastName
 * @param string $email
 *
 * @throws InvalidArgumentException
 */
function validateAccountFields(string $id, string $firstName, string $lastName,
    string $email
) {
    validateAccountID($id);
    validateAccountName("first", $firstName);
    validateAccountName("last", $lastName);
    validateAccountEmail($email);
}

/**
 * Validate the given name is valid for an account
 * Works for either first/last names
 *
 * @param string $identifier Identifier for error messages
 * @param string $name
 *
 * @throws InvalidArgumentException
 */
function validateAccountName(string $identifier, string $name)
{
    $name = trim($name);
    if (!$name) {
        throw new InvalidArgumentException("Invalid {$identifier} name, empty");
    }

    // TODO: name validation
}

/**
 * Validate the given email is valid for an account
 *
 * @param string $email
 *
 * @throws InvalidArgumentException
 */
function validateAccountEmail(string $email)
{
    $email = trim($email);
    if (!$email) {
        throw new InvalidArgumentException("Invalid email, empty");
    }

    // TODO: email validation
}

/**
 * Validate the given ID is valid for the type
 * Mainly checks against EWU and temp IDs
 *
 * @param string $accountID
 * @param int    $type
 */
function validateAccountIDAndType(string $accountID, int $type)
{
    if (validID($accountID) && typeHas($type, ACCOUNT_TYPE_TEMP)) {
        throw new InvalidArgumentException(
            "Account ID w/ EWU ID set cannot have a temp type."
        );
    }
    if (validTempID($accountID) && !typeHas($type, ACCOUNT_TYPE_TEMP)) {
        throw new InvalidArgumentException(
            "Account ID w/ temp ID must have a temp type."
        );
    }
}

/**
 * Check if student ID is valid
 *
 * @param string $studentID Student ID
 *
 * @throws InvalidArgumentException
 */
function validateStudentID(string $studentID)
{
    validateAccountExists($studentID);
    validateAccountHasType($studentID, ACCOUNT_TYPE_STUDENT);
}

/**
 * Validate account ID exists and has temp type
 *
 * @param string $tempID
 *
 * @throws InvalidArgumentException
 */
function validateTempID(string $tempID)
{
    validateAccountExists($tempID);
    validateAccountHasType($tempID, ACCOUNT_TYPE_TEMP);
}

/**
 * Validate account ID has student/temp type
 *
 * @param string $tempStudentID
 *
 * @throws InvalidArgumentException
 */
function validateTempStudentID(string $tempStudentID)
{
    validateTempExists($tempStudentID);

    validateAccountHasType($tempStudentID, ACCOUNT_TYPE_TEMP);
    validateAccountHasType($tempStudentID, ACCOUNT_TYPE_STUDENT);
}

/**
 * Checks if teacher ID is valid
 *
 * @param string $teacherID
 *
 * @throws InvalidArgumentException
 */
function validateTeacherID(string $teacherID)
{
    validateAccountExists($teacherID);
    validateAccountHasType($teacherID, ACCOUNT_TYPE_TEACHER);
}

/**
 * Validate if grader ID is valid
 *
 * @param string $graderID
 *
 * @throws InvalidArgumentException
 */
function validateGraderID(string $graderID)
{
    validateAccountExists($graderID);
    validateAccountHasType($graderID, ACCOUNT_TYPE_GRADER);
}

/**
 * Validate if admin ID is valid
 *
 * @param string $adminID
 *
 * @throws InvalidArgumentException
 */
function validateAdminID(string $adminID)
{
    validateAccountExists($adminID);
    validateAccountHasType($adminID, ACCOUNT_TYPE_ADMIN);
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
 * Validate the value is valid registration state
 *
 * @param int $state Registration state
 *
 * @throws InvalidArgumentException
 */
function validateRegistrationState(int $state)
{
    if (!isValidRegistrationState($state)) {
        throw new InvalidArgumentException(
            "Invalid registration state({$state})"
        );
    }
}

/**
 * Validate the current registration state of a student
 *
 * @param string $studentID
 * @param int    $state
 */
function validateRegistrationStateIs(string $studentID, int $state)
{
    $currentState = getRegistrationState($studentID);
    if ($currentState != $state) {
        throw new InvalidArgumentException(
            sprintf(
                "Current student(%s) state(%d) does not match expected state(%d)",
                $studentID, $currentState, $state
            )
        );
    }
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
 * Validate the exam state allows registration
 * Throws argument exception if the exam state does not allow registration
 *
 * @param int $examID Exam ID
 */
function validateExamAllowsRegistration(int $examID)
{
    $examState = getExamState($examID);
    if (!doesExamStateAllowRegistration($examState)) {
        // throw exception
        throw new InvalidArgumentException(
            sprintf(
                "Invalid exam(%d) state(%s), does not allow registration",
                $examID,
                $examState
            )
        );
    }
}

/**
 * Validate the student is registered for the given exam
 * Throws InvalidArgumentException if there is an issue
 *
 * @param string $studentID Student ID
 * @param int    $examID    Exam ID
 */
function validateStudentIsRegisteredFor(string $studentID, int $examID)
{
    if (!in_array($examID, getExamsRegisteredFor($studentID))) {
        throw new InvalidArgumentException(
            sprintf(
                "Student(%s) is not registered for exam(%d)", $studentID,
                $examID
            )
        );
    }
}

/**
 * Validates the room and seat number are valid for an exam
 * Throws InvalidArgumentException if there is an issue
 *
 * @param int $examID
 * @param int $roomID
 * @param int $seat
 */
function validateExamRoomSeat(int $examID, int $roomID, int $seat)
{
    // get location, rooms
    $info = getExamInformation($examID);
    $rooms = getLocationRooms($info['location_id']);
    foreach ($rooms as $room) {
        if ($room['id'] == $roomID) {
            if ($seat < $room['seats']) {
                // is valid
                return;
            } else {
                // invalid seat number
                throw new InvalidArgumentException(
                    sprintf(
                        "Invalid seat(%d), invalid for room(%d)",
                        $seat, $roomID
                    )
                );
            }
        }
    }

    // invalid room ID
    throw new InvalidArgumentException(
        sprintf(
            "Invalid room id(%d), not part of exam(%d)",
            $roomID, $examID
        )
    );
}

/**
 * Validate the exam has space on the registration
 * Throws InvalidArgumentException if there is an issue
 *
 * @param int $examID
 */
function validateExamRoomAvailable(int $examID)
{
    // TODO: populate
}

/**
 * Validates the exam's location has the given room
 * Throws InvalidArgumentException if there is an issue
 *
 * @param int $examID Exam ID
 * @param int $roomID Room ID
 */
function validateExamHasRoom(int $examID, int $roomID)
{
    // TODO: populate
}

/**
 * Validates the exam has a location ID set
 * Throws InvalidArgumentException if there is an issue
 *
 * @param int $examID Exam ID
 */
function validateExamLocationAvailable(int $examID)
{
    $info = getExamInformation($examID);
    if (is_null($info['location_id'])) {
        throw new InvalidArgumentException(
            sprintf("Exam(%d) has no location available", $examID)
        );
    }
}

/**
 * Validates the student has a seat assigned for an exam
 * Throws InvalidArgumentException if there is an issue
 *
 * @param int    $examID    Exam ID
 * @param string $studentID Student ID
 */
function validateExamSeatAssigned(int $examID, string $studentID)
{
    $seating = getAssignedSeat($studentID, $examID);
    if (is_null($seating['room_id']) || ($seating['seat'] <= 0)) {
        throw new InvalidArgumentException(
            sprintf(
                "Student(%s) does not have a seat assigned for exam(%d)",
                $studentID, $examID
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
    if ($id <= 0) {
        throw new InvalidArgumentException(
            sprintf("Exam ID(%d) is not a valid value", $id)
        );
    }
}

/**
 * Validate the exam ID exists
 * Throws argument exception if there is an issue
 *
 * @param int $id Exam ID
 */
function validateExamIDExists(int $id)
{
    if (!examExists($id)) {
        throw new InvalidArgumentException(
            sprintf("Exam(%d) does not exist", $id)
        );
    }
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
 * Validate that no transaction is active
 * Throws LogicException if there is an issue
 */
function validateNoTransaction()
{
    if (inTransaction()) {
        throw new LogicException("A transaction is active");
    }
}

/**
 * Validate that a transaction is active
 * Throws LogicException if there is an issue
 */
function validateInTransaction()
{
    if (!inTransaction()) {
        throw new LogicException("A transaction is not active");
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
