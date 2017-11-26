<?php
/**
 * To create location without using queries for testing
 */

require_once '../config.php';

echo "attempting to assign grader</br></br>";

function testCreateLocation(string $name, int $seatsReserved, int $limitedSeats,
    array $rooms) {
    $roomStr = print_r($rooms, true);
    echo "test creating location($name), reserved($seatsReserved), limited($limitedSeats), rooms: $roomStr</br>";
    try{
        createLocation($name, $seatsReserved, $limitedSeats, $rooms);
    }catch(Exception $e) {
        $type = gettype($e);
        echo "exception($type): $e</br>";
    }
}

createLocation('dummy location', 10, 0, array(
    array('id' => 1, 'seats' => 30),
    array('id' => 2, 'seats' => 45),
    array('id' => 3, 'seats' => 60),
    array('id' => 6, 'seats' => 50),
));

createLocation('real location', 0, 0, array(
    array('id' => 13, 'seats' => 30),
    array('id' => 11, 'seats' => 45),
    array('id' => 12, 'seats' => 60)
));

createLocation('not so real location', 10, 50, array(
    array('id' => 4, 'seats' => 30),
    array('id' => 1, 'seats' => 45),
    array('id' => 2, 'seats' => 60),
    array('id' => 6, 'seats' => 50),
));


echo "</br></br>completed test</br>";
