<?php

class Name extends Operation
{
    function __construct() {
        parent::registerParameter("Name", "string", "");
        parent::registerValidation("Name", "validateName");
    }

    protected function execute($args) {
        parent::execute($args);
        //concrete logic
    }
}