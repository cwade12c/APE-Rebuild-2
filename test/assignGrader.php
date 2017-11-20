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

echo "attempting to assign grader</br></br>";

function testAssignGrader($graderID, $examID, $categoryID) {
    echo "test assigning grader($graderID) to exam($examID) category($categoryID)</br>";
    try{
        assignGrader($examID, $categoryID, $graderID);
    }catch(Exception $e) {
        $type = gettype($e);
        echo "exception($type): $e</br>";
    }
}

$graderID = '00798787';

testAssignGrader($graderID, 3, 1);


echo "</br></br>completed test</br>";
