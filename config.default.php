<?php

//Debug, include, and general flags configurations
DEFINE("INCLUDE_ACCESS", true); //set to true in order to use includes.php
DEFINE("DEBUG", true); //set to true to display error information

//Paths configurations
DEFINE(
    "INCLUDES_PATH", "/make/this/an/absolute/path/"
); //should be an absolute path with trailing forward slash
DEFINE(
    "CAS_CERT_PATH", "/make/this/absolute/to/some/pem/file.pem"
); //should be an absolute path to a .pem file
DEFINE(
    "LOG_PATH", "/make/this/absolute/to/some.log"
); //should be an absolute path to a .log file
DEFINE(
    "VENDOR_PATH", "/path/to/vendor/includes/"
); //should be an absolute path with a trailing forward slash
DEFINE(
    "TEMPLATES_PATH", "/path/to/twig/html/templates/"
); //should be an absolute path with a trailing forward slash
DEFINE(
    "CACHE_PATH", "/path/to/twig/cache/"
); //should be an absolute path with a trailing forward slash
DEFINE(
    "AUTOLOADER_PATH", VENDOR_PATH . "autoload.php"
); //should be the path to composer's generated autoload file

//Database configurations
DEFINE("HOST", "localhost");
DEFINE("USER", "");
DEFINE("PASS", "");
DEFINE("DB", "");

//Domains and Hosts configurations
DEFINE("DOMAIN", "http://127.0.0.1");
DEFINE("CAS_DOMAIN", "login.ewu.edu");
DEFINE("CAS_HOSTS", array());

//Account configurations
DEFINE("MINIMUM_NUMBER_OF_ADMINS", 1);

//Custom site configurations
DEFINE("SITE_TITLE", "EWU Advanced Placement Exam");
DEFINE(
    "SITE_KEY_WORDS",
    "Eastern,Washington,University,Computer,Science,CS,Advanced,Programming,Exam,APE"
);
DEFINE(
    "SITE_DESCRIPTION",
    "Register and manage your EWU Computer Science Advanced Programming Exam."
);
DEFINE("SITE_AUTHOR", "Eastern Washington University");
DEFINE("HOME_PAGE", DOMAIN . "home.php");
DEFINE("AUTH_PAGE", DOMAIN . "login.php");


try {
    $dsn = "mysql:host=" . HOST . ";dbname=" . DB;
    $db  = new PDO($dsn, USER, PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $error) {
    die('DB Error: ' . $error->getMessage());
}

session_start();

require_once(AUTOLOADER_PATH);

if (DEBUG == true) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}