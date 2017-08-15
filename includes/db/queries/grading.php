<?php
/**
 * Query functions for grading
 *
 * @author         Mathew McCain
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Database
 */

function assignGraderQuery(int $graderId, int $examId, int $categoryId,
    int $wasSubmitted
) {
    $query
         = "INSERT INTO `assigned_graders` (`exam_id`, `category_id`,"
        . " `grader_id`, `submitted`) VALUES (:examId, :categoryId, :graderId,"
        . " :wasSubmitted)";
    $sql = executeQuery(
        $query, array(
            array(':examId', $examId, PDO::PARAM_INT),
            array(':categoryId', $categoryId, PDO::PARAM_INT),
            array(':graderId', $graderId, PDO::PARAM_INT),
            array(':wasSubmitted', $wasSubmitted, PDO::PARAM_INT)
        )
    );

    if ($sql) {
        return true;
    }

    return false;
}

function removeGraderQuery(int $graderId, int $examId, int $categoryId)
{
    $query = "DELETE FROM `assigned_graders`"
        . "WHERE `grader_id` = :id";
    $sql   = executeQuery(
        $query, array(
            array(':id', $graderId, PDO::PARAM_INT))
    );

    if ($sql) {
        return true;
    }

    return false;
}

//get all assigned graders
function getAssignedGradersQuery()
{

}

//get assigned graders by exam id
function getAssignedGradersByExamIdQuery(int $examId)
{
    $query = "SELECT * FROM `assigned_graders` WHERE `exam_id` = :id";
    $sql   = executeQuery(
        $query, array(
            array(':id', $examId, PDO::PARAM_INT)
        )
    );

    return getQueryResults($sql);
}

//get assigned graders by category id
function getAssignedGradersByCategoryIdQuery(int $categoryId)
{
    $query = "SELECT * FROM `assigned_graders` WHERE `category_id` = :id";
    $sql   = executeQuery(
        $query, array(
            array(':id', $categoryId, PDO::PARAM_INT)
        )
    );

    return getQueryResults($sql);
}


//get all exam grades
function getExamGrades()
{

}

//get all exam grades by exam id
function getExamGradesById(int $examId)
{

}

//get all exam grades by student_id
function getExamGradesByStudentId(int $studentId)
{

}


//get assigned graders by exam id and category id
function getAssignedGradersByExam(int $examId, int $categoryId)
{

}

//get assigned graders by grader id
function getAssignedGradersById(int $graderId)
{

}

//get assigned graders by true submissions
function getAssignedGradersBySubmitted()
{

}

//get assigned graders by false submissions
function getAssignedGradersByUnsubmitted()
{

}

//get all grader category grades
function getGraderCategoryGrades()
{

}

//get grader category grades by exam id
function getGraderCategoryGradesByExamId(int $examId)
{

}

//get grader category grades by exam category id
function getGraderCategoryGradesByCategoryIdQuery(int $categoryId)
{
    $query = "SELECT (`grader_id`, `points`) FROM `grader_category_grades` WHERE `category_id` = :id";
    $sql = executeQuery($query, array(
        array(':id', $categoryId)
    ));

    return getQueryResults($sql);
}

//get grader category grades by exam id and category id
function getGraderCategoryGradesByExam(int $examId, int $categoryId)
{

}

//get grader category grades by grader id
function getGraderCategoryGradesByGraderId(int $graderId)
{

}

//get grader category grades by student id
function getGraderCategoryGradesByStudentId(int $studentId)
{

}

//get grader category grades by grader id and student id
function getGraderCategoryGradesByGraderIdAndStudentId(int $graderId,
    int $studentId
) {

}

//get all student category grades
function getStudentCategoryGrades()
{

}

//get student category grades by exam id
function getStudentCategoryGradesByExamIdQuery(int $examId)
{
    $sql = "SELECT (`category_id`, `student_id`, `points`) FROM `student_category_grades` WHERE `exam_id` = :id ORDER BY `student_id`";
    $query = executeQuery($sql, array(
        array(':id', $examId)
        )
    );
    return getQueryResults($query);
}

//get student category grades by category id
function getStudentCategoryGradesByCategoryId(int $categoryId)
{

}

//get student category grades by exam id and category id
function getStudentCategoryGradesByExam(int $examId, int $categoryId)
{

}

//get student category grades by student id
function getStudentCategoryGradesByStudentId(int $studentId)
{

}

//get student category grades by true conflict
function getStudentCategoryGradesByConflict()
{

}

//get student category grades by false conflict
function getStudentCategoryGradesByNoConflict()
{

}


function getExamsAndCategoriesByGraderIdQuery(int $graderId)
{
    $query = "SELECT `exam_id`, `category_id` FROM `assigned_graders` WHERE `grader_id` = :id";
    $sql   = executeQuery(
        $query, array(
            array(':id', $graderId, PDO::PARAM_INT)
        )
    );

    return getQueryResults($sql);
}

