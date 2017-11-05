<?php

/**
 * Check if current account is an admin
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class IsAdmin extends Operation
{
    function __construct()
    {
        parent::registerExecution(array($this, 'isAdmin'));

        parent::registerParameter('accountID', 'string');

        parent::registerAccountIDValidation(
            'validateAccountsMatch', 'accountID'
        );
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function isAdmin(string $accountID)
    {
        $is = accountTypeHas($accountID, ACCOUNT_TYPE_ADMIN);

        return array('isAdmin' => $is);
    }
}