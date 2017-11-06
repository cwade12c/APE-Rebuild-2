// <editor-fold desc="API Functions">
function callAjax(requestType, operationName, operationParameters, callBackFunctions) {
  requestType = requestType || 'GET';
  operationName = operationName || '';
  callBackFunctions = callBackFunctions || {};
  operationParameters = JSON.stringify(operationParameters);

  var successFnc = ('success' in callBackFunctions) ? callBackFunctions['success'] : function (data, status, jqXHR) {};
  var errorFnc = ('error' in callBackFunctions) ? callBackFunctions['error'] : function (jqXHR, status) {};
  var completeFnc = ('complete' in callBackFunctions) ? callBackFunctions['complete'] : function (jqXHR, status) {};

  var fullParameters = {
    operation: operationName,
    parameters: operationParameters
  };

  //fullParameters = _.extend(fullParameters, getAccountProperties());

  var callParameters = {
    type: requestType.toUpperCase(),
    url: 'api/api.php',
    data: fullParameters,
    dataType: 'json',
    success: successFnc,
    error: errorFnc,
    complete: completeFnc
  };

  $.ajax(callParameters);
}

function getAccountProperties() {
  var comments = $('head').getComments();
  var properties = {};
  _.each(comments, function(currentComment) {
    currentComment = currentComment.trim();
    if(currentComment.length) {
      var keyVal = currentComment.split(':');
      properties[keyVal[0].trim()] = keyVal[1].trim();
    }
  })
  return properties;
}

function getAccountProperty(propertyName) {
  var properties = getAccountProperties();
  if(_.has(properties, propertyName)) { return properties[propertyName] };
  return null;
}
// </editor-fold>

// <editor-fold desc="Operation Functions">

function registerStudentForExam(examId, studentId, examObj) {
  $.post("api/controllers/registerForExam.php", {examId: examId, studentId: studentId}).done(function (response) {
    if(response === '1') {
      notification('Successfully registered for the exam', 'success');
      var elementId = 'exam-' + examId;
      var params = '[';
      _.each(examObj, function(currentProperty, index) {
        index !== examObj.length - 1 ? params += '\'' + currentProperty + '\',' : params += '\'' + currentProperty + '\']';
      });
      updateValue(elementId, 'Unregister');
      updateOnClick(elementId, 'deregisterStudentForExam(' + examId + ', ' + studentId + ', ' + params + ')');
      updateClass(elementId, 'btn btn-warning');
      addRowToTable('registered-exams', examObj);
    }
    else {
      notification('Unable to register for the exam');
    }
  });
}

function deregisterStudentForExam(examId, studentId, examObj) {
  $.post("api/controllers/deregisterForExam.php", {examId: examId, studentId: studentId}).done(function (response) {
    if(response === '1') {
      notification('Successfully unregistered for the exam', 'warning');
      var elementId = 'exam-' + examId;
      var params = '[';
      _.each(examObj, function(currentProperty, index) {
        index !== examObj.length - 1 ? params += '\'' + currentProperty + '\',' : params += '\'' + currentProperty + '\']';
      });
      updateValue(elementId, 'Register');
      updateOnClick(elementId, 'registerStudentForExam(' + examId + ', ' + studentId + ', ' + params + ')');
      updateClass(elementId, 'btn btn-primary');
      removeRowFromTable('registered-exams', 'row-' + examId);
    }
    else {
      notification('Unable to unregister for the exam');
    }
  });
}

function viewExamDetails (examId) {
  $('.modal-content').load('api/controllers/examDetails.php?examId=' + examId,function(){
    $('#myModal').modal({show:true});
  });
}

function name(name) {
  var params = {
    name: name
  };

  var callbacks = {
    success: function (response) {
      if(response.success === true) {
        notification(response.data.name, 'success', 'https://google.com', false);
      }
      else {
        notification(response.message);
      }
    },
    error: function (response) {
      notification(response.message);
    }
  };

  callAjax('post', 'name', params, callbacks);
}

function getExamInformation(examId) {
  var params = {
    id: examId
  };

  var callbacks = {
    success: function (response) {
      if(response.success === true) {
        console.log(response);
      }
      else {
        console.log('Error');
      }
    },
    error: function (response) {
      notification(response.message);
    }
  };

  callAjax('get', 'ExamDetails', params, callbacks);
}

