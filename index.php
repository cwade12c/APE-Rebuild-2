<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>

<?php

require_once('config.php');

if (userIsLoggedIn()) {
    redirect('home.php');
}

?>

<?php

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

renderPage('upcoming-apes.twig.html', $parameters);

/*$template = $twig->load('upcoming-apes.twig.html');
echo $template->render(array('params' => $params, 'exams' => $exams, 'resources' => $resources));*/
?>

</body>
</html>
