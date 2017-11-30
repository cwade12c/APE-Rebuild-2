<?php
/**
 * Functions for reports in database
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Database
 */

/**
 * Get all report IDs
 *
 * @return array
 */
function getReportIDs()
{
    $results = getReportIDsQuery();
    $reports = array_column($results, 'id');
    return $reports;
}

/**
 * Get all report IDs and names
 *
 * @return array    elements are associative arrays
 *                  'id', 'name'
 */
function getReportSet()
{
    return getReportIDsNamesQuery();
}

/**
 * Get rows for given report id
 *
 * @param int $id
 *
 * @return array
 */
function getReportRows(int $id)
{
    $results = getReportRowsQuery($id);
    $types = array_column($results, 'type');
    return $types;
}

/**
 * Add new report w/ given name and rows
 *
 * @param string $name
 * @param array  $rows
 */
function addNewReport(string $name, array $rows = array())
{
    startTransaction();

    addNewReportQuery($name);

    $id = getLastInsertedID();

    // not calling setReportRows() to avoid nested transaction
    wipeReportRows($id);
    addReportRowsQuery($id, $rows);

    commit();
}

/**
 * Sets rows for a given report
 *
 * @param int   $id
 * @param array $rows
 */
function setReportRows(int $id, array $rows = array())
{
    startTransaction();

    wipeReportRows($id);
    addReportRowsQuery($id, $rows);

    commit();
}

/**
 * Wipe rows for a given report
 * Intended for internal function use.
 *
 * @param int $id
 */
function wipeReportRows(int $id)
{
    wipeReportRowsQuery($id);
}

/**
 * Update the name and rows of a report ID
 *
 * @param int    $id
 * @param string $name
 * @param array  $rows
 */
function updateReport(int $id, string $name, array $rows)
{
    updateReportNameQuery($id, $name);
    updateReportTypesQuery($id, $rows);
}

/**
 * Used internally for functions to validate the rows array
 * Checks that only positive integers present
 *
 * @param array $rows
 *
 * @return bool
 */
function isValidRows(array $rows)
{
    foreach ($rows as $val) {
        if (!is_int($val)) {
            return false;
        } elseif ($val < 0) {
            return false;
        }
    }

    return true;
}

/**
 * Generate a report based on the given types
 *
 * @param int   $examID
 * @param array $types
 *
 * @return array        contains 2 values
 *                      header
 *                          for column headers
 *                      rows
 *                          resulting rows, below
 *                          some keys may be contained that were not selected
 *                          will be null, rely on header
 *                      report rows, depending on types selected
 *                      possible keys
 *                          'studentID'
 *                          'studentFirstName'
 *                          'studentLastName'
 *                          'studentEmail'
 *                          'studentGrade'
 *                          'studentPassed'
 *                          'studentHash'
 *                          'studentSeating'
 *                      if 'REPORT_TYPE_STUDENT_CATEGORY_GRADES' included
 *                          key format:
 *                          '{category name}Grade'
 */
function generateReport(int $examID, array $types)
{
    $getStudentID = in_array(REPORT_TYPE_STUDENT_ID, $types);
    $getStudentFirstName = in_array(REPORT_TYPE_STUDENT_FIRST_NAME, $types);
    $getStudentLastName = in_array(REPORT_TYPE_STUDENT_LAST_NAME, $types);
    $getStudentEmail = in_array(REPORT_TYPE_STUDENT_EMAIL, $types);
    $getStudentGrade = in_array(REPORT_TYPE_STUDENT_GRADE, $types);
    $getStudentPassed = in_array(REPORT_TYPE_STUDENT_PASSED, $types);
    $getStudentCategories = in_array(
        REPORT_TYPE_STUDENT_CATEGORY_GRADES, $types
    );
    $getStudentHash = in_array(REPORT_TYPE_STUDENT_HASH, $types);
    $getStudentSeating = in_array(REPORT_TYPE_STUDENT_SEATING, $types);

    $getStudentInfo = $getStudentID || $getStudentFirstName
        || $getStudentLastName
        || $getStudentEmail
        || $getStudentHash;
    $getStudentGrading = $getStudentSeating || $getStudentGrade
        || $getStudentPassed
        || $getStudentCategories;


    $categories = getReportCategoryInfo($examID, $getStudentCategories);

    $header = getReportHeader(
        $getStudentID, $getStudentFirstName, $getStudentLastName,
        $getStudentEmail, $getStudentHash, $getStudentSeating,
        $getStudentGrade, $getStudentPassed,
        $getStudentCategories, $categories
    );

    $report = array();

    $registrations = getExamRegistrations($examID);
    foreach ($registrations as $studentID) {
        $row = array();
        if ($getStudentInfo) {
            getReportStudentInfo(
                $row, $studentID, $getStudentID, $getStudentFirstName,
                $getStudentLastName, $getStudentEmail, $getStudentHash
            );
        }
        if ($getStudentGrading) {
            getReportGradeInfo(
                $row, $examID, $studentID, $categories, $getStudentSeating,
                $getStudentPassed, $getStudentGrade, $getStudentCategories
            );
        }
        array_push($report, $row);
    }

    return array($header, $report);
}

/**
 * Helper function for generateReport()
 * gets the category information if necessary
 *
 * @param int  $examID
 * @param bool $getStudentCategories
 *
 * @return array|null
 */
function getReportCategoryInfo(int $examID, bool $getStudentCategories)
{
    $categories = null;
    if ($getStudentCategories) {
        $categories = array();
        $examCategories = getExamCategories($examID);
        foreach ($examCategories as $examCategory) {
            $categoryID = $examCategory['id'];
            $info = getCategoryInfo($categoryID);
            $category = array();
            $category['id'] = $categoryID;
            $category['name'] = $info['name'];
            array_push($categories, $category);
        }
    }

    return $categories;
}

