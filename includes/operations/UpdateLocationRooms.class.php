<?php

/**
 * Update Location Rooms class to update the reserved/limited Seats and Rooms
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class UpdateLocationRooms extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_ADMIN));

        parent::registerParameter('id', 'integer');
        parent::registerParameter('seatsReserved', 'integer');
        parent::registerParameter('limitedSeats', 'integer');
        parent::registerParameter('rooms', 'array');

        parent::registerExecution(array($this, "updateLocationRooms"));

        parent::registerValidation('validateLocationID', 'id');
        parent::registerValidation('validateLocationIDExists', 'id');
        parent::registerValidation('validateLocationRooms', array(
            'seatsReserved',
            'limitedSeats',
            'rooms'
        ));
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function updateLocationRooms(int $id, int $seatsReserved, int $limitedSeats,
                                               array $rooms)
    {
        $locationInformation = getLocationInformation($id);
        $name = $locationInformation['name'];

        updateLocationFull($id, $name, $seatsReserved, $limitedSeats, $rooms);
        return array(true);
    }
}