<?php

class ExamGrade
{
    private $examId;
    private $studentId;
    private $points;
    private $passed;
    private $comment;

    public function __construct($id, $studentId, $points, $hasPassed, $comment)
    {
        $this->examId = $id;
        $this->studentId = $studentId;
        $this->points = $points;
        $this->passed = $hasPassed;
        $this->comment = $comment;
    }

    public function getExamId()
    {
        return $this->examId;
    }

    public function getStudentId()
    {
        return $this->studentId;
    }

    public function getPoints()
    {
        return $this->points;
    }

    public function getPassed()
    {
        return $this->passed;
    }

    public function getComment()
    {
        return $this->comment;
    }
}