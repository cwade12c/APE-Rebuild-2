<?php

global $twig;

$loader = new Twig_Loader_Filesystem(TEMPLATES_PATH);
if (DEBUG) {
    $twig = new Twig_Environment($loader, array(
        'cache' => CACHE_PATH,
        'auto_reload' => true
    ));
}
else {
    $twig = new Twig_Environment($loader, array(
        'cache' => CACHE_PATH
    ));
}
