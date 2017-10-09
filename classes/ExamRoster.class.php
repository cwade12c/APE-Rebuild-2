<?php

class ExamRoster
{
    private $examId;
    private $studentId;
    private $roomId;
    private $seat;

    public function __construct($id, $studentId, $roomId, $seat)
    {
        $this->examId = $id;
        $this->studentId = $studentId;
        $this->roomId = $roomId;
        $this->seat = $seat;
    }

    public function getExamId()
    {
        return $this->examId;
    }

    public function getStudentId()
    {
        return $this->studentId;
    }

    public function getRoomId()
    {
        return $this->roomId;
    }

    public function getSeat()
    {
        return $this->seat;
    }
}