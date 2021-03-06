<?php

/**
 * Update information for an account
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class UpdateAccount extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_ADMIN));

        parent::registerExecution(array($this, 'updateAccount'));

        parent::registerParameter('id', 'string');
        parent::registerParameter('firstName', 'string');
        parent::registerParameter('lastName', 'string');
        parent::registerParameter('email', 'string');
        parent::registerParameter('type', 'integer');

        parent::registerValidation('validateAccountFields', array(
            'id',
            'firstName',
            'lastName',
            'email'
        ));
        parent::registerValidation('validateAccountExists', 'id');
        parent::registerValidation('validateAccountType', 'type');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function updateAccount(string $id, string $firstName, string $lastName, string
    $email, $type)
    {
        updateAccountInfo($id, $firstName, $lastName, $email);
        updateAccountType($id, $type);

        return array(true);
    }
}