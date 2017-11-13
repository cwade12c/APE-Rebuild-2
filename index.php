<?php

require_once("config.php");
initCAS();

if(userIsLoggedIn()) {
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] && !in_array(strtolower($_SERVER['HTTPS']), array("off", "no"))) ? "https" : "http";
    $url = $protocol . "://" . $_SERVER['HTTP_HOST']. $_SERVER['REQUEST_URI'];
    $difference = strlen($url) - strlen(DOMAIN);
    $page = substr($url, -$difference);

    enforceAuthentication();
    if($difference == 0) {
        require("pages/home.php");
    }
    else {
        if(file_exists("pages/" . sanitize($page) . ".php")) {
            require("pages/" . sanitize($page) . ".php");
        }
        else {
            require("pages/home.php");
            echo "<script type='text/javascript'>notification('The page does not exist.');</script>";
        }
    }
}
else {
    $exams = UpcomingExams::getUpcomingExams(); //this will instantiate Exam objects from a Class
    $resources = array(
        array("title" => "Winter 2011 Student Version",
            "content" => "Includes practice material for Data Abstraction (Stock), General (Files), LinkedList (Add, Sort, Remove), and Recursion (LinkedList, GCD).",
            "download" => "http://penguin.ewu.edu/advancement_exam/practice_exams/W11APE.zip",
            "style" => "panel-info"
        ),
        array(
            "title" => "Winter 2012 Student Version",
            "content" => "Includes practice material for Data Abstraction (Student Interface), General (Files), LinkedList (Clear, Add-Ordered, Remove-All), and Recursion (LinkedList, Ackermann).",
            "download" => "http://penguin.ewu.edu/advancement_exam/practice_exams/W12APE.zip",
            "style" => "panel-info"
        ),
        array(
            "title" => "Summer 2013 Student Version",
            "content" => "Includes practice material for Data Abstraction (Inheritance, Interface), General (FileIO_Exceptions, Sorting), LinkedList (Append-All, Remove-All-Occurrences, Set), and Recursion (Hanoi, Power).",
            "download" => "http://penguin.ewu.edu/advancement_exam/practice_exams/Summer2013APE.zip",
            "style" => "panel-danger"
        )
    );

    $parameters = array(
        'exams' => $exams,
        'resources' => $resources
    );

    renderPage('pages/upcoming-apes.twig.html', $parameters);
}