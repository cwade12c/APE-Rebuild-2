<?php

/**
 * Get grader progress for exam/category
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class GraderProgress extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_GRADER));

        parent::registerExecution(array($this, 'getGraderProgress'));

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

    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function getGraderProgress(string $graderID, int $examID,
        int $categoryID
    ) {
        $grades = getGraderCategoryGrades($examID, $categoryID, $graderID);
        $grades = array_map(
            function ($row) {
                $row['studentID'] = hashAccountID($row['studentID']);
                if ($row['points'] == null) {
                    $row['points'] = -1;
                }
                return $row;
            }, $grades
        );

        return array('grades' => $grades);
    }
}