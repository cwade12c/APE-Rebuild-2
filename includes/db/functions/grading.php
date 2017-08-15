<?php
/**
 * Functions for grading within db
 *
 * @author		Mathew McCain
 * @category	APE
 * @package		APE_includes
 * @subpackage	Database
 */

// assign grader to exam/category
function assignGrader(int $graderId, int $examId, int $categoryId, $submitted)
{
    if(accountIsAdmin("" . $_SESSION['ewuId']) || DEBUG) {
        return assignGraderQuery($graderId, $examId, $categoryId, $submitted);
    }

    logSecurityIncident(IS_NOT_ADMIN, $_SESSION['ewuId']);
    return false;
}

// remove grader from exam/category
function removeGrader(int $graderId, int $examId, int $categoryId)
{
    if(accountIsAdmin("" . $_SESSION['ewuId']) || DEBUG) {
        return removeGraderQuery($graderId, $examId, $categoryId);
    }

    logSecurityIncident(IS_NOT_ADMIN, $_SESSION['ewuId']);
    return false;
}

// get graders for exam/category
function getGradersByExam(int $examId)
{
    if(accountIsGrader("". $_SESSION['ewuId']) || accountIsAdmin($_SESSION['ewuId']) || DEBUG) {
        return getAssignedGradersByExamIdQuery($examId);
    }

    logSecurityIncident(IS_NOT_AUTHORIZED, $_SESSION['ewuId']);
}

function getGradersByCategory(int $categoryId)
{
    if(accountIsGrader("".  $_SESSION['ewuId'] || accountIsAdmin($_SESSION['ewuId']) || DEBUG))
    {
        return getAssignedGradersByCategoryIdQuery($categoryId);
    }

    logSecurityIncident(IS_NOT_AUTHORIZED, $_SESSION['ewuId']);
}

// get assigned exams/category for grader
function getExamsAndCategoriesByGrader(int $graderId)
{
    if (accountIsGrader("".  $_SESSION['ewuId'] || accountIsAdmin($_SESSION['ewuId']) || DEBUG))
    {
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
{as
    if (accountIsAdmin("" . $_SESSION['ewuId']) || DEBUG) {
        return getNumberOfUnsubmittedGradersByExamIdQuery($examId);
    }

    logSecurityIncident(IS_NOT_ADMIN, $_SESSION['ewuId']);
}


// state shift from grading to finalizing
// check student/category grades for conflicts between graders

// get all student category grades for exam from all graders
/// only for 1 category
/// return [ [grader_id, grade] , ... ]

// get student grade for category
/// the average/set final

// get all student grades for exam
/// return array [ [category_id, grade], ... ]

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

// finalize exam
/// check in correct state
/// check all conflicts have been handled, all grades available

// cleanup exam grades
/// cleanup all grader information not necessary
/// TODO: determine if necessary, make manual operation by admin/db admin?
