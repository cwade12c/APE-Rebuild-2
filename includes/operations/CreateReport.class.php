<?php

/**
 * Create Report class for creating a new named Report with types
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class CreateReport extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_ADMIN, ACCOUNT_TYPE_TEACHER));

        parent::registerExecution(array($this, 'addReport'));

        parent::registerParameter('name', 'string');
        parent::registerParameter('rows', 'array');

        parent::registerValidation('validateReportRows', 'rows');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function addReport(string $name, array $rows)
    {
        addNewReport($name, $rows);

        return array('createdReport' => true);
    }
}