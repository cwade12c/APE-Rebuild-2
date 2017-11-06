<?php

/**
 * Delete Category class for deleting a Category
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class DeleteCategory extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_ADMIN));

        parent::registerExecution(array($this, 'deleteCategory'));

        parent::registerParameter('id', 'integer');

        parent::registerValidation('validateCategoryID', 'id');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function deleteCategory(int $id)
    {
        deleteCategory($id);
        return array('deletedCategoryID' => $id);
    }
}