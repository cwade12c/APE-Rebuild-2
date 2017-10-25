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

//$students = array($studentA, $studentB, $studentC);

$students = array(
    array(
        "name" => "Curran",
        "age" => 24
    ),
    array(
        "name" => "Kyle",
        "age" => 27
    ),
    array(
        "name" => "Matthew",
        "age" => 25
    )
);

$template = $twig->load('index.twig.html');
echo $template->render(array('firstName' => 'Hello', 'lastName' => 'World', 'students' => $students));