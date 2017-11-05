<?php
/**
 * Constant values in used with the database
 * for areas such as the account type, and student/exam states.
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_includes
 * @subpackage     Database
 */

/* account type */
define('ACCOUNT_TYPE_NONE', 0);
define('ACCOUNT_TYPE_TEMP', 1);
define('ACCOUNT_TYPE_STUDENT', 2);
define('ACCOUNT_TYPE_GRADER', 4);
define('ACCOUNT_TYPE_TEACHER', 8);
define('ACCOUNT_TYPE_ADMIN', 16);

/* student registration state */
define('STUDENT_STATE_INVALID', 0);
define('STUDENT_STATE_READY', 1);
define('STUDENT_STATE_REGISTERED', 2);
define('STUDENT_STATE_PASSED', 3);
define('STUDENT_STATE_BLOCKED', 4);
define('STUDENT_STATE_BLOCKED_BYPASSED', 5);
define('STUDENT_STATE_BLOCKED_IGNORED', 6);

/* exam state */
define('EXAM_STATE_INVALID', 0);
define('EXAM_STATE_HIDDEN', 1);
define('EXAM_STATE_OPEN', 2);
define('EXAM_STATE_CLOSED', 3);
define('EXAM_STATE_IN_PROGRESS', 4);
define('EXAM_STATE_GRADING', 5);
define('EXAM_STATE_FINALIZING', 6);
define('EXAM_STATE_ARCHIVED', 7);

// TODO: constants for report type indexes ?

/* for max failures a student can have before being blocked */
define('MAX_FAILURES_BEFORE_BLOCK', 3);

/* for get exams functions */
define('GET_EXAMS_ALL', 0);
define('GET_EXAMS_OPEN', 1);
define('GET_EXAMS_UPCOMING', 2);
define('GET_EXAMS_GRADING', 3);
define('GET_EXAMS_FINALIZING', 4);
define('GET_EXAMS_NON_ARCHIVED', 5);
define('GET_EXAMS_ARCHIVED', 6);

/* for type of exams to grab */
define('GET_EXAMS_TYPE_BOTH', 0);
define('GET_EXAMS_TYPE_REGULAR', 1);
define('GET_EXAMS_TYPE_IN_CLASS', 2);

/* for general queries, datetime to/from php and mysql */
/* php.net/manual/en/function.date.php */
define('DATETIME_FORMAT', 'Y-m-d H:i:s');

/* For how long before an exam start datetime to open for registration
   Temporary solution until individual datetime can be implemented
 */
define('TIME_BEFORE_OPENING_EXAM_REGISTRATION', '3 months');

/* For the max difference between grades set by a grader for a student's
   category grade.
 */
define('MAX_GRADER_CATEGORY_GRADE_DIFFERENCE_FLAT', 10);
/* Same as above but by percent, use value between 0-1 */
define('MAX_GRADER_CATEGORY_GRADE_DIFFERENT_PERCENT', 0.2);

/*
 * The max length of an account ID.
 * Do not increase w/out increasing in the DB schema as well.
 * Hardcoded as a temporary setup to avoid querying the DB for size.
 */
define('ACCOUNT_ID_MAX_LENGTH', 20);

/* prefix for temp IDs */
define('TEMP_ID_PREFIX', 'T');
/* how many generated values exist for a temp ID */
/* WARNING: DO NOT CHANGE WITHOUT MODIFYING EXISTING VALUES */
/* 20 is the max length within the db */
define(
    'TEMP_ID_BYTES_GENERATED',
    ACCOUNT_ID_MAX_LENGTH - strlen(TEMP_ID_PREFIX)
);

/* regex string for an EWU ID */
define('REGEX_ACCOUNT_ID', '/^00\d{6}$/');
/* regex string for a temp ID */
define(
    'REGEX_TEMP_ID',
    '/^' . TEMP_ID_PREFIX . '[a-zA-Z0-9+\/=]{' . TEMP_ID_BYTES_GENERATED . '}$/'
);

/* Error codes for exceptions */
/* error code base */
define('ERROR_CODE_BASE', 100);
/* General DB error, do not display any info about - log */
define('ERROR_CODE_DB', ERROR_CODE_BASE + 1);
/* Error about an invalid input, specific message should be available */
define('ERROR_CODE_ARG', ERROR_CODE_DB + 1);
/* Error about an invalid action, specific message should be displayed */
define('ERROR_CODE_ACTION', ERROR_CODE_ARG + 1);

/* Generic message for backend exceptions */
define(
    'GENERIC_BACKEND_EXCEPTION_MESSAGE',
    'An error occurred, please try again later or contact an administrator.'
);
