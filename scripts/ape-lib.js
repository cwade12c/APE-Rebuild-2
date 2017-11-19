// <editor-fold desc="API Functions">

var emptyFunction = function () {
};

/**
 * Log helper
 * @param str
 */
function logLine(str) {
    console.log(str + '\n');
}

/**
 * Helper to log line and notification
 * @param str
 */
function logLineNotification(str) {
    logLine(str);
    notification(str);
}

/**
 * Check if property exists in object and return, else return default value
 * @param obj
 * @param property
 * @param defaultValue
 * @returns {*}
 */
function getProperty(obj, property, defaultValue) {
    return obj.hasOwnProperty(property) ? obj[property] : defaultValue;
}

/**
 * Wrapper for an ajax call
 *
 * @param requestType
 * @param parameters
 * @param callBackFunctions Should be js object, accepted functions, each can be function or array of functions
 *                          success
 *                          error
 *                          complete
 * @returns {*}
 */
function callAjax(requestType, parameters, callBackFunctions) {
    requestType = requestType || 'GET';
    callBackFunctions = callBackFunctions || {};

    function convertCallback(fnc) {
        if (!fnc) {
            return [];
        } else if (typeof fnc === 'function') {
            return [fnc];
        } else if (fnc && typeof fnc === 'object' && fnc.constructor === Array) {
            return fnc;
        } else {
            return [];
        }
    }

    function setupCallback(property, defaultFnc) {
        var callback = convertCallback(getProperty(callBackFunctions, property, []));
        callback.unshift(defaultFnc);
        return callback;
    }

    var successFnc = setupCallback('success',
        function (data, status, jqXHR) {
            logLine('Ajax call succeeded');
        }
    );

    var errorFnc = setupCallback('error',
        function (jqXHR, status) {
            logLine('Ajax call error');
        }
    );

    var completeFnc = setupCallback('complete',
        function (jqXHR, status) {
            logLine('Ajax call completed');
        }
    );

    var callParameters = {
        type: requestType.toUpperCase(),
        url: 'api/api.php',
        data: parameters,
        dataType: 'json',
        success: successFnc,
        error: errorFnc,
        complete: completeFnc
    };

    return $.ajax(callParameters);
}

/**
 * Use to call operations
 *
 * @param operationName
 * @param operationParameters
 * @param callBackFunctions
 *          Callbacks that can be set:
 *          success - on ajax.success, and the operation was successful (message, data)
 *          failure - on ajax.success, but operation failed (message)
 *          error - on ajax.error
 *          complete - on ajax.complete
 */
function callAPI(operationName, operationParameters, callBackFunctions) {
    operationName = operationName || '';
    operationParameters = operationParameters || {};
    operationParameters = JSON.stringify(operationParameters);

    var fullParameters = {
        operation: operationName,
        parameters: operationParameters
    };

    callBackFunctions = callBackFunctions || {};

    var successFnc = getProperty(callBackFunctions, 'success', emptyFunction);
    var failureFnc = getProperty(callBackFunctions, 'failure', emptyFunction);

    var success = function (response) {
        if (response.success === true) {
            logLine('API Success, message: ' + response.message);
            logLine('API Success, data: ' + JSON.stringify(response.data));
            successFnc(response.message, response.data);
        } else {
            logLineNotification('API Failed, message: ' + response.message);
            failureFnc(response.message);
        }
    };

    callBackFunctions.failure = undefined;
    callBackFunctions.success = success;

    var errorFnc = getProperty(callBackFunctions, 'error', emptyFunction);

    var error = function () {
        logLine("Operation(" + operationName + ") error");
        errorFnc();
    };

    callBackFunctions.error = undefined;
    callBackFunctions.error = error;

    return callAjax('POST', fullParameters, callBackFunctions);
}

