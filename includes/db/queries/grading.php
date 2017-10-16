<?php
/**
 * Query functions for grading
 *
 * @author         Mathew McCain
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Database
 */

/**
 * Query to assign a grader to exam/category
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $graderID
 */
function assignGraderQuery(int $examID, int $categoryID, string $graderID)
{
    $query = "INSERT INTO `assigned_graders` "
        . " (`exam_id`, `category_id`, `grader_id`) "
        . " VALUES (:examId, :categoryId, :graderId)";
    $sql = executeQuery(
        $query, array(array(':examId', $examID, PDO::PARAM_INT),
                      array(':categoryId', $categoryID, PDO::PARAM_INT),
                      array(':graderId', $graderID, PDO::PARAM_STR))
    );
}

/**
 * Query to un-assign a grader from an exam, category
 * Note: only removes from "assigned_graders" table
 *       Additional modifications may be needed depending on exam state
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $graderID
 */
function deleteAssignedGraderExamCategoryQuery(int $examID, int $categoryID,
    string $graderID
) {
    $query
        = "DELETE FROM `assigned_graders` "
        . " WHERE `exam_id` = :examID AND `category_id` = :categoryID "
        . " AND `grader_id` = :graderID";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':categoryID', $categoryID, PDO::PARAM_INT),
                      array(':graderID', $graderID, PDO::PARAM_STR))
    );
}

/**
 * Query to un-assign grader from an exam fully
 * NOTE: only removes from "assigned_graders" table
 *       Additional actions may be needed depending on exam state
 *
 * @param int    $examID
 * @param string $graderID
 */
function deleteAssignedGraderExamQuery(int $examID, string $graderID)
{
    $query
        = "DELETE FROM `assigned_graders` "
        . " WHERE `exam_id` = :examID AND `grader_id` = :graderID";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':graderID', $graderID, PDO::PARAM_STR))
    );
}


/**
 * Query to get assigned grader IDs
 *
 * @param int $examID
 *
 * @return array      query result rows, elements
 *                    "grader_id" => grader ID
 */
function getAssignedExamGradersQuery(int $examID)
{
    $query = "SELECT DISTINCT `grader_id` FROM `assigned_graders` "
        . " WHERE `exam_id` = :examID ";
    $sql = executeQuery(
        $query, array(array(':examId', $examID, PDO::PARAM_INT))
    );

    return getQueryResults($sql);
}

/**
 * Query to get assigned grader IDs for an exam/category
 *
 * @param int $examID
 * @param int $categoryID
 *
 * @return array           query result rows, elements
 *                         "grader_id" => grader ID
 */
function getAssignedExamCategoryGradersQuery(int $examID, int $categoryID)
{
    $query = "SELECT DISTINCT `grader_id` FROM `assigned_graders` "
        . " WHERE `exam_id` = :examID && `category_id` = :categoryID ";
    $sql = executeQuery(
        $query, array(array(':examId', $examID, PDO::PARAM_INT),
                      array(':categoryID', $categoryID, PDO::PARAM_INT))
    );

    return getQueryResults($sql);
}

/**
 * Query to get assigned exam IDs for a grader
 *
 * @param string $graderID
 *
 * @return array           query result rows, elements
 *                         "exam_id"
 */
function getAssignedExamsQuery(string $graderID)
{
    $query = "SELECT DISTINCT `exam_id` FROM `assigned_graders` "
        . " WHERE `grader_id` = :graderID ";
    $sql = executeQuery(
        $query, array(array(':graderID', $graderID, PDO::PARAM_STR))
    );

    return getQueryResults($sql);
}

/**
 * Query to get assigned exam categories for a grader
 *
 * @param int    $examID
 * @param string $graderID
 *
 * @return array           query result rows, elements
 *                         "category_id"
 */
function getAssignedExamCategoriesQuery(int $examID, string $graderID)
{
    $query = "SELECT DISTINCT `category_id` FROM `assigned_graders` "
        . " WHERE `exam_id` = :examID && `grader_id` = :graderID ";
    $sql = executeQuery(
        $query, array(array(':examId', $examID, PDO::PARAM_INT),
                      array(':graderID', $graderID, PDO::PARAM_STR))
    );

    return getQueryResults($sql);
}

