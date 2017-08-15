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
    return getExamsExtended(GET_EXAMS_ALL, $type);
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
    return getExamsExtended(GET_EXAMS_ARCHIVED, $type);
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
    return getExamsExtended(GET_EXAMS_NON_ARCHIVED, $type);
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
    return getExamsExtended(GET_EXAMS_FINALIZING, $type);
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
    return getExamsExtended(GET_EXAMS_GRADING, $type);
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
    return getExamsExtended(GET_EXAMS_OPEN, $type);
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
function getExamsExtended(int $state, int $type)
{
    // TODO: validate state and type valid
    return getExamsQuery($state, $type);
}

// TODO: get exams w/ teacher ID, archived / non-archived

// TODO: search exams
/// state, date/time (quarter?), in class
/// location/rooms

/**
 * Get all information from exam table entry
 *
 * @param int $id
 *
 * @return mixed
 */
function getExamInformation(int $id)
{
    // TODO: validate id exists
    // TODO: convert information from query results
    // get exam row
    $info = getExamInformationQuery($id);
    // TODO: check that row returned (or exam id existed)

    // convert date time values
    $info['start']  = buildDateTimeFromQuery($info['start']);
    $info['cutoff'] = buildDateTimeFromQuery($info['cutoff']);

    return $info;
}

/**
 * Get state for given exam ID
 *
 * @param int $id
 *
 * @return mixed
 */
function getExamState(int $id)
{
    // TODO: validate id exists
    return getExamStateQuery($id);
}

/**
 * Gets all categories for an exam, will contain
 * category_id and the points value set.
 *
 * @param int $id
 *
 * @return mixed
 */
function getExamCategories(int $id)
{
    // TODO: validate id exists
    // TODO: pull category id information? (name, default points)
    return getExamCategoriesQuery($id);
}

/**
 * Get teacher id for the exam
 *
 * @param int $id
 *
 * @return mixed
 */
function getInClassExamTeacher(int $id)
{
    // TODO: validate id exists
    // TODO: determine if exam is in class
    return getExamTeacherQuery($id);
}

/**
 * Create a regular exam
 *
 * @param DateTime $start
 * @param DateTime $cutoff
 * @param int      $minutes
 * @param int      $passing_grade
 * @param array    $categories
 * @param int      $locationID
 */
function createExam(DateTime $start, DateTime $cutoff, int $minutes,
    int $passing_grade, array $categories, int $locationID
) {
    // call extended create
    createExamExtended(
        $start, $cutoff, $minutes, $passing_grade, $categories, $locationID
    );
}

/**
 * Create an in-class exam
 *
 * @param DateTime $start
 * @param DateTime $cutoff
 * @param int      $minutes
 * @param int      $passingGrade
 * @param array    $categories
 * @param int      $locationID
 * @param string   $teacherID
 */
function createInClassExam(DateTime $start, DateTime $cutoff, int $minutes,
    int $passingGrade, array $categories, int $locationID, string $teacherID
) {
    // call extended create
    createExamExtended(
        $start, $cutoff, $minutes, $passingGrade, $categories, $locationID,
        true, $teacherID
    );
}

/**
 * Extended function to create an exam
 * Only intended for internal use, use createExam() or createInClassExam()
 *
 * @param DateTime $start
 * @param DateTime $cutoff
 * @param int      $minutes
 * @param int      $passingGrade
 * @param array    $categories
 * @param int      $locationID
 * @param bool     $inClass   set to true if an in class exam
 * @param string   $teacherID if in class id, the teacher id associated
 */
function createExamExtended(DateTime $start, DateTime $cutoff, int $minutes,
    int $passingGrade, array $categories, int $locationID,
    bool $inClass = false, string $teacherID = ""
) {
    // validate arguments
    validateDates($start, $cutoff);
    validateExamLength($minutes);
    validateLocationID($locationID);
    if ($inClass) {
        validateTeacherID($teacherID);
    }
    validateExamCategories($passingGrade, $categories);

    // TODO: check for conflicting information w/ existing non-archived exams

    // create exam
    // TODO: create transaction for creation ?

    createExamQuery($start, $cutoff, $minutes, $passingGrade);
    $id = getLastInsertedID();

    // create in class entry


    // create exam categories
    

    // set exam state
    setExamState($id, EXAM_STATE_HIDDEN);

    // TODO: validate success
    // TODO: return exam id ?
}

// TODO: add/remove exam categories ?

// TODO: update exam
function updateExam(int $id, DateTime $start, DateTime $cutoff, int $length,
    int $passingGrade, int $location_id, array $categoryIDs
) {
    // TODO: validate arguments
    /// id exists, datetime(s) valid, length valid, passingGrade valid (reachable)
    /// location (registration count?)
    // TODO: does state allow editing ?

    // get current exam information

    // TODO: determine add/remove categories

    // TODO: re-assign seats for location change

    // TODO: validate success
    // TODO: refresh exam ?
}

// set state of exam (internal)
/// handle transition ?
function setExamState(int $id, int $state)
{
    // TODO: validate id exists

    if ( ! isExamStateValid($state)) {
        throw new InvalidArgumentException('Illegal exam state: ' . $state);
    }

    // TODO: check if current state can be transitioned to given state
    setExamStateQuery($id, $state);

    // TODO: check for success
}

// set exam location