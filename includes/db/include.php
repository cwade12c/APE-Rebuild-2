<?php
/**
 * Main include file for database package
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Database
 */

/* exceptions */
require_once INCLUDES_PATH . 'db/dbExceptions.php';

/* constants */
require_once INCLUDES_PATH . 'db/dbConstants.php';
// may need constants for report type indexes

/* queries */
require_once INCLUDES_PATH . 'db/dbQueriesGeneral.php';
require_once INCLUDES_PATH . 'db/dbQueriesAccounts.php';
// more splits needed

/* functions */
require_once INCLUDES_PATH . 'db/dbFunctions.php';
require_once INCLUDES_PATH . 'db/dbFunctionsAccounts.php';
require_once INCLUDES_PATH . 'db/dbFunctionsExams.php';
require_once INCLUDES_PATH . 'db/dbFunctionsGrading.php';
require_once INCLUDES_PATH . 'db/dbFunctionsLocationsRooms.php';
require_once INCLUDES_PATH . 'db/dbFunctionsCategories.php';
require_once INCLUDES_PATH . 'db/dbFunctionsReports.php';