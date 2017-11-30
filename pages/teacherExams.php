<?php

$teacherID = getCurrentUserID();

$parameters = array(
    'teacherID' => $teacherID
);

renderPage('pages/teacherExams.twig.html', $parameters);