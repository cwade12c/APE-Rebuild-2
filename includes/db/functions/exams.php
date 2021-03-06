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
 * Check if a given exam ID exists
 *
 * @param int $id Exam ID
 *
 * @return bool     If exam exists
 */
function examExists(int $id)
{
    return examExistsQuery($id);
}

/**
 * Get list of IDs for all exams (all states)
 *
 * @param int         $type for the type of exam (both, regular, in class)
 *                          use get exam type values defined in db 'constants.php'
 * @param string|null $teacherID
 *
 * @return mixed
 */
function getExamsAll(int $type = GET_EXAMS_TYPE_BOTH, string $teacherID = null)
{
    return getExamsExtended(GET_EXAMS_ALL, $type, $teacherID);
}

/**
 * Get list of IDs for archived exams
 *
 * @param int         $type for the type of exam (both, regular, in class)
 *                          use get exam type values defined in db 'constants.php'
 * @param string|null $teacherID
 *
 * @return mixed
 */
function getExamsArchived(int $type = GET_EXAMS_TYPE_BOTH,
    string $teacherID = null
) {
    return getExamsExtended(GET_EXAMS_ARCHIVED, $type, $teacherID);
}

/**
 * Get list of IDs for all non-archived exams
 *
 * @param int         $type
 * @param string|null $teacherID
 *
 * @return mixed
 */
function getExamsNonArchived(int $type = GET_EXAMS_TYPE_BOTH,
    string $teacherID = null
) {
    return getExamsExtended(GET_EXAMS_NON_ARCHIVED, $type, $teacherID);
}

/**
 * Get list of IDs for exams in finalizing state
 *
 * @param int         $type for the type of exam (both, regular, in class)
 *                          use get exam type values defined in db 'constants.php'
 * @param string|null $teacherID
 *
 * @return mixed
 */
function getExamsFinalizing(int $type = GET_EXAMS_TYPE_BOTH,
    string $teacherID = null
) {
    return getExamsExtended(GET_EXAMS_FINALIZING, $type, $teacherID);
}

/**
 * Get list of IDs for exams in grading state
 *
 * @param int         $type for the type of exam (both, regular, in class)
 *                          use get exam type values defined in db 'constants.php'
 * @param string|null $teacherID
 *
 * @return mixed
 */
function getExamsGrading(int $type = GET_EXAMS_TYPE_BOTH,
    string $teacherID = null
) {
    return getExamsExtended(GET_EXAMS_GRADING, $type, $teacherID);
}

/**
 * Get list of IDs for upcoming exams (states open, closed, in progress)
 *
 * @param int         $type for the type of exam (both, regular, in class)
 *                          use get exam type values defined in db 'constants.php'
 * @param string|null $teacherID
 *
 * @return mixed
 */
function getExamsUpcoming(int $type = GET_EXAMS_TYPE_BOTH,
    string $teacherID = null
) {
    return getExamsExtended(GET_EXAMS_UPCOMING, $type, $teacherID);
}

/**
 * Get list of IDs for open exams (states hidden, open, closed, in progress)
 *
 * @param int         $type for the type of exam (both, regular, in class)
 *                          use get exam type values defined in db 'constants.php'
 * @param string|null $teacherID
 *
 * @return mixed
 */
function getExamsOpen(int $type = GET_EXAMS_TYPE_BOTH, string $teacherID = null)
{
    return getExamsExtended(GET_EXAMS_OPEN, $type, $teacherID);
}

/**
 * Get list of IDs for exams w/ given information
 * Recommended to use other get exam functions for future proofing.
 *
 * @param int         $state state as defined under get exam states
 *                           defined in db 'constants'.php
 * @param int         $type  for the type of exam (both, regular, in class)
 *                           use get exam type values defined in db 'constants.php'
 * @param string|null $teacherID
 *
 * @return array list of exam IDs
 */
function getExamsExtended(int $state, int $type, string $teacherID = null)
{
    if ($teacherID == null) {
        $exams = getExamsQuery($state, $type);
    } else {
        $exams = getInClassExamsQuery($state, $type, $teacherID);
    }

    $ids = array_column($exams, 'id');

    return $ids;
}

// TODO: get exams w/ teacher ID, archived / non-archived

// TODO: search exams
/// state, date/time (quarter?), in class
/// location/rooms
/// may need to create custom class to define search parameters into one object

/**
 * Get all information from exam table entry
 *
 * @param int $id
 *
 * @return mixed
 */
function getExamInformation(int $id)
{
    $info = getExamInformationQuery($id);

    $info['passingGrade'] = $info['passing_grade'];
    unset($info['passing_grade']);

    $info['isRegular'] = $info['is_regular'];
    unset($info['is_regular']);

    $info['locationID'] = $info['location_id'];
    unset($info['location_id']);

    return $info;
}

