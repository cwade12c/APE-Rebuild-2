<?php

$actions = array();

registerAction($actions, ACTION_CREATE, "Add", "addExam()");
registerAction($actions, ACTION_GENERIC, "Details", "viewDetails()");
registerAction($actions, ACTION_UPDATE, "Edit", "editExam()");
registerAction($actions, ACTION_DELETE, "Delete", "");

$parameters = array(
    "actions" => $actions
);

renderPage("pages/exams.twig.html", $parameters);