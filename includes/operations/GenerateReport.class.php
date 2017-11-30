<?php

/**
 * Generate Report class to generate an array of result rows for a report
 * An authorizationToken is returned, which can be passed to a DownloadReport file
 * as a security validation check
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class GenerateReport extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_ADMIN, ACCOUNT_TYPE_TEACHER));

        parent::registerParameter("examID", "integer");
        parent::registerParameter("types", "array");

        parent::registerValidation("validateExamIDExists", "examID");

        parent::registerExecution(array($this, "buildReport"));
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function buildReport(int $examID, array $types)
    {
        $generatedReport = generateReport($examID, $types);

        return array(
            'reportHeaders' => $generatedReport[0],
            'reportRows' => $generatedReport[1],
            'authorizationToken' => hash(HASHING_ALGORITHM, date('m/d/Y h') .
                AUTHORIZATION_TOKEN)
        );
    }
}