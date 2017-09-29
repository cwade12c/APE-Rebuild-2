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

echo "testing locations / rooms<br><br>";


/*
 *
 * Need to test ways to catch the false return false positive
 *
 * If just getQueryResult() is used, cannot distinguish
 * But if use getQueryResultRow, can explicitly get the result
 *  What happens if error to return false occurs?
 */

//$query = "SELECT ('220' IN (SELECT `name` FROM `rooms`)) AS FOUNDIT";
/// work as intended, true

//$query = "SELECT ('2018' IN (SELECT `name` FROM `rooms`)) AS FOUNDIT";
/// false returned

//$query = "SELECT ('2018' IN (SELECT `name` FROM `roomsa`)) AS FOUNDIT";
/// throws exception down in getQueryResult*()
/// null returned from executeQuery

// TODO: get case where an actual false would be a false positive for error
/// i.e. way of forcing a false return

$query = "SELECT ('2018' IN (SELECT `name` FROM `rooms`)) AS FOUNDIT";

$sql = executeQuery($query);

$sqlType = gettype($sql);
$sqlIsNull = is_null($sql);
echo "sqlType: {$sqlType}<br>";
echo "sqlIsNull: {$sqlIsNull}<br>";

$result = getQueryResultRow($sql);
$result = getQueryResultRow($sql);
//$result = getQueryResult($sql);

$resultType = gettype($result);
echo "result type: {$resultType}<br>";

if ($result) {
    echo "result given<br>";
    if ($resultType == 'array') {
        print_r($result);
        echo "<br>";
    }else{
        echo "result: {$result}<br>";
    }
}else{
    echo "no result<br>";
    echo "result value: {$result}<br>";
}



echo "<br><br>EOLFL";




