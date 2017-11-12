<?php

/**
 * Assign grader to an exam category
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class AssignGrader extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(
            array(ACCOUNT_TYPE_ADMIN, ACCOUNT_TYPE_TEACHER)
        );

        parent::registerParameter('examID', 'integer');
        parent::registerParameter('categoryID', 'integer');
        parent::registerParameter('graderID', 'string');

        parent::registerExecution(array($this, "assignGrader"));

        parent::registerAccountIDValidation(
            'validateUserCanEditExam', 'examID'
        );

        parent::registerValidation('validateExamIDExists', 'examID');
        parent::registerValidation('validateExamCategory', array('examID', 'categoryID'));
        parent::registerValidation('validateExamStateAllowsGraderAssignment', 'examID');
        parent::registerValidation('validateGraderID', 'graderID');
        parent::registerValidation('validateGraderNotAssigned', array('graderID', 'examID', 'categoryID'));
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function assignGrader(int $examID, int $categoryID,
        string $graderID
    ) {
        assignGrader($examID, $categoryID, $graderID);

        return array(true);
    }
}