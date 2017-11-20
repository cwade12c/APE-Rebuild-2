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

  return $.ajax(callParameters);
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

  return callAjax('post', 'name', params, callbacks);
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

  return callAjax('get', 'ExamDetails', params, callbacks);
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

  return callAjax('get', 'StudentState', params, callbacks);
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

  return callAjax('post', 'RegisterForExam', params, callbacks);
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

  return callAjax('post', 'RegisterStudentForExam', params, callbacks);
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

  return callAjax('get', 'MyExams', params, callbacks);
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

  return callAjax('get', 'GraderAssignedExamDetails', params, callbacks);
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

  return callAjax('post', 'CreateAccount', params, callbacks);
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

  return callAjax('post', 'CreateAccounts', params, callbacks);
}

function getLocations() {
  var params = {};

  var callbacks = {
    success: function (response) {
      if(response.success === true) {
        return response;
      }
      else {
        console.log('Error');
      }
    },
    error: function (response) {
      notification(response.message);
    }
  };

  return callAjax('get', 'Locations', params, callbacks);
}

function getLocation(locationId) {
  var params = {
    id: locationId
  };

  var callbacks = {
    success: function (response) {
      if(response.success === true) {
        return response;
      }
      else {
        notification(response.message);
      }
    },
    error: function (response) {
      notification(response.message);
    }
  };

  return callAjax('get', 'Location', params, callbacks);
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

  return callAjax('get', 'Rooms', params, callbacks);
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

  return callAjax('get', 'Room', params, callbacks);
}

function getCategories() {
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

  return callAjax('get', 'Categories', params, callbacks);
}

