<?php

/**
 * Get finalization progress for an exam
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class ExamFinalizationProgress extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(
            array(ACCOUNT_TYPE_TEACHER, ACCOUNT_TYPE_ADMIN)
        );

        parent::registerParameter('examID', 'integer');
        parent::registerStaticParameter('finalizeState', EXAM_STATE_FINALIZING);

        parent::registerExecution(array($this, 'getProgress'));

        parent::registerAccountIDValidation(
            'validateUserCanEditExam', 'examID'
        );

        parent::registerValidation('validateExamIDExists', 'examID');
        parent::registerValidation(
            'validateExamStateIs', array('examID', 'finalizeState')
        );
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    /**
     * @param int $examID
     *
     * @return array        results, associative array
     *                      'students' => student grade details
     *                      See StudentFinalizationProgress:getProgress()
     *                      for keys, info
     */
    public static function getProgress(int $examID)
    {
        $registrations = getExamRegistrations($examID);
        $conflicts = getConflicts($examID);
        $graders = getAssignedExamGradersCategories($examID);

        $students = array();
        foreach ($registrations as $studentID) {
            $student = StudentFinalizationProgress::getBasicInfo(
                $examID, $studentID
            );
            $studentConflicts = StudentFinalizationProgress::getStudentConflicts(
                $examID, $studentID, $conflicts, $graders
            );
            $student['conflicts'] = $studentConflicts;
            array_push($students, $studentID);
        }

        return array('students' => $students);
    }
}