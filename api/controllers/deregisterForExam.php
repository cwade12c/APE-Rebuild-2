<?php

require_once('../../config.php');
enforceAuthentication();
global $params;

if(isset($_POST['examId']) &&
    isset($_POST['studentId'])
) {
    //validate if examId is legit
    //then validate if $param['id'] === $_POST['studentId']
    $result = deregisterStudentFromExam((int)$_POST['examId'], $params['id']);
    echo $result;
}
else {
    echo false;
}