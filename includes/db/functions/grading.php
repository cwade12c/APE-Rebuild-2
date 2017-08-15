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

// assign grader to exam/category
function assignGrader(int $graderId, int $examId, int $categoryId, $submitted)
{
    if (accountIsAdmin("" . $_SESSION['ewuId']) || DEBUG) {
        return assignGraderQuery($graderId, $examId, $categoryId, $submitted);
    }

    logSecurityIncident(IS_NOT_ADMIN, $_SESSION['ewuId']);

    return false;
}

// remove grader from exam/category
function removeGrader(int $graderId, int $examId, int $categoryId)
{
    if (accountIsAdmin("" . $_SESSION['ewuId']) || DEBUG) {
        return removeGraderQuery($graderId, $examId, $categoryId);
    }

    logSecurityIncident(IS_NOT_ADMIN, $_SESSION['ewuId']);

    return false;
}

// get graders for exam/category
function getGradersByExam(int $examId)
{
    if (accountIsGrader("" . $_SESSION['ewuId'])
        || accountIsAdmin(
            $_SESSION['ewuId']
        )
        || DEBUG
    ) {
        return getAssignedGradersByExamIdQuery($examId);
    }

    logSecurityIncident(IS_NOT_AUTHORIZED, $_SESSION['ewuId']);
}

function getGradersByCategory(int $categoryId)
{
    if (accountIsGrader(
        "" . $_SESSION['ewuId'] || accountIsAdmin($_SESSION['ewuId']) || DEBUG
    )) {
        return getAssignedGradersByCategoryIdQuery($categoryId);
    }

    logSecurityIncident(IS_NOT_AUTHORIZED, $_SESSION['ewuId']);
}

// get assigned exams/category for grader
function getExamsAndCategoriesByGrader(int $graderId)
{
    if (accountIsGrader(
        "" . $_SESSION['ewuId'] || accountIsAdmin($_SESSION['ewuId']) || DEBUG
    )) {
        return getExamsAndCategoriesByGraderIdQuery($graderId);
    }

    logSecurityIncident(IS_NOT_AUTHORIZED, $_SESSION['ewuId']);
}

// get if all graders submitted
function isAllGradesSubmitted(int $examId)
{
    if (accountIsAdmin("" . $_SESSION['ewuId']) || DEBUG) {
        return isAllGradesSubmittedByExamIdQuery($examId);
    }

    logSecurityIncident(IS_NOT_ADMIN, $_SESSION['ewuId']);

    return false;
}

// submit for grader

// get graders submitted/not submitted
function getSubmittedGraders(int $examId)
{
    if (accountIsAdmin("" . $_SESSION['ewuId']) || DEBUG) {
        return getSubmittedGradersByExamIdQuery($examId);
    }

    logSecurityIncident(IS_NOT_ADMIN, $_SESSION['ewuId']);
}

function getUnsubmittedGraders(int $examId)
{
    if (accountIsAdmin("" . $_SESSION['ewuId']) || DEBUG) {
        return getUnsubmittedGradersByExamIdQuery($examId);
    }

    logSecurityIncident(IS_NOT_ADMIN, $_SESSION['ewuId']);
}

/// get count for each

function getNumberOfSubmittedGraders(int $examId)
{
    if (accountIsAdmin("" . $_SESSION['ewuId']) || DEBUG) {
        return getNumberOfSubmittedGradersByExamIdQuery($examId);
    }

    logSecurityIncident(IS_NOT_ADMIN, $_SESSION['ewuId']);
}

function getNumberOfUnsubmittedGraders(int $examId)
{
    if (accountIsAdmin("" . $_SESSION['ewuId']) || DEBUG) {
        return getNumberOfUnsubmittedGradersByExamIdQuery($examId);
    }

    logSecurityIncident(IS_NOT_ADMIN, $_SESSION['ewuId']);
}


// state shift from grading to finalizing
function setExamToFinalizing(int $examId)
{
    setExamState($examId, EXAM_STATE_FINALIZING);
}


// check student/category grades for conflicts between graders
function getStudentCategoryGradeConflicts(int $examId)
{
    if (accountIsAdmin("" . $_SESSION['ewuId']) || DEBUG) {
        return getStudentCategoryGradeConflictsByExamIdQuery($examId);
    }

    logSecurityIncident(IS_NOT_ADMIN, $_SESSION['ewuId']);
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

function getStudentAverageByCategory(int $studentId, int $categoryId)
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
function getStudentGrades(int $studentId)
{
    if ($studentId == $_SESSION['ewuId']) {
        return getExamGradesByStudentId($studentId);
    }

    //logSecurityIncident("$_SESSION['ewuId'] tried to access the grades of $studentId");

    return "";
}

// pass/fail student, set comment/grade for category
/// separate one for exam

function passStudent(int $examId, int $studentId)
{
    if (accountIsAdmin("" . $_SESSION['ewuId']) || DEBUG) {
        return passStudentQuery($examId, $studentId);
    }

    logSecurityIncident(IS_NOT_ADMIN, $_SESSION['ewuId']);
}

function failStudent(int $examId, int $studentId)
{
    if (accountIsAdmin("" . $_SESSION['ewuId']) || DEBUG) {
        return failStudentQuery($examId, $studentId);
    }

    logSecurityIncident(IS_NOT_ADMIN, $_SESSION['ewuId']);
}

function gradeCategory(int $examId, int $categoryId, int $studentId, int $grade,
    string $comments
) {
    if (accountIsGrader("" . $_SESSION['ewuId']) || DEBUG) {
        $cleanComments = sanitize($comments);

        return gradeCategoryByIdQuery(
            $examId, $categoryId, $studentId, $grade, $cleanComments
        );
    }

    logSecurityIncident(IS_NOT_GRADER, $_SESSION['ewuId']);
}


// finalize exam
/// check in correct state
/// check all conflicts have been handled, all grades available

// cleanup exam grades
/// cleanup all grader information not necessary
/// TODO: determine if necessary, make manual operation by admin/db admin?
