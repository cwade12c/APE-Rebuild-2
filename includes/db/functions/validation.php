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
 *
 * @return bool
 */
function validateExamAttributes(DateTime $start, DateTime $cutoff, int $minutes,
    int $passingGrade, array $categories, int $locationID,
    bool $inClass = false, string $teacherID = ""
) {
    // TODO remove this thing

    validateExamLength($minutes);
    validateLocationID($locationID);
    if ($inClass) {
        validateTeacherID($teacherID);
    }
    validateExamCategories($passingGrade, $categories);
    return true;
}

/**
 * Checks if start and cutoff datetimes for an exam are valid
 * if any issues are found, an invalid argument exception is thrown.
 * Helper method for exam functions.
 *
 * @param DateTime|string $start  start datetime
 * @param DateTime|string $cutoff cutoff datetime
 *
 * @return bool
 */
function validateDates($start, $cutoff)
{
    if (gettype($start) == 'string') {
        $start = DateTime::createFromFormat(DATETIME_FORMAT, $start);
    }
    if (gettype($cutoff) == 'string') {
        $cutoff = DateTime::createFromFormat(DATETIME_FORMAT, $cutoff);
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

    return true;
}

/**
 * Checks if exam length (in minutes) is valid
 * Throws argument exceptions if there is an issue
 *
 * @param int $length exam length
 *
 * @return bool
 */
function validateExamLength(int $length)
{
    if ($length <= 0) {
        throw new InvalidArgumentException('Invalid exam length: ' . $length);
    }
    // TODO: check config max length value (minutes)

    return true;
}

/**
 * Checks if a location ID is valid
 * Throws argument exceptions if there is an issue
 * Does not check if location w/ ID exists
 *
 * @param int $id location ID
 *
 * @return bool
 */
function validateLocationID(int $id)
{
    if ($id <= 0) {
        throw new InvalidArgumentException('Invalid location id: ' . $id);
    }

    return true;
}

/**
 * Checks if location w/ ID exists
 * Throws argument exception if there is an issue
 *
 * @param int $id location ID
 *
 * @return bool
 */
function validateLocationIDExists(int $id)
{
    if (!locationExists($id)) {
        throw new InvalidArgumentException("Location({$id}) does not exist");
    }

    return true;
}

/**
 * Checks if location name is valid
 * Throws argument exception if there is an issue
 * Does not check if location w/ name exists
 *
 * @param string $name location name
 *
 * @return bool
 */
function validateLocationName(string $name)
{
    // TODO: character set validation, whitespace
    // TODO: string length validation

    return true;
}

/**
 * Check that no location uses the given name
 * Throws argument exception if there is an issue.
 *
 * @param string $name Location name
 *
 * @return bool
 */
function validateLocationNameDoesNotExist(string $name)
{
    if (locationNameExists($name)) {
        throw new InvalidArgumentException("Location(\"{$name}\") exists");
    }

    return true;
}

/**
 * Check if the new name is a change from the current name
 * Then checks if another location uses the same name
 * If duplicate is found, then an argument exception is thrown
 *
 * @param int    $id   Location ID
 * @param string $name New/current location name
 *
 * @return bool
 */
function validateLocationNameChange(int $id, string $name)
{
    $info = getLocationInformation($id);
    if (strcmp($name, $info['name']) != 0) {
        validateLocationNameDoesNotExist($name);
    }

    return true;
}

/**
 * Checks if a room ID is valid
 * Throws argument exceptions if there is an issue
 * Does not check if room w/ ID exists
 *
 * @param int $id room ID
 *
 * @return bool
 */
function validateRoomID(int $id)
{
    if ($id <= 0) {
        throw new InvalidArgumentException('Invalid room id: ' . $id);
    }

    return true;
}

/**
 * Checks if room w/ ID exists
 * Throws argument exception if there is an issue
 *
 * @param int $id room ID
 *
 * @return bool
 */
function validateRoomIDExists(int $id)
{
    if (!roomExists($id)) {
        throw new InvalidArgumentException("Room({$id}) does not exist");
    }

    return true;
}

/**
 * Checks if room name is valid
 * Throws argument exception if there is an issue
 * Does not check if room w/ name exists
 *
 * @param string $name room name
 *
 * @return bool
 */
function validateRoomName(string $name)
{
    // TODO: character set validation, whitespace
    // TODO: string length validation

    return true;
}

/**
 * Check that no room uses the given name
 * Throws argument exception if there is an issue.
 *
 * @param string $name Room name
 *
 * @return bool
 */
function validateRoomNameDoesNotExist(string $name)
{
    if (roomNameExists($name)) {
        throw new InvalidArgumentException("Room(\"{$name}\") exists");
    }

    return true;
}

/**
 * Validates a name change for a room
 * Checks if name changes from current name,
 * then checks if new name is valid and does not exist.
 * Throws argument exception if there is an issue
 *
 * @param int    $id
 * @param string $name
 *
 * @return bool
 */
function validateRoomNameChange(int $id, string $name)
{
    $info = getRoomInformation($id);
    if (strcmp($name, $info['name']) != 0) {
        validateRoomNameDoesNotExist($name);
    }

    return true;
}

/**
 * Validates the number of seats is valid for a location/room entry
 * Throws argument exception if there is an issue
 *
 * @param int $seats
 *
 * @return bool
 */
function validateSeatCount(int $seats)
{
    if ($seats <= 0) {
        throw new InvalidArgumentException("Invalid seat count({$seats})");
    }

    return true;
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
 *
 * @return bool
 */
function validateLocationRooms(int $seatsReserved, int $limitedSeats,
    array $rooms
) {
    // TODO: populate

    return true;
}

/**
 * Validate it is safe to delete the given room
 *
 * @param int $id Room ID
 *
 * @return bool
 */
function validateRoomIDSafeDelete(int $id)
{
    validateRoomIDExists($id);
    // TODO: populate

    return true;
}

/**
 * Validate it is safe to edit the room to given seat count
 *
 * @param int $id
 *
 * @return bool
 */
function validateRoomIDSafeEdit(int $id, int $seats)
{
    validateRoomIDExists($id);
    validateSeatCount($seats);

    // TODO: populate
    // really only want to check if lowering the seat amount
    // then checking the currently assigned seats

    return true;
}

/**
 * Checks if account ID is valid
 *
 * @param string $id
 *
 * @return bool
 */
function validateAccountID(string $id)
{
    if (!validID($id) && !validTempID($id)) {
        throw new InvalidArgumentException(
            "Account ID is not a valid format", ERROR_CODE_ARG
        );
    }
    return true;
}

/**
 * @param string $id
 *
 * @return bool
 */
function validateEWUID(string $id)
{
    if (!validID($id)) {
        throw new InvalidArgumentException(
            "EWU ID is not a valid format", ERROR_CODE_ARG
        );
    }
    return true;
}

/**
 * Validate the given account exists
 *
 * @param string $id
 *
 * @return bool
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
    return true;
}

/**
 * Validate the account id is a temp id
 * and the account exists
 *
 * @param string $id
 *
 * @return bool
 */
function validateTempExists(string $id)
{
    if (!validTempID($id)) {
        throw new InvalidArgumentException(
            "Account ID ({$id}) is not a valid temp ID"
        );
    }
    validateAccountExists($id);
    return true;
}

/**
 * Validate an account has the given type set
 *
 * @param string $id
 * @param int    $type
 *
 * @return bool
 * @throws InvalidArgumentException
 */
function validateAccountHasType(string $id, int $type)
{
    if (!accountTypeHas($id, $type)) {
        throw new InvalidArgumentException(
            "Account ID({$id}) does not have type({$type})", ERROR_CODE_ARG
        );
    }
    return true;
}

/**
 * Validates that at least one field is available for a temp student
 * And any given value is valid
 *
 * @param string|null $firstName
 * @param string|null $lastName
 * @param string|null $email
 *
 * @return bool
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

    return true;
}

/**
 * Validate all fields for an account
 *
 * @param string $id
 * @param string $firstName
 * @param string $lastName
 * @param string $email
 *
 * @return bool
 * @throws InvalidArgumentException
 */
function validateAccountFields(string $id, string $firstName, string $lastName,
    string $email
) {
    validateAccountID($id);
    validateAccountName("first", $firstName);
    validateAccountName("last", $lastName);
    validateAccountEmail($email);
    return true;
}

/**
 * Validate the given name is valid for an account
 * Works for either first/last names
 *
 * @param string $identifier Identifier for error messages
 * @param string $name
 *
 * @return bool
 * @throws InvalidArgumentException
 */
function validateAccountName(string $identifier, string $name)
{
    $name = trim($name);
    if (!$name) {
        throw new InvalidArgumentException("Invalid {$identifier} name, empty");
    }

    // TODO: name validation

    return true;
}

/**
 * Validate the given email is valid for an account
 *
 * @param string $email
 *
 * @return bool
 * @throws InvalidArgumentException
 */
function validateAccountEmail(string $email)
{
    $email = trim($email);
    if (!$email) {
        throw new InvalidArgumentException("Invalid email, empty");
    }

    // TODO: email validation

    return true;
}

/**
 * Validate the given ID is valid for the type
 * Mainly checks against EWU and temp IDs
 *
 * @param string $accountID
 * @param int    $type
 *
 * @return bool
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
    return true;
}

/**
 * Check if given student ID can register for an exam
 *
 * @param string $studentID
 *
 * @return bool
 */
function validateStudentIDCanRegister(string $studentID)
{
    $state = getRegistrationState($studentID);
    if (!canStudentStateRegister($state)) {
        throw new InvalidArgumentException('Student state cannot register');
    }
    return true;
}

/**
 * Check if student ID is valid
 *
 * @param string $studentID Student ID
 *
 * @return bool
 * @throws InvalidArgumentException
 */
function validateStudentID(string $studentID)
{
    validateAccountExists($studentID);
    validateAccountHasType($studentID, ACCOUNT_TYPE_STUDENT);
    return true;
}

/**
 * Validate account ID exists and has temp type
 *
 * @param string $tempID
 *
 * @return bool
 * @throws InvalidArgumentException
 */
function validateTempID(string $tempID)
{
    validateAccountExists($tempID);
    validateAccountHasType($tempID, ACCOUNT_TYPE_TEMP);
    return true;
}

/**
 * Validate account ID has student/temp type
 *
 * @param string $tempStudentID
 *
 * @return bool
 * @throws InvalidArgumentException
 */
function validateTempStudentID(string $tempStudentID)
{
    validateTempExists($tempStudentID);
    validateAccountHasType($tempStudentID, ACCOUNT_TYPE_TEMP);
    validateAccountHasType($tempStudentID, ACCOUNT_TYPE_STUDENT);
    return true;
}

/**
 * Checks if teacher ID is valid
 *
 * @param string $teacherID
 *
 * @return bool
 * @throws InvalidArgumentException
 */
function validateTeacherID(string $teacherID)
{
    validateAccountExists($teacherID);
    validateAccountHasType($teacherID, ACCOUNT_TYPE_TEACHER);
    return true;
}

/**
 * Validate if grader ID is valid
 *
 * @param string $graderID
 *
 * @return bool
 * @throws InvalidArgumentException
 */
function validateGraderID(string $graderID)
{
    validateAccountExists($graderID);
    validateAccountHasType($graderID, ACCOUNT_TYPE_GRADER);

    return true;
}

/**
 * Validate if admin ID is valid
 *
 * @param string $adminID
 *
 * @return bool
 * @throws InvalidArgumentException
 */
function validateAdminID(string $adminID)
{
    validateAccountExists($adminID);
    validateAccountHasType($adminID, ACCOUNT_TYPE_ADMIN);

    return true;
}

/**
 * Validate the given category ID is valid
 * Throws argument exception if there is an issue
 *
 * @param int $id
 *
 * @return bool
 */
function validateCategoryID(int $id)
{
    if (!categoryExists($id)) {
        throw new InvalidArgumentException("Category ID($id) does not exist");
    }

    return true;
}

/**
 * Validate the grader is assigned to the exam
 *
 * @param string $graderID
 * @param int    $examID
 *
 * @return bool
 */
function validateGraderAssignedToExam(string $graderID, int $examID)
{
    if (!isGraderAssignedExam($graderID, $examID)) {
        throw new InvalidArgumentException(
            "Grader($graderID) not assigned to exam($examID)"
        );
    }
    return true;
}

/**
 * Validate the grader is assigned to the exam category
 *
 * @param string $graderID
 * @param int    $examID
 * @param int    $categoryID
 *
 * @return bool
 */
function validateGraderAssignedToExamCategory(string $graderID, int $examID,
    int $categoryID
) {
    if (!isGraderAssignedExamCategory($graderID, $examID, $categoryID)) {
        throw new InvalidArgumentException(
            "Grader($graderID) not assigned to exam($examID) category($categoryID)"
        );
    }
    return true;
}

/**
 * Validate grader is not assigned to exam/category
 *
 * @param string $graderID
 * @param int    $examID
 * @param int    $categoryID
 *
 * @return bool
 */
function validateGraderNotAssigned(string $graderID, int $examID, int $categoryID)
{
    if (isGraderAssignedExamCategory($graderID, $examID, $categoryID)) {
        throw new InvalidArgumentException(
            "Grader($graderID) already assigned to exam($examID) category($categoryID)"
        );
    }
    return true;
}

/**
 * Validate the grader has not submitted for exam category
 *
 * @param string $graderID
 * @param int    $examID
 * @param int    $categoryID
 *
 * @return bool
 */
function validateGraderNotSubmitted(string $graderID, int $examID,
    int $categoryID
) {
    if (isGraderCategorySubmitted($examID, $categoryID, $graderID)) {
        throw new InvalidArgumentException(
            "Grader($graderID) has submitted grades for exam($examID) category($categoryID)"
        );
    }
    return true;
}

/**
 * Validate grader has set grades for exam category
 *
 * @param string $graderID
 * @param int    $examID
 * @param int    $categoryID
 *
 * @return bool
 */
function validateGraderCategorySet(string $graderID, int $examID,
    int $categoryID
) {
    if (!allGraderCategoryPointsSet($examID, $categoryID, $graderID)) {
        throw new InvalidArgumentException(
            "Grader($graderID) has not set all student grades for exam($examID) category($categoryID)"
        );
    }
    return true;
}

/**
 * Validate the value is valid registration state
 *
 * @param int $state Registration state
 *
 * @return bool
 * @throws InvalidArgumentException
 */
function validateRegistrationState(int $state)
{
    if (!isValidRegistrationState($state)) {
        throw new InvalidArgumentException(
            "Invalid registration state({$state})"
        );
    }

    return true;
}

/**
 * Validate the current registration state of a student
 *
 * @param string $studentID
 * @param int    $state
 *
 * @return bool
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

    return true;
}

/**
 * Validate that a student is in the blocked registration state
 *
 * @param string $studentID
 */
function validateStudentBlocked(string $studentID)
{
    $state = getRegistrationState($studentID);
    if ($state != STUDENT_STATE_BLOCKED) {
        throw new InvalidArgumentException("student($studentID) is not in the blocked state($state)");
    }

    return true;
}

/**
 * Validate registration for an exam
 * Throws argument exception if there is an issue
 *
 * @param int    $examID    Exam ID
 * @param string $studentID Student ID
 *
 * @return bool
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

    return true;
}

/**
 * Validate de-registration from an exam
 * Throws argument exception if there is an issue
 *
 * @param int    $examID    Exam ID
 * @param string $studentID Student ID
 *
 * @return bool
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

    return true;
}

/**
 * Validate the exam state allows registration
 * Throws argument exception if the exam state does not allow registration
 *
 * @param int $examID Exam ID
 *
 * @return bool
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

    return true;
}

/**
 * Validate the student is registered for the given exam
 * Throws InvalidArgumentException if there is an issue
 *
 * @param string $studentID Student ID
 * @param int    $examID    Exam ID
 *
 * @return bool
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

    return true;
}

/**
 * Validates the room and seat number are valid for an exam
 * Throws InvalidArgumentException if there is an issue
 *
 * @param int $examID
 * @param int $roomID
 * @param int $seat
 *
 * @return bool
 */
function validateExamRoomSeat(int $examID, int $roomID, int $seat)
{
    // get location, rooms
    $info = getExamInformation($examID);
    $rooms = getLocationRooms($info['locationID']);
    foreach ($rooms as $room) {
        if ($room['id'] == $roomID) {
            if ($seat < $room['seats']) {
                // is valid
                return true;
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
 *
 * @return bool
 */
function validateExamRoomAvailable(int $examID)
{
    // TODO: populate

    return true;
}

/**
 * Validates the exam's location has the given room
 * Throws InvalidArgumentException if there is an issue
 *
 * @param int $examID Exam ID
 * @param int $roomID Room ID
 *
 * @return bool
 */
function validateExamHasRoom(int $examID, int $roomID)
{
    // TODO: populate

    return true;
}

/**
 * Validates the exam has a location ID set
 * Throws InvalidArgumentException if there is an issue
 *
 * @param int $examID Exam ID
 *
 * @return bool
 */
function validateExamLocationAvailable(int $examID)
{
    $info = getExamInformation($examID);
    if (is_null($info['locationID'])) {
        throw new InvalidArgumentException(
            sprintf("Exam(%d) has no location available", $examID)
        );
    }

    return true;
}

/**
 * Validates the student has a seat assigned for an exam
 * Throws InvalidArgumentException if there is an issue
 *
 * @param int    $examID    Exam ID
 * @param string $studentID Student ID
 *
 * @return bool
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

    return true;
}

/**
 * Validate the given exam ID is valid
 * Throws argument exception if there is an issue
 *
 * @param int $id
 *
 * @return bool
 */
function validateExamID(int $id)
{
    if ($id <= 0) {
        throw new InvalidArgumentException(
            sprintf("Exam ID(%d) is not a valid value", $id)
        );
    }

    return true;
}

/**
 * Validate the exam ID exists
 * Throws argument exception if there is an issue
 *
 * @param int $id Exam ID
 *
 * @return bool
 */
function validateExamIDExists(int $id)
{
    if (!examExists($id)) {
        throw new InvalidArgumentException(
            sprintf("Exam(%d) does not exist", $id)
        );
    }

    return true;
}

/**
 * Checks if list of categories and passing grade is valid for an exam
 * Throws argument exceptions if there is an issue
 *
 * @param int   $passingGrade
 * @param array $categories
 *
 * @return bool
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

    return true;
}

/**
 * Validate an exam has the following category assigned
 *
 * @param int $examID
 * @param int $categoryID
 *
 * @return bool
 */
function validateExamCategory(int $examID, int $categoryID)
{
    $categories = getExamCategories($examID);
    $categoryIDs = array_column($categories, 'id');

    if (!in_array($categoryID, $categoryIDs)) {
        throw new InvalidArgumentException(
            "Exam($examID) does not have the category($categoryID) assigned"
        );
    }

    return true;
}

/**
 * Validate the state of an exam is an allowed value
 *
 * @param int       $examID
 * @param int|array $stateExpected can be an exam state or list of states
 *
 * @return bool
 */
function validateExamStateIs(int $examID, $stateExpected)
{
    $statesAllowed = null;
    $type = gettype($stateExpected);
    if ($type == 'integer') {
        $statesAllowed = array($stateExpected);
    } else {
        if ($type == 'array') {
            $statesAllowed = $stateExpected;
        } else {
            throw new InvalidArgumentException(
                "Type of states expected not valid ($type)"
            );
        }
    }

    foreach ($stateExpected as $state) {
        if (!isExamStateValid($state)) {
            throw new InvalidArgumentException(
                'state given to expect is not valid'
            );
        }
    }

    $state = getExamState($examID);
    if (!in_array($state, $statesAllowed)) {
        throw new InvalidArgumentException("Exam state not allowed ($state)");
    }

    return true;
}

/**
 * Validate exam state
 *
 * @param int $state
 *
 * @return bool
 */
function validateExamState(int $state)
{
    if (!isExamStateValid($state)) {
        throw new InvalidArgumentException('State is not valid');
    }

    return true;
}

/**
 * Validate an exam's state allows edits such as location updates
 *
 * @param int $examID
 *
 * @return bool
 */
function validateExamStateAllowsEdits(int $examID)
{
    $state = getExamState($examID);
    if (!doesExamStateAllowEdits($state)) {
        throw new InvalidArgumentException("Exam state does not allow edits");
    }

    return true;
}

/**
 * Validate an exam's state allows graders to be assigned
 *
 * @param int $examID
 *
 * @return bool
 */
function validateExamStateAllowsGraderAssignment(int $examID)
{
    $state = getExamState($examID);
    if (!doesExamStateAllowGraderAssignments($state)) {
        throw new InvalidArgumentException("Exam state does not allow grader assignments");
    }

    return true;
}

/**
 * Validate if user can edit this exam
 *
 * @param string $accountID
 * @param int    $examID
 *
 * @return bool
 */
function validateUserCanEditExam(string $accountID, int $examID)
{
    $type = getAccountType($accountID);
    if (typeHas($type, ACCOUNT_TYPE_ADMIN)) {
        return true;
    }else if (typeHas($type, ACCOUNT_TYPE_TEACHER)) {
        $examTeacher = getInClassExamTeacher($examID);
        if (!$examTeacher || ($examTeacher != $accountID)) {
            throw new InvalidArgumentException("Exam does not belong to this teacher");
        }
        return true;
    }

    throw new InvalidArgumentException("User cannot edit this exam");
}

/**
 * Validate that the following account ID matches the current exam
 *
 * @param string $accountIDA
 * @param string $accountIDB
 *
 * @return bool
 */
function validateAccountsMatch(string $accountIDA, string $accountIDB)
{
    if ($accountIDA != $accountIDB) {
        throw new InvalidArgumentException('Accounts do not match');
    }
    return true;
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
 *
 * @return bool
 */
function validateKeysExist(array $arr, array $keys)
{
    foreach ($keys as $key) {
        if (!array_key_exists($key, $arr)) {
            throw new InvalidArgumentException("Key '{$key}' does not exist");
        }
    }
    return true;
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
 * Validate the value is type of array
 *
 * @param        $value
 * @param string $msg
 */
function validateIsArray($value, string $msg)
{
    validateIsType($value, 'array', $msg);
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
 * Validate a string can be parsed to a datetime
 *
 * @param string $value
 */
function validateStringIsDateTime(string $value)
{
    try{
        DateTime::createFromFormat(DATETIME_FORMAT, $value);
    }catch(Exception $e) {
        throw new InvalidArgumentException('Failed to parse datetime from string');
    }
}