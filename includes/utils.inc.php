<?php

function redirect($path)
{
    $url = DOMAIN . $path;
    echo "<meta http-equiv='refresh' content='0;url=$url' />";
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

function getCurrentUserID()
{
    global $params;
    return $params['id'];
}

/**
 * Get the query string and parse into array
 * @return array
 */
function getParseQuery()
{
    $str = $_SERVER['QUERY_STRING'];
    $arr = array();
    parse_str($str, $arr);
    return $arr;
}

/**
 * Get value from query string or default
 *
 * @param string $key
 * @param        $default
 *
 * @return mixed
 */
function getQueryVar(string $key, $default)
{
    global $params;

    if (!isset($params['query']) && !isset($params['query'][$key])) {
        return $default;
    }
    return $params['query'][$key];
}

function renderPage(string $template, array $pageParams = array())
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

function registerAction(array &$actions, string $type, string $text, string $funcSignature) {
    $action = array();
    $icon = getActionIcon($type);

    $action["type"] = $type;
    $action["text"] = $icon . "&nbsp;" . $text;
    $action["funcSignature"] = $funcSignature;

    array_push($actions, $action);
}

function getActionIcon(string $type) {
    switch($type) {
        case ACTION_CREATE:
            return '<span class="fa fa-plus-circle"></span>';
            break;
        case ACTION_UPDATE:
            return '<span class="fa fa-pencil"></span>';
            break;
        case ACTION_DELETE:
            return '<span class="fa fa-remove"></span>';
            break;
        case ACTION_ARCHIVE:
            return '<span class="fa fa-archive"></span>';
            break;
        case ACTION_GENERIC:
            return '<span class="fa fa-bullseye"></span>';
            break;
    }
    return "";
}