<?php

/**
 * Upgrade a temp account to a full account
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class TempToFull extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_ADMIN));

        parent::registerExecution(array($this, 'promoteAccount'));

        // should require names/email, but on login they are populated
        parent::registerParameter('currentID', 'string');
        parent::registerParameter('newID', 'string');

        // only temp is valid on students, so don't need to specify
        parent::registerValidation('validateTempExists', 'currentID');
        parent::registerValidation('validateEWUID', 'newID');
        parent::registerValidation(
            array($this, 'validateNewIDAllowed'), 'newID'
        );
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function promoteAccount(string $currentID, string $newID)
    {
        promoteTempToStudent($currentID, $newID);

        return array(true);
    }

    // if new id exists, validate that is not
    public static function validateNewIDAllowed(string $newID)
    {
        if (!accountExists($newID)) {
            return true;
        }

        // TODO any validations before combining accounts

        return true;
    }
}