/**
 * Query to create entries in "grader_category_grades"
 * Using list of categories and students
 *
 * @param int    $examID
 * @param string $graderID
 * @param array  $categories
 * @param array  $students
 */
function createGraderCategoryGradesQuery(int $examID, string $graderID,
    array $categories, array $students
) {
    list($values, $params) = buildReportRowsValuesString(
        $examID, $graderID, $categories, $students
    );

    $query = sprintf(
        "INSERT INTO `grader_category_grades` "
        . " (`exam_id`, `category_id`, `grader_id`, `student_id`) "
        . " VALUES %s", $values
    );
    $sql = executeQuery($query, $params);
}

/**
 * Helper function for createGraderCategoryGradesQuery()
 * Creates the values string and parameters array for the query
 *
 * @param int    $examID
 * @param string $graderID
 * @param array  $categories
 * @param array  $students
 *
 * @return array
 */
function buildGraderCategoryGradesInsertParamsValues(int $examID,
    string $graderID, array $categories, array $students
) {
    $params = array();
    $values = array();

    array_push($params, array(':examID', $examID, PDO::PARAM_INT));
    array_push($params, array(':graderID', $graderID, PDO::PARAM_STR));

    $studentKeys = array();
    foreach ($students as $i => $studentID) {
        $key = sprintf(":studentID%d", $i);
        array_push($studentKeys, $key);
        array_push($params, array($key, $studentID, PDO::PARAM_STR));
    }

    foreach ($categories as $i => $categoryID) {
        $categoryKey = sprintf(":categoryID%d", $i);
        array_push($params, array($categoryKey, $categoryID, PDO::PARAM_INT));

        foreach ($studentKeys as $studentKey) {
            $val = sprintf(
                "(:examID, %s, :graderID, %s)", $categoryKey, $studentKey
            );
            array_push($values, $val);
        }
    }

    // build values string
    $valuesStr = implode(',', $values);

    return array($valuesStr, $params);
}

/**
 * Query to insert the entries for grader/category with a set of students
 * Used if a grader is assigned during the grading state
 *
 * @param int    $examID
 * @param string $graderID
 * @param int    $categoryID
 * @param array  $students
 */
function insertGraderToGraderCategoryGradesQuery(int $examID, string $graderID,
    int $categoryID, array $students
) {
    list($values, $params) = buildInsertGraderParamsValues(
        $examID, $graderID, $categoryID, $students
    );

    $query = sprintf(
        "INSERT INTO `grader_category_grades` "
        . " (`exam_id`, `category_id`, `grader_id`, `student_id`) "
        . " VALUES %s", $values
    );
    $sql = executeQuery($query, $params);
}

/**
 * Helper function for insertGraderToGraderCategoryGradesQuery()
 * Creates values string and parameters for query
 *
 * @param int    $examID
 * @param string $graderID
 * @param int    $categoryID
 * @param array  $students
 *
 * @return array
 */
function buildInsertGraderParamsValues(int $examID, string $graderID,
    int $categoryID, array $students
) {
    $params = array();
    $values = array();

    array_push($params, array(':examID', $examID, PDO::PARAM_INT));
    array_push($params, array(':graderID', $graderID, PDO::PARAM_STR));
    array_push($params, array(':categoryID', $categoryID, PDO::PARAM_INT));

    foreach ($students as $i => $studentID) {
        $key = sprintf(":studentID%d", $i);
        array_push($params, array($key, $studentID, PDO::PARAM_STR));

        $val = sprintf("(:examID, :categoryID, :graderID, %s)", $key);
        array_push($values, $val);
    }

    // build values string
    $valuesStr = implode(',', $values);

    return array($valuesStr, $params);
}

/**
 * Query to insert student into "grader_category_grades" during grading
 *
 * @param int    $examID
 * @param string $studentID
 * @param array  $gradersCategories array of assigned graders and categories
 *                                  Element format-
 *                                  "graderID" => grader ID
 *                                  "categories" => category IDs
 */
function insertStudentToGraderCategoryGradesQuery(int $examID,
    string $studentID, array $gradersCategories
) {
    list($values, $params) = buildInsertStudentGraderCategoryParamsValues(
        $examID, $studentID, $gradersCategories
    );

    $query = sprintf(
        "INSERT INTO `grader_category_grades` "
        . " (`exam_id`, `category_id`, `grader_id`, `student_id`) "
        . " VALUES %s", $values
    );
    $sql = executeQuery($query, $params);
}

