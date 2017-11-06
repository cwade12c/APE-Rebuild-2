<?php

/**
 * Update Location Name class to update the Name of a Location
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class UpdateLocationName extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_ADMIN));

        parent::registerParameter('id', 'integer');
        parent::registerParameter('name', 'string');

        parent::registerExecution(array($this, "updateLocationName"));

        parent::registerValidation('validateLocationID', 'id');
        parent::registerValidation('validateLocationIDExists', 'id');
        parent::registerValidation('validateLocationNameChange', array('id', 'name'));
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function updateLocationName(int $id, string $name)
    {
        updateLocationName($id, $name);
        return array(true);
    }
}