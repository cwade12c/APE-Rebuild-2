<?php
require_once INCLUDES_PATH . 'operations/Operation.class.php';

$self = basename($_SERVER['PHP_SELF']);
$path = INCLUDES_PATH . 'operations/';
foreach (scandir($path) as $file) {
    if ($file != $self
        && $file != 'Operation.class.php'
        && preg_match('/^.+\.class\.php$/', $file)) {
        require_once($path . $file);
    }
}