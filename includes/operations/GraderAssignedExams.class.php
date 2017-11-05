<?php

/**
 * Summary of assigned exams for a grader
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class GraderAssignedExams extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_GRADER));

        parent::registerExecution(array($this, 'getAssignedExams'));

        parent::registerParameter('graderID', 'string');

        parent::registerValidation('validateGraderID', 'graderID');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function getAssignedExams(string $graderID)
    {
        $examCategoryIDs = getAssignedExamsCategories($graderID);
        $assignedInfo = array();
        foreach ($examCategoryIDs as $ids) {
            array_push($assignedInfo, self::getAssignedInfo($ids, $graderID));
        }

        return array('assignedInfo' => $assignedInfo);
    }

    public static function getAssignedInfo(array $examCategoryIDs,
        string $graderID
    ) {
        $info = array();

        $examID = $examCategoryIDs['examID'];
        $examInfo = getExamInformation($examID);
        $state = $examInfo['state'];
        $info['state'] = $state;

        $categoryIDs = $examCategoryIDs['categories'];

        $examSubmitted = true;

        $info['examID'] = $examID;
        $categories = array();
        foreach ($categoryIDs as $categoryID) {
            $categoryInfo = getCategoryInfo($categoryID);
            $categoryInfo['categoryID'] = $categoryID;
            $submitted = isGraderCategorySubmitted(
                $examID, $categoryID, $graderID
            );
            $categoryInfo['submitted'] = $submitted;

            if (!$submitted) {
                $examSubmitted = false;
            }

            $gradesTotal = 0;
            $gradesSet = 0;
            if (doesExamStateAllowGrading($state)) {
                $grades = getGraderCategoryGrades(
                    $examID, $categoryID, $graderID
                );
                $gradesTotal = count($grades);
                $gradesSet = count(
                    array_filter(
                        $grades, function ($grade) {
                        return ($grade['points'] != true);
                    }
                    )
                );
            }

            $categoryInfo['gradesTotal'] = $gradesTotal;
            $categoryInfo['gradesSet'] = $gradesSet;

            array_push($categories, $categoryInfo);
        }
        $info['categories'] = $categories;
        $info['submitted'] = $examSubmitted;

        return $info;
    }
}