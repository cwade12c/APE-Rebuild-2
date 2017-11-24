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

    /**
     * Performs operation
     *
     * @param int $examID
     *
     * @return array      (public) details for an exam
     *                    'examID'
     *                    'start'           start datetime
     *                    'cutoff'          registration cutoff datetime
     *                    'length'          length in minutes of exam
     *                    'locationName'    'N/A' if does not exist
     *                    'totalSeats'      the max amount of seats
     *                                      minus the amount of reserved
     *                                      and any max cap set
     *                                      '-1' if issue
     *                    'takenSeats'      seats currently assigned
     *                                      '-1' if issue
     *                    'stateStr'        string representation
     *                                      of exam state
     *                    'isRegular'       if exam is regular/in-class
     *                     'teacherID'      ID of teacher if in-class
     *                                      null otherwise
     */
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

        list(
            $isRegular, $teacherID
            )
            = ExamDetailsFull::getExamInClassInformation($info);

        return array(
            'examID' => $examID,
            'start' => $info['start'],
            'cutoff' => $info['cutoff'],
            'length' => $info['length'],
            'locationName' => $locationName,
            'totalSeats' => $totalSeats,
            'takenSeats' => $seatsTaken,
            'stateStr' => examStateToString($info['state']),
            'isRegular' => $isRegular,
            'teacherID' => $teacherID
        );
    }
}