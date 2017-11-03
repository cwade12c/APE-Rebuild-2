<?php

/**
 * Summary of upcoming exams
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class UpcomingExams extends Operation
{
    function __construct()
    {
        parent::requireLogin(false);

        parent::registerExecution(array($this, 'getUpcomingExams'));
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function getUpcomingExams()
    {
        $upcomingIDs = getExamsUpcoming(GET_EXAMS_TYPE_REGULAR);

        $exams = array();

        foreach($upcomingIDs as $examID) {
            $info = ExamDetails::getExamInformation($examID);
            array_push($exams, $info);
        }

        return array('exams' => $exams);
    }
}