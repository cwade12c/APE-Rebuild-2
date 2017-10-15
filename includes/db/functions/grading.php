<?php
/**
 * Functions for grading within db
 *
 * @author         Mathew McCain
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Database
 */

/**
 * Assign grader to exam/category
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $graderID
 */
function assignGrader(int $examID, int $categoryID, string $graderID)
{
    // validate exam ID, category ID
    // validate exam state allows
    // validate grader ID

    // TODO: populate
}

/**
 * Un-assign grader from exam/category
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $graderID
 */
function unAssignGrader(int $examID, int $categoryID, string $graderID)
{
    // validate exam ID, category ID
    // validate grader ID
    // validate assigned as grader to exam/category
    // validate exam valid after un-assign

    // TODO: populate
}

/**
 * Un-assign grader from exam fully (all categories assigned)
 *
 * @param int    $examID
 * @param string $graderID
 */
function unAssignGraderFromExam(int $examID, string $graderID)
{
    // validate exam ID
    // validate grader ID
    // validate assigned as grader to exam
    // validate exam valid after un-assign

    // TODO: populate
}

/**
 * Un-assign grader from all assigned exams
 *
 * @param string $graderID
 */
function unAssignGraderFull(string $graderID)
{
    // validate grader ID
    // validate un-assign is valid for exams

    // TODO: populate
}

/**
 * Get assigned graders for given exam
 *
 * @param int $examID
 *
 * @return array      List of grader IDs assigned
 */
function getAssignedExamGraders(int $examID)
{
    // validate exam ID

    // TODO: populate

    return array();
}

/**
 * Get assigned graders for given exam category
 *
 * @param int $examID
 * @param int $categoryID
 *
 * @return array          List of grader IDs assigned
 */
function getAssignedExamCategoryGraders(int $examID, int $categoryID)
{
    // validate exam ID, category ID
    // validate exam category

    // TODO: populate

    return array();
}

/**
 * Get assigned exams for a grader
 *
 * @param string $graderID
 *
 * @return array           List of assigned exam IDs
 */
function getAssignedExams(string $graderID)
{
    // validate grader ID

    // TODO: populate

    return array();
}

/**
 * Get assigned categories for grader for exam
 *
 * @param string $graderID
 * @param int    $examID
 *
 * @return array           List of category IDs
 */
function getAssignedExamCategories(string $graderID, int $examID)
{
    // validate grader ID
    // validate grader assigned to exam

    // TODO: populate

    return array();
}

/**
 * Get assigned exams and categories for an exam
 *
 * @param string $graderID
 *
 * @return array           List of arrays of exams and categories, format
 *                          "examID" => exam ID
 *                          "categories" => array of category IDs
 */
function getAssignedExamsCategories(string $graderID)
{
    // validate grader ID

    // TODO: populate

    return array();
}

/**
 * Not intended for outside use
 * Used for entering the grading state of an exam
 * Creates the grader category grades entries
 *
 * @param int $examID
 */
function createGraderStudentCategoryGradeEntries(int $examID)
{
    // get exam categories
    // get category graders
    // get students
    // build all permutations
    // TODO: populate
}

/**
 * To insert a student for grading
 * Intended for students that are added to the roster at a later date
 *
 * @param int    $examID
 * @param string $studentID
 */
function insertStudentForGrading(int $examID, string $studentID)
{
    // validate exam id
    // validate student ID
    // validate student registered for exam

    // TODO: populate
    // transaction, state check
}

/**
 * Internal function
 * Resets submitted status for all graders
 * Used when inserting a new student
 *
 * @param int $examID
 */
function resetAllSubmitted(int $examID)
{
    // TODO: populate
    // transaction, state check
}

/**
 * Check if assigned grader category is all submitted
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $graderID
 *
 * @return bool
 */
function isGraderCategorySubmitted(int $examID, int $categoryID,
    string $graderID
) {
    // validate exam id, category id
    // validate grader id
    // validate grader assigned

    // TODO: populate

    return false;
}

