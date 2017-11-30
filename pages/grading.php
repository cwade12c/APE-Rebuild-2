<?php

$graderID = getCurrentUserID();

$parameters = array('graderID' => $graderID);

renderPage('pages/grading.twig.html', $parameters);