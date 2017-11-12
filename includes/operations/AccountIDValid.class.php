<?php

/**
 * Check if given account ID is valid
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class AccountIDValid extends Operation
{
    function __construct()
    {
        parent::registerExecution(array($this, 'isValid'));

        parent::registerParameter('accountID', 'string');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function isValid(string $accountID)
    {
        $valid = validID($accountID) || validTempID($accountID);

        return array('valid' => $valid);
    }
}