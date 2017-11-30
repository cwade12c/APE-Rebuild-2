<?php

/**
 * Set finalization progress for a student
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class SetFinalizationProgress extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(
            array(ACCOUNT_TYPE_TEACHER, ACCOUNT_TYPE_ADMIN)
        );

        parent::registerParameter('examID', 'integer');
        parent::registerParameter('studentID', 'string');
        parent::registerParameter('grade', 'integer');
        parent::registerParameter('passed', 'boolean');

        parent::registerStaticParameter('finalizeState', EXAM_STATE_FINALIZING);

        parent::registerExecution(array($this, 'setProgress'));

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
        parent::registerValidation(
            'validateExamGrade', array('examID', 'grade')
        );
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function setProgress(int $examID, string $studentID, int $grade, bool $passed)
    {
        setStudentExamGrade($examID, $studentID, $grade, $passed);

        return array(true);
    }
}