<?php

/**
 * Create Room class for creating a Room
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class CreateRoom extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_ADMIN));

        parent::registerExecution(array($this, 'createRoom'));

        parent::registerParameter('name', 'string');
        parent::registerParameter('seats', 'integer');

        parent::registerValidation('validateRoomName', 'name');
        parent::registerValidation('validateRoomNameDoesNotExist', 'name');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function createRoom(string $name, int $seats)
    {
        createRoom($name, $seats);

        $id = getLastInsertedID();
        return array('roomID' => $id);
    }
}