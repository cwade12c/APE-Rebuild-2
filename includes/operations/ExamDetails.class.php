<?php
/**
 * Exam Details class for retrieving dates/times, location, space, state
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class ExamDetails extends Operation
{
    function __construct() {
        parent::requireLogin(false);

        parent::registerExecution('getExamInformation');

        parent::registerParameter("id", "int");

        parent::registerValidation("validateExamIDExists", "id");
        parent::registerValidation("validateExamID", "id");
    }
}