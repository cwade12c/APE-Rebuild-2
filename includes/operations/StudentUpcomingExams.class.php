<?php

/**
 * Summary of upcoming exams for a student (for homepage, if registered)
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class StudentUpcomingExams extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_STUDENT));

        parent::registerExecution(array($this, 'getUpcomingExams'));

        parent::registerParameter('studentID', 'string');

        parent::registerValidation('validateStudentID', 'studentID');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function getUpcomingExams(string $studentID)
    {
        $exams = UpcomingExams::getUpcomingExams();
        foreach ($exams as &$examInfo) {
            $examInfo['registered'] = isStudentRegisteredFor(
                $examInfo['id'], $studentID
            );
        }

        return array('exams' => $exams);
    }
}