function isAllGradesSubmittedByExamIdQuery(int $examId)
{
    $query = "SELECT count(*) FROM `assigned_graders` WHERE `exam_id` = :id AND `submitted` = :wasSubmitted";
    $sql = executeQuery(
        $query, array(
            array(':id', $examId, PDO::PARAM_INT),
            array(':wasSubmitted', 0, PDO::PARAM_INT)
        )
    );
    $result = getQueryResult($sql);

    return $result == 0;
}

function getSubmittedGradersByExamIdQuery(int $examId)
{
    $query = "SELECT `grader_id` FROM `assigned_graders` WHERE `exam_id` = :id AND `submitted` = :wasSubmitted";
    $sql   = executeQuery(
        $query, array(
            array(':id', $examId, PDO::PARAM_INT),
            array(':wasSubmitted', 1, PDO::PARAM_INT)
        )
    );

    return getQueryResults($sql);
}

function getUnsubmittedGradersByExamIdQuery(int $examId)
{
    $query = "SELECT `grader_id` FROM `assigned_graders` WHERE `exam_id` = :id AND `submitted` = :wasSubmitted";
    $sql   = executeQuery(
        $query, array(
            array(':id', $examId, PDO::PARAM_INT),
            array(':wasSubmitted', 0, PDO::PARAM_INT)
        )
    );

    return getQueryResults($sql);
}

function getNumberOfSubmittedGradersByExamIdQuery(int $examId)
{
    $query = "SELECT count(grader_id) FROM `assigned_graders` WHERE `exam_id` = :id AND `submitted` = :wasSubmitted";
    $sql = executeQuery(
        $query, array(
            array(':id', $examId, PDO::PARAM_INT),
            array(':wasSubmitted', 1, PDO::PARAM_INT)
        )
    );

    return getQueryResult($sql);
}

function getNumberOfUnsubmittedGradersByExamIdQuery(int $examId)
{
    $query = "SELECT count(grader_id) FROM `assigned_graders` WHERE `exam_id` = :id AND `submitted` = :wasSubmitted";
    $sql = executeQuery(
        $query, array(
            array(':id', $examId, PDO::PARAM_INT),
            array(':wasSubmitted', 0, PDO::PARAM_INT)
        )
    );

    return getQueryResult($sql);
}

function getStudentAverageByCategoryIdQuery(int $studentId, int $categoryId)
{
    $query = "SELECT avg(points) FROM `student_category_grades` WHERE `student_id` = :studentId AND `category_id` = :categoryId";
    $sql = executeQuery($query, array(
        array(':studentId', $studentId),
        array(':categoryId', $categoryId)
    ));

    return getQueryResult($sql);
}

function getStudentCategoryGradeConflictsByExamIdQuery(int $examId)
{
    $query = "SELECT `student_id` FROM `student_category_grades` WHERE `exam_id` = :id AND `conflict` = :isConflicted";
    $sql = executeQuery($query, array(
        array(':id', $examId),
        array(':isConflicted', 1)
    ));

    return getQueryResults($sql);
}

function passStudentQuery(int $examId, int $studentId)
{
    $query = "UPDATE `exam_grades` SET `passed` = :isPassed WHERE `exam_id` = :examId AND `student_id` = :studentId";
    $sql = executeQuery($query, array(
        array(':isPassed', 1),
        array(':examId', $examId),
        array(':studentId', $studentId)
    ));

    if($sql) {
        return true;
    }

    return false;
}

function failStudentQuery(int $examId, int $studentId)
{
    $query = "UPDATE `exam_grades` SET `passed` = :isPassed WHERE `exam_id` = :examId AND `student_id` = :studentId";
    $sql = executeQuery($query, array(
        array(':isPassed', 0),
        array(':examId', $examId),
        array(':studentId', $studentId)
    ));

    if($sql) {
        return true;
    }

    return false;
}

function gradeCategoryByIdQuery($examId, $categoryId, $studentId, $grade, $comments)
{
    $query = "SELECT * FROM `student_category_grades` WHERE `exam_id` = :examId AND `category_id` = :catId AND `student_id` = :studentId";
    $sql = executeQuery($query, array(
        array(':examId', $examId),
        array(':catId', $categoryId),
        array(':studentId', $studentId)
    ));

    if($sql) {
        $query = "UPDATE `student_category_grades` SET `points` = :grade, `comment` = :comments WHERE `exam_id` = :examId AND `category_id` = :catId AND `student_id` = :studentId";
        $sql = executeQuery($query, array(
            array(':grade', $grade),
            array(':comments', $comments),
            array(':examId', $examId),
            array(':catId', $categoryId),
            array(':studentId', $studentId)
        ));

        if($sql) {
            return true;
        }
        return false;
    }
    else {
        $query = "INSERT INTO `student_category_gradea` VALUES (:examId, :catId, :studentId, :points, :isConflicted, :comments)";
        $sql = executeQuery($query, array(
           array(':examId', $examId),
           array(':catId', $categoryId),
           array(':studentId', $studentId),
           array(':points', $grade),
           array(':isConflicted', GRADER_NO_CONFLICT),
           array(':comments', $comments)
        ));

        if($sql) {
            return true;
        }
        return false;
    }
}

?>
