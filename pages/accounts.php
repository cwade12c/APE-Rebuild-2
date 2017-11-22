<?php

$actions = array();

registerAction($actions, ACTION_CREATE, "Add", "addAccount()");
registerAction($actions, ACTION_UPDATE, "Edit", "editAccount()");

$parameters = array(
    "actions" => $actions
);

renderPage("pages/accounts.twig.html", $parameters);