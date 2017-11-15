<?php

global $twig;

$loader = new Twig_Loader_Filesystem(TEMPLATES_PATH);
if (ENABLE_CACHING) {
    $twig = new Twig_Environment($loader, array(
        'cache' => CACHE_PATH
    ));
}
else {
    $twig = new Twig_Environment($loader, array(
        'cache' => CACHE_PATH,
        'auto_reload' => true
    ));
}