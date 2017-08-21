<?php
/**
 * Functions for grades in database
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Database
 */

//get student grades (if validation check is true)
function getStudentGrades(int $studentId)
{
    if ($studentId == $_SESSION['ewuId']) {
        return getExamGradesByStudentId($studentId);
    }

    //logSecurityIncident("$_SESSION['ewuId'] tried to access the grades of $studentId");

    return "";
}