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
        $op = new CreateLocation();

        $result = $op->execute($args, $accountID);

        echo "results: </br>";
        print_r($result);
        echo "</br>";
    } catch (Exception $e) {
        echo "exception - $e</br>";
    }
    echo "</br>";
}

testOperation(array(
    'name' => 'just a dumb name',
    'seatsReserved' => 5,
    'limitedSeats' => 0,
    'rooms' => array(
        array('id' => 1, 'seats' => 15),
        array('id' => 2, 'seats' => 32)
    )

), '00111134');


echo "</br></br>completed test</br>";
