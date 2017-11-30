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
 * Check if grader assigned to exam
 *
 * @param string $graderID
 * @param int    $examID
 *
 * @return bool
 */
function isGraderAssignedExam(string $graderID, int $examID)
{
    return isGraderAssignedExamQuery($graderID, $examID);
}

/**
 * Check if grader assigned to exam category
 *
 * @param string $graderID
 * @param int    $examID
 * @param int    $categoryID
 *
 * @return bool
 */
function isGraderAssignedExamCategory(string $graderID, int $examID, int $categoryID)
{
    return isGraderAssignedExamCategoryQuery($graderID, $examID, $categoryID);
}

/**
 * Assign grader to exam/category
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $graderID
 */
function assignGrader(int $examID, int $categoryID, string $graderID)
{
    startTransaction();

    assignGraderQuery($examID, $categoryID, $graderID);

    $state = getExamState($examID);
    if ($state == EXAM_STATE_GRADING) {
        insertGraderDuringGrading($examID, $categoryID, $graderID);
    }

    commit();
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
    deleteAssignedGraderExamCategoryQuery($examID, $categoryID, $graderID);

    $state = getExamState($examID);
    if ($state == EXAM_STATE_GRADING) {
        deleteGraderCategoryGrades($examID, $categoryID, $graderID);
    }
}

/**
 * Un-assign grader from exam fully (all categories assigned)
 *
 * @param int    $examID
 * @param string $graderID
 */
function unAssignGraderFromExam(int $examID, string $graderID)
{
    deleteAssignedGraderExamQuery($examID, $graderID);

    $state = getExamState($examID);
    if ($state == EXAM_STATE_GRADING) {
        deleteGraderExamGrades($examID, $graderID);
    }
}

/**
 * Un-assign grader from all assigned exams
 * Will only work for exam's whose state allows removal of graders
 *
 * @param string $graderID
 */
function unAssignGraderFull(string $graderID)
{
    // validate grader ID
    // validate un-assign is valid for exams, state

    $exams = getAssignedExams($graderID);
    foreach ($exams as $examID) {
        $state = getExamState($examID);
        if (!doesExamStateAllowGraderRemovals($state)) {
            continue;
        }

        unAssignGraderFromExam($examID, $graderID);
    }
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
    $results = getAssignedExamGradersQuery($examID);

    $graders = array_column($results, 'grader_id');

    return $graders;
}

/**
 * Get assigned graders and categories
 *
 * @param int $examID
 *
 * @return array      Array of graders and assigned categories
 *                    "graderID" => grader ID
 *                    "categories" => category IDs
 */
function getAssignedExamGradersCategories(int $examID)
{
    $assigned = array();

    $graders = getAssignedExamGraders($examID);
    foreach ($graders as $graderID) {
        $assignedCategories = array();
        $assignedCategories['graderID'] = $graderID;

        $categories = getAssignedExamCategories($graderID, $examID);
        $assignedCategories['categories'] = $categories;

        array_push($assigned, $assignedCategories);
    }

    return $assigned;
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
    $results = getAssignedExamCategoryGradersQuery($examID, $categoryID);
    $graders = array_column($results, 'grader_id');

    return $graders;
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
    $results = getAssignedExamsQuery($graderID);
    $exams = array_column($results, 'exam_id');

    return $exams;
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
    $results = getAssignedExamCategoriesQuery($examID, $graderID);
    $categories = array_column($results, 'category_id');

    return $categories;
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
    $assigned = array();

    $exams = getAssignedExams($graderID);
    foreach ($exams as $examID) {
        $assignedExam = array();
        $assignedExam["examID"] = $examID;

        $categories = getAssignedExamCategories($graderID, $examID);
        $assignedExam["categories"] = $categories;

        array_push($assigned, $assignedExam);
    }

    return $assigned;
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
    $categoryGraders = getAssignedExamGradersCategories($examID);
    $students = getExamRegistrations($examID);

    foreach ($categoryGraders as $graderCategories) {
        createGraderCategoryGradesQuery(
            $examID, $graderCategories["graderID"],
            $graderCategories["categories"], $students
        );
    }
}

