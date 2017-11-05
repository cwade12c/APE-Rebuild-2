<?php

/**
 * Get grader progress for exam/category
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class SetGraderProgress extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_GRADER));

        parent::registerExecution(array($this, 'setGraderProgress'));

        parent::registerParameter('graderID', 'string');
        parent::registerParameter('examID', 'integer');
        parent::registerParameter('categoryID', 'integer');
        parent::registerParameter('grades', 'array');

        parent::registerStaticParameter(
            'allowedExamStates', array(EXAM_STATE_GRADING)
        );

        parent::registerValidation('validateGraderID', 'graderID');
        parent::registerValidation('validateExamIDExists', 'examID');
        parent::registerValidation('validateCategoryID', 'categoryID');
        parent::registerValidation(
            'validateExamCategory', array('examID', 'categoryID')
        );
        parent::registerValidation(
            'validateExamStateIs', array('examID', 'allowedExamStates')
        );
        parent::registerValidation(
            'validateGraderAssignedToExamCategory',
            array('graderID', 'examID', 'categoryID')
        );
        parent::registerValidation(
            'validateGraderNotSubmitted',
            array('graderID', 'examID', 'categoryID')
        );
        parent::registerValidation(
            array($this, 'validateGradesFormat'), 'grades'
        );
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function setGraderProgress(string $graderID, int $examID,
        int $categoryID, array $grades
    ) {
        $studentMap = self::buildStudentIDMap($examID);
        self::mapGrades($studentMap, $grades);

        setGraderCategoryGrades($examID, $categoryID, $graderID, $grades);

        return array('success' => true);
    }

    public static function buildStudentIDMap(int $examID)
    {
        $ids = getExamRegistrations($examID);
        $map = array();
        foreach ($ids as $id) {
            $hash = hashAccountID($id);
            $map[$hash] = $id;
        }

        return $map;
    }

    public static function mapGrades(array $map, array &$grades)
    {
        foreach ($grades as &$grade) {
            $hash = $grade['studentID'];
            if (!isset($map[$hash])) {
                throw new InvalidArgumentException(
                    "Student ID($hash) is not registered for exam or invalid"
                );
            }
            $id = $map[$hash];
            $grade['id'] = $id;
        }
    }

    public static function validateGradesFormat(array $grades)
    {
        if (empty($grades)) {
            throw new InvalidArgumentException("Grades given empty");
        }

        foreach ($grades as $i => $grade) {
            validateIsArray($grade, "grade index ($i)");
            validateKeysExist($grade, array('studentID', 'points'));
            validateIsInt($grade['studentID'], "grade index ($i) student ID");
            validateIsInt($grade['points'], "grade index ($i) points");
        }

        return true;
    }
}