/**
 * Get location ID of exam
 *
 * @param int $id Exam ID
 *
 * @return int|null Location ID, null if unset
 */
function getExamLocationID(int $id)
{
    $info = getExamInformation($id);
    return $info['locationID'];
}

/**
 * Get state for given exam ID
 *
 * @param int $id exam ID
 *
 * @return int      exam state
 */
function getExamState(int $id)
{
    return getExamStateQuery($id);
}

/**
 * Gets all categories for an exam, will contain
 * category_id and the points value set.
 *
 * @param int $id exam ID
 *
 * @return array    array of exam categories, element format
 *                      'id' => category ID
 *                      'points' => category points
 */
function getExamCategories(int $id)
{
    $categories = getExamCategoriesQuery($id);

    foreach ($categories as &$category) {
        $category['id'] = $category['category_id'];
        unset($category['category_id']);
    }

    return $categories;
}

/**
 * Get points for exam/category
 *
 * @param int $examID
 * @param int $categoryID
 *
 * @return int
 */
function getExamCategoryPoints(int $examID, int $categoryID)
{
    return getExamCategoryPointsQuery($examID, $categoryID);
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
 * @param array    $categories array of exam category information
 *                             Each index must follow format
 *                             array(
 *                             'id' => category id
 *                             'points' => points possible
 *                             )
 * @param int      $locationID
 * @param bool     $inClass    set to true if an in class exam
 * @param string   $teacherID  if in class id, the teacher id associated
 */
function createExamExtended(DateTime $start, DateTime $cutoff, int $minutes,
    int $passingGrade, array $categories, int $locationID,
    bool $inClass = false, string $teacherID = ""
) {
    startTransaction();

    // create exam
    createExamQuery(
        $start, $cutoff, $minutes, $passingGrade, $locationID, !$inClass
    );
    $id = getLastInsertedID();

    // create in class entry
    if ($inClass) {
        createExamInClassQuery($id, $teacherID);
    }

    // create exam categories
    createExamCategoriesQuery($id, $categories);

    // set exam state
    setExamState($id, EXAM_STATE_HIDDEN);

    commit();
}

// TODO: add/remove exam categories ?

// TODO: separate updateExam()
// into updateExamInformation(), just dates, times, location
// and updateExamCategories(), passing grade and categories

/**
 * Updates attributes about a given exam
 *
 * Attributes similar to createExamExtended(),
 * with a specification for the exam ID and omission of the in class attribute
 *
 * @param int      $id              ID of the exam to update
 * @param DateTime $start           start datetime of exam
 * @param DateTime $cutoff          cutoff datetime for registration
 * @param int      $minutes         length of exam in minutes
 * @param int      $passingGrade    passing grade for exam
 * @param int      $locationID      location ID
 * @param array    $categories      list of categories in format
 *                                  array(
 *                                  'id' => category id
 *                                  'points' => points possible
 *                                  )
 */
function updateExam(int $id, DateTime $start, DateTime $cutoff, int $minutes,
    int $passingGrade, int $locationID, array $categories
) {
    startTransaction();

    // update exam
    updateExamQuery($id, $start, $cutoff, $minutes, $passingGrade, $locationID);
    updateExamCategories($id, $categories);

    commit();
}

/**
 * Updates categories for a given exam
 * Only intended for internal use
 * TODO: adjust so can be used outside of updateExam() ?
 *
 * @param int   $id            ID of exam
 * @param array $newCategories same format as necessary by updateExam()
 */
function updateExamCategories(int $id, array $newCategories)
{
    // get current exam categories
    // elements are associative array - 'category_id', 'points'
    $currentCategories = getExamCategories($id);

    list(
        $categoriesToRemove, $categoriesToAdd, $categoriesToUpdate
        )
        = determineExamCategoryChanges($currentCategories, $newCategories);

    removeExamCategoriesQuery($id, $categoriesToRemove);
    updateExamCategoriesQuery($id, $categoriesToUpdate);
    createExamCategoriesQuery($id, $categoriesToAdd);
}

/**
 * Helper function for updateExamCategories()
 * Not intended for outside use
 * Determines category IDs to update, add and remove
 *
 * @param array $currentCategories   array of current categories
 *                                   elements in format of
 *                                   array(
 *                                   'id' => category id,
 *                                   'points' => category points
 *                                   )
 * @param array $newCategoryIDs      array of new categories
 *                                   elements in format of
 *                                   array(
 *                                   'id' => category id,
 *                                   'points' => category points
 *                                   )
 *
 * @return array                    return of format
 *                                  array(
 *                                  array of IDs to remove,
 *                                  array of categories to add,
 *                                      element format of
 *                                      array(
 *                                      'id' => category id,
 *                                      'points' => category points
 *                                      )
 *                                  array of categories to update
 *                                      element format of
 *                                      array(
 *                                      'id' => category id,
 *                                      'points' => category points
 *                                      )
 *                                  )
 */
function determineExamCategoryChanges(array $currentCategories,
    array $newCategories
) {
    // build list of current category IDs
    $currentCategoryIDs = array_map(
        'mapExamCategoryIDsOut', $currentCategories
    );

    // build list of new category IDs
    $newCategoryIDs = array_map(
        'mapExamCategoryIDsOut', $newCategories
    );

    // determine changes necessary
    $categoriesToRemove = array();
    $categoriesToAdd = array();
    $categoriesToUpdate = array();

    // check new category IDs
    foreach ($newCategoryIDs as $catID) {
        // check for update
        if (in_array($catID, $currentCategoryIDs)) {
            array_push($categoriesToUpdate, $catID);
        } else {
            array_push($categoriesToAdd, $catID);
        }
    }
    // check old category IDs for removals
    foreach ($currentCategoryIDs as $catID) {
        if (!in_array($catID, $newCategoryIDs)) {
            array_push($categoriesToRemove, $catID);
        }
    }

    // build return arrays
    $categoriesToAdd = mapExamCategoriesBack($categoriesToAdd, $newCategories);
    $categoriesToUpdate = mapExamCategoriesBack(
        $categoriesToUpdate, $newCategories
    );

    return array($categoriesToRemove, $categoriesToAdd, $categoriesToUpdate);
}

/**
 * Helper function for determineExamCategoryChanges();
 * Used by array_map() to pull all category IDs
 *
 * @param array $category   single category in format
 *                          array(
 *                          'id' => category id,
 *                          'points' => category points
 *                          )
 *
 * @return int              category id
 */
function mapExamCategoryIDsOut(array $category)
{
    return $category['id'];
}

/**
 * Helper function for determineExamCategoryChanges()
 * Used to map an array of category ID back to an array of format
 * array(ID, points)
 *
 * @param array $categoryID   array of exam category IDs
 * @param array $categoryList array to grab points from, with format of
 *                            'id' => category id
 *                            'points' => points
 *
 * @return array        array with elements in format of
 *                      'id' => category ID
 *                      'points' => category points
 */
function mapExamCategoriesBack(array $categoryIDs, array $categories)
{
    // cannot use array_map, due to issues with an array as an argument
    // will pass each element of that array along, not whole array
    $normalizedCategories = array();
    foreach ($categoryIDs as $id) {
        // find category information
        foreach ($categories as $category) {
            if ($category['id'] == $id) {
                array_push($normalizedCategories, $category);
                break;
            }
        }
        // safe to assume all category IDs will map back correctly
        // TODO: check for anyway?
    }

    return $normalizedCategories;
}

/**
 * Update time info for an exam
 *
 * @param int      $examID
 * @param DateTime $start
 * @param DateTime $cutoff
 * @param int      $length
 */
function updateExamTime(int $examID, DateTime $start, DateTime $cutoff,
    int $length
) {
    updateExamTimeQuery($examID, $start, $cutoff, $length);
}

/**
 * Update exam grade related info
 *
 * @param int   $examID
 * @param int   $passingGrade
 * @param array $categories
 */
function updateExamGrades(int $examID, int $passingGrade, array $categories)
{
    startTransaction();

    updateExamPassingGradeQuery($examID, $passingGrade);
    updateExamCategories($examID, $categories);

    commit();
}

/**
 * Set exam state
 *
 * @param int $id
 * @param int $state
 */
function setExamState(int $id, int $state)
{
    setExamStateQuery($id, $state);
}

/**
 * Set exam location
 *
 * @param int $id
 * @param int $locationID
 */
function setExamLocation(int $id, int $locationID)
{
    setExamLocationQuery($id, $locationID);
}

/**
 * Check if user can edit the given exam
 *
 * @param string $accountID
 * @param int    $examID
 *
 * @return bool
 */
function canEditExam(string $accountID, int $examID)
{
    $type = getAccountType($accountID);
    if (typeHas($type, ACCOUNT_TYPE_ADMIN)) {
        return true;
    } elseif (typeHas($type, ACCOUNT_TYPE_TEACHER)) {
        $examTeacher = getInClassExamTeacher($examID);
        return ($examTeacher && ($examTeacher == $accountID));
    }

    return false;
}

// TODO: have extended location check for setExamLocation() and updateExam()?
// will simplify the checks
// or just limit those functions to be used by exam interactions
// where it will validate the seating before hand?