<?php

/**
 * Get information on exam categories
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class ExamCategories extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(
            array(ACCOUNT_TYPE_ADMIN, ACCOUNT_TYPE_TEACHER, ACCOUNT_TYPE_GRADER)
        );

        parent::registerExecution(array($this, 'getExamCategories'));

        parent::registerParameter('id', 'integer');
        parent::registerValidation('validateExamIDExists', 'id');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function getExamCategories(int $examID)
    {
        $categories = getExamCategories($examID);
        foreach($categories as &$category) {
            $categoryID = $category['id'];
            $info = getCategoryInfo($categoryID);
            $category['name'] = $info['name'];
        }

        return array('categories' => $categories);
    }
}