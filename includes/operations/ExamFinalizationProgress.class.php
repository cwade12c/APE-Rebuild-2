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
     *
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
    public static function getProgress(int $examID)
    {
        $registrations = getExamRegistrations($examID);
        $conflicts = getConflicts($examID);
        $graders = getAssignedExamGradersCategories($examID);

        $students = array();
        foreach ($registrations as $studentID) {
            $student = array();
            $student['id'] = $studentID;

            self::getStudentInfo($student);
            self::getStudentGrade($student, $examID);
            self::getStudentConflicts($student, $examID, $conflicts, $graders);

            $student['comment'] = getStudentExamComment($examID, $studentID);

            array_push($students, $studentID);
        }

        return array('students' => $students);
    }

    public static function getStudentInfo(array &$student)
    {
        $info = getAccountInfo($student['id']);
        $student['firstName'] = $info['firstName'];
        $student['lastName'] = $info['lastName'];
        $student['email'] = $info['email'];
    }

    public static function getStudentGrade(array &$student, int $examID)
    {
        $studentID = $student['id'];

        $grade = getStudentExamGradeDetails($examID, $studentID);
        $student['grade'] = $grade['grade'];
        $student['passed'] = $grade['passed'];

        // category grades
    }

    public static function getStudentConflicts(array &$student, int $examID,
        array $conflicts, array $graders
    ) {
        $studentID = $student['id'];
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

        $student['conflicts'] = $studentConflicts;
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
        int $categoryID,
        array $categoryGraders
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