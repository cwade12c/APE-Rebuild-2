<?php

function redirect($path)
{
    header('Location: ' . DOMAIN . $path, true, 302);
    exit;
}

function sanitize($input)
{
    return htmlentities($input);
}

function logSecurityIncident(string $event, string $extendedInfo)
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

?>