<?php

$actions = array();

registerAction($actions, ACTION_CREATE, "Add", "addRoom()");
registerAction($actions, ACTION_UPDATE, "Edit", "editRoom()");
registerAction($actions, ACTION_DELETE, "Delete", "");

$parameters = array(
    "actions" => $actions
);

renderPage("pages/rooms.twig.html", $parameters);