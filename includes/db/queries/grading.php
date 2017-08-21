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

function assignGraderQuery(string $graderId, int $examId, int $categoryId,
    int $wasSubmitted
) {
    $query
         = "INSERT INTO `assigned_graders` (`exam_id`, `category_id`,"
        . " `grader_id`, `submitted`) "
        . "VALUES (:examId, :categoryId, :graderId, :wasSubmitted)";
    $sql = executeQuery(
        $query, array(
            array(':examId', $examId, PDO::PARAM_INT),
            array(':categoryId', $categoryId, PDO::PARAM_INT),
            array(':graderId', $graderId, PDO::PARAM_STR),
            array(':wasSubmitted', $wasSubmitted, PDO::PARAM_INT)
        )
    );

    if ($sql) {
        return true;
    }

    return false;
}

function removeGraderQuery(string $graderId, int $examId, int $categoryId)
{
    $query = "DELETE FROM `assigned_graders`"
        . "WHERE `grader_id` = :grader AND"
        . "`exam_id` = :exam AND"
        . "`category_id` = :category";
    $sql   = executeQuery(
        $query, array(
            array(':grader', $graderId, PDO::PARAM_STR),
            array(':exam', $examId, PDO::PARAM_INT),
            array(':category', $categoryId, PDO::PARAM_INT)
        )
    );

    if ($sql) {
        return true;
    }

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


//get grader category grades by exam category id
function getGraderCategoryGradesByCategoryIdQuery(int $categoryId)
{
    $query
         = "SELECT (`grader_id`, `points`) FROM `grader_category_grades` "
        . "WHERE `category_id` = :id";
    $sql = executeQuery(
        $query, array(
            array(':id', $categoryId, PDO::PARAM_INT)
        )
    );

    return getQueryResults($sql);
}

//get all student category grades
function getStudentCategoryGrades()
{

}

//get student category grades by exam id
function getStudentCategoryGradesByExamIdQuery(int $examId)
{
    $sql
           = "SELECT (`category_id`, `student_id`, `points`) FROM `student_category_grades` "
        . "WHERE `exam_id` = :id "
        . "ORDER BY `student_id`";
    $query = executeQuery(
        $sql, array(
            array(':id', $examId, PDO::PARAM_INT)
        )
    );

    return getQueryResults($query);
}

function getExamsAndCategoriesByGraderIdQuery(string $graderId)
{
    $query
         = "SELECT `exam_id`, `category_id` FROM `assigned_graders` "
        . "WHERE `grader_id` = :id";
    $sql = executeQuery(
        $query, array(
            array(':id', $graderId, PDO::PARAM_STR)
        )
    );

    return getQueryResults($sql);
}

function isAllGradesSubmittedByExamIdQuery(int $examId)
{
    $query
            = "SELECT count(*) FROM `assigned_graders` "
        . "WHERE `exam_id` = :id "
        . "AND `submitted` = :wasSubmitted";
    $sql    = executeQuery(
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
    $query
         = "SELECT `grader_id` FROM `assigned_graders` "
        . "WHERE `exam_id` = :id "
        . "AND `submitted` = :wasSubmitted";
    $sql = executeQuery(
        $query, array(
            array(':id', $examId, PDO::PARAM_INT),
            array(':wasSubmitted', 1, PDO::PARAM_INT)
        )
    );

    return getQueryResults($sql);
}

function getUnsubmittedGradersByExamIdQuery(int $examId)
{
    $query
         = "SELECT `grader_id` FROM `assigned_graders` "
        . "WHERE `exam_id` = :id "
        . "AND `submitted` = :wasSubmitted";
    $sql = executeQuery(
        $query, array(
            array(':id', $examId, PDO::PARAM_INT),
            array(':wasSubmitted', 0, PDO::PARAM_INT)
        )
    );

    return getQueryResults($sql);
}

function getNumberOfSubmittedGradersByExamIdQuery(int $examId)
{
    $query
         = "SELECT count(grader_id) FROM `assigned_graders` "
        . "WHERE `exam_id` = :id "
        . "AND `submitted` = :wasSubmitted";
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
    $query
         = "SELECT count(grader_id) FROM `assigned_graders` "
        . "WHERE `exam_id` = :id "
        . "AND `submitted` = :wasSubmitted";
    $sql = executeQuery(
        $query, array(
            array(':id', $examId, PDO::PARAM_INT),
            array(':wasSubmitted', 0, PDO::PARAM_INT)
        )
    );

    return getQueryResult($sql);
}

function getStudentAverageByCategoryIdQuery(string $studentId, int $categoryId)
{
    $query
         = "SELECT avg(points) FROM `student_category_grades` "
        . "WHERE `student_id` = :studentId "
        . "AND `category_id` = :categoryId";
    $sql = executeQuery(
        $query, array(
            array(':studentId', $studentId, PDO::PARAM_STR),
            array(':categoryId', $categoryId, PDO::PARAM_INT)
        )
    );

    return getQueryResult($sql);
}

function getStudentCategoryGradeConflictsByExamIdQuery(int $examId)
{
    $query
         = "SELECT `student_id` FROM `student_category_grades` "
        . "WHERE `exam_id` = :id "
        . "AND `conflict` = :isConflicted";
    $sql = executeQuery(
        $query, array(
            array(':id', $examId, PDO::PARAM_INT),
            array(':isConflicted', GRADER_CONFLICT, PDO::PARAM_INT)
        )
    );

    return getQueryResults($sql);
}

function passStudentQuery(int $examId, string $studentId)
{
    $query
         = "UPDATE `exam_grades` SET `passed` = :isPassed "
        . "WHERE `exam_id` = :examId "
        . "AND `student_id` = :studentId";
    $sql = executeQuery(
        $query, array(
            array(':isPassed', 1, PDO::PARAM_INT),
            array(':examId', $examId, PDO::PARAM_INT),
            array(':studentId', $studentId, PDO::PARAM_STR)
        )
    );

    if ($sql) {
        return true;
    }

    return false;
}

function failStudentQuery(int $examId, string $studentId)
{
    $query
         = "UPDATE `exam_grades` SET `passed` = :isPassed "
        . "WHERE`exam_id` = :examId "
        . "AND `student_id` = :studentId";
    $sql = executeQuery(
        $query, array(
            array(':isPassed', 0),
            array(':examId', $examId, PDO::PARAM_INT),
            array(':studentId', $studentId, PDO::PARAM_STR)
        )
    );

    if ($sql) {
        return true;
    }

    return false;
}

function gradeCategoryByIdQuery(int $examId, int $categoryId, string $studentId,
    int $grade, string $comments
) {
    $query
         = "SELECT * FROM `student_category_grades` "
        . "WHERE `exam_id` = :examId "
        . "AND `category_id` = :catId "
        . "AND `student_id` = :studentId";
    $sql = executeQuery(
        $query, array(
            array(':examId', $examId, PDO::PARAM_INT),
            array(':catId', $categoryId, PDO::PARAM_INT),
            array(':studentId', $studentId, PDO::PARAM_STR)
        )
    );

    if ($sql) {
        $query
             = "UPDATE `student_category_grades` SET `points` = :grade, "
            . "`comment` = :comments "
            . "WHERE `exam_id` = :examId "
            . "AND `category_id` = :catId "
            . "AND `student_id` = :studentId";
        $sql = executeQuery(
            $query, array(
                array(':grade', $grade, PDO::PARAM_INT),
                array(':comments', $comments, PDO::PARAM_STR),
                array(':examId', $examId, PDO::PARAM_INT),
                array(':catId', $categoryId, PDO::PARAM_INT),
                array(':studentId', $studentId, PDO::PARAM_STR)
            )
        );

        if ($sql) {
            return true;
        }

        return false;
    } else {
        $query
             = "INSERT INTO `student_category_grades` "
            . "VALUES (:examId, :catId, :studentId, :points, :isConflicted, "
            . ":comments)";
        $sql = executeQuery(
            $query, array(
                array(':examId', $examId, PDO::PARAM_INT),
                array(':catId', $categoryId, PDO::PARAM_INT),
                array(':studentId', $studentId, PDO::PARAM_STR),
                array(':points', $grade, PDO::PARAM_INT),
                array(':isConflicted', GRADER_NO_CONFLICT, PDO::PARAM_INT),
                array(':comments', $comments, PDO::PARAM_INT)
            )
        );

        if ($sql) {
            return true;
        }

        return false;
    }
}

function finalizeExamByIdQuery(int $examId)
{
    $query = "UPDATE `exams` SET `state` = :examState "
        . "WHERE `id` = :id";
    $sql = executeQuery($query, array(
       array(':examState', EXAM_STATE_FINALIZING, PDO::PARAM_INT),
       array(':id', $examId, PDO::PARAM_INT)
    ));

    if ($sql) {
        return true;
    }

    return false;
}

?>
