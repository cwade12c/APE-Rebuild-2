<?php

/**
 * Update Category class to update the Name and Points of a Category
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class UpdateCategory extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_ADMIN));

        parent::registerParameter('id', 'integer');
        parent::registerParameter('name', 'string');
        parent::registerParameter('points', 'integer');

        parent::registerExecution(array($this, "updateCategory"));

        parent::registerValidation('validateCategoryID', 'id');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function updateCategory(int $id, string $name, int $points)
    {
        updateCategory($id, $name, $points);

        return array('updatedCategory' => array(
            'id' => $id,
            'name' => $name,
            'points' => $points
        ));
    }
}