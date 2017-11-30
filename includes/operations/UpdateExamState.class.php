<?php

/**
 * Update the state for an exam
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class UpdateExamState extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_ADMIN, ACCOUNT_TYPE_TEACHER));

        parent::registerParameter('examID', 'integer');
        parent::registerParameter('state', 'integer');

        parent::registerExecution(array($this, "updateExamState"));

        parent::registerAccountIDValidation('validateUserCanEditExam', 'examID');

        parent::registerValidation('validateExamIDExists', 'examID');
        parent::registerValidation('validateExamState', 'state');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function updateExamState(int $examID, int $state)
    {
        setExamState($examID, $state);

        return array(true);
    }
}