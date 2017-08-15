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

/* for get exams functions */
define('GET_EXAMS_ALL', 0);
define('GET_EXAMS_OPEN', 1);
define('GET_EXAMS_GRADING', 2);
define('GET_EXAMS_FINALIZING', 3);
define('GET_EXAMS_NON_ARCHIVED', 4);
define('GET_EXAMS_ARCHIVED', 5);

/* for type of exams to grab */
define('GET_EXAMS_TYPE_BOTH', 0);
define('GET_EXAMS_TYPE_REGULAR', 1);
define('GET_EXAMS_TYPE_IN_CLASS', 2);

/* for general queries, datetime to/from php and mysql */
/* php.net/manual/en/function.date.php */
define('DATETIME_FORMAT', 'Y-m-d H:i:s');

// TODO: fill the following values
/* values that could be pulled from a config file */
// EWU ID regex
// temp ID regex
// open registration values
/// 1) days before start date to open
/// or 2) date per quarter to open any registrations