<?php

/**
 * Check if current grader is assigned to the exam/category
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class IsGraderAssigned extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_GRADER));

        parent::registerExecution(array($this, 'isGraderAssigned'));

        parent::registerParameter('graderID', 'string');
        parent::registerParameter('examID', 'integer');
        parent::registerParameter('categoryID', 'integer');

        parent::registerValidation('validateGraderID', 'graderID');
        parent::registerValidation('validateExamIDExists', 'examID');
        parent::registerValidation('validateCategoryID', 'categoryID');
        parent::registerValidation(
            'validateExamCategory', array('examID', 'categoryID')
        );

        parent::registerAccountIDValidation(
            'validateAccountsMatch', 'graderID'
        );
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function isGraderAssigned(string $graderID, int $examID, int $categoryID)
    {
        $is = isGraderAssignedExamCategory($graderID, $examID, $categoryID);

        return array('isAssigned' => $is);
    }
}