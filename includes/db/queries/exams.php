<?php
/**
 * Query functions for exams
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Database
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
