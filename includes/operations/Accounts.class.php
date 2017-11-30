<?php

/**
 * Accounts class for getting a list of all the accounts
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class Accounts extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_ADMIN, ACCOUNT_TYPE_TEACHER));

        parent::registerExecution(array($this, 'getAccounts'));
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function getAccounts()
    {
        $accounts = getAllAccounts();
        return array(
            "accounts" => $accounts
        );
    }
}