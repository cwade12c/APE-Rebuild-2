<?php

/**
 * Exam Details class for retrieving dates/times, location, space, state
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class StudentState extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_STUDENT, ACCOUNT_TYPE_GRADER,
            ACCOUNT_TYPE_TEACHER, ACCOUNT_TYPE_ADMIN));

        parent::registerParameter('studentid', 'string');

        parent::registerExecution(array($this, 'getStudentState'));

        parent::registerValidation('validateStudentID', 'studentId');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function getStudentState(string $studentId)
    {
        $stateInt = getRegistrationState($studentId);
        $stateStr = studentStateToString($stateInt);
        $canRegister = canStudentStateRegister($stateInt);

        return array(
            "state" => $stateInt,
            "stateText" => $stateStr,
            "canRegister" => $canRegister
        );
    }
}