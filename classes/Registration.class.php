<?php

class Registration
{
    private $id;
    private $state;

    public function __construct($id, $state)
    {
        $this->id = $id;
        $this->state = $state;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getState()
    {
        return $this->state;
    }
}