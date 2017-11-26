<?php

/**
 * Get full details of an individual exam
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class ExamDetailsFull extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(
            array(ACCOUNT_TYPE_TEACHER, ACCOUNT_TYPE_ADMIN)
        );

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

        list(
            $locationID, $locationName, $maxSeats, $reservedSeats, $totalSeats,
            $seatsTaken, $rooms
            )
            = self::getLocationInformation($examID);

        $categories = self::getCategoryInformation($examID);

        $registrations = self::getRegistrationInformation($examID);

        $graders = self::getGraderInformation($examID);

        list($isRegular, $teacherID) = self::getExamInClassInformation($info);

        return array(
            'examID'        => $examID,
            'start'         => $info['start'],
            'cutoff'        => $info['cutoff'],
            'length'        => $info['length'],
            'passingGrade'  => $info['passingGrade'],
            'locationID'    => $locationID,
            'locationName'  => $locationName,
            'maxSeats'      => $maxSeats,
            'reservedSeats' => $reservedSeats,
            'totalSeats'    => $totalSeats,
            'takenSeats'    => $seatsTaken,
            'rooms'         => $rooms,
            'categories'    => $categories,
            'registrations' => $registrations,
            'graders'       => $graders,
            'state'         => $info['state'],
            'stateStr'      => examStateToString($info['state']),
            'teacherID'     => $teacherID,
            'isRegular'     => $isRegular
        );
    }

    public static function getLocationInformation(int $examID)
    {
        $locationName = "N/A";
        $maxSeats = -1;
        $reservedSeats = -1;
        $totalSeats = -1;
        $seatsTaken = -1;

        $locationID = getExamLocationID($examID);

        $rooms = null;

        if ($locationID != null) {
            $locationInfo = getLocationInformation($locationID);
            $locationName = $locationInfo['name'];
            $reservedSeats = $locationInfo['reserved_seats'];

            $maxSeats = getLocationRoomsMaxSeats($locationID);
            $totalSeats = $maxSeats - $reservedSeats;
            $seatsTaken = getAssignedSeatCount($examID);

            // room info
            $rooms = self::getLocationRoomsInformation($locationID);
        }

        return array($locationID, $locationName, $maxSeats, $reservedSeats,
                     $totalSeats, $seatsTaken, $rooms);
    }

    public static function getLocationRoomsInformation(int $locationID)
    {
        $rooms = getLocationRooms($locationID);
        foreach ($rooms as &$room) {
            $info = getRoomInformation($room['id']);
            $room['name'] = $info['name'];
        }
        return $rooms;
    }

    public static function getCategoryInformation(int $examID)
    {
        $categories = getExamCategories($examID);
        foreach ($categories as &$category) {
            $categoryID = $category['id'];
            $info = getCategoryInfo($categoryID);
            $category['name'] = $info['name'];
            //$category['points'] = $info['points'];
        }

        return $categories;
    }

    public static function getRegistrationInformation(int $examID)
    {
        $registeredIDs = getExamRegistrations($examID);
        $registrations = array();
        foreach ($registeredIDs as $studentID) {
            $info = getAccountInfo($studentID);
            $info['studentID'] = $studentID;
            array_push($registrations, $info);
        }

        return $registrations;
    }

    public static function getGraderInformation(int $examID)
    {
        $graderInfo = array();
        $graderCategories = getAssignedExamGradersCategories($examID);
        foreach ($graderCategories as $grader) {
            $graderID = $grader['graderID'];

            $accountInfo = getAccountInfo($graderID);
            $grader['firstName'] = $accountInfo['firstName'];
            $grader['lastName'] = $accountInfo['lastName'];

            $categories = array();
            foreach ($grader['categories'] as $categoryID) {
                $category = array();
                $category['categoryID'] = $categoryID;
                $category['name'] = getCategoryName($categoryID);
                $category['submitted'] = isGraderCategorySubmitted(
                    $examID,
                    $categoryID, $graderID
                );
                array_push($categories, $category);
            }
            $grader['categories'] = $categories;

            array_push($graderInfo, $grader);
        }

        return $graderInfo;
    }

    public static function getExamInClassInformation(array $examInfo)
    {
        $isRegular = $examInfo['isRegular'];
        $teacherID = $isRegular ? null : getInClassExamTeacher($examInfo['id']);

        return array($isRegular, $teacherID);
    }
}