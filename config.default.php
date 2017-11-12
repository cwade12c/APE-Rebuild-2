<?php

DEFINE(
    "CONFIG_PATH", "/var/www/html/config"
); //an absolute path to the configuration directory
DEFINE(
    "INCLUDE_ACCESS", true
); //set to true in order to use includes.php

require_once(CONFIG_PATH . "path.config.php");
require_once(CONFIG_PATH . "db.config.php");
require_once(CONFIG_PATH . "host.config.php");
require_once(CONFIG_PATH . "general.config.php");
require_once(CONFIG_PATH . "navigation.config.php");
require_once(CONFIG_PATH . "security.config.php");

require_once(AUTOLOADER_PATH);

// Do not edit below this line
if(DEBUG) { //TODO comment this out before prod
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    //displayDebugAlert();

    if(isset($_GET['debugrole'])) {
        DEFINE("DEBUG_ROLE", $_GET['debugrole']);
    }
    else {
        DEFINE("DEBUG_ROLE", 16);
    }
}

try {
    $dsn = "mysql:host=" . HOST . ";dbname=" . DB;
    $db  = new PDO($dsn, USER, PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $error) {
    die('DB Error: ' . $error->getMessage());
}

global $params;
$params = array(
    'isLoggedIn' => false,
    'availableNavLinks' => GUEST_LINKS
);

function displayDebugAlert() {
    echo "<div class=\"alert alert-danger\" style=\"text-align:center;\" role=\"alert\">
  <span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span>
  WARNING: Debug is enabled! This introduces security risks. Turn it off before entering prod.
</div>";
}

session_start();