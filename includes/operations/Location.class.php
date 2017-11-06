<?php

/**
 * Location class to retrieve information about a particular location
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class Location extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_STUDENT, ACCOUNT_TYPE_GRADER,
            ACCOUNT_TYPE_TEACHER, ACCOUNT_TYPE_ADMIN));

        parent::registerParameter('id', 'integer');

        parent::registerExecution(array($this, "getLocation"));

        parent::registerValidation('validateLocationID', 'id');
        parent::registerValidation('validateLocationIDExists', 'id');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function getLocation(int $id)
    {
        $location = getLocationInformation($id);
        $location['id'] = $id;
        $roomsInformation = getLocationRooms($id);
        $rooms = array();

        foreach($roomsInformation as $currentRoomInformation) {
            $room = getRoomInformation($currentRoomInformation['id']);
            $room['id'] = $currentRoomInformation['id'];
            $room['seats'] = $currentRoomInformation['seats'];
            array_push($rooms, $room);
        }

        $location['rooms'] = $rooms;

        return array(
            'location' => $location
        );
    }
}