<?php

class Account
{
    private $id;
    private $type;
    private $firstName;
    private $lastName;
    private $email;

    public function __construct($id, $type, $fName, $lName, $email)
    {
        $this->id = $id;
        $this->type = $type;
        $this->firstName = $fName;
        $this->lastName = $lName;
        $this->email = $email;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getEmail()
    {
        return $this->email;
    }
}