<?php

if ( ! defined('INCLUDE_ACCESS')) {
    die('Direct access to this file is denied.');
}

$directory        = INCLUDES_PATH;
$filesInDirectory = scandir($directory);

foreach ($filesInDirectory as &$fileName) {
    $lengthOfFileName = strlen($fileName);
    $extension        = substr(
        $fileName, $lengthOfFileName - 4, $lengthOfFileName - 1
    );

    if ($extension == '.php' && $fileName != 'includes.php') {
        $pathToFile = $directory . $fileName;
        require_once($pathToFile);
    }
}

?>