<?php

/**
 * Locations class to retrieve all of the locations
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class Locations extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_STUDENT, ACCOUNT_TYPE_GRADER,
            ACCOUNT_TYPE_TEACHER, ACCOUNT_TYPE_ADMIN));

        parent::registerExecution(array($this, "getLocations"));
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function getLocations()
    {
        $locationIDs = getLocations();
        $locations = array();

        foreach($locationIDs as $locationID) {
            $location = getLocationInformation($locationID['id']);
            $location['id'] = $locationID['id'];

            $roomsInformation = getLocationRooms($locationID['id']);
            $rooms = array();

            foreach($roomsInformation as $currentRoomInformation) {
                $room = getRoomInformation($currentRoomInformation['id']);
                $room['id'] = $currentRoomInformation['id'];
                $room['seats'] = $currentRoomInformation['seats'];
                array_push($rooms, $room);
            }

            $location['rooms'] = $rooms;
            array_push($locations, $location);
        }

        return array(
            'locations' => $locations
        );
    }
}