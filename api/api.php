<?php
require_once('../config.php');

initCAS();
initApi();

if($_SESSION['username'] != 'Guest') {
    enforceAuthentication();
}

$response = array(
    "data" => array(),
    "success" => true,
    "message" => "OK"
);

if (!empty($_POST)) {
    processRequest($_POST);
} elseif (!empty($_GET)) {
    processRequest($_GET);
} else {
    sendResponse(); //Invokes default Invalid Request error
}

/**
 * @param $args
 */
function processRequest($args)
{
    $operation = $args["operation"];
    $parameters = json_decode($args["parameters"], true);

    if (empty($operation)) {
        sendResponse(
            array(), false, "Cannot have an empty operation"
        );
    } elseif (!isValidOperation($operation)) {
        sendResponse(array(), false, "An invalid operation was specified!");
    } else {
        $concreteOperation = new $operation;
        $response["success"] = true;
        $response["message"] = "OK";
        $response["data"] = array();

        try {
            global $params;
            $response["data"] = $concreteOperation->execute(
                $parameters, $params['id']
            );
        } catch (Exception $exception) {
            $response["success"] = false;
            $response["message"] = convertToError($exception);
        }

        sendResponse(
            $response["data"], $response["success"], $response["message"]
        );
    }
}

/**
 * @param $operation
 *
 * @return bool
 */
function isValidOperation($operation)
{
    return is_subclass_of($operation, 'Operation');
}

/**
 * @param string $message
 *
 * @return string
 */
function convertToError(string $message) {
    $message = str_replace("Exception:", "", $message);
    if(strpos($message, "in /var")) {
        $message = substr($message, 0, strpos($message, "in /var"));
    }
    return trim($message);
}

/**
 * @param array       $data
 * @param bool|null   $success
 * @param string|null $resultMessage
 */
function sendResponse($data = array(), bool $success = null,
    string $resultMessage = null
) {
    $results["data"] = $data;
    $results["success"] = $success != null ? $success : false;
    $results["message"] = $resultMessage != null ? $resultMessage
        : "Invalid request!";

    header('Content-type: application/json');
    echo json_encode($results);
}