<?php

class AssignedGrader
{
    private $examId;
    private $categoryId;
    private $graderId;
    private $hasSubmitted;

    public function __construct($examId, $catId, $graderId, $submitted)
    {
        $this->examId = $examId;
        $this->categoryId = $catId;
        $this->graderId = $graderId;
        $this->hasSubmitted = $submitted;
    }

    public function getExamId()
    {
        return $this->examId;
    }

    public function getCategoryId()
    {
        return $this->categoryId;
    }

    public function getGraderId()
    {
        return $this->graderId;
    }

    public function getHasSubmitted()
    {
        return $this->hasSubmitted;
    }
}