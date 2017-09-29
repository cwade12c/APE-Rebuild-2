<?php
/**
 * Query functions for exams
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Database
 */

/**
 * Query to get list of exam IDs based on criteria
 *
 * @param int $state state of exam to find
 * @param int $type
 *
 * @return mixed        list of exam IDs
 */
function getExamsQuery(int $state, int $type)
{
    // build query string
    list($stateStr, $typeStr, $params) = buildFindExamsStateTypeStrings(
        $state, $type
    );
    // execute
    $query = sprintf(
        "SELECT `id` FROM `exams`"
        . " WHERE (%s && %s)", $stateStr, $typeStr
    );
    $sql = executeQuery($query, $params);

    return getQueryResults($sql);
}

/**
 * Builds the necessary boolean comparison strings and parameters array
 * for the function getExamsQuery().
 * Not intended for outside use.
 *
 * @param int $state
 * @param int $type
 *
 * @return array
 */
function buildFindExamsStateTypeStrings(int $state, int $type)
{
    list($stateStr, $params) = buildFindExamStateString($state);
    $typeStr = buildFindExamTypeString($type);

    return array($stateStr, $typeStr, $params);
}

/**
 * Builds the string and params array
 * to be used in a select query to find an exam by a state/set of states.
 * Only used internally by function buildFindExamsStateTypeStrings()
 * Not intended for outside use
 *
 * @param int $state a get exams value defined in db 'constants.php'
 *
 * @return array
 */
function buildFindExamStateString(int $state)
{
    $params = array();

    // build state boolean statement
    $stateCompares = array();
    if ($state == GET_EXAMS_ALL) {
        array_push($stateCompares, "true");
    } elseif ($state == GET_EXAMS_OPEN) {
        // states: hidden, open, closed, in progress
        // push comparisons
        array_push($stateCompares, "`state` == :state_hidden");
        array_push($stateCompares, "`state` == :state_open");
        array_push($stateCompares, "`state` == :state_closed");
        array_push($stateCompares, "`state` == :state_in_progress");
        // push params for states
        array_push(
            $params, array(':state_hidden', EXAM_STATE_HIDDEN, PDO::PARAM_INT)
        );
        array_push(
            $params, array(':state_open', EXAM_STATE_OPEN, PDO::PARAM_INT)
        );
        array_push(
            $params, array(':state_closed', EXAM_STATE_CLOSED, PDO::PARAM_INT)
        );
        array_push(
            $params,
            array(':state_in_progress', EXAM_STATE_IN_PROGRESS, PDO::PARAM_INT)
        );
    } elseif ($state == GET_EXAMS_GRADING) {
        // grading state
        // comparison
        array_push($stateCompares, "`state` == :state_grading");
        // params
        array_push(
            $params,
            array(':state_grading', EXAM_STATE_GRADING, PDO::PARAM_INT)
        );
    } elseif ($state == GET_EXAMS_FINALIZING) {
        // finalizing state
        // comparison
        array_push($stateCompares, "`state` == :state_finalizing");
        // params
        array_push(
            $params,
            array(':state_finalizing', EXAM_STATE_FINALIZING, PDO::PARAM_INT)
        );
    } elseif ($state == GET_EXAMS_NON_ARCHIVED) {
        // any state but archived
        // comparison
        array_push($stateCompares, "`state` != :state_archived");
        // params
        array_push(
            $params,
            array(':state_archived', EXAM_STATE_ARCHIVED, PDO::PARAM_INT)
        );
    } elseif ($state == GET_EXAMS_ARCHIVED) {
        // archived state
        // comparison
        array_push($stateCompares, "`state` != :state_archived");
        // params
        array_push(
            $params,
            array(':state_archived', EXAM_STATE_ARCHIVED, PDO::PARAM_INT)
        );
    }
    // build string for state boolean statement
    // wrap each comparison w/ '()'
    if (count($stateCompares) > 1) {
        $wrapCompare = function (string $val) {
            return sprintf("(%s)", $val);
        };
        $stateCompares = array_map($wrapCompare, $stateCompares);

    }
    $stateStr = sprintf("(%s)", implode('||', $stateCompares));

    return array($stateStr, $params);
}

