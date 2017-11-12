<?php

/**
 * Get full details for an account
 * Currently just names/email/permissions
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class AccountDetails extends Operation
{
    function __construct()
    {
        parent::registerExecution(array($this, 'getDetails'));

        parent::registerParameter('accountID', 'string');

        parent::registerValidation('validateAccountExists', 'accountID');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function getDetails(string $accountID)
    {
        $info = getAccountInfo($accountID);
        $firstName = $info['firstName'];
        $lastName = $info['lastName'];
        $email = $info['email'];

        $type = getAccountType($accountID);
        $isAdmin = typeHas($type, ACCOUNT_TYPE_ADMIN);
        $isTeacher = typeHas($type, ACCOUNT_TYPE_TEACHER);
        $isGrader = typeHas($type, ACCOUNT_TYPE_GRADER);
        $isStudent = typeHas($type, ACCOUNT_TYPE_STUDENT);
        $isTemp = typeHas($type, ACCOUNT_TYPE_TEMP);

        return array('firstName' => $firstName,
                     'lastName'  => $lastName,
                     'email'     => $email,
                     'isAdmin'   => $isAdmin,
                     'isTeacher' => $isTeacher,
                     'isGrader'  => $isGrader,
                     'isStudent' => $isStudent,
                     'isTemp'    => $isTemp);
    }
}