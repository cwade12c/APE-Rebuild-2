#!/usr/bin/php7.0
<?php
require_once '../config.php';

/*
 * Script to run any necessary refresh functions
 * set cron job
 * 	5 * * * *
 */
 
refreshExams();