function getAccountProperties() {
    var comments = $('head').getComments();
    var properties = {};
    _.each(comments, function (currentComment) {
        currentComment = currentComment.trim();
        if (currentComment.length) {
            var keyVal = currentComment.split(':');
            properties[keyVal[0].trim()] = keyVal[1].trim();
        }
    });
    return properties;
}

function getAccountProperty(propertyName) {
    var properties = getAccountProperties();
    if (_.has(properties, propertyName)) {
        return properties[propertyName]
    }

    return null;
}

// </editor-fold>

// <editor-fold desc="Operation Functions">

function registerStudentForExam(examId, studentId, examObj) {
    $.post("api/controllers/registerForExam.php", {examId: examId, studentId: studentId}).done(function (response) {
        if (response === '1') {
            notification('Successfully registered for the exam', 'success');
            var elementId = 'exam-' + examId;
            var params = '[';
            _.each(examObj, function (currentProperty, index) {
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
        if (response === '1') {
            notification('Successfully unregistered for the exam', 'warning');
            var elementId = 'exam-' + examId;
            var params = '[';
            _.each(examObj, function (currentProperty, index) {
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

function viewExamDetails(examId) {
    $('.modal-content').load('api/controllers/examDetails.php?examId=' + examId, function () {
        $('#myModal').modal({show: true});
    });
}

function name(name) {
    var params = {
        name: name
    };

    var callbacks = {
        success: function (message, data) {
            notification(data.name, 'success', 'https://google.com', false);
        },
        failure: function (message) {
            notification(message);
        },
        error: function (response) {
            notification(response.message);
        }
    };

    return callAPI('name', params, callbacks);
}

function getExamInformation(examId) {
    var params = {
        id: examId
    };

    return callAPI('ExamDetails', params);
}

function getStudentState(studentId) {
    var params = {
        studentId: studentId
    };

    return callAPI('StudentState', params);
}

function registerForExam(examId) {
    var params = {
        "examID": examId,
        "studentID": getAccountProperty('accountID')
    };

    return callAPI('RegisterForExam', params);
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

    return callAPI('MyExams', params);
}

function getGraderExamDetails(examId) {
    var params = {
        graderID: '' + getAccountProperty('accountID'),
        examID: examId
    };

    return callAPI('GraderAssignedExamDetails', params);
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
        success: function () {
            notification('Successfully created ' + firstName + ' ' + lastName + '.', 'success');
        }
    };

    return callAPI('CreateAccount', params, callbacks);
}

function createAccounts(accounts) {
    var params = {
        accounts: accounts || []
    };

    var callbacks = {
        success: function () {
            notification('Successfully created ' + params.accounts.length + ' accounts.', 'success');
        }
    };

    return callAPI('CreateAccounts', params, callbacks);
}

function getLocations() {
    var params = {};

    var callbacks = {
        failure: function (message) {
            logLineNotification("Locations failed: " + message);
        }
    };

    return callAPI('Locations', params, callbacks);
}

function getLocation(locationId) {
    var params = {
        id: locationId
    };

    var callbacks = {
        failure: function (message) {
            logLineNotification("Location failed: " + message);
        }
    };

    return callAPI('Location', params, callbacks);
}

function getRooms() {
    return callAPI('Rooms');
}

function getRoom(roomId) {
    var params = {
        id: roomId
    };

    return callAPI('Room', params);
}

function getCategories() {
    var params = {};

    return callAPI('Categories', params);
}

function getCategory(categoryId) {
    var params = {
        id: categoryId
    };

    return callAPI('Category', params);
}

function getDefaultCategories() {
    var params = {};

    return callAPI('DefaultCategories', params);
}

function getReportTypes() {
    var params = {};

    return callAPI('ReportTypes', params);
}

function generateReport(examId, reportTypes) {
    var params = {
        examID: examId,
        types: reportTypes || []
    };

    return callAPI('GenerateReport', params);
}

function getReports() {
    var params = {};

    return callAPI('Reports', params);
}

function updateRoom(roomId, roomName, seatCount) {
    var params = {
        id: roomId,
        name: roomName,
        seats: seatCount
    };

    return callAPI('UpdateRoom', params);
}

function updateCategory(categoryId, categoryName, points) {
    var params = {
        id: categoryId,
        name: categoryName,
        points: points
    };

    var callbacks = {
        success: function () {
            notification('Successfully updated the category "' + params.name + '".', 'success');
        }
    };

    return callAPI('UpdateCategory', params, callbacks);
}

function updateDefaultCategories(categoryIdList) {
    var params = {
        idList: categoryIdList || []
    };

    var callbacks = {
        success: function () {
            notification('Successfully updated the default categories with IDs [' + params.idList.join() + '].', 'success');
        }
    };

    return callAPI('UpdateDefaultCategories', params, callbacks);
}

function updateLocationName(locationId, locationName) {
    var params = {
        id: locationId,
        name: locationName
    };

    return callAPI('UpdateLocationName', params);
}

function updateLocationRooms(locationId, reservedSeats, limitedSeats, rooms) {
    var params = {
        id: locationId,
        seatsReserved: reservedSeats,
        limitedSeats: limitedSeats,
        rooms: rooms || []
    };

    return callAPI('UpdateLocationRooms', params);
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
        success: function () {
            notification('Successfully created Location "' + params.name + '".');
        }
    };

    return callAPI('CreateLocation', params, callbacks);
}

function createRoom(name, seats) {
    var params = {
        name: name,
        seats: seats
    };

    var callbacks = {
        success: function () {
            notification('Successfully created Room "' + params.name + '".', 'success');
        }
    };

    return callAPI('CreateRoom', params, callbacks);
}

function createReport(reportName, reportTypes) {
    var params = {
        name: reportName,
        rows: reportTypes
    };

    var callbacks = {
        success: function () {
            notification('Successfully created Report "' + params.name + '".', 'success');
        }
    };

    return callAPI('CreateReport', params, callbacks);
}

function updateReport(reportId, reportName, reportTypes) {
    var params = {
        id: reportId,
        name: reportName,
        rows: reportTypes
    };

    var callbacks = {
        success: function () {
            notification('Successfully updated Report "' + params.name + '".', 'success');
        }
    };

    return callAPI('UpdateReport', params, callbacks);
}


function createCategory(name, points) {
    var params = {
        name: name,
        points: points
    };

    var callbacks = {
        success: function () {
            notification('Successfully created Category "' + params.name + '".', 'success');
        }
    };

    return callAPI('CreateCategory', params, callbacks);
}

function deleteLocation(locationId) {
    var params = {
        id: locationId
    };

    var callbacks = {
        success: function () {
            notification('Successfully deleted the Location with id of ' + params.id, 'success');
        }
    };

    return callAPI('DeleteLocation', params, callbacks);
}

function deleteRoom(roomId) {
    var params = {
        id: roomId
    };

    var callbacks = {
        success: function () {
            notification('Successfully deleted the Room with id of ' + params.id, 'success');
        }
    };

    return callAPI('DeleteRoom', params, callbacks);
}

function deleteCategory(categoryId) {
    var params = {
        id: categoryId
    };

    var callbacks = {
        success: function () {
            notification('Successfully deleted the Category with id of ' + params.id, 'success');
        }
    };

    return callAPI('DeleteCategory', params, callbacks);
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
    _.each(tdCollection, function (currentTd) {
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
    if (type === 'integer') {
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

    if (notificationUrl.length > 0) {
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
function activeLink() {
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

function setDateTimePicker(elementId, options, selectDateCallbackFunction) {
    selectDateCallbackFunction = selectDateCallbackFunction || function (ct, $i) {
    };

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

    if (!_.has(options, 'onSelectDate')) {
        options.onSelectDate = selectDateCallbackFunction;
    }

    jQuery('#' + elementId).datetimepicker(options);
}

//</editor-fold>