<?php

/**
 * Update Room class to update the Name and Seat Count of a Room
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class UpdateRoom extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_ADMIN));

        parent::registerParameter('id', 'integer');
        parent::registerParameter('name', 'string');
        parent::registerParameter('seats', 'integer');

        parent::registerExecution(array($this, "updateRoom"));

        parent::registerValidation('validateRoomID', 'id');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function updateRoom(int $id, string $name, int $seats)
    {
        updateRoom($id, $name, $seats);
        return array(true);
    }
}