/**
 * Helper function for insertStudentToGraderCategoryGradesQuery()
 * Builds insert values and parameters for query
 *
 * @param int    $examID
 * @param string $studentID
 * @param array  $gradersCategories
 *
 * @return array
 */
function buildInsertStudentGraderCategoryParamsValues(int $examID,
    string $studentID, array $gradersCategories
) {
    $params = array();
    $values = array();

    array_push($params, array(':examID', $examID, PDO::PARAM_INT));
    array_push($params, array(':studentID', $studentID, PDO::PARAM_STR));

    $categoryKeys = array();

    foreach ($gradersCategories as $i => $assignedGraderCategories) {
        $graderID = $assignedGraderCategories["graderID"];
        $graderKey = sprintf(":graderID%d", $i);
        array_push($params, array($graderKey, $graderID, PDO::PARAM_STR));

        foreach ($assignedGraderCategories["categories"] as $categoryID) {
            $categoryKey = getCategoryKeyFromID($categoryKeys, $categoryID);
            $val = sprintf(
                "(:examID, %s, %s, :studentID)", $categoryKey, $graderKey
            );
        }
    }

    foreach ($categoryKeys as $categoryID => $categoryKey) {
        array_push($params, array($categoryKey, $categoryID, PDO::PARAM_INT));
    }

    // build values string
    $valuesStr = implode(',', $values);

    return array($valuesStr, $params);
}

/**
 * Helper function for buildInsertStudentGraderCategoryParamsValues()
 * Used to store/retrieve keys for category IDs
 * Just to simplify
 *
 * @param array $categoryKeys
 * @param int   $categoryID
 *
 * @return string             The key associated w/ the ID
 */
function getCategoryKeyFromID(array &$categoryKeys, int $categoryID)
{
    if (!array_key_exists($categoryID, $categoryKeys)) {
        $categoryKeys[$categoryID] = sprintf(":categoryID%d", $categoryID);
    }
    return $categoryKeys[$categoryID];
}

/**
 * Query to reset submissions for all graders/categories in an exam
 *
 * @param int $examID
 */
function resetAllSubmittedQuery(int $examID)
{
    $query = "UPDATE `assigned_graders` "
        . " SET `submitted` = :false "
        . " WHERE `exam_id` = :examID";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':false', false, PDO::PARAM_BOOL))
    );
}

/**
 * Query to check if given grader has submitted their scores for exam/category
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $graderID
 *
 * @return bool
 */
function isGraderCategorySubmittedQuery(int $examID, int $categoryID,
    string $graderID
) {
    $query = "SELECT `submitted` "
        . " FROM `assigned_graders` "
        . " WHERE `exam_id` = :examID AND `category_id` = :categoryID "
        . " AND `grader_id` = :graderID";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':categoryID', $categoryID, PDO::PARAM_INT),
                      array(':graderID', $graderID, PDO::PARAM_STR))
    );

    return getQueryResult($sql);
}

/**
 * Query to check if grader has submitted all assigned categories for an exam
 *
 * @param int    $examID
 * @param string $graderID
 *
 * @return bool
 */
function isGraderExamSubmittedQuery(int $examID, string $graderID)
{
    $query = "SELECT (count(*) = 0) AS `all_submitted` "
        . " FROM `assigned_graders` "
        . " WHERE `exam_id` = :examID AND `grader_id` = :graderID"
        . " AND NOT `submitted`";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':graderID', $graderID, PDO::PARAM_STR))
    );

    return getQueryResult($sql);
}

/**
 * Query to check if all graders/categories for an exam are submitted
 *
 * @param int $examID
 *
 * @return mixed
 */
function isExamSubmittedQuery(int $examID)
{
    $query = "SELECT (count(*) = 0) AS `all_submitted` "
        . " FROM `assigned_graders` "
        . " WHERE `exam_id` = :examID AND NOT `submitted`";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT))
    );

    return getQueryResult($sql);
}

/**
 * Query to get all students and points set for a grader/category/exam
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $graderID
 *
 * @return array             Rows of query result, elements
 *                           "student_id"
 *                           "points"
 */
