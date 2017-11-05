<?php

/**
 * Check if current account is a student
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class IsTeacher extends Operation
{
    function __construct()
    {
        parent::registerExecution(array($this, 'isTeacher'));

        parent::registerParameter('accountID', 'string');

        parent::registerAccountIDValidation(
            'validateAccountsMatch', 'accountID'
        );
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function isTeacher(string $accountID)
    {
        $is = accountTypeHas($accountID, ACCOUNT_TYPE_TEACHER);

        return array('isTeacher' => $is);
    }
}