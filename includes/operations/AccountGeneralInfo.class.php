<?php

/**
 * Get names/email of given account
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class AccountGeneralInfo extends Operation
{
    function __construct()
    {
        parent::registerExecution(array($this, 'getInfo'));

        parent::registerParameter('accountID', 'string');

        parent::registerValidation('validateAccountExists', 'accountID');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function getInfo(string $accountID)
    {
        $info = getAccountInfo($accountID);
        $firstName = $info['firstName'];
        $lastName = $info['lastName'];
        $email = $info['email'];

        return array('firstName' => $firstName, 'lastName' => $lastName,
                     'email'     => $email);
    }
}