function getGraderCategoryGradesQuery(int $examID, int $categoryID,
    string $graderID
) {
    $query = "SELECT `student_id`, `points` "
        . " FROM `grader_category_grades` "
        . " WHERE `exam_id` = :examID AND `category_id` = :categoryID "
        . " AND `grader_id` = :graderID";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':categoryID', $categoryID, PDO::PARAM_INT),
                      array(':graderID', $graderID, PDO::PARAM_STR))
    );

    return getQueryResults($sql);
}

/**
 * Query to update points for student from a given grader in category/exam
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $graderID
 * @param string $studentID
 * @param int    $points
 */
function setGraderCategoryStudentGradeQuery(int $examID, int $categoryID,
    string $graderID, string $studentID, int $points
) {
    $query
        = "UPDATE `grader_category_grades` "
        . " SET `points` = :points "
        . " WHERE `exam_id` = :examID AND `category_id` = :categoryID "
        . " AND `grader_id` = :graderID AND `student_id` = :studentID";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':categoryID', $categoryID, PDO::PARAM_INT),
                      array(':graderID', $graderID, PDO::PARAM_STR),
                      array(':studentID', $studentID, PDO::PARAM_STR),
                      array(':points', $points, PDO::PARAM_INT))
    );
}

/**
 * Query to set the submitted value for a grader/exam/category
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $graderID
 * @param bool   $submitted
 */
function setGraderCategorySubmittedQuery(int $examID, int $categoryID,
    string $graderID, bool $submitted
) {
    $query
        = "UPDATE `assigned_graders` "
        . " SET `submitted` = :submitted "
        . " WHERE `exam_id` = :examID AND `category_id` = :categoryID "
        . " AND `grader_id` = :graderID";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':categoryID', $categoryID, PDO::PARAM_INT),
                      array(':graderID', $graderID, PDO::PARAM_STR),
                      array(':submitted', $submitted, PDO::PARAM_BOOL))
    );
}

/**
 * Query to check that all points are set for students by a grader
 * for a category/exam
 * Note: points being set means non-null
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $graderID
 *
 * @return bool
 */
function allGraderCategoryPointsSetQuery(int $examID, int $categoryID,
    string $graderID
) {
    $query
        = "SELECT (count(*) = 0) AS `all_set` "
        . " FROM `grader_category_grades` "
        . " WHERE `exam_id` = :examID AND `category_id` = :categoryID "
        . " AND `grader_id` = :graderID AND `points` IS NULL";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':categoryID', $categoryID, PDO::PARAM_INT),
                      array(':graderID', $graderID, PDO::PARAM_STR))
    );

    return getQueryResult($sql);
}

/**
 * Query to create row in "student_category_grades"
 * For student/category
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $studentID
 * @param int    $points
 * @param bool   $conflict
 */
function createStudentCategoryGradeQuery(int $examID, int $categoryID,
    string $studentID, int $points, bool $conflict
) {
    $query
        = "INSERT INTO `student_category_grades` "
        . " (`exam_id`, `category_id`, `student_id`, `points`, `conflict`) "
        . " VALUES (:examID, :categoryID, :studentID, :point, :conflict)";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':categoryID', $categoryID, PDO::PARAM_INT),
                      array(':studentID', $studentID, PDO::PARAM_STR),
                      array(':points', $points, PDO::PARAM_INT),
                      array(':conflict', $conflict, PDO::PARAM_BOOL))
    );
}

/**
 * Query to get all points for a category/student from each grader
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $studentID
 *
 * @return array             Array of query result rows
 *                           "points" => points set by 1 grader (can be null)
 */
function getStudentCategoryPointsQuery(int $examID, int $categoryID,
    string $studentID
) {
    $query
        = "SELECT `points` "
        . " FROM `student_category_grades` "
        . " WHERE `exam_id` = :examID AND `category_id` = :categoryID "
        . " AND `student_id` = :studentID";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':categoryID', $categoryID, PDO::PARAM_INT),
                      array(':studentID', $studentID, PDO::PARAM_STR))
    );

    return getQueryResults($sql);
}

/**
 * Query to set the points/conflict for a student category grade
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $studentID
 * @param int    $points
 * @param bool   $conflict
 */
