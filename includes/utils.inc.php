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

?>