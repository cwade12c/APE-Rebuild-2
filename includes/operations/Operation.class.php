<?php

abstract class Operation
{
    protected $parameters = array();
    protected $validations = array();

    protected function registerParameter(string $name, string $type, string $default) {
        array_push($this->parameters, array(
            "name" => $name,
            "type" => $type,
            "default" => $default
        ));
    }
    protected function registerValidation(string $input, string $fncName) {
        array_push($this->validations, array(
            "input" => $input,
            "fncName" => $fncName
        ));
    }

    protected function execute($args) {
        if(count($args) != count($this->parameters)) {
            throw new Exception("Invalid number of parameters!");
        }

    }
}