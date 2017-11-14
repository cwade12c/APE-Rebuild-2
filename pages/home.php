<?php

//enforceAuthentication();

/*echo accountIsStudent((string)$_SESSION['ewuid']);

var_dump($params);*/

/*$template = $twig->load('home.twig.html');
echo $template->render(array('params' => $params));*/

//renderPage("home.twig.html", array());

$upcomingExams = getUpcomingExams();
$registeredExams = getExamsRegisteredFor($params['id']);

$parameters = array(
    'upcomingExams' => $upcomingExams,
    'registeredExams' => $registeredExams
);

renderPage("pages/home.twig.html", $parameters);

//updateReportTypesQuery(8, array(1,2,3,4));

/*$result = registerStudentForExam(9, $params["id"]);
var_dump($result);*/
