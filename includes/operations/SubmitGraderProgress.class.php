<?php

/**
 * Get grader progress for exam/category
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class SubmitGraderProgress extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_GRADER));

        parent::registerExecution(array($this, 'submit'));

        parent::registerParameter('graderID', 'string');
        parent::registerParameter('examID', 'integer');
        parent::registerParameter('categoryID', 'integer');

        parent::registerStaticParameter(
            'allowedExamStates', array(EXAM_STATE_GRADING)
        );

        parent::registerValidation('validateGraderID', 'graderID');
        parent::registerValidation('validateExamIDExists', 'examID');
        parent::registerValidation('validateCategoryID', 'categoryID');
        parent::registerValidation(
            'validateExamCategory', array('examID', 'categoryID')
        );
        parent::registerValidation(
            'validateExamStateIs', array('examID', 'allowedExamStates')
        );
        parent::registerValidation(
            'validateGraderAssignedToExamCategory',
            array('graderID', 'examID', 'categoryID')
        );
        parent::registerValidation(
            'validateGraderNotSubmitted',
            array('graderID', 'examID', 'categoryID')
        );
        parent::registerValidation(
            'validateGraderCategorySet',
            array('graderID', 'examID', 'categoryID')
        );
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function submit(string $graderID, int $examID,
        int $categoryID
    ) {
        setGraderCategorySubmitted($examID, $categoryID, $graderID, true);

        return array('success' => true);
    }
}