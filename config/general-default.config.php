<?php
//General configurations

//Custom site configurations
DEFINE("SITE_TITLE", "EWU Advanced Placement Exam");

DEFINE("HOME_PAGE", DOMAIN . "home");

DEFINE("AUTH_PAGE", DOMAIN . "login");

//Twig caching configuration
DEFINE("ENABLE_CACHING", true); //make sure CACHE_PATH is defined in path.config.php

//Account.class configurations
DEFINE("MINIMUM_NUMBER_OF_ADMINS", 1);