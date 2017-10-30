<?php

class Room
{
    private $id;
    private $name;
    private $seats;

    public function __construct($id, $name, $seats)
    {
        $this->id = $id;
        $this->name = $name;
        $this->seats = $seats;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSeats()
    {
        return $this->seats;
    }
}