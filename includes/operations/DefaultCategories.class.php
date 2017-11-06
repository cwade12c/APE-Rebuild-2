<?php

/**
 * Default Categories class to retrieve all of the default categories
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class DefaultCategories extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_STUDENT, ACCOUNT_TYPE_GRADER,
            ACCOUNT_TYPE_TEACHER, ACCOUNT_TYPE_ADMIN));

        parent::registerExecution(array($this, "getDefaultCats"));
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function getDefaultCats()
    {
        $categoryIDs = getDefaultCategories();
        $defaultCategories = array();

        foreach($categoryIDs as $categoryID) {
            $category = getCategoryInfo($categoryID['id']);
            $category['id'] = $categoryID['id'];

            array_push($defaultCategories, $category);
        }

        return array(
            'defaultCategories' => $defaultCategories
        );
    }
}