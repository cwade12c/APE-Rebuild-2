<?php

/**
 * Update location for an exam
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class UpdateExamLocation extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_ADMIN, ACCOUNT_TYPE_TEACHER));

        parent::registerParameter('examID', 'integer');
        parent::registerParameter('locationID', 'integer');

        parent::registerExecution(array($this, "updateExamLocation"));

        parent::registerAccountIDValidation('validateUserCanEditExam', 'examID');

        parent::registerValidation('validateExamIDExists', 'examID');
        parent::registerValidation('validateLocationIDExists', 'locationID');
        parent::registerValidation('validateExamStateAllowsEdits', 'examID');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function updateExamLocation(int $examID, int $locationID)
    {
        setExamLocation($examID, $locationID);

        return array(true);
    }
}