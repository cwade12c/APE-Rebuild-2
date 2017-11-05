<?php

/**
 * Check if current account is a student
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class IsStudent extends Operation
{
    function __construct()
    {
        parent::registerExecution(array($this, 'isStudent'));

        parent::registerParameter('accountID', 'string');

        parent::registerAccountIDValidation(
            'validateAccountsMatch', 'accountID'
        );
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function isStudent(string $accountID)
    {
        $is = accountTypeHas($accountID, ACCOUNT_TYPE_STUDENT);

        return array('isStudent' => $is);
    }
}