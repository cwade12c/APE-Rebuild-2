<?php

/**
 * Operation for a Student to register their account for an Exam
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class RegisterForExam extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_STUDENT));

        parent::registerExecution(array($this, "registerForExam"));

        parent::registerParameter("examID", "integer");
        parent::registerParameter("studentID", "string");

        parent::registerAccountIDValidation(
            'validateAccountsMatch', 'studentID'
        );

        parent::registerValidation("validateExamIDExists", "examID");
        parent::registerValidation("validateExamAllowsRegistration", "examID");
        parent::registerValidation("validateExamRoomAvailable", "examID");
        parent::registerValidation(
            "validateRegistrationStateIs", array("studentID", "state")
        );
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function registerForExam(int $examID, string $studentID)
    {
        registerStudentForExam($examID, $studentID);
    }
}