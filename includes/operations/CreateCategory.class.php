<?php

/**
 * Create Category class for creating a Category
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class CreateCategory extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_ADMIN));

        parent::registerExecution(array($this, 'createCategory'));

        parent::registerParameter('name', 'string');
        parent::registerParameter('points', 'integer');

        parent::registerValidation('validateCategoryPoints', 'points');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function createCategory(string $name, int $points)
    {
        createCategory($name, $points);

        $id = getLastInsertedID();
        return array('categoryID' => $id);
    }
}