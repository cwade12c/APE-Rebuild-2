<?php

/**
 * get registration info for an exam (not grades)
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class ExamRegistrations extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(
            array(ACCOUNT_TYPE_TEACHER, ACCOUNT_TYPE_ADMIN)
        );

        parent::registerExecution(array($this, 'getExamRegistrations'));

        parent::registerParameter('id', 'integer');
        parent::registerValidation('validateExamIDExists', 'id');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function getExamRegistrations(int $examID)
    {
        $studentIDs = getExamRegistrations($examID);
        $students = array();
        foreach($studentIDs as $studentID) {
            $info = getAccountInfo($studentID);
            $info['id'] = $studentID;
            array_push($students, $info);
        }

        return array('students' => $students);
    }
}