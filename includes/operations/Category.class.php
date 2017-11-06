<?php

/**
 * Category class to retrieve information about a particular category
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class Category extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_STUDENT, ACCOUNT_TYPE_GRADER,
            ACCOUNT_TYPE_TEACHER, ACCOUNT_TYPE_ADMIN));

        parent::registerParameter('id', 'integer');

        parent::registerExecution(array($this, "getCategory"));

        parent::registerValidation('validateCategoryID', 'id');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function getCategory(int $id)
    {
        $category = getCategoryInfo($id);
        $category['id'] = $id;

        return array('category' => $category);
    }
}