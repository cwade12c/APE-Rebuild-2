<?php
/**
 * based off 'config.php', edited for testing
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_testing
 * @subpackage     Includes
 */


DEFINE("INCLUDE_ACCESS", true);
DEFINE("DEBUG", true);
DEFINE("INCLUDES_PATH", "../includes/");

DEFINE("SITE_TITLE", "EWU Advanced Placement Exam");
DEFINE("DOMAIN", "http://146.187.134.42/");

DEFINE("HOST", "localhost");
DEFINE("USER", "root");
// bB*-7Q7p\$M4
// _ZmQ833k#PP$.
DEFINE("PASS", "wrong");
DEFINE("DB", "test_new_ape_database");

try {
    $dsn = "mysql:host=" . HOST . ";dbname=" . DB;
    $db  = new PDO($dsn, USER, PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $error) {
    die('DB Error: ' . $e->getMessage());
}

session_start();

require_once(INCLUDES_PATH . 'includes.php');

if (DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}