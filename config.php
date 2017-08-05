<?php

DEFINE("INCLUDE_ACCESS", true);
DEFINE("DEBUG", true);
DEFINE("INCLUDES_PATH", "./includes/");

DEFINE("SITE_TITLE", "EWU Advanced Placement Exam");
DEFINE("DOMAIN", "http://146.187.134.42/");
DEFINE("CAS_DOMAIN", "login.ewu.edu");
DEFINE("CAS_CERT_PATH", INCLUDES_PATH . 'comodo_combo.pem');
DEFINE("CAS_HOSTS", array(
    'it-adfs01.eastern.ewu.edu',
    'it-casauth01.eastern.ewu.edu'
));
DEFINE("HOME_PAGE", DOMAIN . "home.php");
DEFINE("AUTH_PAGE", DOMAIN . "login.php");

DEFINE("HOST", "localhost");
DEFINE("USER", "root");
DEFINE("PASS", "bB*-7Q7p\$M4");
DEFINE("DB", "ape_database");

try {
    $dsn = "mysql:host=" . HOST . ";dbname=" . DB;
    $db = new PDO($dsn, USER, PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $error) {
    die('DB Error: ' . $e->getMessage());
}

session_start();

require_once(INCLUDES_PATH . 'includes.php');

if (DEBUG == true) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

checkIfUserIsLoggedIn();

?>
