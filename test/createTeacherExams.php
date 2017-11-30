<?php
/**
 * For creating in-class exams without queries for testing
 */

require_once '../config.php';

echo "attempting to create in-class exams</br></br>";

function testCreateInClassExam($teacherID, $start, $cutoff, $length,
    $passingGrade, $categories, $locationID
) {
    echo "creating in-class exam ...</br>";
    try {
        createInClassExam(
            $start, $cutoff, $length, $passingGrade,
            $categories, $locationID, $teacherID
        );
    } catch (Exception $e) {
        $type = gettype($e);
        echo "exception($type): $e</br>";
    }
}

/*
DateTime $start, DateTime $cutoff, int $minutes,
int $passingGrade, array $categories, int $locationID, string $teacherID
*/

$teacherID = '00798787';

testCreateInClassExam(
    $teacherID,
    new DateTime('2017-9-21 15:00:00'),
    new DateTime('2017-9-21 13:00:00'),
    180,
    80,
    array(
        array('id' => 1, 'points' => 25),
        array('id' => 2, 'points' => 25),
        array('id' => 3, 'points' => 25),
        array('id' => 4, 'points' => 25)
    ),
    35
);

testCreateInClassExam(
    $teacherID,
    new DateTime('2018-12-21 15:00:00'),
    new DateTime('2018-12-21 13:00:00'),
    240,
    80,
    array(
        array('id' => 1, 'points' => 25),
        array('id' => 2, 'points' => 25),
        array('id' => 3, 'points' => 25),
        array('id' => 4, 'points' => 25)
    ),
    35
);

testCreateInClassExam(
    $teacherID,
    new DateTime('2019-9-21 15:00:00'),
    new DateTime('2019-9-21 15:00:00'),
    120,
    80,
    array(
        array('id' => 1, 'points' => 25),
        array('id' => 2, 'points' => 25),
        array('id' => 3, 'points' => 25),
        array('id' => 4, 'points' => 25)
    ),
    35
);


echo "</br></br>completed test</br>";
