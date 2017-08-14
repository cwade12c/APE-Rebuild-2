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
require_once INCLUDES_PATH . 'db/exceptions.php';

/* constants */
require_once INCLUDES_PATH . 'db/constants.php';

/* queries */
require_once INCLUDES_PATH . 'db/queries/general.php';
require_once INCLUDES_PATH . 'db/queries/accounts.php';
require_once INCLUDES_PATH . 'db/queries/exams.php';
require_once INCLUDES_PATH . 'db/queries/examInteractions.php';
require_once INCLUDES_PATH . 'db/queries/grading.php';
require_once INCLUDES_PATH . 'db/queries/locationsRooms.php';
require_once INCLUDES_PATH . 'db/queries/categories.php';
require_once INCLUDES_PATH . 'db/queries/reports.php';

/* functions */
require_once INCLUDES_PATH . 'db/functions/accounts.php';
require_once INCLUDES_PATH . 'db/functions/exams.php';
require_once INCLUDES_PATH . 'db/functions/examInteractions.php';
require_once INCLUDES_PATH . 'db/functions/grading.php';
require_once INCLUDES_PATH . 'db/functions/locationsRooms.php';
require_once INCLUDES_PATH . 'db/functions/categories.php';
require_once INCLUDES_PATH . 'db/functions/reports.php';