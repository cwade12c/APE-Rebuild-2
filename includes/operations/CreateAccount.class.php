<?php

/**
 * Create Account class for creating a single account
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class CreateAccount extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_ADMIN));

        parent::registerExecution(array($this, 'createAccount'));

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
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function createAccount(string $id, string $firstName, string $lastName, string
    $email, int $type)
    {
        switch($type) {
            case ACCOUNT_TYPE_TEMP:
                createTempStudent($firstName, $lastName, $email);
                break;
            case ACCOUNT_TYPE_STUDENT:
                createStudent($id, $firstName, $lastName, $email);
                break;
            case ACCOUNT_TYPE_GRADER:
                createGrader($id, $firstName, $lastName, $email);
                break;
            case ACCOUNT_TYPE_TEACHER:
                createTeacher($id, $firstName, $lastName, $email);
                break;
            case ACCOUNT_TYPE_ADMIN:
                createAdmin($id, $firstName, $lastName, $email);
                break;
            default:
                throw new Exception('An invalid account type was specified.');
                break;
        }
        return array(true);
    }
}