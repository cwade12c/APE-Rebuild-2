<?php

class ExamCategory
{
    private $id;
    private $categoryId;
    private $points;

    public function __construct($id, $catId, $points)
    {
        $this->id = $id;
        $this->categoryId = $catId;
        $this->points = $points;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCategoryId()
    {
        return $this->categoryId;
    }

    public function getPoints()
    {
        return $this->points;
    }
}