/**
 * Check if assigned grader has submitted all categories for exam
 *
 * @param int    $examID
 * @param string $graderID
 *
 * @return bool
 */
function isGraderExamSubmitted(int $examID, string $graderID)
{
    // validate exam ID
    // validate grader ID
    // validate grader assigned

    // TODO: populate

    return false;
}

/**
 * Get the graders list of students/points for an exam category
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $graderID
 *
 * @return array             List of students and points, format
 *                           "student_id" => student ID
 *                           "points" => points set
 */
function getGraderCategoryGrades(int $examID, int $categoryID, string $graderID)
{
    // validate exam ID, category ID
    // validate grader ID
    // validate grader assigned

    // TODO: populate

    return array();
}

/**
 * Sets the student points for a grader's assigned category
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $graderID
 * @param array  $points     Array of points
 *                           "studentID" => student ID
 *                           "points" => points set
 *                           if points are < 0, set to null
 */
function setGraderCategoryGrades(int $examID, int $categoryID, string $graderID,
    array $points
) {
    // validate exam ID, category ID
    // validate grader ID
    // validate grader assigned
    // validate points array

    // TODO: populate
}

/**
 * Get the points for a student in assigned graders category
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $graderID
 * @param string $studentID
 * @param int    $points
 */
function setStudentCategoryPoints(int $examID, int $categoryID,
    string $graderID, string $studentID, int $points
) {
    // validate

    // TODO: populate
}

/**
 * Set the submission value for a grader's category
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $graderID
 * @param bool   $submit
 */
function setGraderCategorySubmitted(int $examID, int $categoryID,
    string $graderID, bool $submit
) {
    // validate
    // validate can submit (if true)

    // TODO: populate
}

/**
 * Check if all students have a point set for a grader, category
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $graderID
 *
 * @return bool
 */
function allGraderCategoryPointsSet(int $examID, int $categoryID,
    string $graderID
) {
    // validate

    // TODO: populate

    return false;
}

/**
 * For transition to finalization state
 * Build student category grades entries
 *
 * @param int $examID
 */
function createStudentCategoryGrades(int $examID)
{
    // get students
    // get categories
    // get category points for each student
    // TODO: populate
}

/**
 * Get the points set by each grader for a student in a category
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $studentID
 *
 * @return array             list of points
 */
function getStudentCategoryPoints(int $examID, int $categoryID,
    string $studentID
) {
    // TODO: populate
    return array();
}

/**
 * Determine the grade value for an exam, and if a conflict exists
 *
 * @param array $points
 *
 * @return array                Results
 *                              "categoryGrade" => category grade
 *                              "conflict" => if conflict exists
 */
function determineCategoryGrade(array $points)
{
    // TODO: populate
    return array();
}

/**
 * Create student category grade entry
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $studentID
 * @param int    $points
 * @param bool   $conflict
 */
function createStudentCategoryGradeEntry(int $examID, int $categoryID, string $studentID, int $points, bool $conflict)
{
    // TODO: populate
}

/**
 * Resolve a student's category grade conflict
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $studentID
 * @param int    $grade
 */
function resolveStudentCategoryGrade(int $examID, int $categoryID, string $studentID, int $grade)
{
    // TODO: populate
}

/**
 * Creates the exam grades for an exam
 *
 * @param int $examID
 */
function createStudentExamGrades(int $examID)
{
    // get students
    // get student category grades
    // calculate exam grades / passed
    // TODO: populate
}

/**
 * Determine exam grade for set of category points
 *
 * @param int   $pointsToPass
 * @param array $categoryPoints
 *
 * @return array                Array of results
 *                              "grade" => exam grade
 *                              "passed" => exam passed
 */
function determineExamGrade(int $pointsToPass, array $categoryPoints)
{
    // TODO: populate
    return array();
}

