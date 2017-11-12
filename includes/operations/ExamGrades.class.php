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
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_ADMIN, ACCOUNT_TYPE_TEACHER));

        parent::registerExecution(array($this, 'getExamGrades'));

        parent::registerParameter('examID', 'integer');
        parent::registerStaticParameter('archivedState', EXAM_STATE_ARCHIVED);

        parent::registerAccountIDValidation(
            'validateUserCanEditExam', 'examID'
        );

        parent::registerValidation('validateExamIDExists', 'examID');
        parent::registerValidation(
            'validateExamStateIs', array('examID', 'archivedState')
        );
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    /**
     * @param int $examID
     *
     * @return array        Associative array, keys
     *                      'grades' => set of grade info
     *                      grade info
     *                      'studentID'
     *                      'grade'
     *                      'passed'
     */
    public static function getExamGrades(int $examID)
    {
        $registrations = getExamRegistrations($examID);
        $grades = array();
        foreach ($registrations as $studentID) {
            $info = getStudentExamGradeDetails($examID, $studentID);
            $grade = array('studentID' => $studentID,
                           'grade'     => $info['grade'],
                           'passed'    => $info['passed']);
            array_push($grades, $grade);
        }

        return array(
            'grades' => $grades
        );
    }
}