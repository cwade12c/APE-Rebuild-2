<?php

/**
 * Exam Details class for retrieving dates/times, location, space, state
 *
 * @author         Curran Higgins
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class ExamDetails extends Operation
{
    function __construct()
    {
        parent::requireLogin(false);

        parent::registerExecution(array($this, 'getExamInformation'));

        parent::registerParameter('id', 'integer');
        parent::registerValidation('validateExamIDExists', 'id');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function getExamInformation(int $examID)
    {
        // get start/cutoff datetime, location name, space taken/total, state
        $info = getExamInformation($examID);

        $locationName = "N/A";
        $totalSeats = -1;
        $seatsTaken = -1;

        $locationID = $info['locationID'];
        if ($locationID != null) {
            $locationInfo = getLocationInformation($info['locationID']);
            $locationName = $locationInfo['name'];
            $reservedSeats = $locationInfo['reserved_seats'];

            $maxSeats = getLocationRoomsMaxSeats($locationID);
            $totalSeats = $maxSeats - $reservedSeats;
            $seatsTaken = getAssignedSeatCount($examID);
        }

        return array(
            'start' => $info['start'],
            'cutoff' => $info['cutoff'],
            'locationName' => $locationName,
            'totalSeats' => $totalSeats,
            'takenSeats' => $seatsTaken,
            'state' => $info['state']
        );
    }
}