/**
 * Internal function
 * To insert a grader during the grading state
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $graderID
 */
function insertGraderDuringGrading(int $examID, int $categoryID,
    string $graderID
) {
    $students = getExamRegistrations($examID);
    insertGraderToGraderCategoryGradesQuery(
        $examID, $graderID, $categoryID, $students
    );
}

/**
 * To insert a student for grading
 * Intended for students that are added to the roster during grading
 *
 * @param int    $examID
 * @param string $studentID
 */
function insertStudentForGrading(int $examID, string $studentID)
{
    $gradersCategories = getAssignedExamGradersCategories($examID);
    insertStudentToGraderCategoryGradesQuery(
        $examID, $studentID, $gradersCategories
    );

    resetAllSubmitted($examID);
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
    resetAllSubmittedQuery($examID);
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
    return isGraderCategorySubmittedQuery($examID, $categoryID, $graderID);
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
    return isGraderExamSubmittedQuery($examID, $graderID);
}

/**
 * Check if all graders for an exam have submitted
 *
 * @param int $examID
 *
 * @return mixed
 */
function isExamSubmitted(int $examID)
{
    return isExamSubmittedQuery($examID);
}

/**
 * Get the graders list of students/points for an exam category
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $graderID
 *
 * @return array             List of students and points, format
 *                           "studentID" => student ID
 *                           "points" => points set, can be null
 */
function getGraderCategoryGrades(int $examID, int $categoryID, string $graderID)
{
    $results = getGraderCategoryGradesQuery($examID, $categoryID, $graderID);
    $categoryPoints = array_map(
        function ($row) {
            $row['studentID'] = $row['student_id'];
            unset($row['student_id']);
            return $row;
        }, $results
    );

    return $categoryPoints;
}

/**
 * Get grader's category grade for a student
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $graderID
 * @param string $studentID
 *
 * @return int
 */
function getGraderCategoryStudentGrade(int $examID, int $categoryID, string $graderID, string $studentID)
{
    return getGraderCategoryStudentGradeQuery($examID, $categoryID, $graderID, $studentID);
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
    startTransaction();

    foreach ($points as $studentPoints) {
        $studentID = $studentPoints['studentID'];
        $pointsSet = $studentPoints['points'];
        if ($pointsSet < 0) {
            continue;
        }

        error_log("Saving($examID, $categoryID, $graderID): $studentID, $pointsSet");

        setGraderCategoryStudentGradeQuery(
            $examID, $categoryID, $graderID, $studentID, $pointsSet
        );
    }

    commit();
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
    if ($points < 0) {
        $points = null;
    }

    setGraderCategoryStudentGradeQuery(
        $examID, $categoryID, $graderID, $studentID, $points
    );
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
    setGraderCategorySubmittedQuery($examID, $categoryID, $graderID, $submit);
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
    return allGraderCategoryPointsSetQuery($examID, $categoryID, $graderID);
}

/**
 * For transition to finalization state
 * Build student category grades entries
 *
 * @param int $examID
 */
function createStudentCategoryGrades(int $examID)
{
    $students = getExamRegistrations($examID);
    $categories = getExamCategories($examID);
    $categoryIDs = array_column($categories, "id");

    foreach ($students as $studentID) {
        foreach ($categoryIDs as $categoryID) {
            $points = getStudentCategoryPoints(
                $examID, $categoryID, $studentID
            );
            $calculatedGrade = determineCategoryGrade($points);
            createStudentCategoryGradeQuery(
                $examID, $categoryID, $studentID,
                $calculatedGrade['categoryGrade'], $calculatedGrade['conflict']
            );
        }
    }
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
    // TODO: validation

    $results = getStudentCategoryPointsQuery($examID, $categoryID, $studentID);
    $points = array_column($results, "points");

    return $points;
}

/**
 * Determine the grade value for an exam, and if a conflict exists
 *
 * @param array $points
 *
 * @return array         Results
 *                       "categoryGrade" => category grade
 *                       "conflict" => if conflict exists
 */
