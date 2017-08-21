<?php
/**
 * Functions for grading within db
 *
 * @author		Mathew McCain
 * @author      Curran Higgins
 * @category	APE
 * @package		APE_includes
 * @subpackage	Database
 */

// assign grader to exam/category
function assignGrader(int $graderId, int $examId, int $categoryId, $submitted)
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
    return pass
    logSecurityIncident(IS_NOT_GRADER, $_SESSION['ewuId']);StudentQuery($examId, $studentId);
}

function failStudent(int $examId, string $studentId)
{
    return failStudentQuery($examId, $studentId);
}

function gradeCategory(int $examId, int $categoryId, string $studentId, int $grade, string $comments)
{
    $cleanComments = sanitize($comments);
    return gradeCategoryByIdQuery($examId, $categoryId, $studentId, $grade, $cleanComments);
}

// finalize exam
/// check in correct state
/// check all conflicts have been handled, all grades available

function finalizeExam(int $examId)
{
    $examState = getExamState($examId);

    if($examState == EXAM_STATE_GRADING)
    {
        $potentialConflicts = getStudentCategoryGradeConflicts($examId);
        $isAllSubmitted = isAllGradesSubmitted($examId);

        if(count($potentialConflicts) == 0 && $isAllSubmitted == true) {
            return finalizeExamByIdQuery($examId);
        }
    }

    return false;
}


// cleanup exam grades
/// cleanup all grader information not necessary
/// TODO: determine if necessary, make manual operation by admin/db admin?
