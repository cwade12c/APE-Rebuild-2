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
 * @param DateTime $start
 * @param DateTime $cutoff
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
    if ($unixStart > $unixCutoff) {
        throw new InvalidArgumentException(
            'Start datetime after registration cutoff datetime'
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
 * @param int $length
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
 *
 * @param int $id
 *
 * @return bool
 */
function validateLocationID(int $id)
{
    if ($id < 0) {
        throw new InvalidArgumentException('Invalid location id: ' . $id);
    }
    // TODO: check id exists
}

/**
 * Checks if teacher ID is valid
 * Throws argument exceptions if there is an issue
 *
 * @param bool   $inClass
 * @param string $teacherID
 */
function validateTeacherID(string $teacherID)
{
    // TODO: validate teacher id, validate exists
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
 * @param mixed  $value
 * @param string $msg String for portion of exception message
 */
function validateIsString(mixed $value, string $msg)
{
    validateType($value, 'string', $msg);
}

/**
 * Validate given value is a type of integer
 *
 * @param mixed  $value
 * @param string $msg String for portion of exception message
 */
function validateIsInt(mixed $value, string $msg)
{
    validateType($value, 'integer', $msg);
}

/**
 * Validate that a value is of a given type
 * Throws exception if type does not match
 *
 * @param mixed  $value
 * @param string $type Value type as a string
 *                     See http://php.net/manual/en/function.gettype.php
 * @param string $msg  String for the 'what' portion of the exception message
 */
function validateIsType(mixed $value, string $type, string $msg)
{
    $actualType = gettype($value);
    if (strcasecmp($actualType, $type) != 0) {
        throw new InvalidArgumentException(
            "Expected {$type} value type for ({$msg})"
        );
    }
}