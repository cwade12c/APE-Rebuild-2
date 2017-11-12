<?php

/**
 * Get exam details related to grading (passing grade, category info)
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class ExamGradeInfo extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(
            array(ACCOUNT_TYPE_ADMIN, ACCOUNT_TYPE_TEACHER, ACCOUNT_TYPE_GRADER)
        );

        parent::registerExecution(array($this, 'getInformation'));

        parent::registerParameter('id', 'integer');

        parent::registerValidation('validateExamIDExists', 'id');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function getInformation(int $examID)
    {
        $info = getExamInformation($examID);
        $passingGrade = $info['passingGrade'];

        $categories = getExamCategories($examID);
        foreach ($categories as &$category) {
            $info = getCategoryInfo($category['id']);
            $category['name'] = $info['name'];
        }

        return array(
            'passingGrade' => $passingGrade,
            'categories'   => $categories
        );
    }
}