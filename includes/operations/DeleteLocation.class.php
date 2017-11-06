<?php

/**
 * Delete Location class for deleting a Location
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class DeleteLocation extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_ADMIN));

        parent::registerExecution(array($this, 'deleteLocation'));

        parent::registerParameter('id', 'integer');

        parent::registerValidation('validateLocationIDExists', 'id');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function deleteLocation(int $id)
    {
        deleteLocation($id);
        return array('deletedID' => $id);
    }
}