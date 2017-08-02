<?php
//rename this file to "config.php" when deploying

DEFINE("INCLUDE_ACCESS", true);
DEFINE("DEBUG", true);
DEFINE("INCLUDES_PATH", "");

DEFINE("SITE_TITLE", "EWU Advanced Placement Exam");
DEFINE("DOMAIN", "http://127.0.0.1");
DEFINE("CAS_DOMAIN", "login.ewu.edu");
DEFINE("CAS_CERT_PATH", "");
DEFINE("CAS_HOSTS", array());
DEFINE("HOME_PAGE", DOMAIN . "home.php");
DEFINE("AUTH_PAGE", DOMAIN . "login.php");

DEFINE("HOST", "localhost");
DEFINE("USER", "");
DEFINE("PASS", "");
DEFINE("DB", "");

try {
    $dsn = "mysql:host=" . HOST . ";dbname=" . DB;
    $db = new PDO($dsn, USER, PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $error) {
    die('DB Error: ' . $e->getMessage());
}

session_start();

require_once('includes/includes.php');

if (DEBUG == true) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

checkIfUserIsLoggedIn();

?>