function getCategory(categoryId) {
  var params = {
    id: categoryId
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

  return callAjax('get', 'Category', params, callbacks);
}

function getDefaultCategories() {
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

  return callAjax('get', 'DefaultCategories', params, callbacks);
}

function getReportTypes() {
  var params = {};

  var callbacks = {
    success: function (response) {
      if(response.success === true) {
        console.log(response);
      }
      else {
        notification(response.message);
      }
    },
    error: function (response) {
      notification(response.message);
    }
  };

  return callAjax('get', 'ReportTypes', params, callbacks);
}

function generateReport(examId, reportTypes) {
  var params = {
    examID: examId,
    types: reportTypes || []
  };

  var callbacks = {
    success: function (response) {
      if(response.success === true) {
        notification('Successfully generated the report!', 'success');
        console.log(response);
      }
      else {
        notification(response.message);
      }
    },
    error: function (response) {
      notification(response.message);
    }
  };

  return callAjax('post', 'GenerateReport', params, callbacks);
}

function getReports() {
  var params = {};

  var callbacks = {
    success: function (response) {
      if(response.success === true) {
        console.log(response);
      }
      else {
        notification(response.message);
      }
    },
    error: function (response) {
      notification(response.message);
    }
  };

  return callAjax('get', 'Reports', params, callbacks);
}

function updateRoom(roomId, roomName, seatCount) {
  var params = {
    id: roomId,
    name: roomName,
    seats: seatCount
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

  return callAjax('post', 'UpdateRoom', params, callbacks);
}

function updateCategory(categoryId, categoryName, points) {
  var params = {
    id: categoryId,
    name: categoryName,
    points: points
  };

  var callbacks = {
    success: function (response) {
      if(response.success === true) {
        notification('Successfully updated the category "' + params.name + '".', 'success');
      }
      else {
        notification(response.message);
      }
    },
    error: function (response) {
      notification(response.message);
    }
  };

  return callAjax('post', 'UpdateCategory', params, callbacks);
}

function updateDefaultCategories(categoryIdList) {
  var params = {
    idList: categoryIdList || []
  };

  var callbacks = {
    success: function (response) {
      if(response.success === true) {
        notification('Successfully updated the default categories with IDs [' + params.idList.join() + '].', 'success');
      }
      else {
        notification(response.message);
      }
    },
    error: function (response) {
      notification(response.message);
    }
  };

  return callAjax('post', 'UpdateDefaultCategories', params, callbacks);
}

function updateLocationName(locationId, locationName) {
  var params = {
    id: locationId,
    name: locationName
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

  return callAjax('post', 'UpdateLocationName', params, callbacks);
}

function updateLocationRooms(locationId, reservedSeats, limitedSeats, rooms) {
  var params = {
    id: locationId,
    seatsReserved: reservedSeats,
    limitedSeats: limitedSeats,
    rooms: rooms || []
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

  return callAjax('post', 'UpdateLocationRooms', params, callbacks);
}

function createLocation(name, reservedSeats, limitedSeats, rooms) {
  var params = {
    name: name,
    seatsReserved: reservedSeats,
    limitedSeats: limitedSeats,
    rooms: rooms || [
      {
        id: 1,
        seats: 33
      },
      {
        id: 2,
        seats: 33
      }
    ]
  };

  var callbacks = {
    success: function (response) {
      if(response.success === true) {
        notification('Successfully created Location "' + params.name + '".');
      }
      else {
        console.log('Error');
      }
    },
    error: function (response) {
      notification(response.message);
    }
  };

  return callAjax('post', 'CreateLocation', params, callbacks);
}

function createRoom(name, seats) {
  var params = {
    name: name,
    seats: seats
  };

  var callbacks = {
    success: function (response) {
      if(response.success === true) {
        notification('Successfully created Room "' + params.name + '".', 'success');
      }
      else {
        notification(response.message);
      }
    },
    error: function (response) {
      notification(response.message);
    }
  };

  return callAjax('post', 'CreateRoom', params, callbacks);
}

function createReport(reportName, reportTypes) {
  var params = {
    name: reportName,
    rows: reportTypes
  };

  var callbacks = {
    success: function (response) {
      if(response.success === true) {
        notification('Successfully created Report "' + params.name + '".', 'success');
        console.log(response);
      }
      else {
        notification(response.message);
      }
    },
    error: function (response) {
      notification(response.message);
    }
  };

  return callAjax('post', 'CreateReport', params, callbacks);
}

function updateReport(reportId, reportName, reportTypes) {
  var params = {
    id: reportId,
    name: reportName,
    rows: reportTypes
  };

  var callbacks = {
    success: function (response) {
      if(response.success === true) {
        notification('Successfully updated Report "' + params.name + '".', 'success');
        console.log(response);
      }
      else {
        notification(response.message);
      }
    },
    error: function (response) {
      notification(response.message);
    }
  };

  return callAjax('post', 'UpdateReport', params, callbacks);
}


function createCategory(name, points) {
  var params = {
    name: name,
    points: points
  };

  var callbacks = {
    success: function (response) {
      if(response.success === true) {
        notification('Successfully created Category "' + params.name + '".', 'success');
      }
      else {
        notification(response.message);
      }
    },
    error: function (response) {
      notification(response.message);
    }
  };

  return callAjax('post', 'CreateCategory', params, callbacks);
}

function deleteLocation(locationId) {
  var params = {
    id: locationId,
  };

  var callbacks = {
    success: function (response) {
      if(response.success === true) {
        notification('Successfully deleted the Location with id of ' + params.id, 'success');
      }
      else {
        notification(response.message);
      }
    },
    error: function (response) {
      notification(response.message);
    }
  };

  return callAjax('post', 'DeleteLocation', params, callbacks);
}

function deleteRoom(roomId) {
  var params = {
    id: roomId,
  };

  var callbacks = {
    success: function (response) {
      if(response.success === true) {
        notification('Successfully deleted the Room with id of ' + params.id, 'success');
      }
      else {
        notification(response.message);
      }
    },
    error: function (response) {
      notification(response.message);
    }
  };

  return callAjax('post', 'DeleteRoom', params, callbacks);
}

function deleteCategory(categoryId) {
  var params = {
    id: categoryId,
  };

  var callbacks = {
    success: function (response) {
      if(response.success === true) {
        notification('Successfully deleted the Category with id of ' + params.id, 'success');
      }
      else {
        notification(response.message);
      }
    },
    error: function (response) {
      notification(response.message);
    }
  };

  return callAjax('post', 'DeleteCategory', params, callbacks);
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

function addOptionToSelect(selectElementId, optionCollection) {
  var options = [];
  _.each(optionCollection, function (currentOption) {
    options.push('<option value="' + currentOption.value + '">' + currentOption.text + '</option>');
  })
  $('#' + selectElementId).html(options.join(''));
}

function getElementValue(elementId, type) {
  var value = $('#' + elementId).val();
  if(type === 'integer') {
    return parseInt(value);
  }
  return value;
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
};

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
    format: 'Y-m-d H:i:s',
    formatDate: 'Y-m-d',
    formatTime: 'H:i:s',
    validateOnBlur: true,
    timepicker: true,
    datepicker: true,
    minDate: 0,
    minTime: 0,
    todayButton: true,
    defaultSelect: true,
    allowBlank: false,
    onSelectDate: selectDateCallbackFunction
  };
  options = options || defaultOptions;

  if(!_.has(options, 'onSelectDate')) {
    options.onSelectDate = selectDateCallbackFunction;
  }

  jQuery('#' + elementId).datetimepicker(options);
}

/**
 * Initializes a table as a DataTable (sorting, downloading, filtering,
 * selecting)
 * @param tableId The id attribute associated with the target table
 * @param dataTableOptions A JSON object of DataTable options
 * @param overrideOptions If true, will override all default options
 */
function initializeDataTableById (tableId, dataTableOptions, overrideOptions) {
  var dataTable;

  if(!dataTableOptions || !_.isObject(dataTableOptions)) {
    dataTable = $('#' + tableId).DataTable(getDataTableOptions());
  }
  else {
    if(overrideOptions) {
      dataTable = $('#' + tableId).DataTable(dataTableOptions);
    }
    else {
      var customOptions = getDataTableOptions();
      customOptions = _.extend(customOptions, dataTableOptions);
      dataTable =  $('#' + tableId).DataTable(customOptions);
    }
  }
  dataTable.on('select', function() {
    toggleSelectorActions(true);
  });
  dataTable.on('deselect', function() {
    toggleSelectorActions(false);
  });
  return dataTable;
}

function isRowSelected (dataTableReference) {
  return dataTableReference.rows('.selected').any();
}

function getSelectedRowByIndex (dataTableReference, columnIndex) {
  if(isRowSelected(dataTableReference)) {
    return dataTableReference.row('.selected').data()[columnIndex];
  }
  return null;
}

/**
 * Internal function to get the default DataTable options
 * @returns {{columnDefs: [*], select: string, dom: string, buttons: [*,*,*,*,*]}}
 */
function getDataTableOptions () {
  var getFileName = function() {
    var d = new Date();
    var n = d.getTime();
    return 'export' + n;
  };

  return {
    columnDefs: [
      {
        'className': 'dt-center', 'targets': '_all'
      }
    ],
    select: 'single',
    dom: 'Bfrtip',
    buttons: [
      {
        extend: 'copy',
        text: '<i class="fa fa-files-o"></i> Copy',
        filename: getFileName()
      },
      {
        extend: 'csv',
        text: '<i class="fa fa-file-text-o"></i> CSV',
        filename: getFileName()
      },
      {
        extend: 'excel',
        text: '<i class="fa fa-file-excel-o"></i> XLSX',
        filename: getFileName()
      },
      {
        extend: 'pdf',
        text: '<i class="fa fa-file-pdf-o"></i> PDF',
        filename: getFileName()
      },
      {
        extend: 'print',
        text: '<i class="fa fa-print"></i> Print'
      }
    ],
    pageLength: 10
  };
}

/**
 * Enable or disable all action buttons that have a className of "action-select"
 * @param enabled A boolean that determines if the buttons are enabled/disabled
 */
function toggleSelectorActions (enabled) {
  var selectorActions = document.getElementsByClassName('action-select');
  _.each(selectorActions, function(currentSelectorAction) {
    if(enabled) {
      currentSelectorAction.classList.remove('disabled');
      currentSelectorAction.disabled = false;
    }
    else {
      currentSelectorAction.classList.add('disabled');
      currentSelectorAction.disabled = true;    }
  });
}

function setConfirmationModal (elementIdToWatch, deleteCallback, cancelCallback) {
  var deleteCallbackFnc = deleteCallback || function () {};
  var cancelCallbackFnc = cancelCallback || function () {};

  $('#' + elementIdToWatch).bootstrap_confirm_delete({
    heading: 'WARNING',
    message: 'Are you sure that you want to delete the selected item?',
    btn_ok_label: 'Delete',
    btn_cancel_label: 'Cancel',
    delete_callback: deleteCallbackFnc,
    cancel_callback: cancelCallbackFnc
  });
}

function loadModal (templateUrl, modalId) {
  $('.modal-content').load(templateUrl, function() {
    $('#' + modalId).modal({show:true});
  });
}

//</editor-fold>