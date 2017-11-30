<?php

/**
 * To resolve the state of a student in the blocked state
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class ResolveBlock extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_ADMIN));

        parent::registerExecution(array($this, 'promoteAccount'));

        parent::registerParameter('studentID', 'string');
        parent::registerParameter('newState', 'integer');

        parent::registerValidation('validateStudentID', 'studentID');
        parent::registerValidation('validateStudentBlocked', 'studentID');
        parent::registerValidation('validateRegistrationState', 'newState');
        parent::registerValidation(array($this, 'validateNewStateValid'), 'newState');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function promoteAccount(string $studentID, int $newState)
    {
        setRegistrationState($studentID, $newState);
        return array(true);
    }

    public static function validateNewStateValid(int $state)
    {
        if ($state != STUDENT_STATE_BLOCKED_BYPASSED
            && $state != STUDENT_STATE_BLOCKED_IGNORED
        ) {
            throw new InvalidArgumentException("student state($state) not a valid block resolution state");
        }
        return true;
    }
}