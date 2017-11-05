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

function testOperation() {
    echo "</br>testing some operation):</br>";

    try {
        // create

        // build args, execute

        echo "results: </br>";
        //print_r($result);
        echo "</br>";
    } catch (Exception $e) {
        echo "exception - $e</br>";
    }
    echo "</br>";
}

//testOperation();


echo "</br></br>completed test</br>";
