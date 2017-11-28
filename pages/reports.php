<?php

$actions = array();

registerAction($actions, ACTION_GENERIC, "Configure", "configureReport");

$parameters = array(
    "actions" => $actions
);

renderPage("pages/reports.twig.html", $parameters);