/**
 * Builds the string to find an exam type (regular, in class)
 * in a select query.
 * Only used internally by function buildFindExamsStateTypeStrings()
 * Not intended for outside use
 *
 * @param int $type a get exam type value defined in db 'constants.php'
 *
 * @return string
 */
function buildFindExamTypeString(int $type)
{
    // build type boolean statement
    $typeStr = "";
    if ($type == GET_EXAMS_TYPE_BOTH) {
        $typeStr = "true";
    } elseif ($type == GET_EXAMS_TYPE_REGULAR) {
        $typeStr = "`is_regular`";
    } elseif ($type == GET_EXAMS_TYPE_IN_CLASS) {
        $typeStr = "!`is_regular`";
    }

    return $typeStr;
}

/**
 * Get exam row w/ given ID
 *
 * @param int $id
 *
 * @return mixed
 */
function getExamInformationQuery(int $id)
{
    $query
        = "SELECT `id`, `is_regular`, `start`, `cutoff`, "
        . " `length`, `passing_grade`, `location_id`, `state` "
        . " FROM `exams` WHERE (`id` = :id)";
    $sql = executeQuery($query, array(array(':id', $id, PDO::PARAM_INT)));

    $result = getQueryResultRow($sql);
    // convert date time values
    $result['start'] = buildDateTimeFromQuery($result['start']);
    $result['cutoff'] = buildDateTimeFromQuery($result['cutoff']);

    return $result;
}

/**
 * Get state for given exam ID
 *
 * @param int $id
 *
 * @return mixed
 */
function getExamStateQuery(int $id)
{
    $query = "SELECT `state` FROM `exams` WHERE (`id` = :id)";
    $sql = executeQuery($query, array(array(':id', $id, PDO::PARAM_INT)));

    return getQueryResultRow($sql);
}

/**
 * Get categories for an exam
 *
 * @param int $id
 *
 * @return mixed
 */
function getExamCategoriesQuery(int $id)
{
    $query = "SELECT `category_id`, `points` "
        . "FROM `exam_categories` WHERE (`id` = :id)";
    $sql = executeQuery($query, array(array(':id', $id, PDO::PARAM_INT)));

    return getQueryResults($sql);
}

/**
 * Get teacher id for an in class exam
 *
 * @param int $id
 *
 * @return mixed
 */
function getExamTeacherQuery(int $id)
{
    $query = "SELECT `teacher_id` FROM `in_class_exams` "
        . " WHERE (`id` = :id)";
    $sql = executeQuery($query, array(array(':id', $id, PDO::PARAM_INT)));

    return getQueryResult($sql);
}

/**
 * Inserts an exam with the following information
 * State will default to 0
 *
 * @param DateTime $start
 * @param DateTime $cutoff
 * @param int      $length
 * @param int      $passingGrade
 * @param int      $locationID
 * @param bool     $isRegular
 */
function createExamQuery(DateTime $start, DateTime $cutoff, int $length,
    int $passingGrade, int $locationID, bool $isRegular = true
) {
    $query = "INSERT INTO `exams`"
        . "(`is_regular`, `start`, `cutoff`, `length`, `passing_grade`, `location_id`)"
        . "VALUES (:isReg, :start, :cutoff, :len, :passingGrade, :locationID);";
    $sql = executeQuery(
        $query, array(
            array(':isReg', $isRegular, PDO::PARAM_BOOL),
            buildDateTimeStrParam(':start', $start),
            buildDateTimeStrParam(':cutoff', $cutoff),
            array(':len', $length, PDO::PARAM_INT),
            array(':passingGrade', $passingGrade, PDO::PARAM_INT),
            array(':locationID', $locationID, PDO::PARAM_INT)
        )
    );
}

