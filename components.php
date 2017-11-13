<?php

include('config.php');
enforceAuthentication();

$parameters = array();

renderPage("pages/components.twig.html", $parameters);

