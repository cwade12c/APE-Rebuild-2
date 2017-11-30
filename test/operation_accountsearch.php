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

function testOperation($accountID, $args) {
    echo "</br>testing some operation:</br>";
    echo "account ID: $accountID</br>";
    echo "args: ";
    print_r($args);
    echo "</br>";

    try {
        $op = new AccountSearch();

        $result = $op->execute($args, $accountID);

        echo "results: </br>";
        print_r($result);
        echo "</br>";
    } catch (Exception $e) {
        echo "exception - $e</br>";
    }
    echo "</br>";
}

testOperation('00798787', array(
    'allowedTypes' => array(ACCOUNT_TYPE_ADMIN)
));

testOperation('00798787', array(
    'allowedTypes' => array(ACCOUNT_TYPE_TEACHER)
));

testOperation('00798787', array(
    'allowedTypes' => array(ACCOUNT_TYPE_STUDENT)
));

echo "</br></br>completed test</br>";
