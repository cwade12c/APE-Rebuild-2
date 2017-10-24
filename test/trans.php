<?php
/**
 * test of exams
 *
 * @author         Mathew McCain
 * @category       APE
 * @package        APE_testing
 * @subpackage     exams
 */

require_once '../config.php';

echo "testing transactions<br><br>";

// test global
global $transactionCounter;
if (is_null($transactionCounter)) {
    echo "is null";
}else{
    echo "not null";
}

echo "<br>";

// test reference, global

function &getVal() {
    global $someVar;
    if (is_null($someVar)) {
        $someVar = 42;
    }
    return $someVar;
}

$tvar = &getVal();
echo "value: {$tvar}<br>";
$tvar++;
echo "changed value: {$tvar}<br>";
$uvar = &getVal();
echo "grab again value: {$uvar}<br>";

echo "<br><br>";

// now test transaction
function printRooms($str) {
    $rooms = getRooms();
    echo $str.", rooms: <br>";
    foreach($rooms as $roomID) {
        $info = getRoomInformation($roomID);
        echo "     {$roomID}: {$info['name']}, {$info['seats']}<br>";
    }
    echo "<br><br>";
}

printRooms("pre-test");

startTransaction();

createRoom("test room", 123);

printRooms("added");

rollback();
printRooms("roll backed");

echo "<br><br>EOLFL";




