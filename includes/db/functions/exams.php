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
    validateExamID($id);
    return examExistsQuery($id);
}

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
 * @return array    list of exam IDs
 */
function getExamsExtended(int $state, int $type)
{
    // TODO: validate state and type valid
    $exams = getExamsQuery($state, $type);

    // build array as only a list of IDs
    $fncGetExamID = function (array $exam) {
        return $exam['id'];
    };
    $ids = array_map($fncGetExamID, $exams);

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
    return $info['location_id'];
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
    validateExamID($id);
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
    // TODO: validate id exists
    // TODO: pull category id information? (name, default points)
    $categories = getExamCategoriesQuery($id);

    normalizeExamCategoriesArray($categories);

    return $categories;
}

/**
 * Helper function for getExamCategories()
 * Transforms category array format
 * Resulting array element format
 *  array(
 *  'id' => category id,
 *  'points' => category points
 *  )
 *
 * @param array $categories category array from getExamCategoriesQuery()
 *                          element format of
 *                          array(
 *                          'category_id' => category id,
 *                          'points' => category points
 *                          )
 */
function normalizeExamCategoriesArray(array &$categories)
{
    foreach ($categories as &$category) {
        $category['id'] = $category['category_id'];
        unset($category['category_id']);
    }
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
    // validate arguments
    validateExamAttributes(
        $start, $cutoff, $minutes, $passingGrade, $categories, $locationID,
        $inClass, $teacherID
    );

    // TODO: check for conflicting information w/ existing non-archived exams
    // TODO: create transaction for query set

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

    // TODO: validate success
    // TODO: return exam id ?
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
    // validate arguments
    validateExamID($id);
    validateExamAttributes(
        $start, $cutoff, $minutes, $passingGrade, $categories, $locationID
    );


    // TODO: validate exam state allows updates
    // TODO: validate new location has enough space for current students


    // TODO: create transaction ? race conditions possible

    //$currentInfo = getExamInformation($id);

    // update exam
    updateExamQuery($id, $start, $cutoff, $minutes, $passingGrade, $locationID);
    updateExamCategories($id, $categories);

    // TODO: if location changed, reset seat assignments
    // TODO: any changes necessary for graders assigned to categories
    /// transfer graders if necessary

    // TODO: validate success
    // TODO: refresh exam ?
    /// set 'dirty bit' to mark as needing refresh, such as assigned seats?
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

    // determine changes necessary
    list(
        $categoriesToRemove, $categoriesToAdd, $categoriesToUpdate
        )
        = determineExamCategoryChanges($currentCategories, $newCategories);

    // remove categories
    removeExamCategoriesQuery($id, $categoriesToRemove);

    // update categories
    updateExamCategoriesQuery($id, $categoriesToUpdate);

    // add categories
    createExamCategoriesQuery($id, $categoriesToAdd);

    // TODO: check for success?
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

// set state of exam (internal)
/// handle transition ?
function setExamState(int $id, int $state)
{
    // TODO: validate id exists

    // TODO: validate exam state function
    if (!isExamStateValid($state)) {
        throw new InvalidArgumentException('Illegal exam state: ' . $state);
    }

    // TODO: check if current state can be transitioned to given state
    setExamStateQuery($id, $state);

    // TODO: check for success
}

// set exam location
function setExamLocation(int $id, int $locationID)
{
    // validate arguments
    validateExamID($id);
    validateLocationID($locationID);

    // TODO: check if new location will cause issues with existing seating
    setExamLocationQuery($id, $locationID);

    // TODO: reset seating?

    // TODO: check for success
}

// TODO: have extended location check for setExamLocation() and updateExam()?
// will simplify the checks
// or just limit those functions to be used by exam interactions
// where it will validate the seating before hand?