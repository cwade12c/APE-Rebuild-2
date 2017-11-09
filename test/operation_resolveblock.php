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

function testOperation($args, $accountID)
{
    echo "</br>testing some operation):</br>";

    try {
        // create
        $op = new ResolveBlock();

        // build args, execute
        $result = $op->execute($args, $accountID);

        echo "results: </br>";
        print_r($result);
        echo "</br>";
    } catch (Exception $e) {
        echo "exception - $e</br>";
    }
    echo "</br>";
}

$id = '00784772';

$state = getRegistrationState($id);
echo "$id ($state)</br>";

testOperation(
    array('studentID' => $id, 'newState' => 5),
    '00688391'
);

$state = getRegistrationState($id);
echo "$id ($state)</br>";


echo "</br></br>completed test</br>";