function setStudentCategoryGradeQuery(int $examID, int $categoryID,
    string $studentID, int $points, bool $conflict
) {
    $query
        = "UPDATE `student_category_grades` "
        . " SET `points` = :points, `conflict` = :conflict "
        . " WHERE `exam_id` = :examID AND `category_id` = :categoryID "
        . " AND `student_id` = :studentID";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':categoryID', $categoryID, PDO::PARAM_INT),
                      array(':studentID', $studentID, PDO::PARAM_STR),
                      array(':points', $points, PDO::PARAM_INT),
                      array(':submitted', $conflict, PDO::PARAM_BOOL))
    );
}

/**
 * Query to get if any conflicts exist for an exam
 *
 * @param int $examID
 *
 * @return bool
 */
function conflictsExistQuery(int $examID)
{
    $query
        = "SELECT (count(*) > 0) AS `conflicts_exist` "
        . " FROM `student_category_grades` "
        . " WHERE `exam_id` = :examID AND `conflict`";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT))
    );

    return getQueryResult($sql);
}

/**
 * Query to get all conflicts
 *
 * @param int $examID
 *
 * @return array      List of query result rows, elements
 *                    "student_id"
 *                    "category_id"
 */
function getConflictsQuery(int $examID)
{
    $query
        = "SELECT `student_id`, `category_id` "
        . " FROM `student_category_grades` "
        . " WHERE `exam_id` = :examID AND `conflict`";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT))
    );

    return getQueryResults($sql);
}

/**
 * Query to create row in "exam_grades" for student
 *
 * @param int    $examID
 * @param string $studentID
 * @param int    $points
 * @param bool   $passed
 */
function createStudentExamGradeQuery(int $examID, string $studentID,
    int $points, bool $passed
) {
    $query
        = "INSERT INTO `exam_grades` "
        . " (`exam_id`, `student_id`, `points`, `passed`) "
        . " VALUES (:examID, :studentID, :point, :passed)";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':studentID', $studentID, PDO::PARAM_STR),
                      array(':points', $points, PDO::PARAM_INT),
                      array(':passed', $passed, PDO::PARAM_BOOL))
    );
}

/**
 * Query to get the student's grades per exam category
 *
 * @param int    $examID
 * @param string $studentID
 *
 * @return array            Array of query results, elements
 *                          "category_id"
 *                          "points"
 */
function getStudentCategoryGradesQuery(int $examID, string $studentID)
{
    $query
        = "SELECT `category_id`, `points` "
        . " FROM `student_category_grades` "
        . " WHERE `exam_id` = :examID AND `student_id` = :studentID";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':studentID', $studentID, PDO::PARAM_STR))
    );

    return getQueryResults($sql);
}

/**
 * Query to get the grade for a student/category
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $studentID
 *
 * @return int
 */
function getStudentCategoryGradeQuery(int $examID, int $categoryID,
    string $studentID
) {
    $query
        = "SELECT `points` "
        . " FROM `student_category_grades` "
        . " WHERE `exam_id` = :examID AND `category_id` = :categoryID "
        . " AND `student_id` = :studentID";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':categoryID', $categoryID, PDO::PARAM_INT),
                      array(':studentID', $studentID, PDO::PARAM_STR))
    );

    return getQueryResult($sql);
}

/**
 * Query to get exam grade for a student/exam
 *
 * @param int    $examID
 * @param string $studentID
 *
 * @return int
 */
function getStudentExamGradeQuery(int $examID, string $studentID)
{
    $query
        = "SELECT `points` "
        . " FROM `exam_grades` "
        . " WHERE `exam_id` = :examID AND `student_id` = :studentID";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':studentID', $studentID, PDO::PARAM_STR))
    );

    return getQueryResult($sql);
}

/**
 * Query to get exam grade comment for student/exam
 *
 * @param int    $examID
 * @param string $studentID
 *
 * @return string|null
 */
function getStudentExamGradeCommentQuery(int $examID, string $studentID)
{
    $query
        = "SELECT `comment` "
        . " FROM `exam_grades` "
        . " WHERE `exam_id` = :examID AND `student_id` = :studentID";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':studentID', $studentID, PDO::PARAM_STR))
    );

    return getQueryResult($sql);
}

