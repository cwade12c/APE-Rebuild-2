<?php
require_once('../config.php');
enforceAuthentication();

if(!empty($_POST)) {
    processRequest($_POST);
}
elseif(!empty($_GET)) {
    processRequest($_GET);
}
else {
    echo "Invalid request!";
}

function processRequest($args) {
    $operation = $args["operation"];
    $parameters = $args["parameters"];

    if(empty($operation) || empty($parameters)) {
        die("Cannot have an empty operation or parameters!");
    }
    elseif(!isValidOperation($operation)) {
        die("An invalid operation was specified!");
    }
    else {
        $concreteOperation = new $operation; //validations should run upon instantiation
        $concreteOperation->execute($parameters);
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