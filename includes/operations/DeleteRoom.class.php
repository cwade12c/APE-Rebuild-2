<?php

/**
 * Delete Room class for deleting a Room
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class DeleteRoom extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_ADMIN));

        parent::registerExecution(array($this, 'deleteRoom'));

        parent::registerParameter('id', 'integer');

        parent::registerValidation('validateRoomIDExists', 'id');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function deleteRoom(int $id)
    {
        deleteRoom($id);
        return array('deletedID' => $id);
    }
}