/**
 * Generates header for the report
 *
 * @param bool       $getStudentID
 * @param bool       $getStudentFirstName
 * @param bool       $getStudentLastName
 * @param bool       $getStudentEmail
 * @param bool       $getStudentGrade
 * @param bool       $getStudentPassed
 * @param bool       $getStudentCategories
 * @param array|null $categories
 *
 * @return array
 */
function getReportHeader(bool $getStudentID, bool $getStudentFirstName,
    bool $getStudentLastName, bool $getStudentEmail, bool $getStudentHash,
    bool $getStudentSeating, bool $getStudentGrade,
    bool $getStudentPassed, bool $getStudentCategories, array $categories = null
) {
    $header = array();

    $cheapPush = function (&$arr, $condition, $value) {
        if ($condition) {
            array_push($arr, $value);
        }
    };

    $cheapPush($header, $getStudentID, REPORT_COLUMN_STUDENT_ID);
    $cheapPush($header, $getStudentFirstName, REPORT_COLUMN_STUDENT_FIRST_NAME);
    $cheapPush($header, $getStudentLastName, REPORT_COLUMN_STUDENT_LAST_NAME);
    $cheapPush($header, $getStudentEmail, REPORT_COLUMN_STUDENT_EMAIL);
    $cheapPush($header, $getStudentHash, REPORT_COLUMN_STUDENT_HASH);
    $cheapPush($header, $getStudentSeating, REPORT_COLUMN_STUDENT_SEATING);
    $cheapPush($header, $getStudentGrade, REPORT_COLUMN_STUDENT_GRADE);
    $cheapPush($header, $getStudentPassed, REPORT_COLUMN_STUDENT_PASSED);

    if ($getStudentCategories && $categories) {
        foreach ($categories as $category) {
            $name = $category['name'];
            array_push($header, "$name Grade");
        }
    }

    return $header;
}

/**
 * Helper function for generateReport()
 * Get student info for a report row
 *
 * @param array  $row
 * @param string $studentID
 * @param bool   $getStudentID
 * @param bool   $getStudentFirstName
 * @param bool   $getStudentLastName
 * @param bool   $getStudentEmail
 */
function getReportStudentInfo(array &$row, string $studentID,
    bool $getStudentID, bool $getStudentFirstName,
    bool $getStudentLastName, bool $getStudentEmail, bool $getStudentHash
) {
    $info = getAccountInfo($studentID);
    $row[REPORT_COLUMN_STUDENT_ID] = $getStudentID ? $studentID : null;
    $row[REPORT_COLUMN_STUDENT_FIRST_NAME] =
        $getStudentFirstName ? $info['firstName'] : null;
    $row[REPORT_COLUMN_STUDENT_LAST_NAME] =
        $getStudentLastName ? $info['lastName'] : null;
    $row[REPORT_COLUMN_STUDENT_EMAIL] =
        $getStudentEmail ? $info['email'] : null;
    $row[REPORT_COLUMN_STUDENT_HASH] =
        $getStudentHash ? hashAccountID($studentID) : null;
}

/**
 * Helper function for generateReport()
 * Get grade info for a report row
 *
 * @param array  $row
 * @param int    $examID
 * @param string $studentID
 * @param array  $categories
 * @param bool   $getStudentPassed
 * @param bool   $getStudentGrade
 * @param bool   $getStudentCategories
 */
function getReportGradeInfo(array &$row, int $examID, string $studentID,
    $categories, bool $getStudentSeating, bool $getStudentPassed,
    bool $getStudentGrade, bool $getStudentCategories
) {
    $info = getStudentExamGradeDetails($examID, $studentID);
    $row[REPORT_COLUMN_STUDENT_SEATING] =
        $getStudentSeating ? getStudentSeating($studentID, $examID) : null;
    $row[REPORT_COLUMN_STUDENT_GRADE] =
        $getStudentGrade ? $info['grade'] : null;
    $row[REPORT_COLUMN_STUDENT_PASSED] =
        $getStudentPassed ? $info['passed'] : null;
    if ($getStudentCategories && $categories) {
        foreach ($categories as $category) {
            $name = $category['name'];
            $key = "{$name} Grade";
            $grade = getStudentCategoryGrade(
                $examID, $category['id'], $studentID
            );
            $row[$key] = $grade;
        }
    }
}

/**
 * Helper function for getReportGradeInfo()
 * Gets assigned seating for a student, N/A if does not exist
 *
 * @param string $studentID
 *
 * @return string
 */
function getStudentSeating(string $studentID, int $examID)
{
    $seating = getAssignedSeat($studentID, $examID);
    $roomID = $seating['room_id'];
    $seat = $seating['seat'];
    if (!isSeatSet($roomID, $seat)) {
        return 'N/A';
    }

    $name = getRoomName($roomID);
    return "$name #$seat";
}

/**
 * Check if given type is valid
 *
 * @param int $type
 *
 * @return bool
 */
function isValidReportColumnType(int $type)
{
    switch ($type) {
        case REPORT_TYPE_STUDENT_ID:
        case REPORT_TYPE_STUDENT_FIRST_NAME:
        case REPORT_TYPE_STUDENT_LAST_NAME:
        case REPORT_TYPE_STUDENT_EMAIL:
        case REPORT_TYPE_STUDENT_GRADE:
        case REPORT_TYPE_STUDENT_PASSED:
        case REPORT_TYPE_STUDENT_CATEGORY_GRADES:
            return true;
        case REPORT_TYPE_NONE:
        default:
            return false;
    }
}