function registerStudentForExam(examId, studentId) {
  $.post("api/post/registerForExam.php", {examId: examId, studentId: studentId}).done(function (response) {
    if(response === '1') {
      notification('Successfully registered for the exam', 'success');
      var elementId = 'exam-' + examId;
      updateValue(elementId, 'Unregister');
      updateOnClick(elementId, 'deregisterStudentForExam(' + examId + ', ' + studentId + ')');
      updateClass(elementId, 'btn btn-warning');
    }
    else {
      notification('Unable to register for the exam');
    }
  });
}

function deregisterStudentForExam(examId, studentId) {
  $.post("api/post/deregisterForExam.php", {examId: examId, studentId: studentId}).done(function (response) {
    if(response === '1') {
      notification('Successfully unregistered for the exam', 'warning');
      var elementId = 'exam-' + examId;
      updateValue(elementId, 'Register');
      updateOnClick(elementId, 'registerStudentForExam(' + examId + ', ' + studentId + ')');
      updateClass(elementId, 'btn btn-primary');
    }
    else {
      notification('Unable to unregister for the exam');
    }
  });
}