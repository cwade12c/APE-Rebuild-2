<?php

class Name extends Operation
{
    function __construct() {
        parent::registerParameter("Name", "string", "");
        parent::registerValidation("Name", "validateName");
    }

    public function execute($args) {
        parent::execute($args);
        echo "Concrete logic";
    }
}