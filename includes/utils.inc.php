<?php

function redirect($path)
{
    $url = DOMAIN . $path;
    echo "<meta http-equiv='refresh' content='0;url=$url' />";
}

function sanitize(string $input)
{
    // TODO replace with standard function to handle
    $cleanInput = preg_replace("/%00/", "", $input);
    $cleanInput = preg_replace("/%3C/", "", $cleanInput);
    $cleanInput = preg_replace("/%3c/", "", $cleanInput);
    $cleanInput = preg_replace("/%3E/", "", $cleanInput);
    $cleanInput = preg_replace("/%3e/", "", $cleanInput);
    $cleanInput = preg_replace('/[^A-Za-z0-9\-_]+/', '', $cleanInput);

    return htmlentities($cleanInput);
}

function logSecurityIncident(string $event, string $extendedInfo)
{
    if (is_writable(LOG_PATH)) {
        $message = date("m/d/Y") . " => $event : $extendedInfo [" . $_SERVER['REMOTE_ADDR'] .
            "] [" . $_SERVER['HTTP_REFERER'] . "] [" . getCurrentUserID() . "] [" . getParam('email', 'N/A') .
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

/**
 * Used to ensure the existing parameters are not overwritten
 */
function setupParams()
{
    global $params;
    if (!isset($params)) {
        $params = array();
    }
}

/**
 * To set a global parameter
 * @param string $key
 * @param        $value
 */
function setParam(string $key, $value)
{
    setupParams();
    global $params;
    $params[$key] = $value;
}

/**
 * To get global parameter or default value
 *
 * @param string $key
 * @param        $default
 *
 * @return mixed
 */
function getParam(string $key, $default = null)
{
    global $params;
    if (isset($params[$key])) {
        return $params[$key];
    }
    return $default;
}

function getCurrentUserID()
{
    return getParam('id');
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
    $query = getParam('query', array());
    if (!isset($query[$key])) {
        return $default;
    }
    return $query[$key];
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