<?php

/**
 * Create Accounts class for creating multiple accounts
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class CreateAccounts extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_ADMIN));

        parent::registerExecution(array($this, 'createAccounts'));

        parent::registerParameter('accounts', 'array');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function createAccounts(array $accounts)
    {
        if(sizeof($accounts) < 1) {
            throw new Exception("No valid accounts were specified to create!");
        }

        foreach($accounts as $account) {
            CreateAccount::createAccount($account['id'], $account['firstName'], $account['lastName'],
                $account['email'], $account['type']);
        }

        return array(true);
    }
}