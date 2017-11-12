<?php

/**
 * Check if current user can finalize an exam
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class CanFinalizeExam extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(
            array(ACCOUNT_TYPE_ADMIN, ACCOUNT_TYPE_TEACHER)
        );

        parent::registerParameter('examID', 'integer');
        parent::registerStaticParameter('finalizeState', EXAM_STATE_FINALIZING);

        parent::registerExecution(array($this, 'canFinalize'));

        parent::registerAccountIDValidation(
            'validateUserCanEditExam', 'examID'
        );

        parent::registerValidation('validateExamIDExists', 'examID');
        parent::registerValidation('validateExamStateIs', array('examID', 'finalizeState'));
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function canFinalize(int $examID) {
        return array('canFinalize' => true);
    }
}