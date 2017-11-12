<?php

/**
 * Reports class to retrieve a list of reports (id, name) and their rows (type id)
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class Reports extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_ADMIN, ACCOUNT_TYPE_TEACHER));

        parent::registerExecution(array($this, "getReports"));
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function getReports()
    {
        $reportSet = getReportSet();
        $reports = array();

        foreach($reportSet as $report) {
            $rows = getReportRows($report['id']);
            $report['rows'] = $rows;
            array_push($reports, $report);
        }

        return array(
            'reports' => $reports
        );
    }
}