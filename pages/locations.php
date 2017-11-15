<?php

$actions = array();

registerAction($actions, ACTION_CREATE, "Add", "addLocation()");
registerAction($actions, ACTION_UPDATE, "Edit", "editLocation(locationId)");
registerAction($actions, ACTION_DELETE, "Delete", "removeLocation(locationId)");

$parameters = array(
    "actions" => $actions
);

renderPage("pages/locations.twig.html", $parameters);