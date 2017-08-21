<?php

require_once('../config.php');

class Student
{
    private $firstName;

    public function __construct( $firstName )
    {
        $this->firstName = $firstName;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }
}

$studentA = new Student("Curran");
$studentB = new Student("Kyle");
$studentC = new Student("Mathew");

$students = array($studentA, $studentB, $studentC);

$template = $twig->load('index.html');
echo $template->render(array('firstName' => 'Hello', 'lastName' => 'World', 'students' => $students));