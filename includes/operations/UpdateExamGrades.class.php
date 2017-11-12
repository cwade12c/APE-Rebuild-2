<?php

/**
 * Update exam grades (categories, passing grade)
 * For categories, expected element format
 *  'id' => category ID, 'points' => category points
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class UpdateExamGrades extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(
            array(ACCOUNT_TYPE_ADMIN, ACCOUNT_TYPE_TEACHER)
        );

        parent::registerParameter('examID', 'integer');
        parent::registerParameter('passingGrade', 'integer');
        parent::registerParameter('categories', 'array');

        parent::registerExecution(array($this, "updateExamGrades"));

        parent::registerAccountIDValidation(
            'validateUserCanEditExam', 'examID'
        );

        parent::registerValidation('validateExamIDExists', 'examID');
        parent::registerValidation('validateExamStateAllowsEdits', 'examID');
        parent::registerValidation(
            'validateExamCategories', array('passingGrade', 'categories')
        );
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function updateExamGrades(int $examID, int $passingGrade,
        array $categories
    ) {
        updateExamGrades($examID, $passingGrade, $categories);

        return array(true);
    }
}