/**
 * Inserts value into 'in_class_exams' table
 *
 * @param int $id
 * @param int $teacherID
 */
function createExamInClassQuery(int $id, int $teacherID)
{
    $query = "INSERT INTO `in_class_exams`(`id`,`teacher_id`)"
        . "VALUES (:id, :teacherID);";
    $sql = executeQuery(
        $query, array(
            array(':id', $id, PDO::PARAM_INT),
            array(':teacherID', $teacherID, PDO::PARAM_INT)
        )
    );
}

/**
 * Inserts exam category entries.
 *
 * @param int   $examID     ID of the exam
 * @param array $categories array of category information.
 *                          Each index must follow the format
 *                          array(
 *                          'id' => category id
 *                          'points' => points set
 *                          )
 */
function createExamCategoriesQuery(int $examID, array $categories)
{
    if (count($categories) <= 0) {
        return;
    }

    list($valuesStr, $params) = buildExamCategoriesStringParams(
        $examID, $categories
    );

    $query = sprintf(
        "INSERT INTO `exam_categories`(`id`,`category_id`,`points`)"
        . "VALUES %s;", $valuesStr
    );
    $sql = executeQuery($query, $params);
}

/**
 * Builds the parameters and values string for the function createExamCategoriesQuery()
 * Not intended for outside use
 *
 * @param int   $examID             ID of the exam
 * @param array $categories         array of category information
 *                                  each index must follow the format
 *                                  array(
 *                                  'id' => category ID
 *                                  'points' => set category points
 *                                  )
 *
 * @return array
 */
function buildExamCategoriesStringParams(int $examID, array $categories)
{
    $params = array();
    array_push($params, array(':id', $examID, PDO::PARAM_INT));
    $values = array();
    // build from list of categories
    foreach ($categories as $i => $category) {
        // build param identifiers
        $keyID = sprintf(":catID%d", $i);
        $keyPoints = sprintf(":points%d", $i);
        // build value string
        $str = sprintf("(:id, %s, %s)", $keyID, $keyPoints);
        array_push($values, $str);
        // build params
        array_push($params, array($keyID, $category['id'], PDO::PARAM_INT));
        array_push(
            $params, array($keyPoints, $category['points'], PDO::PARAM_INT)
        );
    }
    // build final string
    $valuesStr = implode(',', $values);

    return array($valuesStr, $params);
}

/**
 * Query to remove exam categories
 *
 * @param int   $id         exam id
 * @param array $categories list of category IDs to remove
 */
function removeExamCategoriesQuery(int $id, array $categories)
{
    // check that categories available
    if (count($categories) <= 0) {
        return;
    }

    list($whereStr, $params) = buildRemoveExamCategoriesStringParam(
        $id, $categories
    );

    $query = sprintf(
        "DELETE FROM `exam_categories` "
        . "WHERE %s", $whereStr
    );

    $sql = executeQuery($query, $params);
}

/**
 * Helper function for removeExamCategoriesQuery()
 * Builds the parameters and the string to match the exam categories
 * in the where section.
 * Not intended for outside use.
 *
 * @param int   $id         exam id
 * @param array $categories array of category IDs
 *
 * @return array            array of format
 *                          array(
 *                          where matching string
 *                          parameter array
 *                          )
 */
function buildRemoveExamCategoriesStringParam(int $id, array $categories)
{
    $params = array();
    array_push($params, array(':id', $id, PDO::PARAM_INT));

    $categoryIDArr = array();

    // build params from category IDs
    foreach ($categories as $i => $categoryID) {
        $keyName = sprintf(':categoryID%d', $i);
        array_push($params, array($keyName, $categoryID, PDO::PARAM_INT));

        array_push($categoryIDArr, $keyName);
    }

    // build string
    $categoryArrStr = implode(',', $categoryIDArr);
    $whereStr = sprintf(
        '(`id` = :id) && (`category_id` in (%s))', $categoryArrStr
    );

    return array($whereStr, $params);
}

