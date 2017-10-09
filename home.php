<?php

include('config.php');

enforceAuthentication();

/*echo accountIsStudent((string)$_SESSION['ewuid']);

var_dump($params);*/

/*$template = $twig->load('home.twig.html');
echo $template->render(array('params' => $params));*/

renderPage("home.twig.html", array());

?>