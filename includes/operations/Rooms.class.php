<?php

/**
 * Rooms class to retrieve all of the rooms
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class Rooms extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_STUDENT, ACCOUNT_TYPE_GRADER,
            ACCOUNT_TYPE_TEACHER, ACCOUNT_TYPE_ADMIN));

        parent::registerExecution(array($this, "getRooms"));
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function getRooms()
    {
        $roomIDs = getRooms();
        $rooms = array();

        foreach($roomIDs as $roomID) {
            $room = getRoomInformation($roomID['id']);
            $room['id'] = $roomID['id'];

            array_push($rooms, $room);
        }

        return array(
            'rooms' => $rooms
        );
    }
}