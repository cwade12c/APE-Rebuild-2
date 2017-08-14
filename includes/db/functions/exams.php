<?php
/**
 * Functions for exams in database
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Database
 */

/**
 * Get list of IDs for all exams (all states)
 *
 * @param int $type for the type of exam (both, regular, in class)
 *                  use get exam type values defined in db 'constants.php'
 *
 * @return mixed
 */
function getExamsAll(int $type = GET_EXAMS_TYPE_BOTH)
{
    return getExams(GET_EXAMS_ALL, $type);
}

/**
 * Get list of IDs for archived exams
 *
 * @param int $type for the type of exam (both, regular, in class)
 *                  use get exam type values defined in db 'constants.php'
 *
 * @return mixed
 */
function getExamsArchived(int $type = GET_EXAMS_TYPE_BOTH)
{
    return getExams(GET_EXAMS_ARCHIVED, $type);
}

/**
 * Get list of IDs for all non-archived exams
 *
 * @param int $type
 *
 * @return mixed
 */
function getExamsNonArchived(int $type = GET_EXAMS_TYPE_BOTH)
{
    return getExams(GET_EXAMS_NON_ARCHIVED, $type);
}

/**
 * Get list of IDs for exams in finalizing state
 *
 * @param int $type for the type of exam (both, regular, in class)
 *                  use get exam type values defined in db 'constants.php'
 *
 * @return mixed
 */
function getExamsFinalizing(int $type = GET_EXAMS_TYPE_BOTH)
{
    return getExams(GET_EXAMS_FINALIZING, $type);
}

/**
 * Get list of IDs for exams in grading state
 *
 * @param int $type for the type of exam (both, regular, in class)
 *                  use get exam type values defined in db 'constants.php'
 *
 * @return mixed
 */
function getExamsGrading(int $type = GET_EXAMS_TYPE_BOTH)
{
    return getExams(GET_EXAMS_GRADING, $type);
}

/**
 * Get list of IDs for upcoming exams (states hidden, open, closed, in progress)
 *
 * @param int $type for the type of exam (both, regular, in class)
 *                  use get exam type values defined in db 'constants.php'
 *
 * @return mixed
 */
function getExamsUpcoming(int $type = GET_EXAMS_TYPE_BOTH)
{
    return getExams(GET_EXAMS_OPEN, $type);
}

/**
 * Get list of IDs for exams w/ given information
 * Recommended to use other get exam functions for future proofing.
 *
 * @param int $state state as defined under get exam states
 *                   defined in db 'constants'.php
 * @param int $type  for the type of exam (both, regular, in class)
 *                   use get exam type values defined in db 'constants.php'
 *
 * @return mixed
 */
function getExams(int $state, int $type)
{
    // TODO: validate state and type valid
    return getExamsQuery($state, $type);
}

// TODO: get exams w/ teacher ID, archived / non-archived
/// rename getExams to getExamsExtended

// search exams
/// state, date/time (quarter?), in class
/// location/rooms

// get exam information
function getExamInformation(int $id)
{
    // TODO: validate id exists
    // TODO: convert information from query results
    return getExamInformationQuery($id);
}

// get exam categories
function getExamCategories(int $id)
{
    // TODO: validate id exists
}

// get teacher id for in class exam
function getInClassExamTeacher(int $id) {
    // TODO: validate id exists
    // TODO: determine if exam is in class
    // TODO: get teacher id
}

// create exam
function createExam()
{
    // arguments
    // start datetime, cutoff registration datetime
    // int length, int passing_grade
    // int location id (can be null?)

    // call extended create

}

// create in class exam
function createInClassExam()
{
    // arguments
    // start datetime, cutoff registration datetime
    // int length, int passing_grade
    // int location id (can be null?)
    // int teacher id
    // list of category ids

    // call extended create
}

// create exam extended (internal only)
function createExamExtended()
{
    // arguments-

    // TODO: validate arguments
    // TODO: check for conflicting information w/ existing non-archived exams

    // TODO: validate success
    // TODO: return exam id ?
}

// update exam
function updateExam(int $id, DateTime $start, DateTime $cutoff, int $length,
    int $passing_grade, int $location_id, array $categoryIDs
) {
    // TODO: validate arguments
    /// id exists, datetime(s) valid, length valid, passing_grade valid (reachable)
    /// location (registration count?)
    // TODO: does state allow editing ?

    // get current exam information

    // TODO: determine add/remove categories

    // TODO: re-assign seats for location change

    // TODO: validate success
}

// refresh exam state, transition
function refreshExam(int $id)
{
    /**
     * get exam information
     * check state, check conditions accordingly and transition
     */
}

// set state of exam (internal)
/// handle transistions ?
function setExamState(int $id, int $state) {}
