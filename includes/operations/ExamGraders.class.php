<?php

/**
 * Get basic information on graders for an exam
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class ExamGraders extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(
            array(ACCOUNT_TYPE_TEACHER, ACCOUNT_TYPE_ADMIN)
        );

        parent::registerExecution(array($this, 'getExamGraders'));

        parent::registerParameter('id', 'integer');
        parent::registerValidation('validateExamIDExists', 'id');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function getExamGraders(int $examID)
    {
        $graderIDs = getAssignedExamGraders($examID);
        $graders = array();
        foreach($graderIDs as $graderID) {
            $graderInfo = array();
            $graderInfo['id'] = $graderID;

            $accountInfo = getAccountInfo($graderID);
            $graderInfo['firstName'] = $accountInfo['firstName'];
            $graderInfo['lastName'] = $accountInfo['lastName'];
            $graderInfo['email'] = $accountInfo['email'];

            $categoryIDs = getAssignedExamCategories($graderID, $examID);
            $categories = array();
            foreach($categoryIDs as $categoryID) {
                $categoryInfo = array();
                $categoryInfo['id'] = $categoryID;
                $categoryInfo['submitted'] = isGraderCategorySubmitted(
                    $examID, $categoryID, $graderID
                );
                array_push($categories, $categoryInfo);
            }

            $graderInfo['categories'] = $categories;
            array_push($graders, $graderInfo);
        }

        return array('graders' => $graders);
    }
}