/**
 * Query to get exam grade passed for student/exam
 *
 * @param int    $examID
 * @param string $studentID
 *
 * @return bool
 */
function getStudentExamGradePassedQuery(int $examID, string $studentID)
{
    $query
        = "SELECT `passed` "
        . " FROM `exam_grades` "
        . " WHERE `exam_id` = :examID AND `student_id` = :studentID";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':studentID', $studentID, PDO::PARAM_STR))
    );

    return getQueryResult($sql);
}

/**
 * Query to get exam grade details for student/exam
 *
 * @param int    $examID
 * @param string $studentID
 *
 * @return array            array of query result row, elements
 *                          "points"
 *                          "passed"
 */
function getStudentExamGradeDetailsQuery(int $examID, string $studentID)
{
    $query
        = "SELECT `points`, `passed` "
        . " FROM `exam_grades` "
        . " WHERE `exam_id` = :examID AND `student_id` = :studentID";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':studentID', $studentID, PDO::PARAM_STR))
    );

    return getQueryResultRow($sql);
}

/**
 * Query to get full details for exam grade for student/exam
 *
 * @param int    $examID
 * @param string $studentID
 *
 * @return array            array of query result row, elements
 *                          "points"
 *                          "passed"
 *                          "comment'
 */
function getStudentExamGradeFullQuery(int $examID, string $studentID)
{
    $query
        = "SELECT `points`, `passed`, `comment` "
        . " FROM `exam_grades` "
        . " WHERE `exam_id` = :examID AND `student_id` = :studentID";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':studentID', $studentID, PDO::PARAM_STR))
    );

    return getQueryResultRow($sql);
}

/**
 * Query to get the graders and points set for a student/category
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $studentID
 *
 * @return array             Rows of query result, elements
 *                           "grader_id"
 *                           "points"
 */
function getStudentCategoryGraderPointsQuery(int $examID, int $categoryID,
    string $studentID
) {
    $query
        = "SELECT `grader_id`, `points` "
        . " FROM `grader_category_grades` "
        . " WHERE `exam_id` = :examID AND `category_id` = :graderCategory "
        . " AND `student_id` = :studentID";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':categoryID', $categoryID, PDO::PARAM_INT),
                      array(':studentID', $studentID, PDO::PARAM_STR))
    );

    return getQueryResults($sql);
}

/**
 * Query to update a row in "exam_grades"
 *
 * @param int         $examID
 * @param string      $studentID
 * @param int         $points
 * @param bool        $passed
 * @param string|null $comment
 */
function setStudentExamEntryQuery(int $examID, string $studentID, int $points,
    bool $passed, string $comment
) {
    $query = "UPDATE `exam_grades` "
        . " SET `points` = :points, `passed` = :passed, `comment` = :comment "
        . " WHERE `exam_id` = :examID AND `student_id` = :studentID";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':studentID', $studentID, PDO::PARAM_STR),
                      array(':points', $points, PDO::PARAM_INT),
                      array(':passed', $passed, PDO::PARAM_BOOL),
                      array(':comment', $comment, PDO::PARAM_STR))
    );
}

/**
 * Query to update points for a student's exam grade
 *
 * @param int    $examID
 * @param string $studentID
 * @param int    $points
 */
function setStudentExamPointsQuery(int $examID, string $studentID, int $points)
{
    $query = "UPDATE `exam_grades` "
        . " SET `points` = :points "
        . " WHERE `exam_id` = :examID AND `student_id` = :studentID";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':studentID', $studentID, PDO::PARAM_STR),
                      array(':points', $points, PDO::PARAM_INT))
    );
}

/**
 * Query to update exam passed for student's exam grade
 *
 * @param int    $examID
 * @param string $studentID
 * @param bool   $passed
 */
function setStudentExamPassedQuery(int $examID, string $studentID, bool $passed)
{
    $query = "UPDATE `exam_grades` "
        . " SET `passed` = :passed "
        . " WHERE `exam_id` = :examID AND `student_id` = :studentID";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':studentID', $studentID, PDO::PARAM_STR),
                      array(':passed', $passed, PDO::PARAM_BOOL))
    );
}

/**
 * Query to update exam comment for student's exam grade
 *
 * @param int    $examID
 * @param string $studentID
 * @param string $comment
 */
