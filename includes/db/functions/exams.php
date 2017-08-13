<?php
/**
 * Functions for exams in database
 *
 * @author         Mathew McCain
 * @author         Curran Higgins
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

// TODO: get exams w/ teacher ID

// search exams
/// state, date/time (quarter?), in class

// get exam information
function getExamInformation(int $id)
{
}

// create exam
function createExam()
{
}

// update exam

// update exam location
/// re-assign seats

// add/remove category for exam
/// check state, check for assigned graders
// get categories for exam

// refresh/check exam state
// set exam state
// finalize exam

// create in class exam
// assign teacher(s)
// get teacher
// search exams by in class/teacher
// get non-archived exams for teacher

// set location for exam
// get location for exam
// update location for exam
/// check if seat count difference will cause an issue (block, return false)
/// randomize rooms/seats

// updating exam information
/// date/times, passing grade