function determineCategoryGrade(array $points)
{
    $lowest = min($points);
    $highest = max($points);
    $grade = array_sum($points) / count($points);

    $differenceFlat = $highest - $lowest;
    $differencePercent = 1 - ($highest / $lowest);

    $conflict = ($differenceFlat >= MAX_GRADER_CATEGORY_GRADE_DIFFERENCE_FLAT)
        || ($differencePercent >= MAX_GRADER_CATEGORY_GRADE_DIFFERENT_PERCENT);

    return array('categoryGrade' => $grade, 'conflict' => $conflict);
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
function createStudentCategoryGradeEntry(int $examID, int $categoryID,
    string $studentID, int $points, bool $conflict
) {
    createStudentCategoryGradeQuery(
        $examID, $categoryID, $studentID, $points, $conflict
    );
}

/**
 * Resolve a student's category grade conflict
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $studentID
 * @param int    $grade
 */
function resolveStudentCategoryGradeConflict(int $examID, int $categoryID,
    string $studentID, int $grade
) {
    setStudentCategoryGradeQuery(
        $examID, $categoryID, $studentID, $grade, false
    );
}

/**
 * Check if conflicts exist for an exam
 * Used for an exam in the finalization state
 *
 * @param int $examID
 *
 * @return bool
 */
function conflictsExist(int $examID)
{
    return conflictsExistQuery($examID);
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
    $results = getConflictsQuery($examID);

    $studentIDsToCategories = array();
    foreach ($results as $row) {
        $studentID = $row['student_id'];
        $categoryID = $row['category_id'];

        if (!array_key_exists($studentID, $studentIDsToCategories)) {
            $studentIDsToCategories[$studentID] = array();
        }
        array_push($studentIDsToCategories[$studentID], $categoryID);
    }

    $conflicts = array();
    foreach ($studentIDsToCategories as $studentID => $categories) {
        $conflict = array('studentID'  => $studentID,
                          'categories' => $categories);
        array_push($conflict, $conflict);
    }

    return $conflicts;
}

/**
 * Internal function
 * Creates the exam grades for an exam
 * Used for moving to exam finalization
 *
 * @param int $examID
 */
function createStudentExamGrades(int $examID)
{
    $examInfo = getExamInformation($examID);
    $passingGrade = $examInfo['passingGrade'];

    $students = getExamRegistrations($examID);
    foreach ($students as $studentID) {
        $grades = getStudentCategoryGrades($examID, $studentID);
        $categoryGrades = array_column($grades, 'grade');

        $examGrade = determineExamGrade($passingGrade, $categoryGrades);

        createStudentExamGradeQuery(
            $examID, $studentID, $examGrade['grade'], $examGrade['passed']
        );
    }
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
    $grade = array_sum($categoryPoints);
    $passed = $grade >= $pointsToPass;

    return array('grade' => $grade, 'passed' => $passed);
}

/**
 * Creates entry for a student's exam grade
 *
 * @param int    $examID
 * @param string $studentID
 * @param int    $points
 * @param bool   $passed
 */
function createStudentExamGradeEntry(int $examID, string $studentID,
    int $points, bool $passed
) {
    createStudentExamGradeQuery($examID, $studentID, $points, $passed);
}

/**
 * Set student exam grade
 *
 * @param int    $examID
 * @param string $studentID
 * @param int    $points
 * @param bool   $passed
 */
function setStudentExamGrade(int $examID, string $studentID, int $points, bool $passed)
{
    setStudentExamGradeQuery($examID, $studentID, $points, $passed);
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
    $results = getStudentCategoryGradesQuery($examID, $studentID);
    $categoryGrades = array_map(
        function ($row) {
            $newRow = array();
            $newRow['categoryID'] = $row['category_id'];
            $newRow['grade'] = $row['points'];

            return $newRow;
        }, $results
    );

    return $categoryGrades;
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
function getStudentCategoryGrade(int $examID, int $categoryID, string $studentID
) {
    return getStudentCategoryGradeQuery($examID, $categoryID, $studentID);
}

/**
 * Get comment for a student/category
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $studentID
 *
 * @return string
 */
function getStudentCommentGrade(int $examID, int $categoryID, string $studentID
) {
    return getStudentCategoryCommentQuery($examID, $categoryID, $studentID);
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
    return getStudentExamGradeQuery($examID, $studentID);
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
    return getStudentExamGradeCommentQuery($examID, $studentID);
}

/**
 * Get if student has passed the exam
 *
 * @param int    $examID
 * @param string $studentID
 *
 * @return bool
 */
function getStudentExamPassed(int $examID, string $studentID)
{
    // TODO: validations

    return getStudentExamGradePassedQuery($examID, $studentID);
}

/**
 * Get information about a student's exam grade
 *
 * @param int    $examID
 * @param string $studentID
 *
 * @return array            Array of exam grade information
 *                          "grade" => grade of exam
 *                          "passed" => if passed exam
 */
function getStudentExamGradeDetails(int $examID, string $studentID)
{
    // TODO: validations

    $result = getStudentExamGradeDetailsQuery($examID, $studentID);
    $result['grade'] = $result['points'];
    unset($result['points']);

    return $result;
}

/**
 * Get all fields for a students exam grade
 *
 * @param int    $examID
 * @param string $studentID
 *
 * @return array            Exam grade information
 *                          "grade" => exam grade
 *                          "passed" => is passed exam
 *                          "comment" => exam grade comment (can be null)
 */
function getStudentExamGradeFull(int $examID, string $studentID)
{
    // TODO: validations

    $result = getStudentExamGradeFullQuery($examID, $studentID);
    $result['grade'] = $result['points'];
    unset($result['points']);

    return $result;
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
function getStudentCategoryGraderPoints(int $examID, int $categoryID,
    string $studentID
) {
    // TODO: validations

    $results = getStudentCategoryGraderPointsQuery(
        $examID, $categoryID, $studentID
    );
    $graderPoints = array_map(
        function ($row) {
            $newRow = array();
            $newRow['graderID'] = $row['grader_id'];
            $newRow['grade'] = $row['points'];

            return $newRow;
        }, $results
    );

    return $graderPoints;
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
function setStudentExamEntry(int $examID, string $studentID, int $points,
    bool $passed, string $comment
) {
    // TODO: validations

    setStudentExamEntryQuery($examID, $studentID, $points, $passed, $comment);
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
    // TODO: validations

    setStudentExamPointsQuery($examID, $studentID, $points);
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
    // TODO: validations

    setStudentExamPassedQuery($examID, $studentID, $passed);
}

/**
 * Set student exam grade comment
 *
 * @param int         $examID
 * @param string      $studentID
 * @param string|null $comment
 */
function setStudentExamComment(int $examID, string $studentID,
    string $comment
) {
    // TODO: validations

    setStudentExamCommentQuery($examID, $studentID, $comment);
}

/**
 * Used to finalize an exam
 * Checks for
 *
 * @param int $examID
 */
function finalizeExam(int $examID)
{
    transitionExamToArchived($examID);
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
    // TODO: validations

    return hasPassedExamQuery($studentID);
}

/**
 * Get the number of exams passed
 *
 * @param string $studentID
 *
 * @return int
 */
function getExamsPassedCount(string $studentID)
{
    // TODO: validations

    return getExamsPassedCountQuery($studentID);
}

/**
 * Get the number of exams failed
 *
 * @param string $studentID
 *
 * @return int
 */
function getExamsFailedCount(string $studentID)
{
    // TODO: validations

    return getExamsFailedCountQuery($studentID);
}

/**
 * Delete all grade entries for a grader from an exam
 *
 * @param int    $examID
 * @param string $graderID
 */
function deleteGraderExamGrades(int $examID, string $graderID)
{
    // TODO: validations

    deleteGraderExamGradesQuery($examID, $graderID);
}

/**
 * Delete the entries for an assigned grader / category
 *
 * @param int    $examID
 * @param int    $categoryID
 * @param string $graderID
 */
function deleteGraderCategoryGrades(int $examID, int $categoryID,
    string $graderID
) {
    // TODO: validations

    deleteGraderCategoryGradesQuery($examID, $categoryID, $graderID);
}