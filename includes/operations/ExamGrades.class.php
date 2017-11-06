<?php

/**
 * Get the grades for an exam
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class ExamGrades extends Operation
{
    function __construct()
    {
        parent::requireLogin(false);

        parent::registerExecution(array($this, 'getExamInformation'));

        parent::registerParameter('id', 'integer');
        parent::registerStaticParameter('archivedState', EXAM_STATE_ARCHIVED);

        parent::registerValidation('validateExamIDExists', 'id');
        parent::registerValidation('validateExamStateIs',array('id', 'archivedState'));
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function getExamGrades(int $examID)
    {
        $registrations = getExamRegistrations($examID);
        $grades = array();
        foreach($registrations as $studentID) {
            $points = getStudentExamGrade($examID, $studentID);
            $grade = array('studentID' => $studentID, 'grade' => $points);
            array_push($grades, $grade);
        }

        return array(
            'examID' => $examID,
            'grades' => $grades
        );
    }
}