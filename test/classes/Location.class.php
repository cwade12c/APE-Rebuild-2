<?php

class Location
{
    private $id;
    private $name;
    private $reservedSeats;
    private $limitedSeats;

    public function __construct($id, $name, $reservedSeats, $limitedSeats)
    {
        $this->id = $id;
        $this->name = $name;
        $this->reservedSeats = $reservedSeats;
        $this->limitedSeats = $limitedSeats;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getReservedSeats()
    {
        return $this->reservedSeats;
    }

    public function getLimitedSeats()
    {
        return $this->limitedSeats;
    }
}