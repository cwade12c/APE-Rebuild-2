<?php

/**
 * Operation to retrieve all of the exams belonging to the accountID
 *
 * @author         Curran Higgins
 * @category       APE
 * @package        APE_includes
 * @subpackage     Operation
 */
class MyExams extends Operation
{
    function __construct()
    {
        parent::setAllowedAccountTypes(array(ACCOUNT_TYPE_STUDENT));

        parent::registerExecution(array($this, "getMyExams"));

        parent::registerParameter("studentID", "string");

        parent::registerValidation("validateStudentID", "studentID");
    }

    public function execute(array $args, string $accountID = null)
    {
        return parent::execute($args, $accountID);
    }

    public static function getMyExams(string $studentID)
    {
        global $params;
        if($studentID != $params['id']) {
            throw new Exception("The account you are trying to get exams for does not belong to you!");
        }

        $myExams = getExamsRegisteredFor($studentID);
        $registered = array();
        $grading = array();
        $completed = array();

        foreach($myExams as &$currentExamId) {
            $examDetails = ExamDetails::getExamInformation($currentExamId);
            $state = $examDetails['state'];

            if($state >= EXAM_STATE_GRADING && $state < EXAM_STATE_ARCHIVED) { //grading
                array_push($grading, $examDetails);
            }
            elseif($state == EXAM_STATE_ARCHIVED) { //completed
                function deleteFromArray(array &$arr, array $keys)
                {
                    foreach($keys as &$key) {
                        if(array_key_exists($key, $arr)) {
                            unset($arr[$key]);
                        }
                    }
                }

                deleteFromArray($examDetails, array(
                    'locationName',
                    'totalSeats',
                    'takenSeats'
                ));

                $categoryGradesResult = getStudentCategoryGrades($currentExamId, $studentID);
                $categoryGrades = array();

                foreach($categoryGradesResult as $categoryGradeInformation) {
                    $fullInformation = getCategoryInfo($categoryGradeInformation['categoryID']);
                    $fullInformation['grade'] = $categoryGradeInformation['grade'];

                    array_push($categoryGrades, $fullInformation);
                }

                $examGradesFull = getStudentExamGradeFull($currentExamId, $studentID);

                $examDetails['categoryGrades'] = $categoryGrades;
                $examDetails['examGradeInformation'] = $examGradesFull;

                array_push($completed, $examDetails);
            }
            elseif($state <= EXAM_STATE_IN_PROGRESS && $state >= EXAM_STATE_OPEN) { //registered
                array_push($registered, $examDetails);
            }
        }

        return array(
            'registered' => $registered,
            'grading' => $grading,
            'completed' => $completed
        );
    }
}