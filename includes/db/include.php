<?php
/**
 * Main include file for database package
 *
 * @author		Mathew McCain
 * @category	APE
 * @package		APE_includes
 * @subpackage	Database
 */

/* exceptions */
require_once 'dbExceptions.php';

/* constants */
require_once 'dbConstants.php';
// may need constants for report type indexes

/* queries */
require_once 'dbQueriesGeneral.php';
require_once 'dbQueriesAccounts.php';
// more splits needed

/* functions */
require_once 'dbFunctions.php';
require_once 'dbFunctionsAccounts.php';
require_once 'dbFunctionsExams.php';
require_once 'dbFunctionsGradings.php';
require_once 'dbFunctionsLocationsRooms.php';
require_once 'dbFunctionsCategories.php';
require_once 'dbFunctionsReports.php';