/**
 * Creates entry for a student's exam grade
 *
 * @param int    $examID
 * @param string $studentID
 * @param int    $points
 * @param bool   $passed
 */
function createStudentExamGradeEntry(int $examID, string $studentID, int $points, bool $passed)
{
    // TODO: populate
}

/**
 * Get all category grades for a student
 *
 * @param int    $examID
 * @param string $studentID
 *
 * @return array            Array of categories, grades
 *                          "categoryID" => category ID
 *                          "grade" => grade
 */
function getStudentCategoryGrades(int $examID, string $studentID)
{
    // TODO: populate
    return array();
}

/**
 * Get grade for a student/category
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $studentID
 *
 * @return int
 */
function getStudentCategoryGrade(int $examID, int $categoryID, string $studentID)
{
    // TODO: populate
    return 0;
}

/**
 * Get student exam grade
 *
 * @param int    $examID
 * @param string $studentID
 *
 * @return int
 */
function getStudentExamGrade(int $examID, string $studentID)
{
    // TODO: populate
    return 0;
}

/**
 * Get student exam comment
 *
 * @param int    $examID
 * @param string $studentID
 *
 * @return string|null
 */
function getStudentExamComment(int $examID, string $studentID)
{
    // TODO: populate
    return "";
}

/**
 * Get if student has passed an exam
 *
 * @param int    $examID
 * @param string $studentID
 *
 * @return bool
 */
function getStudentExamPassed(int $examID, string $studentID)
{
    // TODO: populate
    return false;
}

/**
 * Get grades set by each grader for a student, category
 * Used for conflict view/resolution
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $studentID
 *
 * @return array             List of graders and points set
 *                           "graderID" => grader ID
 *                           "grade" => points set
 */
function getStudentCategoryGraderPoints(int $examID, int $categoryID, string $studentID)
{
    // TODO: populate
    return array();
}

/**
 * Check if conflicts exist for an exam
 * Used for an exam in the finalization state
 *
 * @param int $examID
 *
 * @return array
 */
function conflictsExist(int $examID)
{
    // TODO: populate
    return array();
}

/**
 * Get all conflicts for an exam
 *
 * @param int $examID
 *
 * @return array      List of students and conflicting categories
 *                    "studentID" => student ID
 *                    "categories" => list of category IDs in conflict
 */
function getConflicts(int $examID)
{
    // TODO: populate
    return array();
}

/**
 * Set student exam entry values
 *
 * @param int         $examID
 * @param string      $studentID
 * @param int         $points
 * @param bool        $passed
 * @param string|null $comment
 */
function setStudentExamEntry(int $examID, string $studentID, int $points, bool $passed, string $comment = null)
{
    // TODO: populate
}

/**
 * Set student exam grade points
 *
 * @param int    $examID
 * @param string $studentID
 * @param int    $points
 */
function setStudentExamPoints(int $examID, string $studentID, int $points)
{
    // TODO: populate
}

/**
 * Set student exam grade passed
 *
 * @param int    $examID
 * @param string $studentID
 * @param bool   $passed
 */
function setStudentExamPassed(int $examID, string $studentID, bool $passed)
{
    // TODO: populate
}

/**
 * Set student exam grade comment
 *
 * @param int         $examID
 * @param string      $studentID
 * @param string|null $comment
 */
function setStudentExamComment(int $examID, string $studentID, string $comment = null)
{
    // TODO: populate
}

////////////////////////////////////////////////////////////////////////
// TODO: reuse necessary functions/queries

// assign grader to exam/category
function aAssignGrader(int $graderId, int $examId, int $categoryId, $submitted)
{
    return assignGraderQuery($graderId, $examId, $categoryId, $submitted);
}

// remove grader from exam/category
function removeGrader(int $graderId, int $examId, int $categoryId)
{
    return removeGraderQuery($graderId, $examId, $categoryId);
}

// get graders for exam/category
function getGradersByExam(int $examId)
{
    return getAssignedGradersByExamIdQuery($examId);
}

