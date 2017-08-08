<?php
/**
 * Functions to be used with the database
 * TODO: split functions into separate files, put in own sub directory
 * TODO: use this file to include those (or make common dir?)
 * visibility attribute?
 * files w/ private/public in name to be more specific
 *
 * @author		Mathew McCain
 * @category	APE
 * @package		APE_includes
 * @subpackage	Database
 */

/**
 * TODO: determine way to lock DB
 * Specifically for operations that may cause race conditions with registration
 * transactions may work
 * exam state to temp block registration ?
 *
 * TODO: use classes for return information, or arrays in specific format?
 */


// register student
// unregister student
// set/get registration state

// assign student to room/seat
// randomly assign all students for given exam (room, seat)
// get students registered

// create exam
// search exam
/// state, date/time (quarter?), in class
/// all exams in finalizing state
// refresh/check exam state
// set exam state
// finalize exam

// create in class exam
// assign teacher(s)
// get teacher
// search exams by in class/teacher
// get non-archived exams for teacher

// get total seat counts for exam
/// total, reserved, for students, open
// get open seat count

// set location for exam
// get location for exam
// update location for exam
/// check if seat count difference will cause an issue (block, return false)
/// randomize rooms/seats

// updating exam information
/// date/times, passing grade

// create location
/// check for duplicate names

// delete location
/// check
// check if location isn't used in non-archived exams
// check for non-archived exams w/out location

// remove location from archived-exams

// add/remove room from location
/// check for issue w/ current exams
// create room

// add/remove category for exam
/// check state, check for assigned graders
// get default categories
// get categories for exam

// assign grader to exam/category
// remove grader from exam/category
// get graders for exam/category

// get assigned exams/category for grader

// get if all graders submitted
// submit for grader
// get graders submitted/not submitted
/// get count for each

// state shift from grading to finalizing
// check student/category grades for conflicts between graders

// get all student category grades for exam from all graders
/// only for 1 category
/// return [ [grader_id, grade] , ... ]

// get student grade for category
/// the average/set final

// get all student grades for exam
/// return array [ [category_id, grade], ... ]

// pass/fail student, set comment/grade for category
/// separate one for exam

// finalize exam
/// check in correct state
/// check all conflicts have been handled, all grades available

// cleanup exam grades
/// cleanup all grader information not necessary
/// TODO: determine if necessary, make manual operation by admin/db admin?

// TODO: determine if rows should just have an index value, no name to parse
// reports
// get all pre-defined reports
// get rows/types for report id
// create report
// add/remove rows from report
// update row types

