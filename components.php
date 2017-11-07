<?php

include('config.php');
enforceAuthentication();

$parameters = array();

renderPage("components.twig.html", $parameters);