function setStudentExamCommentQuery(int $examID, string $studentID,
    string $comment
) {
    $query = "UPDATE `exam_grades` "
        . " SET `comment` = :comment "
        . " WHERE `exam_id` = :examID AND `student_id` = :studentID";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':studentID', $studentID, PDO::PARAM_STR),
                      array(':comment', $comment, PDO::PARAM_STR))
    );
}

/**
 * Query to check if a student has passed any exam
 *
 * @param string $studentID
 *
 * @return bool
 */
function hasPassedExamQuery(string $studentID)
{
    $query
        = "SELECT (count(*) > 0) AS `passed_exam` "
        . " FROM "
        . "( SELECT `exam_id` FROM `exam_grades` "
        . "  WHERE `student_id` = :studentID AND `passed`) examsPassed "
        . " join "
        . "( SELECT `id` FROM `exams` "
        . "  WHERE `state` = :archivedState) examsArchived "
        . " ON (`examsPassed`.`exam_id` = `examsArchived`.`id`)";

    $sql = executeQuery(
        $query, array(array(':studentID', $studentID, PDO::PARAM_STR),
                      array(':archivedState', EXAM_STATE_ARCHIVED,
                            PDO::PARAM_INT))
    );

    return getQueryResult($sql);
}

/**
 * Get count of exams passed for a student
 * Only checks exams in the archived state
 *
 * @param string $studentID
 *
 * @return int
 */
function getExamsPassedCountQuery(string $studentID)
{
    $query
        = "SELECT count(*) AS `exams_passed` "
        . " FROM "
        . "( SELECT `exam_id` FROM `exam_grades` "
        . "  WHERE `student_id` = :studentID AND `passed`) examsPassed "
        . " join "
        . "( SELECT `id` FROM `exams` "
        . "  WHERE `state` = :archivedState) examsArchived "
        . " ON (`examsPassed`.`exam_id` = `examsArchived`.`id`)";

    $sql = executeQuery(
        $query, array(array(':studentID', $studentID, PDO::PARAM_STR),
                      array(':archivedState', EXAM_STATE_ARCHIVED,
                            PDO::PARAM_INT))
    );

    return getQueryResult($sql);
}

/**
 * Query to get the number of exams failed
 * Only looks at exams in the archived state
 *
 * @param string $studentID
 *
 * @return int
 */
function getExamsFailedCountQuery(string $studentID)
{
    $query
        = "SELECT count(*) AS `exams_failed` "
        . " FROM "
        . "( SELECT `exam_id` FROM `exam_grades` "
        . "  WHERE `student_id` = :studentID AND NOT `passed`) examsPassed "
        . " join "
        . "( SELECT `id` FROM `exams` "
        . "  WHERE `state` = :archivedState) examsArchived "
        . " ON (`examsPassed`.`exam_id` = `examsArchived`.`id`)";

    $sql = executeQuery(
        $query, array(array(':studentID', $studentID, PDO::PARAM_STR),
                      array(':archivedState', EXAM_STATE_ARCHIVED,
                            PDO::PARAM_INT))
    );

    return getQueryResult($sql);
}

/**
 * Query to delete entries in "grader_category_grades"
 * for an exam/grader
 *
 * @param int    $examID
 * @param string $graderID
 */
function deleteGraderExamGradesQuery(int $examID, string $graderID)
{
    $query
        = "DELETE FROM `grader_category_grades` "
        . " WHERE `exam_id` = :examID AND `grader_id` = :graderID";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':graderID', $graderID, PDO::PARAM_STR))
    );
}

/**
 * Query to delete entries in "grader_category_grades"
 * for an exam/category/grader
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $graderID
 */
function deleteGraderCategoryGradesQuery(int $examID, int $categoryID,
    string $graderID
) {
    $query
        = "DELETE FROM `grader_category_grades` "
        . " WHERE `exam_id` = :examID AND `category_id` = :categoryID "
        . " AND `grader_id` = :graderID";
    $sql = executeQuery(
        $query, array(array(':examID', $examID, PDO::PARAM_INT),
                      array(':categoryID', $categoryID, PDO::PARAM_INT),
                      array(':graderID', $graderID, PDO::PARAM_STR))
    );
}




