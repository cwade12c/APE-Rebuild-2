<?php

/**
 * Update exam time
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class UpdateExamTime extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(
            array(ACCOUNT_TYPE_ADMIN, ACCOUNT_TYPE_TEACHER)
        );

        parent::registerParameter('examID', 'integer');
        parent::registerParameter('start', 'string');
        parent::registerParameter('cutoff', 'string');
        parent::registerParameter('length', 'integer');

        parent::registerExecution(array($this, "updateExamTime"));

        parent::registerAccountIDValidation(
            'validateUserCanEditExam', 'examID'
        );

        parent::registerValidation('validateExamIDExists', 'examID');
        parent::registerValidation('validateExamStateAllowsEdits', 'examID');
        parent::registerValidation('validateStringIsDateTime', 'start');
        parent::registerValidation('validateStringIsDateTime', 'cutoff');
        parent::registerValidation('validateDates', array('start', 'cutoff'));
        parent::registerValidation('validateExamLength', 'length');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function updateExamTime(int $examID, string $start,
        string $cutoff, int $length
    ) {
        $start = DateTime::createFromFormat(DATETIME_FORMAT, $start);
        $cutoff = DateTime::createFromFormat(DATETIME_FORMAT, $cutoff);

        updateExamTime($examID, $start, $cutoff, $length);

        return array(true);
    }
}