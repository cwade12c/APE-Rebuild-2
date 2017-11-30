<?php

/**
 * Update Report class to update the name and types of a report
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class UpdateReport extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_ADMIN, ACCOUNT_TYPE_TEACHER));

        parent::registerParameter('id', 'integer');
        parent::registerParameter('name', 'string');
        parent::registerParameter('rows', 'array');

        parent::registerExecution(array($this, "modifyReport"));

        parent::registerValidation('validateReportIDExists', 'id');
        parent::registerValidation('validateReportRows', 'rows');
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function modifyReport(int $id, string $name, array $rows)
    {
        updateReport($id, $name, $rows);
        return Reports::getReports();
    }
}