function getStudentState(studentId) {
  var params = {
    studentId: studentId
  };

  var callbacks = {
    success: function (response) {
      if(response.success === true) {
        console.log(response);
      }
      else {
        console.log('Error');
      }
    },
    error: function (response) {
      notification(response.message);
    }
  };

  callAjax('get', 'StudentState', params, callbacks);
}

function registerForExam(examId) {
  var params = {
    "examID": examId,
    "studentID": getAccountProperty('accountID')
  };

  var callbacks = {
    success: function (response) {
      if(response.success === true) {
        console.log(response);
      }
      else {
        console.log('Error');
      }
    },
    error: function (response) {
      notification(response.message);
    }
  };

  callAjax('post', 'RegisterForExam', params, callbacks);
}

/*function registerStudentForExam(examId, studentId) {
  var params = {
    "examID": examId,
    "studentID": studentId
  };

  var callbacks = {
    success: function (response) {
      if(response.success === true) {
        console.log(response);
      }
      else {
        console.log('Error');
      }
    },
    error: function (response) {
      notification(response.message);
    }
  };

  callAjax('post', 'RegisterStudentForExam', params, callbacks);
}*/

function getMyExams() {
  var params = {
    studentID: '' + getAccountProperty('accountID')
  };

  var callbacks = {
    success: function (response) {
      if(response.success === true) {
        console.log(response);
      }
      else {
        console.log('Error');
      }
    },
    error: function (response) {
      notification(response.message);
    }
  };

  callAjax('get', 'MyExams', params, callbacks);
}

function getGraderExamDetails(examId) {
  var params = {
    graderID: '' + getAccountProperty('accountID'),
    examID: examId
  };

  var callbacks = {
    success: function (response) {
      if(response.success === true) {
        console.log(response);
      }
      else {
        console.log('Error');
      }
    },
    error: function (response) {
      notification(response.message);
    }
  };

  callAjax('get', 'GraderAssignedExamDetails', params, callbacks);
}

function createAccount(id, firstName, lastName, email, type) {
  var params = {
    id: id,
    firstName: firstName,
    lastName: lastName,
    email: email,
    type: type
  };

  var callbacks = {
    success: function (response) {
      if(response.success === true) {
        notification('Successfully created ' + firstName + ' ' + lastName + '.', 'success');
      }
      else {
        notification(response.message);
      }
    },
    error: function (response) {
      notification(response.message);
    }
  };

  callAjax('post', 'CreateAccount', params, callbacks);
}

function createAccounts(accounts) {
  var params = {
    accounts: accounts || []
  };

  var callbacks = {
    success: function (response) {
      if(response.success === true) {
        notification('Successfully created ' + params.accounts.length + ' accounts.', 'success');
      }
      else {
        notification(response.message);
      }
    },
    error: function (response) {
      notification(response.message);
    }
  };

  callAjax('post', 'CreateAccounts', params, callbacks);
}

function getLocations() {
  var params = {};

  var callbacks = {
    success: function (response) {
      if(response.success === true) {
        console.log(response);
      }
      else {
        console.log('Error');
      }
    },
    error: function (response) {
      notification(response.message);
    }
  };

  callAjax('get', 'Locations', params, callbacks);
}

function getLocation(locationId) {
  var params = {
    id: locationId
  };

  var callbacks = {
    success: function (response) {
      if(response.success === true) {
        console.log(response);
      }
      else {
        console.log('Error');
      }
    },
    error: function (response) {
      notification(response.message);
    }
  };

  callAjax('get', 'Location', params, callbacks);
}

function getRooms() {
  var params = {};

  var callbacks = {
    success: function (response) {
      if(response.success === true) {
        console.log(response);
      }
      else {
        console.log('Error');
      }
    },
    error: function (response) {
      notification(response.message);
    }
  };

  callAjax('get', 'Rooms', params, callbacks);
}

function getRoom(roomId) {
  var params = {
    id: roomId
  };

  var callbacks = {
    success: function (response) {
      if(response.success === true) {
        console.log(response);
      }
      else {
        console.log('Error');
      }
    },
    error: function (response) {
      notification(response.message);
    }
  };

  callAjax('get', 'Room', params, callbacks);
}

