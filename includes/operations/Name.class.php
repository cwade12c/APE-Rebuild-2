<?php

class Name extends Operation
{
    function __construct()
    {

        parent::registerExecution(array($this, "name"));

        parent::registerOptionalParameter("Name", "string", "");
        parent::registerValidation("Name", "validateName");
    }

    public function execute(array $args, string $accountID = null)
    {
        parent::execute($args, $accountID);
    }

    private static function name(string $name)
    {
        $validationResult["data"] = array(
            "name"       => $name,
            "otherValue" => "a constant value"
        );

        return $validationResult;
    }
}