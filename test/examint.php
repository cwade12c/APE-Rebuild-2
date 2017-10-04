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

echo "testing exam interaction specifics<br><br>";

/*
 * Testing exam/location ID
 * DB was designed with idea that a location could be null
 *  (to be set later)
 *
 * Will need to add checks to catch a null location during
 *  state change to active
 *      need to prevent
 *  when admin logged in
 *  in-class exams, not really necessary
 *
 */

$checkID = 11;
echo "Checking exam({$checkID}) for null location ID-<br>";

$info = getExamInformation($checkID);
if(is_null($info['location_id'])) {
    echo " - is null<br>";
}else{
    $value = $info['location_id'];
    echo " - not null({$value})<br>";
}

echo "<br><br>EOLFL";




