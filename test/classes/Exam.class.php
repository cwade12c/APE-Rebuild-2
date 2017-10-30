<?php

class Exam
{
    private $id;
    private $isRegular;
    private $start;
    private $stop;
    private $cutoff;
    private $length;
    private $passingGrade;
    private $locationId;
    private $state;

    public function __construct($examId, $isRegular, $startTime, $endTime, $cutOff, $length, $passingGrade, $locationId, $state)
    {
        $this->id = $examId;
        $this->isRegular = $isRegular;
        $this->start = $startTime;
        $this->stop = $endTime;
        $this->cutoff = $cutOff;
        $this->length = $length;
        $this->passingGrade = $passingGrade;
        $this->locationId = $locationId;
        $this->state = $state;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIsRegular()
    {
        return $this->isRegular;
    }

    public function getStart()
    {
        return $this->start;
    }

    public function getStop()
    {
        return $this->stop;
    }

    public function getCutoff()
    {
        return $this->cutoff;
    }

    public function getLength()
    {
        return $this->length;
    }

    public function getPassingGrade()
    {
        return $this->passingGrade;
    }

    public function getLocationId()
    {
        return $this->locationId;
    }

    public function getState()
    {
        return $this->state;
    }
}