//</editor-fold>

//<editor-fold desc="DOM Utility Functions">

/**
 * A function to update the value of an element by id
 *
 * @param elementId
 * @param value
 */
function updateValue(elementId, value) {
  $('#' + elementId).text(value);
}

/**
 * A function to update the onClick attribute of an element by id
 * to be equal to the function signature
 *
 * @param elementId the id attribute of the ele you wish to update
 * @param signature the function signature to be passed to the onClick attr
 */
function updateOnClick(elementId, signature) {
  updateAttribute(elementId, 'onclick', signature);
}

function updateClass(elementId, cssClass) {
  updateAttribute(elementId, 'class', cssClass);
}

function updateAttribute(elementId, attr, value) {
  $('#' + elementId).attr(attr, value);
}

function addRowToTable(tableElementId, tdCollection) {
  var html = '<tr id="row-' + _.first(tdCollection) + '">';
  _.each(tdCollection, function(currentTd) {
    html += ('<td>' + currentTd + '</td>');
  });
  html += '</tr>';

  $('#' + tableElementId).append(html);
}

function removeRowFromTable(tableElementId, rowElementId) {
  $('#' + tableElementId + ' tr#' + rowElementId).remove();
}

/**
 * A function to display a notification message on the window using bootstrap
 * styles
 *
 * @param message The message to display in the notification window
 * @param type success||info||warning||danger
 * @param url an option url to redirect the user to when clicked
 * @param canClose a boolean that, if false, is not able to be exited out of
 */
function notification(message, type, url, canClose) {
  var notificationMessage = message || 'Unable to generate notification message';
  var notificationType = type || 'danger';
  var notificationUrl = url || '';
  var notificationCanClose = canClose || true;

  var options = {
    icon: 'glyphicon glyphicon-ok',
    message: notificationMessage,
  };

  var settings = {
    type: notificationType,
    allow_dismiss: notificationCanClose,
    placement: {
      from: "top",
      align: "right"
    },
    offset: 20,
    spacing: 10,
    z_index: 1031,
    delay: 5000,
    timer: 1000,
    animate: {
      enter: 'animated fadeInDown',
      exit: 'animated fadeOutUp'
    },
    onShow: null,
    onShown: null,
    onClose: null,
    onClosed: null,
    icon_type: 'class'
  };

  if(notificationUrl.length > 0) {
    options.url = notificationUrl;
    options.target = '_blank';
    settings.url_target = '_blank'
  }

  $.notify(options, settings);
}

/**
 * A function that will add a css class called "active" to any links that
 * match the current window.location.href
 */
function activeLink () {
  var url = window.location.href.split('/');
  url = url[url.length - 1].split('?')[0];

  $('ul.nav a[href="' + url + '"]').parent().addClass('active');

  var targetUrl = $('ul.nav a').filter(function () {
    return url.indexOf(this.href);
  }).first();

  targetUrl.parent().addClass('active').parent().parent().addClass('active');
}

$.fn.getComments = function () {
  // https://stackoverflow.com/questions/22562113/read-html-comments-with-js-or-jquery
  return this.contents().map(function () {
    if (this.nodeType === 8) return this.nodeValue;
  }).get();
}

function setDateTimePicker (elementId, options, selectDateCallbackFunction) {
  selectDateCallbackFunction = selectDateCallbackFunction || function (ct, $i) {};

  /*
   * more options => https://xdsoft.net/jqplugins/datetimepicker
   * To allow selectable dates prior to the current date, set minDate to false
   */
  var defaultOptions = {
    lazyInit: true,
    value: null,
    lang: 'en',
    format: 'Y/m/d H:i',
    formatDate: 'Y/m/d',
    formatTime: 'H:i',
    validateOnBlur: true,
    timepicker: true,
    datepicker: true,
    minDate: 0,
    minTime: 0,
    todayButton: true,
    defaultSelect: true,
    allowBlank: false,
    onSelectDate: selectDateCallbackFunction
  }
  options = options || defaultOptions;

  if(!_.has(options, 'onSelectDate')) {
    options.onSelectDate = selectDateCallbackFunction;
  }

  jQuery('#' + elementId).datetimepicker(options);
}

//</editor-fold>