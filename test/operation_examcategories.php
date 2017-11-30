<?php
/**
 * file for testing queries
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_test
 * @subpackage     Blank
 */

require_once '../config.php';

echo "testing operation exam details</br></br>";

function testExamDetails(int $examID) {
    echo "</br>testing exam details($examID):</br>";

    try {
        $opExamDetails = new ExamCategories();

        $id = $examID;
        $result = $opExamDetails->execute(
            array('id' => $id), "00111133"
        );

        echo "results: </br>";
        print_r($result);
        echo "</br>";
    } catch (Exception $e) {
        echo "exception - $e</br>";
    }
    echo "</br>";
}

testExamDetails(14);
testExamDetails(1);


echo "</br></br>completed test</br>";
