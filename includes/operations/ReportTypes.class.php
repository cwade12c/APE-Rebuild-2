<?php

/**
 * Report Types class to retrieve a list of available report types (IDs and Names)
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class ReportTypes extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_ADMIN, ACCOUNT_TYPE_TEACHER));

        parent::registerExecution(array($this, "getReportTypes"));
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function getReportTypes()
    {
        return array(
            'reportTypes' => array(
                [
                    'id' => REPORT_TYPE_NONE,
                    'name' => 'None'
                ],
                [
                    'id' => REPORT_TYPE_STUDENT_ID,
                    'name' => 'Student ID'
                ],
                [
                    'id' => REPORT_TYPE_STUDENT_FIRST_NAME,
                    'name' => 'First Name'
                ],
                [
                    'id' => REPORT_TYPE_STUDENT_LAST_NAME,
                    'name' => 'Last Name'
                ],
                [
                    'id' => REPORT_TYPE_STUDENT_HASH,
                    'name' => 'ID Hash'
                ],
                [
                    'id' => REPORT_TYPE_STUDENT_SEATING,
                    'name' => 'Seat'
                ],
                [
                    'id' => REPORT_TYPE_STUDENT_GRADE,
                    'name' => 'Grade'
                ],
                [
                    'id' => REPORT_TYPE_STUDENT_PASSED,
                    'name' => 'Student Passed'
                ],
                [
                    'id' => REPORT_TYPE_STUDENT_CATEGORY_GRADES,
                    'name' => 'Category Grades'
                ]
            )
        );
    }
}