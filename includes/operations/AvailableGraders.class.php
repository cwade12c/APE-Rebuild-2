<?php

/**
 * Get available graders, id + names/email
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class AvailableGraders extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(
            array(ACCOUNT_TYPE_TEACHER, ACCOUNT_TYPE_ADMIN)
        );

        parent::registerExecution(array($this, 'getGraders'));
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function getGraders()
    {
        $graders = getAllGraders();
        foreach($graders as &$grader) {
            $grader['firstName'] = $grader['f_name'];
            unset($grader['f_name']);

            $grader['lastName'] = $grader['l_name'];
            unset($grader['l_name']);
        }

        return array('graders' => $graders);
    }
}