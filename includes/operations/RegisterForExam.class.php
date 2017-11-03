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
    private $realAccountID;

    function __construct()
    {
        parent::requireLogin(true);
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_STUDENT));

        parent::registerExecution(array($this, "registerForExam"));

        parent::registerParameter("examID", "integer");
        parent::registerParameter("studentID", "string");

        parent::registerValidation("validateExamIDExists", "examID");
        parent::registerValidation("validateExamAllowsRegistration", "examID");
        parent::registerValidation("validateExamRoomAvailable", "examID");
        parent::registerValidation("validateRegistrationStateIs", array("studentID", "state"));
    }

    public function execute(array $args, string $accountID = null)
    {
        $this->realAccountID = $accountID;
        return parent::execute($args, $accountID);
    }

    public static function registerForExam(int $examID, string $studentID)
    {
        if($studentID != $this->realAccountID) {
            throw new Exception("The account you are trying to register does not belong to you!");
        }

        $result = getQueryResult(registerStudentForExam($examID, $studentID));

        return array(
            'registrationSuccess' => $result
        );
    }
}