<?php

/**
 * Resolve grader conflict for an exam in finalization
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class SetComment extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(
            array(ACCOUNT_TYPE_TEACHER, ACCOUNT_TYPE_ADMIN)
        );

        parent::registerParameter('examID', 'integer');
        parent::registerParameter('studentID', 'string');
        parent::registerParameter('comment', 'string');

        parent::registerStaticParameter('finalizeState', EXAM_STATE_FINALIZING);

        parent::registerExecution(array($this, 'setComment'));

        parent::registerAccountIDValidation(
            'validateUserCanEditExam', 'examID'
        );

        parent::registerValidation('validateExamIDExists', 'examID');
        parent::registerValidation(
            'validateExamStateIs', array('examID', 'finalizeState')
        );
        parent::registerValidation('validateStudentID', 'studentID');
        parent::registerValidation('validateStudentIsRegisteredFor', array('studentID', 'examID'));
        parent::registerValidation('validateComment', 'comment');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function setComment(int $examID, string $studentID, string $comment)
    {
        setStudentExamComment($examID, $studentID, $comment);

        return array(true);
    }
}