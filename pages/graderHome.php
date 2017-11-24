<?php

$graderID = getCurrentUserID();

$parameters = array('graderID' => $graderID);

renderPage("pages/graderhome.twig.html", $parameters);