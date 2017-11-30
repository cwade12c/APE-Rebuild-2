<?php
/**
 * To assign a grader without using queries for testing
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

//testAssignGrader($graderID, 3, 1);
testAssignGrader($graderID, 3, 2);
testAssignGrader($graderID, 3, 3);
testAssignGrader($graderID, 3, 4);


echo "</br></br>completed test</br>";
