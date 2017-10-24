<label for="tester">Enter your params using the following format: <b>paramValue ; paramType, paramValue1 ; int, paramValue2 ; string</b></label>
<form name="tester" method="POST">

<?php
require_once('../config.php');

function getListOfFunctions()
{
    $listOfFunctions = array();

    $file = fopen('./functions.txt', 'rb');
    while (($line = fgets($file)) !== false) {
        $tmpArr = substr($line, 0, 2) === 'f:' ? array($line, 1) : array($line, 0);
        array_push($listOfFunctions, $tmpArr);
    }

    return $listOfFunctions;
}

function getSafeList()
{
    $listOfSafeFunctions = array();
    $listOfFunctions = getListOfFunctions();

    foreach($listOfFunctions as &$currentFunction)
    {
        if($currentFunction[1] === 1) {
            $safeFunction = substr($currentFunction[0], 2, strlen($currentFunction[0]) - 1);
            array_push($listOfSafeFunctions, $safeFunction);
        }
    }

    return $listOfSafeFunctions;
}


function populateSelector()
{
    $availableFunctions = getListOfFunctions();

    echo "<select name=\"funcToTest\">";
    foreach($availableFunctions as &$currentFunction) {
        if($currentFunction[1] === 0) {
            echo "    <option value=\"%00\">$currentFunction[0]</option>";
        }
        else {
            $signature = substr($currentFunction[0], 2, strlen($currentFunction[0]) - 1);
            echo "    <option value=\"$signature\">$signature</option>";
        }
    }
    echo "</select>";
}

function splitParams(string $params)
{
    if(empty($params)) {
        return array();
    }

    $params = str_replace(" ", "", $params);
    $listOfParamsDirty = explode("," , $params);
    $listOfParamsClean = array();
    foreach($listOfParamsDirty as &$currentParam)
    {
        $paramType = explode(";", $currentParam);
        if($paramType) {
            switch($paramType[1]) {
                case "int":
                    array_push($listOfParamsClean, intval($paramType[0]));
                    break;
                case "string":
                    array_push($listOfParamsClean, strval($paramType[0]));
                    break;
            }
        }
        else {
            die("Invalid parameters! Make sure that you specify the parameter type.");
        }

    }

    return $listOfParamsClean;
}

function executeTest(string $signature, array $params)
{
    $listOfSafeFunctions = getSafeList();
    if(in_array($signature, $listOfSafeFunctions)) {
        $endMarker = strpos($signature, "(");
        if($endMarker) {
            $signature = substr($signature, 0, $endMarker);

            return call_user_func_array($signature, $params);
        }
    }
    else {
        die("Invalid function! Don't manipulate with the select options...");
    }
}

populateSelector();
?>
<br />
<input type="text" name="params" placeholder="comma,separated,params" />
<br />
<input type="submit" value="Test function" name="submit" />

<?php

if(isset($_POST['submit']) && isset($_POST['params'])) {
    if($_POST['funcToTest'] != "%00") {
        $params = strval($_POST['params']);
        $results = executeTest($_POST['funcToTest'], splitParams($params));
        if($results !== null) {
            echo '<hr />Result is: ';
            var_dump($results);
        }
        else {
            echo "<hr />No results were returned.";
        }
    }
}

?>