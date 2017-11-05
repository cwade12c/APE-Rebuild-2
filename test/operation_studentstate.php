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

echo "testing operation student state</br></br>";

function testStudentState($studentID, $accountID)
{
    echo "</br>testing student state($studentID):</br>";

    try {
        $op = new StudentState();

        $result = $op->execute(
            array('studentid' => $studentID), $accountID
        );

        echo "results: </br>";
        print_r($result);
        echo "</br>";
    } catch (Exception $e) {
        echo "exception - $e</br>";
    }
    echo "</br>";
}

testStudentState('TGqj6WXMKKIo3dR+UQ3M', 'TGqj6WXMKKIo3dR+UQ3M');
testStudentState('00688391', '00688391');

echo "</br></br>completed test</br>";
