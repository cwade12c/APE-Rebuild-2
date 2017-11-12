<?php

/**
 * Resolve grader conflict for an exam in finalization
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class ResolveConflict extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(
            array(ACCOUNT_TYPE_TEACHER, ACCOUNT_TYPE_ADMIN)
        );

        parent::registerParameter('examID', 'integer');
        parent::registerParameter('categoryID', 'integer');
        parent::registerParameter('studentID', 'string');
        parent::registerParameter('grade', 'integer');

        parent::registerStaticParameter('finalizeState', EXAM_STATE_FINALIZING);

        parent::registerExecution(array($this, 'resolve'));

        parent::registerAccountIDValidation(
            'validateUserCanEditExam', 'examID'
        );

        parent::registerValidation('validateExamIDExists', 'examID');
        parent::registerValidation(
            'validateExamStateIs', array('examID', 'finalizeState')
        );
        parent::registerValidation('validateExamCategory', array('examID', 'categoryID'));
        parent::registerValidation('validateStudentID', 'studentID');
        parent::registerValidation('validateStudentIsRegisteredFor', array('studentID', 'examID'));
        parent::registerValidation('validateConflictExists', array('examID', 'categoryID', 'studentID'));
        parent::registerValidation('validateExamCategoryGrade', array('examID', 'categoryID', 'grade'));
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function resolve(int $examID, int $categoryID, string $studentID, int $grade)
    {
        startTransaction();

        resolveStudentCategoryGradeConflict($examID, $categoryID, $studentID, $grade);

        self::refreshExamGrade($studentID, $examID);

        commit();

        return array(true);
    }

    public static function refreshExamGrade($studentID, $examID)
    {
        $examInfo = getExamInformation($examID);
        $passingGrade = $examInfo['passingGrade'];

        $grades = getStudentCategoryGrades($examID, $studentID);
        $categoryGrades = array_column($grades, 'grade');

        $examGrade = determineExamGrade($passingGrade, $categoryGrades);

        setStudentExamGradeQuery(
            $examID, $studentID, $examGrade['grade'], $examGrade['passed']
        );
    }
}