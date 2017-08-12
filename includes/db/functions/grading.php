<?php
/**
 * Functions for grading within db
 *
 * @author		Mathew McCain
 * @category	APE
 * @package		APE_includes
 * @subpackage	Database
 */

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
