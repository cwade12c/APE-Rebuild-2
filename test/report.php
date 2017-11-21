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


echo "testing reports</br></br>";

function testReport($examID, $types) {
    try {
        echo "testing report($examID): ";
        print_r($types);
        echo "</br>";

        list($header, $report) = generateReport($examID, $types);

        echo "Report:</br>";

        echo "header: ";
        print_r($header);
        echo "</br>";

        foreach($report as $row) {
            print_r($row);
            echo "</br>";
        }
    }catch(Exception $e) {
        echo "gen failed: $e</br>";
    }
    echo "</br>";
}

testReport(1, array(1,2,3,4));
testReport(1, array(1,5,6));
testReport(2, array(1,5,6,7));
testReport(14, array(1));
testReport(7, array(1));
testReport(2, array(1,5,6,7,8,9));
testReport(3, array(1,5,6,7,8,9));



echo "</br></br>completed test</br>";
