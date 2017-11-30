<?php

/**
 * Get information on the exam location
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class ExamLocation extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(
            array(ACCOUNT_TYPE_ADMIN, ACCOUNT_TYPE_TEACHER, ACCOUNT_TYPE_GRADER)
        );

        parent::registerExecution(array($this, 'getExamLocation'));

        parent::registerParameter('id', 'integer');
        parent::registerValidation('validateExamIDExists', 'id');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function getExamLocation(int $examID)
    {
        $locationID = getExamLocationID($examID);
        if ($locationID == null) {
            return array('locationID' => null);
        }

        $info = getLocationInformation($locationID);

        $rooms = getLocationRooms($locationID);
        foreach ($rooms as &$room) {
            $roomID = $room['id'];
            $roomInfo = getRoomInformation($roomID);
            $room['name'] = $roomInfo['name'];
        }

        return array('locationID'    => $locationID,
                     'name'          => $info['name'],
                     'reservedSeats' => $info['reservedSeats'],
                     'limitedSeats'  => $info['limitedSeats'],
                     'rooms'         => $rooms);
    }
}