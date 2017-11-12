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
            return true;
        // does not allow
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
 * Does the given exam state allow the grader to view they are assigned
 *
 * @param int $state
 *
 * @return bool
 */
function doesExamStateAllowGraderViewing(int $state)
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
 * Does the exam state allow grading
 * for the finalization state, just means graders can still see the grades
 *
 * @param int $state
 *
 * @return bool
 */
function doesExamStateAllowGrading(int $state)
{
    if (!isExamStateValid($state)) {
        throw new InvalidArgumentException('Invalid exam state: ' . $state);
    }

    switch ($state) {
        // allows
        case EXAM_STATE_GRADING:
        case EXAM_STATE_FINALIZING:
            return true;
        // does not allow
        case EXAM_STATE_HIDDEN:
        case EXAM_STATE_OPEN:
        case EXAM_STATE_CLOSED:
        case EXAM_STATE_IN_PROGRESS:
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
 * Get string representation of exam state
 *
 * @param int $state
 *
 * @return string
 */
function examStateToString(int $state)
{
    switch ($state) {
        // valid states
        case EXAM_STATE_HIDDEN:
            return 'hidden';
        case EXAM_STATE_OPEN:
            return 'open';
        case EXAM_STATE_CLOSED:
            return 'closed';
        case EXAM_STATE_IN_PROGRESS:
            return 'in progress';
        case EXAM_STATE_GRADING:
            return 'grading';
        case EXAM_STATE_FINALIZING:
            return 'finalizing';
        case EXAM_STATE_ARCHIVED:
            return 'archived';
        case EXAM_STATE_INVALID:
        default:
            return 'invalid';
    }
}

/**
 * Get string for student state
 *
 * @param int $state
 *
 * @return string
 */
function studentStateToString(int $state)
{
    switch($state) {
        case STUDENT_STATE_READY:
            return "Ready";
        case STUDENT_STATE_REGISTERED:
            return "Registered";
        case STUDENT_STATE_PASSED:
            return "Passed";
        case STUDENT_STATE_BLOCKED:
            return "Blocked";
        case STUDENT_STATE_BLOCKED_BYPASSED:
            return "Block Bypassed";
        case STUDENT_STATE_BLOCKED_IGNORED:
            return "Block Ignored";
        case STUDENT_STATE_INVALID:
        default:
            return "Invalid";
    }
}

/**
 * Check if given student state can register for an exam
 *
 * @param int $state
 *
 * @return bool
 */
function canStudentStateRegister(int $state)
{
    switch($state) {
        case STUDENT_STATE_READY:
        case STUDENT_STATE_BLOCKED_BYPASSED:
            return true;
        case STUDENT_STATE_REGISTERED:
        case STUDENT_STATE_PASSED:
        case STUDENT_STATE_BLOCKED:
        case STUDENT_STATE_BLOCKED_IGNORED:
        case STUDENT_STATE_INVALID:
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
    }
}

/**
 * Refresh given exam (check state and transition)
 *
 * @param int $id
 */
function refreshExam(int $examID)
{
    $state = getExamState($examID);
    if (!isExamStateValid($state)) {
        error_log("Exam({$examID}) state({$state}) is invalid");
    }

    try {
        switch ($state) {
            case EXAM_STATE_HIDDEN:
                refreshExamStateHidden($examID);
                break;
            case EXAM_STATE_OPEN:
                refreshExamStateOpen($examID);
                break;
            case EXAM_STATE_CLOSED:
                refreshExamStateClosed($examID);
                break;
            case EXAM_STATE_IN_PROGRESS:
                refreshExamStateInProgress($examID);
                break;
            case EXAM_STATE_GRADING:
                refreshExamStateGrading($examID);
                break;
            case EXAM_STATE_FINALIZING:
                refreshExamStateFinalizing($examID);
                break;
            case EXAM_STATE_ARCHIVED:
                refreshExamStateArchived($examID);
                break;
        }
    } catch (Exception $e) {
        error_log(
            "Exception during exam({$examID}) state({$state}) refresh, {$e}"
        );

        try {
            if (inTransaction()) {
                rollback();
            }
        } catch (Exception $e) {
            error_log(
                "exam({$examID}) state({$state}) refresh, failed to cancel active transaction, {$e}"
            );
        }
    }


}

/**
 * Internal function
 * Refreshes exam in the 'hidden' state
 * Checks if an exam should be open
 *
 * @param int $examID
 */
function refreshExamStateHidden(int $examID)
{
    $info = getExamInformation($examID);
    $start = $info['start'];

    $whenToOpenBeforeStart = DateInterval::createFromDateString(
        TIME_BEFORE_OPENING_EXAM_REGISTRATION
    );
    $openOn = date_sub($start, $whenToOpenBeforeStart);

    if (currentDatePassed($openOn)) {
        // TODO log transitioning exam state
        setExamState($examID, EXAM_STATE_OPEN);
    }
}

/**
 * Internal function
 * Refreshes exam in the 'open' state
 * Checks if an exam should have registration closed
 *
 * @param int $examID
 */
function refreshExamStateOpen(int $examID)
{
    $info = getExamInformation($examID);
    $cutoff = $info['cutoff'];

    if (currentDatePassed($cutoff)) {
        // TODO log transitioning exam state
        setExamState($examID, EXAM_STATE_CLOSED);
    }
}

/**
 * Internal function
 * Refreshes exam in the 'closed' state
 * Checks if exam should be in 'in-progress' state
 *
 * @param int $examID
 */
function refreshExamStateClosed(int $examID)
{
    $info = getExamInformation($examID);
    $start = $info['start'];

    if (currentDatePassed($start)) {
        // TODO log transitioning exam state
        transitionExamToInProgress($examID);
    }
}

/**
 * Internal function
 * Refreshes exam in the 'in-progress' state
 * Check if exam should be over
 *
 * @param int $examID
 */
function refreshExamStateInProgress(int $examID)
{
    $info = getExamInformation($examID);
    $start = $info['start'];
    $minutes = $info['length'];
    $interval = new DateInterval("PT{$minutes}M");
    $end = date_add($start, $interval);

    if (!currentDatePassed($end)) {
        return;
    }

    transitionExamToGrading($examID);
}

/**
 * Internal function
 * Refreshes exam in the 'grading' state
 * Check if all graders have submitted
 *
 * @param int $examID
 */
function refreshExamStateGrading(int $examID)
{
    if ((count(getAssignedExamGraders($examID)) == 0)
        || !isExamSubmitted($examID)
    ) {
        return;
    }

    transitionExamToFinalization($examID);
}

/**
 * Internal function
 * Refresh exam in 'finalizing' state
 *
 * @param int $examID
 */
function refreshExamStateFinalizing(int $examID)
{
    // nothing to do, finalization is done by admin/teacher
}

/**
 * Internal function
 * Refresh exam in 'archived' state
 *
 * @param int $examID
 */
function refreshExamStateArchived(int $examID)
{
    // nothing to do, archived
}

/**
 * Internal function
 * Transfer exam state to 'closed'
 *
 * @param int $examID
 */
function transitionExamToClosed(int $examID)
{
    startTransaction();

    setExamState($examID, EXAM_STATE_CLOSED);

    assignExamSeats($examID);

    commit();
}

/**
 * Internal function
 * Transfer exam state to 'in-progress'
 *
 * @param int $examID
 */
function transitionExamToInProgress(int $examID)
{
    setExamState($examID, EXAM_STATE_IN_PROGRESS);
}

/**
 * Internal function
 * Transfer exam state to grading
 *
 * @param int $examID
 */
function transitionExamToGrading(int $examID)
{
    startTransaction();

    setExamState($examID, EXAM_STATE_GRADING);

    createGraderStudentCategoryGradeEntries($examID);

    commit();
}

/**
 * Internal function
 * Transfer exam state to finalization
 *
 * @param int $examID
 */
function transitionExamToFinalization(int $examID)
{
    startTransaction();

    setExamState($examID, EXAM_STATE_FINALIZING);

    createStudentCategoryGrades($examID);
    createStudentExamGrades($examID);

    commit();
}

/**
 * Internal function
 * Transfer exam state to archived
 *
 * @param int $examID
 */
function transitionExamToArchived(int $examID)
{
    startTransaction();

    setExamState($examID, EXAM_STATE_ARCHIVED);

    refreshStudentRegistrationStates($examID);

    // TODO: cleanup of now unused grading / registration values
    // registration can be derived from exam grades

    commit();
}