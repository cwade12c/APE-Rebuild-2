<?php

function redirect($path)
{
    header('Location: ' . DOMAIN . $path, true, 302);
    exit;
}

function sanitize(string $input)
{
    return htmlentities($input);
}

function logSecurityIncident(str $event, $extendedInfo)
{
    if (is_writable(LOG_PATH)) {
        if ( ! $handle = fopen(LOG_PATH, 'a')) {
            if (DEBUG) {
                die("Security incident: unable to read the security log file");
            }
        }
        if (fwrite($handle, $event . " : " . $extendedInfo) === false) {
            if (DEBUG) {
                die("Security incident: unable to write to the security log file");
            }
        }

        fclose($handle);
    } else if (DEBUG) {
        die("Security incident: unable to write to the security log file");
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