<?php
/**
 * functions related to exam states
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Database
 */

// TODO: using an enum for the states would allow these functions to be simplified

/**
 * Check if given exam state allows the exam information to be updated
 * by an admin/teacher
 *
 * @param int $state
 *
 * @return bool
 */
function doesExamStateAllowUpdates(int $state)
{
    if (!isExamStateValid($state)) {
        throw new InvalidArgumentException('Invalid exam state: ' . $state);
    }

    switch ($state) {
        // allows
        case EXAM_STATE_HIDDEN:
        case EXAM_STATE_OPEN:
        case EXAM_STATE_CLOSED:
        case EXAM_STATE_IN_PROGRESS:
        case EXAM_STATE_GRADING:
        case EXAM_STATE_FINALIZING:
            return true;
        // does not allow
        case EXAM_STATE_ARCHIVED:
            return false;
        // invalid state
        default:
            throw new InvalidArgumentException(
                'Unhandled exam state: ' . $state
            );
    }
}

/**
 * Check if the exam state allows students to register
 *
 * @param int $state
 *
 * @return bool
 */
function doesExamStateAllowRegistration(int $state)
{
    if (!isExamStateValid($state)) {
        throw new InvalidArgumentException('Invalid exam state: ' . $state);
    }

    switch ($state) {
        // allows
        case EXAM_STATE_OPEN:
            return true;
        // does not allow
        case EXAM_STATE_HIDDEN:
        case EXAM_STATE_CLOSED:
        case EXAM_STATE_IN_PROGRESS:
        case EXAM_STATE_GRADING:
        case EXAM_STATE_FINALIZING:
        case EXAM_STATE_ARCHIVED:
            return false;
        // invalid state
        default:
            throw new InvalidArgumentException(
                'Unhandled exam state: ' . $state
            );
    }
}

/**
 * Check if exam state allows an admin/teacher to forcefully register
 * a student
 *
 * @param int $state
 *
 * @return bool
 */
function doesExamStateAllowForcedRegistration(int $state)
{
    if (!isExamStateValid($state)) {
        throw new InvalidArgumentException('Invalid exam state: ' . $state);
    }

    switch ($state) {
        // allows
        case EXAM_STATE_OPEN:
            return true;
        // does not allow
        case EXAM_STATE_HIDDEN:
        case EXAM_STATE_CLOSED:
        case EXAM_STATE_IN_PROGRESS:
        case EXAM_STATE_GRADING:
        case EXAM_STATE_FINALIZING:
        case EXAM_STATE_ARCHIVED:
            return false;
        // invalid state
        default:
            throw new InvalidArgumentException(
                'Unhandled exam state: ' . $state
            );
    }
}

/**
 * Check if the given exam state allows editing of exam attributes
 *
 * @param int $state
 *
 * @return bool
 */
function doesExamStateAllowEdits(int $state)
{
    if (!isExamStateValid($state)) {
        throw new InvalidArgumentException('Invalid exam state: ' . $state);
    }

    switch ($state) {
        // allows
        case EXAM_STATE_HIDDEN:
        case EXAM_STATE_OPEN:
        case EXAM_STATE_CLOSED:
        case EXAM_STATE_IN_PROGRESS:
            return true;
        // does not allow
        case EXAM_STATE_GRADING:
        case EXAM_STATE_FINALIZING:
        case EXAM_STATE_ARCHIVED:
            return false;
        // invalid state
        default:
            throw new InvalidArgumentException(
                'Unhandled exam state: ' . $state
            );
    }
}

/**
 * Check if the exam state allows graders to be assigned
 *
 * @param int $state
 *
 * @return bool
 */
function doesExamStateAllowGraderAssignments(int $state)
{
    if (!isExamStateValid($state)) {
        throw new InvalidArgumentException('Invalid exam state: ' . $state);
    }

    switch ($state) {
        // allows
        case EXAM_STATE_HIDDEN:
        case EXAM_STATE_OPEN:
        case EXAM_STATE_CLOSED:
        case EXAM_STATE_IN_PROGRESS:
        case EXAM_STATE_GRADING:
            return true;
        // does not allow
        case EXAM_STATE_FINALIZING:
        case EXAM_STATE_ARCHIVED:
            return false;
        // invalid state
        default:
            throw new InvalidArgumentException(
                'Unhandled exam state: ' . $state
            );
    }
}

/**
 * Check if exam state allows graders to be un-assigned
 *
 * @param int $state
 *
 * @return bool
 */