/**
 * Query to update exam categories
 *
 * @param int   $id         exam id
 * @param array $categories array of categories
 *                          elements in format
 *                          array(
 *                          category_id,
 *                          points
 *                          )
 */
function updateExamCategoriesQuery(int $id, array $categories)
{
    // check that categories available
    if (count($categories) <= 0) {
        return;
    }

    list($setStr, $params) = buildUpdateExamCategoriesStringParams(
        $id, $categories
    );

    $query = sprintf(
        "UPDATE `exam_categories` "
        . "SET `points` = CASE %s ELSE `points` END "
        . "WHERE `id` = :id", $setStr
    );

    $sql = executeQuery($query, $params);
}

/**
 * Helper function for updateExamCategoriesQuery()
 * Not intended for outside use
 * Builds the 'WHEN ... THEN ... ' part of the set query string
 * and the parameters
 *
 * @param int   $id             exam id
 * @param array $categories     array of categories
 *                              elements in format
 *                              array(
 *                              'id' => category_id,
 *                              'points' => points
 *                              )
 *
 * @return array                array in format
 *                              array(
 *                              'when then' set portion string,
 *                              parameters
 *                              )
 *
 */
function buildUpdateExamCategoriesStringParams(int $id, array $categories)
{
    $params = array();
    array_push($params, array(':id', $id, PDO::PARAM_INT));

    // go through categories, build params
    // and 'when then' set string
    $whenThenStrings = array();
    foreach ($categories as $i => $category) {
        // build key names
        $keyNameID = sprintf(':id%d', $i);
        $keyNamePoints = sprintf(':points%d', $i);
        // build params
        array_push($params, array($keyNameID, $category['id'], PDO::PARAM_INT));
        array_push(
            $params, array($keyNamePoints, $category['points'], PDO::PARAM_INT)
        );
        // build str
        array_push(
            $whenThenStrings, sprintf(
                'WHEN `category_id` = %s THEN %s',
                $keyNameID, $keyNamePoints
            )
        );
    }

    $setStr = implode(' ', $whenThenStrings);

    return array($setStr, $params);
}

/**
 * Query to set the state of an exam
 *
 * @param int $id
 * @param int $state
 */
function setExamStateQuery(int $id, int $state)
{
    $query = "UPDATE `exams`"
        . "SET `state`=:state"
        . "WHERE `id`=:id;";
    $sql = executeQuery(
        $query, array(
            array(':id', $id, PDO::PARAM_INT),
            array(':nid', $state, PDO::PARAM_INT)
        )
    );
}

/**
 * Query to set location ID of an exam
 *
 * @param int $id
 * @param int $locationID
 */
function setExamLocationQuery(int $id, int $locationID)
{
    $query = "UPDATE `exams`"
        . "SET `location_id`=:locID"
        . "WHERE `id`=:id;";
    $sql = executeQuery(
        $query, array(
            array(':id', $id, PDO::PARAM_INT),
            array(':locID', $locationID, PDO::PARAM_INT)
        )
    );
}

/**
 * Query to update exam attributes
 *
 * @param int      $id
 * @param DateTime $start
 * @param DateTime $cutoff
 * @param int      $length
 * @param int      $passingGrade
 * @param int      $locationID
 */
function updateExamQuery(int $id, DateTime $start, DateTime $cutoff,
    int $length, int $passingGrade, int $locationID
) {
    $query = "UPDATE `exams` "
        . "SET `start` = :start, `cutoff` = :cutoff, `length` = :len "
        . " , `passing_grade` = :passingGrade, `location_id` = :locationID "
        . "WHERE `id` = :id";
    $sql = executeQuery(
        $query, array(
            array(':id', $id, PDO::PARAM_INT),
            buildDateTimeStrParam(':start', $start),
            buildDateTimeStrParam(':cutoff', $cutoff),
            array(':len', $length, PDO::PARAM_INT),
            array(':passingGrade', $passingGrade, PDO::PARAM_INT),
            array(':locationID', $locationID, PDO::PARAM_INT)
        )
    );
}