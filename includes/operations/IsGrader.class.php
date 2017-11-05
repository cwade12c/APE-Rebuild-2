<?php

/**
 * Check if current account is a grader
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class IsGrader extends Operation
{
    function __construct()
    {
        parent::registerExecution(array($this, 'isGrader'));

        parent::registerParameter('accountID', 'string');

        parent::registerAccountIDValidation(
            'validateAccountsMatch', 'accountID'
        );
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function isGrader(string $accountID)
    {
        $is = accountTypeHas($accountID, ACCOUNT_TYPE_GRADER);

        return array('isGrader' => $is);
    }
}