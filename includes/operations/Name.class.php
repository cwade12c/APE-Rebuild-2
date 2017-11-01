<?php

class Name extends Operation
{
    function __construct() {
        parent::registerOptionalParameter("Name", "string", "");
        parent::registerValidation("Name", "validateName");
    }

    public function execute($args) {
        $validationResult = parent::execute($args);

        if($validationResult["success"] == false) {
            return $validationResult;
        }

        $validationResult["data"] = array(
            "name" => $args["name"],
            "otherValue" => "a constant value"
        );

        return $validationResult;
    }
}