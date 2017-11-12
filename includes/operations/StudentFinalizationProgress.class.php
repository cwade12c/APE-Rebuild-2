<?php

/**
 * Get finalization progress for an exam
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class StudentFinalizationProgress extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(
            array(ACCOUNT_TYPE_TEACHER, ACCOUNT_TYPE_ADMIN)
        );

        parent::registerParameter('examID', 'integer');
        parent::registerParameter('studentID', 'string');

        parent::registerStaticParameter('finalizeState', EXAM_STATE_FINALIZING);

        parent::registerExecution(array($this, 'getProgress'));

        parent::registerAccountIDValidation(
            'validateUserCanEditExam', 'examID'
        );

        parent::registerValidation('validateExamIDExists', 'examID');
        parent::registerValidation(
            'validateExamStateIs', array('examID', 'finalizeState')
        );
        parent::registerValidation(
            'validateStudentIsRegisteredFor', array('studentID', 'examID')
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
     *                      student grade details
     *                          'id', 'firstName', 'lastName', 'email'
     *                          'grade',    exam grade
     *                          'passed',   if passed exam
     *                          'comment',  comment - can be null
     *                          'conflicts', student grade conflicts
     *                                       can be empty
     *                      student grade conflicts
     *                          'categoryID',
     *                          'grade',        average grade for category
     *                          'graderGrades',      grader grade info
     *                      grader grade info
     *                          'graderID',
     *                          'grade'         grade set for student/category
     */
    public static function getProgress(int $examID, string $studentID)
    {
        $info = self::getBasicInfo($examID, $studentID);

        $conflicts = getConflicts($examID);
        $graders = getAssignedExamGradersCategories($examID);
        $studentConflicts = self::getStudentConflicts(
            $examID, $studentID, $conflicts, $graders
        );

        return array('id'        => $info['id'],
                     'firstName' => $info['firstName'],
                     'lastName'  => $info['lastName'],
                     'email'     => $info['email'],
                     'grade'     => $info['grade'],
                     'passed'    => $info['passed'],
                     'comment'   => $info['comment'],
                     'conflicts' => $studentConflicts);
    }

    /**
     * To only get the basic information, to efficiency purposes
     * between ExamFinalizationProgress and StudentFinalizationProgress
     *
     * @param int    $examID
     * @param string $studentID
     *
     * @return array
     */
    public static function getBasicInfo(int $examID, string $studentID)
    {
        $info = getAccountInfo($studentID);
        $firstName = $info['firstName'];
        $lastName = $info['lastName'];
        $email = $info['email'];

        $gradeInfo = getStudentExamGradeFull($examID, $studentID);
        $grade = $gradeInfo['grade'];
        $passed = $gradeInfo['passed'];
        $comment = $gradeInfo['comment'];

        return array('id'        => $studentID,
                     'firstName' => $firstName,
                     'lastName'  => $lastName,
                     'email'     => $email,
                     'grade'     => $grade,
                     'passed'    => $passed,
                     'comment'   => $comment);
    }

    /**
     * Get conflicts for student
     * Takes in list of conflicts/graders for efficiency,
     * used by StudentFinalizationProgress and ExamFinalizationProgress
     *
     * @param int    $examID
     * @param string $studentID
     * @param array  $conflicts
     * @param array  $graders
     *
     * @return array
     */
    public static function getStudentConflicts(int $examID, string $studentID,
        array $conflicts, array $graders
    ) {
        $conflictCategories = self::findStudentConflict($studentID, $conflicts);

        $studentConflicts = array();
        foreach ($conflictCategories as $categoryID) {
            $conflict = array();
            $conflict['categoryID'] = $categoryID;
            $conflict['grade'] = getStudentCategoryGrade(
                $examID, $categoryID, $studentID
            );

            $categoryGraders = self::findCategoryGraders($graders, $categoryID);
            $graderGrades = self::getGraderGrades(
                $studentID, $examID, $categoryID, $categoryGraders
            );

            $conflict['graderGrades'] = $graderGrades;

            array_push($studentConflicts, $conflict);
        }

        return $studentConflicts;
    }

    private static function findStudentConflict(string $studentID,
        array $conflicts
    ) {
        foreach ($conflicts as $conflict) {
            if ($studentID == $conflict['studentID']) {
                return $conflict['categories'];
            }
        }
        return array();
    }

    private static function findCategoryGraders(array $graders, int $categoryID)
    {
        $graderIDs = array();
        foreach ($graders as $grader) {
            if (in_array($categoryID, $graders['categories'])) {
                array_push($graderIDs, $grader['graderID']);
            }
        }
        return $graderIDs;
    }

    private static function getGraderGrades(string $studentID, int $examID,
        int $categoryID, array $categoryGraders
    ) {
        $grades = array();
        foreach ($categoryGraders as $graderID) {
            $grade['graderID'] = $graderID;
            $grade['grade'] = getGraderCategoryStudentGrade(
                $examID, $categoryID, $graderID, $studentID
            );
            array_push($grades, $grade);
        }
        return $grades;
    }
}