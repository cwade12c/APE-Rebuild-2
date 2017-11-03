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

echo "testing operation upcoming exams</br></br>";

function testOp()
{
    echo "</br>testing upcoming exams:</br>";

    try {
        $opExamDetails = new UpcomingExams();

        $result = $opExamDetails->execute(array(), null);

        echo "results: </br>";
        print_r($result);
        echo "</br>";
    } catch (Exception $e) {
        echo "exception - $e</br>";
    }
    echo "</br>";
}

testOp();


echo "</br></br>completed test</br>";
