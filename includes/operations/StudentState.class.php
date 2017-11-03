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
        parent::requireLogin(true);
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_STUDENT, ACCOUNT_TYPE_GRADER,
            ACCOUNT_TYPE_TEACHER, ACCOUNT_TYPE_ADMIN));

        parent::registerExecution(array($this, "getStudentState"));

        parent::registerParameter("studentId", "string");

        /*
         * PHP Notice:  Undefined index: studentid in includes/operations/Operation.class.php on line
         * 378
         * Uncaught TypeError: Argument 1 passed to validateStudentID() must be of the type
         * string, null given,
         */
        //parent::registerValidation("validateStudentID", "studentId");
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function getStudentState(string $studentId)
    {
        $stateInt = getRegistrationState($studentId);
        $stateStr = "";
        $canRegister = $stateInt == STUDENT_STATE_READY;

        switch($stateInt) {
            case STUDENT_STATE_INVALID:
                $stateStr = "Invalid";
            break;
            case STUDENT_STATE_READY:
                $stateStr = "Ready";
            break;
            case STUDENT_STATE_REGISTERED:
                $stateStr = "Registered";
            break;
            case STUDENT_STATE_PASSED:
                $stateStr = "Passed";
            break;
            case STUDENT_STATE_BLOCKED:
                $stateStr = "Blocked";
            break;
            case STUDENT_STATE_BLOCKED_BYPASSED:
                $stateStr = "Block Bypassed";
            break;
            case STUDENT_STATE_BLOCKED_IGNORED:
                $stateStr = "Block Ignored";
            break;
        }

        return array(
            "state" => $stateInt,
            "stateText" => $stateStr,
            "canRegister" => $canRegister
        );
    }
}