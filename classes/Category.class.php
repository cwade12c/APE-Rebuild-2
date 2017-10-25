<?php

class Category
{
    private $id;
    private $name;
    private $points;

    public function __construct($catId, $catName, $catPoints)
    {
        $this->id = $catId;
        $this->name = $catName;
        $this->points = $catPoints;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPoints()
    {
        return $this->points;
    }
}