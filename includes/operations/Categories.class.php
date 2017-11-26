<?php

/**
 * Categories class to retrieve all of the categories
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class Categories extends Operation
{
    function __construct()
    {
        parent::requireLogin(false);

        parent::registerExecution(array($this, "getCategories"));
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function getCategories()
    {
        $categoryIDs = getCategories();
        $categories = array();

        foreach($categoryIDs as $categoryID) {
            $category = getCategoryInfo($categoryID['id']);
            $category['id'] = $categoryID['id'];

            array_push($categories, $category);
        }

        return array(
            'categories' => $categories
        );
    }
}