function doesExamStateAllowGraderRemovals(int $state)
{
    if (!isExamStateValid($state)) {
        throw new InvalidArgumentException('Invalid exam state: ' . $state);
    }

    switch ($state) {
        // allows
        case EXAM_STATE_HIDDEN:
        case EXAM_STATE_OPEN:
        case EXAM_STATE_CLOSED:
        case EXAM_STATE_IN_PROGRESS:
        case EXAM_STATE_GRADING:
            return true;
        // does not allow
        case EXAM_STATE_FINALIZING:
        case EXAM_STATE_ARCHIVED:
            return false;
        // invalid state
        default:
            throw new InvalidArgumentException(
                'Unhandled exam state: ' . $state
            );
    }
}

/**
 * Check if given exam state is valid (recognized in 'db/constants.php')
 *
 * @param int $state
 *
 * @return bool
 */
function isExamStateValid(int $state)
{
    switch ($state) {
        // valid states
        case EXAM_STATE_HIDDEN:
        case EXAM_STATE_OPEN:
        case EXAM_STATE_CLOSED:
        case EXAM_STATE_IN_PROGRESS:
        case EXAM_STATE_GRADING:
        case EXAM_STATE_FINALIZING:
        case EXAM_STATE_ARCHIVED:
            return true;
        // invalid states
        case EXAM_STATE_INVALID:
        default:
            return false;
    }
}

/**
 * Refresh all exams
 * Not intended to be called from a client action
 */
function refreshExams()
{
    $exams = getExamsAll();
    foreach ($exams as $id) {
        refreshExam($id);
        // TODO: return value to issues to log ?
    }
}

/**
 * Refresh given exam (check state and transition)
 *
 * @param int $id
 */
function refreshExam(int $id)
{
    $state = getExamState($id);
    if (!isExamStateValid($state)) {
        // TODO: log error of some kind
        return;
    }

    switch ($state) {
        case EXAM_STATE_HIDDEN:
            refreshExamStateHidden($id);
            break;
        case EXAM_STATE_OPEN:
            refreshExamStateOpen($id);
            break;
        case EXAM_STATE_CLOSED:
            refreshExamStateClosed($id);
            break;
        case EXAM_STATE_IN_PROGRESS:
            refreshExamStateInProgress($id);
            break;
        case EXAM_STATE_GRADING:
            refreshExamStateGrading($id);
            break;
        case EXAM_STATE_FINALIZING:
            refreshExamStateFinalizing($id);
            break;
        case EXAM_STATE_ARCHIVED:
            refreshExamStateArchived($id);
            break;
    }
}

function refreshExamStateHidden(int $examID)
{
    // TODO: populate
    /*
     * Check if registration should be open
     *  transition to open
     */
}

function refreshExamStateOpen(int $examID)
{
    // TODO: populate
    /*
     * Check if cutoff date/time reached
     *  transition to closed
     * Check if location ID set ?
     *
     *
     */
}

function refreshExamStateClosed(int $examID)
{
    // TODO: populate
    /*
     * check if start date/time reached
     *  transition to in progress
     * check if location ID set ?
     */
}

function refreshExamStateInProgress(int $examID)
{
    // TODO: populate
    /*
     * check if length passed since start time
     *  transition to grading
     */
}

function refreshExamStateGrading(int $examID)
{
    // TODO: populate
    /*
     * check if all submitted (in case manual transition did not occur)
     * log that all submitted, but no transition ?
     */
}

function refreshExamStateFinalizing(int $examID)
{
    // TODO: populate
    /*
     * check if submitted by admin/teacher ?
     * no transitions, log?
     */
}

function refreshExamStateArchived(int $examID)
{
    // TODO: populate
}

/**
 * Transfer exam state to in-progress
 *
 * @param int $examID
 */
function transitionExamToInProgress(int $examID)
{
    // TODO: populate
    // use transaction
    // assign seats
}

/**
 * Transfer exam state to grading
 *
 * @param int $examID
 */
function transitionExamToGrading(int $examID)
{
    // TODO: populate
    // use transaction
}

/**
 * Transfer exam state to finalization
 *
 * @param int $examID
 */
function transitionExamToFinalization(int $examID)
{
    // TODO: populate
    // use transaction
}

/**
 * Transfer exam state to archived
 *
 * @param int $examID
 */
function transitionExamToArchived(int $examID)
{
    // TODO: populate
    // use transaction
}