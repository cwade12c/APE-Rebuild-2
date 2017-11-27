<?php

/**
 * Operation for an Admin||Teacher to unregister student from exam
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class UnregisterStudentFromExam extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(
            array(ACCOUNT_TYPE_TEACHER, ACCOUNT_TYPE_ADMIN)
        );

        parent::registerExecution(
            array($this, "unregisterStudent")
        );

        parent::registerParameter('examID', 'integer');
        parent::registerParameter('studentID', 'string');
        parent::registerStaticParameter('studentState', STUDENT_STATE_REGISTERED);

        parent::registerAccountIDValidation(
            'validateUserCanEditExam', 'examID'
        );

        parent::registerValidation('validateExamIDExists', 'examID');
        parent::registerValidation('validateExamAllowsUnregistrations', 'examID');
        parent::registerValidation('validateStudentID', 'studentID');
        parent::registerValidation('validateRegistrationStateIs', array('studentID', 'studentState'));
        parent::registerValidation('validateStudentIsRegisteredFor', array('studentID', 'examID'));
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function unregisterStudent(int $examID, string $studentID) {
        deregisterStudentFromExam($examID, $studentID);

        return array(true);
    }
}