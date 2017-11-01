<?php
require_once('../config.php');
enforceAuthentication();

$response = array(
    "data" => array(),
    "success" => true,
    "message" => "OK"
);

if(!empty($_POST)) {
    processRequest($_POST);
}
elseif(!empty($_GET)) {
    processRequest($_GET);
}
else {
    sendResponse(); //Invokes default Invalid Request error
}

function processRequest($args) {
    $operation = $args["operation"];
    $parameters = $args["parameters"];
    global $params;

    if(empty($operation) || empty($parameters)) {
        sendResponse(array(), false, "Cannot have an empty operation or parameters!");
    }
    elseif(!isValidOperation($operation)) {
        sendResponse(array(), false, "An invalid operation was specified!");
    }
    else {
        $concreteOperation = new $operation; //validations should run upon instantiation
        $response["success"] = true;
        $response["message"] = "OK";
        try {
            $response["data"] = $concreteOperation->execute($parameters, $params['id']);
        }
        catch(Exception $exception) {
            $response["success"] = false;
            $response["message"] = $exception;
        }

        sendResponse($response["data"], $response["success"], $response["message"]);
    }
}

function isValidOperation($operation) {

    $validOperations = file("operations.txt");
    $result = false;

    foreach($validOperations as $op) {
        if(trim($op) == $operation) {
            $result = true;
        }
    }

    return $result;
}

function sendResponse($data=array(), bool $success = null, string $resultMessage = null) {
    $results["data"] = $data;
    $results["success"] = $success != null ? $success : false;
    $results["message"] = $resultMessage != null ? $resultMessage : "Invalid request!";

    header('Content-type: application/json');
    echo json_encode($results);
}