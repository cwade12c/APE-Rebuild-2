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
        $op = new TempToFull();

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

$tempID = 'TJOQDWtmSCULMV5acSj7';
$newID = '00547332';

$info = getAccountInfo($tempID);
echo "current temp info: ";
print_r($info);
echo "</br>";
$type = getAccountType($tempID);
echo "type: $type</br>";

testOperation(
    array('currentID' => $tempID, 'newID' => $newID),
    '00688391'
);

$info = getAccountInfo($newID);
echo "new account info: ";
print_r($info);
echo "</br>";
$type = getAccountType($newID);
echo "type: $type</br>";


echo "</br></br>completed test</br>";
