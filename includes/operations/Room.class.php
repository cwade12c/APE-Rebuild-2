<?php

/**
 * Room class to retrieve information about a particular room
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class Room extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_STUDENT, ACCOUNT_TYPE_GRADER,
            ACCOUNT_TYPE_TEACHER, ACCOUNT_TYPE_ADMIN));

        parent::registerParameter('id', 'integer');

        parent::registerExecution(array($this, "getRoom"));

        parent::registerValidation('validateRoomID', 'id');
        parent::registerValidation('validateRoomIDExists', 'id');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function getRoom(int $id)
    {
        return array(
            'room' => getRoomInformation($id)
        );
    }
}