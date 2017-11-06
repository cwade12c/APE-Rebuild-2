<?php

/**
 * Extended assigned exam details for a grader
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class GraderAssignedExamDetails extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_GRADER));

        parent::registerExecution(array($this, 'getAssignedExamDetails'));

        parent::registerParameter('graderID', 'string');
        parent::registerParameter('examID', 'integer');

        parent::registerValidation('validateGraderID', 'graderID');
        parent::registerValidation('validateExamIDExists', 'examID');
        parent::registerValidation('isGraderAssignedExam', array('graderID', 'examID'));
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function getAssignedExamDetails(string $graderID, int $examID)
    {
        $examCategoryIDs = getAssignedExamCategories($graderID, $examID);
        $assignedExams = GraderAssignedExams::getAssignedInfo(array(
            'examID' => $examID,
            'categories' => $examCategoryIDs
        ), $graderID);

        return array('assignedExamDetails' => $assignedExams,
                     'graderIsAssigned' => true);
    }
}