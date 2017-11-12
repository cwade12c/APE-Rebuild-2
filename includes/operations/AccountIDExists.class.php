<?php

/**
 * Check if given account ID exists
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class AccountIDExists extends Operation
{
    function __construct()
    {
        parent::registerExecution(array($this, 'exists'));

        parent::registerParameter('accountID', 'string');

        parent::registerValidation('validateAccountID', 'accountID');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function exists(string $accountID)
    {
        $exists = accountExists($accountID);

        return array('exists' => $exists);
    }
}