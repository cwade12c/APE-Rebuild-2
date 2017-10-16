<?php
/**
 * General, helper functions
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Database
 */

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

    $unixStart  = $start->getTimestamp();
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
 * Checks if list of categories and passing grade is valid for an exam
 * Throws argument exceptions if there is an issue
 *
 * @param int   $passingGrade
 * @param array $categories
 */
function validateExamCategories(int $passingGrade, array $categories)
{
    // validate categories array

    // validate passing grade
    // check if reachable
}