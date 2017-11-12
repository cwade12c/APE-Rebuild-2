<?php

/**
 * Operation for an Admin||Teacher to register a Student for an Exam
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class RegisterStudentForExam extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(
            array(ACCOUNT_TYPE_TEACHER, ACCOUNT_TYPE_ADMIN)
        );

        parent::registerExecution(
            array($this, "registerStudentForExamAsProxy")
        );

        parent::registerParameter("examID", "integer");
        parent::registerParameter("studentID", "string");

        parent::registerAccountIDValidation(
            'validateUserCanEditExam', 'examID'
        );

        parent::registerValidation("validateExamIDExists", "examID");
        parent::registerValidation("validateExamAllowsRegistration", "examID");
        parent::registerValidation("validateExamRoomAvailable", "examID");
        parent::registerValidation('validateStudentIDCanRegister', 'studentID');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function registerStudentForExamAsProxy(int $examID,
        string $studentID
    ) {
        registerStudentForExam($examID, $studentID);

        return array(true);
    }
}