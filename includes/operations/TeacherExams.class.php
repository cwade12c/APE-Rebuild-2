<?php

/**
 * Summary of a teacher exams
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class TeacherExams extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_TEACHER));

        parent::registerExecution(array($this, 'getAssignedExams'));

        parent::registerParameter('teacherID', 'string');

        parent::registerValidation('validateTeacherID', 'teacherID');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function getAssignedExams(string $teacherID)
    {
        $examIDs = getExamsNonArchived(GET_EXAMS_TYPE_IN_CLASS, $teacherID);
        $exams = array_map(array('ExamDetails', 'getExamInformation'), $examIDs);
        return array('exams' => $exams);
    }
}