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

        parent::registerAccountIDValidation(
            'validateAccountsMatch', 'graderID'
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
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    /**
     * @param string $graderID
     * @param int    $examID
     * @param int    $categoryID
     *
     * @return array             Resulting progress, format
     *                           'grades' => grades progress
     *                           'categoryName'
     *                           'categoryPoints'
     *                           grades progress format
     *                           'studentID' => hash of student ID
     *                           'points' => points saved by grader
     *                                       (-1) if not set
     */
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

        $categoryName = getCategoryName($categoryID);
        $categoryPoints = getExamCategoryPoints($examID, $categoryID);

        return array('grades'         => $grades,
                     'categoryName'   => $categoryName,
                     'categoryPoints' => $categoryPoints);
    }
}