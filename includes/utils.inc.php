<?php

function redirect($path)
{
    header('Location: ' . DOMAIN . $path, true, 302);
    exit;
}

function sanitize(string $input)
{
    $cleanInput = preg_replace("/%00/", "", $input);
    $cleanInput = preg_replace("/%3C/", "", $cleanInput);
    $cleanInput = preg_replace("/%3c/", "", $cleanInput);
    $cleanInput = preg_replace("/%3E/", "", $cleanInput);
    $cleanInput = preg_replace("/%3e/", "", $cleanInput);
    $cleanInput = preg_replace('/[^A-Za-z0-9]+/', '', $cleanInput);

    return htmlentities($cleanInput);
}

function logSecurityIncident(string $event, string $extendedInfo)
{
    if (is_writable(LOG_PATH)) {
        global $params;
        $message = date("m/d/Y") . " => $event : $extendedInfo [" . $_SERVER['REMOTE_ADDR'] .
            "] [" . $_SERVER['HTTP_REFERER'] . "] [" . $params['id'] . "] [" . $params['email'] .
            "] \n";

        if ( ! $handle = fopen(LOG_PATH, 'a')) {
            die("Security incident ($event) : unable to read the security log file");
        }
        if (fwrite($handle, $message) === false) {
            die("Security incident ($event) : unable to write to the security log file");
        }

        fclose($handle);
    } else {
        die("Security incident ($event) : unable to write to the security log file");
    }
}

function renderPage(string $template, array $pageParams)
{
    global $params, $twig;
    $page = $twig->load($template);

    $parameters = array(
        'params' => $params
    );

    foreach ($pageParams as $key => $value) {
        $parameters[$key] = $value;
    }

    echo $page->render($parameters);
}

?>