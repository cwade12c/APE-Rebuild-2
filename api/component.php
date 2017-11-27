<?php
require_once('../config.php');
initCAS();
enforceAuthentication();

if(isset($_GET['componentName'])) {
    $componentName = sanitize($_GET['componentName']);
    $params = array();

    foreach($_GET as $key => $value) {
        if($key != "componentName") {
            $params[$key] = sanitize($value);
        }
    }

    if(file_exists(TEMPLATES_PATH . "/components/$componentName.twig.html")) {
        renderPage("components/$componentName.twig.html", $params);
    }
    else {
        die("Error: component $componentName.twig.html does not exist.");
    }
}