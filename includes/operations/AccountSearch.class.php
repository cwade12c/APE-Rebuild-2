<?php
/**
 * Operation to search for all accounts with the given type(s)
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class AccountSearch extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_ADMIN, ACCOUNT_TYPE_TEACHER));

        parent::registerParameter('allowedTypes', 'array');

        parent::registerValidation(array($this, 'validateTypes'), 'allowedTypes');

        parent::registerExecution(array($this, 'searchAccounts'));
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function validateTypes(array $allowedTypes) {
        validateIsArray($allowedTypes, 'allowed account types');

        foreach($allowedTypes as $type) {
            validateIsInt($type, 'allowed account type value');
            if (!validSingleAccountType($type)) {
                throw new InvalidArgumentException('Invalid account type');
            }
        }

        return true;
    }

    /**
     * Search for accounts
     *
     * @param array $allowedTypes types of accounts to search for
     *
     * @return array
     */
    public static function searchAccounts(array $allowedTypes)
    {
        $accounts = array();

        foreach($allowedTypes as $type) {
            $foundAccounts = getAllAccountsWithType($type);
            foreach($foundAccounts as $account) {
                $accountID = $account['id'];
                if (!isset($accounts[$accountID])) {
                    $accounts[$accountID] = $account;
                }
            }
        }

        $accounts = array_values($accounts);

        return array(
            "accounts" => $accounts
        );
    }
}