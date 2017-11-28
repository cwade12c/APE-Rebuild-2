<?php

$actions = array();

registerAction($actions, ACTION_CREATE, "Add", "addExam()");
registerAction($actions, ACTION_UPDATE, "Edit", "editExam()");
registerAction($actions, ACTION_GENERIC, "Details", "viewDetails()");

$parameters = array(
    "actions" => $actions
);

renderPage("pages/exams.twig.html", $parameters);