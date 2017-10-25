<?php
require_once('../config.php');
enforceAuthentication();

if(isset($_POST['controller']) && isset($_POST['json'])) {
    $controller = $_POST['controller'];
    $json = $_POST['json'];
    if(is_array($json)) {
        $parameters = array();

        foreach($json as $key => $value) {
            $parameters[$key] = sanitize($value);
        }

        $controller = API_PATH . 'post/' . $controller . '.php';
        postRequest($controller, $parameters);
    }
}

function postRequest($controller, $parameters) {
    $fields = http_build_query($parameters);
    $chan = curl_init();

    curl_setopt($chan, CURLOPT_URL, 'http://146.187.134.42/chdev/api/controllers/name.php');
    curl_setopt($chan, CURLOPT_POST, count($parameters));
    curl_setopt($chan, CURLOPT_POSTFIELDS, $fields);

    curl_exec($chan);
    curl_close($chan);
}