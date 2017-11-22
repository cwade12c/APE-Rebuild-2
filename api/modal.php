<?php
require_once('../config.php');
initCAS();
enforceAuthentication();

if(isset($_GET['modalName'])) {
    $modalName = sanitize($_GET['modalName']);
    $params = array();
    foreach($_GET as $key => $value) {
        if($key != "modalName") {
            $params[$key] = sanitize($value);
        }
    }


    if(file_exists(TEMPLATES_PATH . "/modals/$modalName.twig.html")) {
        renderPage("modals/$modalName.twig.html", $params);
    }
    else {
        die("Error: modal template $modalName.twig.html does not exist.");
    }
}