function getGradersByCategory(int $categoryId)
{
    return getAssignedGradersByCategoryIdQuery($categoryId);
}

// get assigned exams/category for grader
function getExamsAndCategoriesByGrader(int $graderId)
{
    return getExamsAndCategoriesByGraderIdQuery($graderId);
}

// get if all graders submitted
function isAllGradesSubmitted(int $examId)
{
    return isAllGradesSubmittedByExamIdQuery($examId);
}

// submit for grader

// get graders submitted/not submitted
function getSubmittedGraders(int $examId)
{
    return getSubmittedGradersByExamIdQuery($examId);
}

function getUnsubmittedGraders(int $examId)
{
    return getUnsubmittedGradersByExamIdQuery($examId);
}

/// get count for each

function getNumberOfSubmittedGraders(int $examId)
{
    return getNumberOfSubmittedGradersByExamIdQuery($examId);
}

function getNumberOfUnsubmittedGraders(int $examId)
{
    return getNumberOfUnsubmittedGradersByExamIdQuery($examId);
}


// state shift from grading to finalizing
function setExamToFinalizing(int $examId)
{
    setExamState($examId, EXAM_STATE_FINALIZING);
}


// check student/category grades for conflicts between graders
function getStudentCategoryGradeConflicts(int $examId)
{
    return getStudentCategoryGradeConflictsByExamIdQuery($examId);
}

// get all student category grades for exam from all graders
/// only for 1 category
/// return [ [grader_id, grade] , ... ]

function getCategoryGrades(int $categoryId)
{
    return getGraderCategoryGradesByCategoryIdQuery($categoryId);
}

// get student grade for category
/// the average/set final

function getStudentAverageByCategory(string $studentId, int $categoryId)
{
    return getStudentAverageByCategoryIdQuery($studentId, $categoryId);
}

// get all student grades for exam
/// return array [ [category_id, grade], ... ]

function getStudentGradesByExam(int $examId)
{
    return getStudentCategoryGradesByExamIdQuery($examId);
}

// get all student grades of student by studentId
function getStudentGrades(string $studentId)
{
    return getExamGradesByStudentId($studentId);
}

// pass/fail student, set comment/grade for category
/// separate one for exam

function passStudent(int $examId, string $studentId)
{
    return pass;
    //logSecurityIncident(IS_NOT_GRADER, $_SESSION['ewuId']);StudentQuery($examId, $studentId);
}

function failStudent(int $examId, string $studentId)
{
    return failStudentQuery($examId, $studentId);
}

function gradeCategory(int $examId, int $categoryId, string $studentId,
    int $grade, string $comments
) {
    $cleanComments = sanitize($comments);
    return gradeCategoryByIdQuery(
        $examId, $categoryId, $studentId, $grade, $cleanComments
    );
}

// finalize exam
/// check in correct state
/// check all conflicts have been handled, all grades available

function finalizeExam(int $examId)
{
    $examState = getExamState($examId);

    if ($examState == EXAM_STATE_GRADING) {
        $potentialConflicts = getStudentCategoryGradeConflicts($examId);
        $isAllSubmitted = isAllGradesSubmitted($examId);

        if (count($potentialConflicts) == 0 && $isAllSubmitted == true) {
            return finalizeExamByIdQuery($examId);
        }
    }

    return false;
}


// cleanup exam grades
/// cleanup all grader information not necessary
/// TODO: determine if necessary, make manual operation by admin/db admin?

/**
 * Get the number of exams failed
 *
 * @param string $studentID Student ID
 *
 * @return int              Number of exams failed
 */
function getFailedExamCount(string $studentID)
{
    // TODO: populate
    return 0;
}

/**
 * Check if student has passed an exam
 *
 * @param string $studentID Student ID
 *
 * @return bool             If student has passed an exam
 */
function hasPassedExam(string $studentID)
{
    // TODO: populate
    return false;
}




