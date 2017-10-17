<?php

require_once('../../config.php');

if(isset($_POST['examId']) &&
    isset($_POST['studentId'])
) {
  //validate if examId is legit
  //then validate if $param['id'] === $_POST['studentId']
  $result = registerStudentForExam((int)$_POST['examId'], (string)$_POST['studentId']);
  echo $result;
}
else {
    echo false;
}