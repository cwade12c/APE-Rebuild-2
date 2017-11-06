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

echo "testing operations...</br></br>";

function testOperation($args, $accountID) {
    echo "</br>testing some operation:</br>";
    echo "account ID: $accountID</br>";
    echo "args: ";
    print_r($args);
    echo "</br>";

    try {
        $op = new TeacherExams();

        $result = $op->execute($args, $accountID);

        echo "results: </br>";
        print_r($result);
        echo "</br>";
    } catch (Exception $e) {
        echo "exception - $e</br>";
    }
    echo "</br>";
}

testOperation(array("teacherID" => '00111131'), '00111131');
testOperation(array("teacherID" => '00111132'), '00111132');


echo "</br></br>completed test</br>";
