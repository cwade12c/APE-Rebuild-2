// <editor-fold desc="Constants">

// account type constants
function ACCOUNT_TYPE_NONE() {
    return 0;
}

function ACCOUNT_TYPE_TEMP() {
    return 1;
}

function ACCOUNT_TYPE_STUDENT() {
    return 2;
}

function ACCOUNT_TYPE_GRADER() {
    return 4;
}

function ACCOUNT_TYPE_TEACHER() {
    return 8;
}

function ACCOUNT_TYPE_ADMIN() {
    return 16;
}

/**
 * To parse account type string to actual value
 * @param str
 * @returns {undefined}
 */
function parseAccountType(str) {
    switch (str) {
        case 'ACCOUNT_TYPE_NONE':
            return ACCOUNT_TYPE_NONE();
        case 'ACCOUNT_TYPE_TEMP':
            return ACCOUNT_TYPE_TEMP();
        case 'ACCOUNT_TYPE_STUDENT':
            return ACCOUNT_TYPE_STUDENT();
        case 'ACCOUNT_TYPE_GRADER':
            return ACCOUNT_TYPE_GRADER();
        case 'ACCOUNT_TYPE_TEACHER':
            return ACCOUNT_TYPE_TEACHER();
        case 'ACCOUNT_TYPE_ADMIN':
            return ACCOUNT_TYPE_ADMIN();
        default:
            return undefined;
    }
}

// exam state constants

function EXAM_STATE_INVALID() {
    return 0;
}

function EXAM_STATE_HIDDEN() {
    return 1;
}

function EXAM_STATE_OPEN() {
    return 2;
}

function EXAM_STATE_CLOSED() {
    return 3;
}

function EXAM_STATE_IN_PROGRESS() {
    return 4;
}

function EXAM_STATE_GRADING() {
    return 5;
}

function EXAM_STATE_FINALIZING() {
    return 6;
}

function EXAM_STATE_ARCHIVED() {
    return 7;
}

function examStateAllowed(state, allowed) {
    return (state > EXAM_STATE_INVALID()) && ($.inArray(state, allowed) > -1);
}

function examStateCanEdit(state) {
    var allowedStates = [EXAM_STATE_HIDDEN(), EXAM_STATE_OPEN(), EXAM_STATE_CLOSED(),
        EXAM_STATE_IN_PROGRESS(), EXAM_STATE_GRADING(), EXAM_STATE_FINALIZING(), EXAM_STATE_ARCHIVED()];
    return examStateAllowed(state, allowedStates);
}

function examStateCanAssign(state) {
    var allowedStates = [EXAM_STATE_HIDDEN(), EXAM_STATE_OPEN(), EXAM_STATE_CLOSED(),
        EXAM_STATE_IN_PROGRESS(), EXAM_STATE_GRADING()];
    return examStateAllowed(state, allowedStates);
}

function examStateCanRegister(state) {
    var allowedStates = [EXAM_STATE_HIDDEN(), EXAM_STATE_OPEN(), EXAM_STATE_CLOSED(),
        EXAM_STATE_IN_PROGRESS(), EXAM_STATE_GRADING()];
    return examStateAllowed(state, allowedStates);
}

// Constants for exam search

// get exam states
function GET_EXAMS_ALL() {
    return 0;
}

function GET_EXAMS_OPEN() {
    return 1;
}

function GET_EXAMS_UPCOMING() {
    return 2;
}

function GET_EXAMS_GRADING() {
    return 3;
}

function GET_EXAMS_FINALIZING() {
    return 4;
}

function GET_EXAMS_NON_ARCHIVED() {
    return 5;
}

function GET_EXAMS_ARCHIVED() {
    return 6;
}

// get exam types
function GET_EXAMS_TYPE_BOTH() {
    return 0;
}

function GET_EXAMS_TYPE_REGULAR() {
    return 1;
}

function GET_EXAMS_TYPE_IN_CLASS() {
    return 2;
}

/**
 * Parses exam search states string to constants value
 * @param str
 * @returns {undefined}
 */
function parseGetExamStates(str) {
    switch (str) {
        case 'GET_EXAMS_ALL':
            return GET_EXAMS_ALL();
        case 'GET_EXAMS_OPEN':
            return GET_EXAMS_OPEN();
        case 'GET_EXAMS_UPCOMING':
            return GET_EXAMS_UPCOMING();
        case 'GET_EXAMS_GRADING':
            return GET_EXAMS_GRADING();
        case 'GET_EXAMS_FINALIZING':
            return GET_EXAMS_FINALIZING();
        case 'GET_EXAMS_NON_ARCHIVED':
            return GET_EXAMS_NON_ARCHIVED();
        case 'GET_EXAMS_ARCHIVED':
            return GET_EXAMS_ARCHIVED();
        default:
            return undefined;
    }
}

/**
 * Parses exam search types string to constants value
 * @param str
 * @returns {undefined}
 */
function parseGetExamsType(str) {
    switch (str) {
        case 'GET_EXAMS_TYPE_BOTH':
            return GET_EXAMS_TYPE_BOTH();
        case 'GET_EXAMS_TYPE_REGULAR':
            return GET_EXAMS_TYPE_REGULAR();
        case 'GET_EXAMS_TYPE_IN_CLASS':
            return GET_EXAMS_TYPE_IN_CLASS();
        default:
            return undefined;
    }
}

// </editor-fold>


// <editor-fold desc="API Functions">

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
            errorLine('Ajax call error');
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
            notification(response.message);
            errorLine('API Failed, message: ' + response.message);
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
        return properties[propertyName];
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

function getUpcomingExams() {
    var params = {};

    return callAPI('UpcomingExams', params);
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

function getAccounts() {
    var params = {};

    return callAPI('Accounts', params);
}

function getAccountDetails(accountID) {
    var params = {
        accountID: accountID
    };

    return callAPI('AccountDetails', params);
}

function getAccountInfo(accountID) {
    var params = {
        accountID: accountID
    };

    return callAPI('AccountGeneralInfo', params);
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

function updateAccount(id, firstName, lastName, email, type) {
    var params = {
        id: id,
        firstName: firstName,
        lastName: lastName,
        email: email,
        type: type
    };

    var callbacks = {
        success: function () {
            notification('Successfully updated ' + firstName + ' ' + lastName + '. Please refresh the page to see the changes.', 'success');
        }
    };

    return callAPI('UpdateAccount', params, callbacks);
}

function getLocations() {
    var params = {};

    var callbacks = {
        failure: function (message) {
            errorLineNotification("Locations failed: " + message);
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
            errorLineNotification("Location failed: " + message);
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

    var callbacks = {
        success: function (message, data) {
            if (message === 'OK') {
              notification('Successfully updated the room information! Please refresh the page to view the changes.', 'success');
            }
        }
    };

    return callAPI('UpdateRoom', params, callbacks);
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

    var callbacks = {
        success: function (message, data) {
            if (message === 'OK') {
                notification('Successfully updated the location name! Please refresh the page to view the changes.', 'success');
            }
        }
    };

    return callAPI('UpdateLocationName', params, callbacks);
}

function updateLocationRooms(locationId, reservedSeats, limitedSeats, rooms) {
    var params = {
        id: locationId,
        seatsReserved: reservedSeats,
        limitedSeats: limitedSeats,
        rooms: rooms || []
    };

    var callbacks = {
        success: function (message, data) {
            if (message === 'OK') {
                notification('Successfully updated the location rooms! Please refresh the page to view the changes.', 'success');
            }
        }
    };

    return callAPI('UpdateLocationRooms', params, callbacks);
}

function createLocation(name, reservedSeats, limitedSeats, rooms) {
    var params = {
        name: name,
        seatsReserved: reservedSeats,
        limitedSeats: limitedSeats,
        rooms: rooms
    };

    var callbacks = {
        success: function () {
            notification('Successfully created Location "' + params.name + '".', 'success');
        },
        failure: function () {
            notification()
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

function searchExams(states, types, teacherID, callbacks) {
    if (_.isUndefined(teacherID)) {
        teacherID = null;
    }

    var params = {
        states: states,
        types: types,
        teacherID: teacherID
    };

    return callAPI('ExamSearch', params, callbacks);
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

function addRowToTable(tableElementId, tdCollection, rowID) {
    rowID = rowID || ("row-" + _.first(tdCollection));

    var html = '<tr id="' + rowID + '">';
    _.each(tdCollection, function (currentTd) {
        html += ('<td>' + currentTd + '</td>');
    });
    html += '</tr>';

    $('#' + tableElementId).append(html);
}

function addOptionToSelect(selectElementId, optionCollection) {
    var options = [];
    _.each(optionCollection, function (currentOption) {
        if (currentOption.badgeText) {
            var badgeText = currentOption.badgeText;
            var badgeType = currentOption.badgeType || 'primary';
            options.push('<option data-content="' + currentOption.text + ' <span class=\'label label-' + badgeType + ' pull-right\' style=\'margin-top:3px;margin-right:12px;\'>' + currentOption.badgeText + '</span>" value="' + currentOption.value + '">' + currentOption.text + '</option>');
        }
        else {
            options.push('<option value="' + currentOption.value + '">' + currentOption.text + '</option>');
        }
    });
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
    var notificationMessage = message;
    var notificationType = type || 'danger';
    var notificationUrl = url || '';
    var notificationCanClose = canClose || true;

    if (!notificationMessage) {
        logLine('Unable to generate error message');
        return;
    }

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
    var multiplePermissions = getAccountProperty('multiplePermissions');

    url = url[url.length - 1].split('?')[0];

    $('ul.nav a[href="' + url + '"]').parent().addClass('active-link');

    if(url !== 'home' && multiplePermissions === 'true') {
        $('ul.nav a[href="' + url + '"]').parent().parent().parent().attr('class', 'panel-collapse collapse in');
        $('ul.nav a[href="' + url + '"]').parent().parent().parent().attr('aria-expanded', 'true');
        $('ul.nav a[href="' + url + '"]').parent().parent().parent().parent().prev().addClass('active-link-set');
    }
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
        onSelect: selectDateCallbackFunction
    };
    options = options || defaultOptions;

    if (!_.has(options, 'onSelectDate')) {
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
function initializeDataTableById(tableId, dataTableOptions, overrideOptions) {
    var dataTable;

    if (!dataTableOptions || !_.isObject(dataTableOptions)) {
        dataTable = $('#' + tableId).DataTable(getDataTableOptions());
    }
    else {
        if (overrideOptions) {
            dataTable = $('#' + tableId).DataTable(dataTableOptions);
        }
        else {
            var customOptions = getDataTableOptions();
            customOptions = _.extend(customOptions, dataTableOptions);
            dataTable = $('#' + tableId).DataTable(customOptions);
        }
    }
    dataTable.on('select', function () {
        toggleSelectorActions(true);
    });
    dataTable.on('deselect', function () {
        toggleSelectorActions(false);
    });
    $('.dataTables_filter input').attr("placeholder", "Search all columns");
    return dataTable;
}

function isRowSelected(dataTableReference) {
    return dataTableReference.rows('.selected').any();
}

function getSelectedRowByIndex(dataTableReference, columnIndex) {
    if (isRowSelected(dataTableReference)) {
        return dataTableReference.row('.selected').data()[columnIndex];
    }
    return null;
}

/**
 * Internal function to get the default DataTable options
 * @returns {{columnDefs: [*], select: string, dom: string, buttons: [*,*,*,*,*]}}
 */
function getDataTableOptions() {
    var getFileName = function () {
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
function toggleSelectorActions(enabled) {
    var selectorActions = document.getElementsByClassName('action-select');
    _.each(selectorActions, function (currentSelectorAction) {
        if (enabled) {
            currentSelectorAction.classList.remove('disabled');
            currentSelectorAction.disabled = false;
        }
        else {
            currentSelectorAction.classList.add('disabled');
            currentSelectorAction.disabled = true;
        }
    });
}

/**
 * Sets up a watcher on an elementId such that when the element is clicked,
 * a confirmation will popup asking the user if they wish to delete the item.
 * If the user selects "Delete", then the passed deleteCallback function is
 * invoked. Else, the passed cancelCallback function is invoked.
 * @param elementIdToWatch The id of the element to watch for a click event
 * @param deleteCallback The function to invoke when delete is selected
 * @param cancelCallback The function to invoke when cancel is selected
 */
function setConfirmationModal(elementIdToWatch, deleteCallback, cancelCallback) {
    var deleteCallbackFnc = deleteCallback || function () {
    };
    var cancelCallbackFnc = cancelCallback || function () {
    };

    $('#' + elementIdToWatch).bootstrap_confirm_delete({
        heading: 'WARNING',
        message: 'Are you sure that you want to delete the selected item?',
        btn_ok_label: 'Delete',
        btn_cancel_label: 'Cancel',
        delete_callback: deleteCallbackFnc,
        cancel_callback: cancelCallbackFnc
    });
}

/**
 * Pops a modal based on the fileName of the modal-body and the id of the
 * modal container
 * @param modalName The filename (no extension) of the modal-body located in
 *        ./templates/modals/
 * @param modalId The id associated with the modal container as defined by
 *        {% include 'modals/modal.twig.html' with {'modalId': 'modalName'} %}
 * @param modalParams An optional Object containing parameters that you want
 *        passed so that the modal's Twig template can interpolate them
 */
function loadModal(modalName, modalId, modalParams) {
    var query = 'modalName=' + modalName;

    _.forEach(modalParams, function (value, key) {
        query += '&' + key + '=' + value;
    });

    $('.modal-content').load('api/modal.php?' + query, function () {
        $('#' + modalId).modal({show: true});
    });
}

/**
 * Includes a Twig component with optional parameters and injects it into the
 * element containing the specified elementId
 * @param componentName The filename (no extension) of the component located in ./templates/components/
 * @param elementId The id associated with the element where you want the component to go
 * @param componentParams An optional Object containing parameters that you want passed to the Twig component
 */
function loadComponent(componentName, elementId, componentParams) {
    var query = 'componentName=' + componentName;

    _.forEach(componentParams, function (value, key) {
        query += '&' + key + '=' + value;
    });

    $('#' + elementId).load('api/component.php?' + query);
}

/**
 * Return all of the selected values from a <select multiple></select> ele
 * by id value
 * @param selectId The id value associated with the select element
 * @returns {*|jQuery} An array of option values
 */
function getSelectValues(selectId) {
    return $('#' + selectId).val();
}

/**
 * Set the value of an element based on the element's ID
 * @param elementId The ID attribute of the element whose value you wish to
 *        set/modify
 * @param value The value that you wish to assign to the element
 */
function setElementValueById(elementId, value) {
    $('#' + elementId).val(value);
}

/**
 * Select the default selected values of a select tag that is using the
 * selectpicker class with a multiple attribute
 * @param valueCollection An array of values to be selected
 */
function setSelectPickerValues(valueCollection) {
    $('.selectpicker').selectpicker('val', valueCollection);
}

/**
 * Converts an account type value (int) to its corresponding text value
 * @param accountTypeValue An int that represents the account type
 * @returns {*} A text value that is associated with the accountTypeValue
 */
function accountTypeValueToText(accountTypeValue) {
    switch (accountTypeValue) {
        case 0:
            return 'None';
            break;
        case 1:
            return 'Temporary';
            break;
        case 2:
            return 'Student';
            break;
        case 4:
            return 'Grader';
            break;
        case 5:
            return 'Grader, Temporary';
            break;
        case 6:
            return 'Grader, Student';
            break;
        case 7:
            return 'Grader, Student, Temporary';
            break;
        case 8:
            return 'Teacher';
            break;
        case 9:
            return 'Teacher, Temporary';
            break;
        case 10:
            return 'Teacher,  Student';
            break;
        case 11:
            return 'Teacher, Student, Temporary';
            break;
        case 12:
            return 'Teacher, Grader';
            break;
        case 13:
            return 'Teacher, Grader, Temporary';
            break;
        case 14:
            return 'Teacher, Grader, Student';
            break;
        case 15:
            return 'Teacher, Grader, Student, Temporary';
            break;
        case 16:
            return 'Administrator';
            break;
        case 17:
            return 'Administrator, Temporary';
            break;
        case 18:
            return 'Administrator, Student';
            break;
        case 19:
            return 'Administrator, Student, Temporary';
            break;
        case 20:
            return 'Administrator, Grader';
            break;
        case 21:
            return 'Administrator, Grader, Temporary';
            break;
        case 22:
            return 'Administrator, Grader, Student';
            break;
        case 23:
            return 'Administrator, Grader, Student, Temporary';
            break;
        case 24:
            return 'Administrator, Teacher';
            break;
        case 25:
            return 'Administrator, Teacher, Temporary';
            break;
        case 26:
            return 'Administrator, Teacher, Student';
            break;
        case 27:
            return 'Administrator, Teacher, Student, Temporary';
            break;
        case 28:
            return 'Administrator, Teacher, Grader';
            break;
        case 29:
            return 'Administrator, Teacher, Grader, Temporary';
            break;
        case 30:
            return 'Administrator, Teacher, Grader, Student';
            break;
        case 31:
            return 'Administrator, Teacher, Grader, Student, Temporary';
            break;
        default:
            return 'Unknown';
            break;
    }
}

/**
 * A helper function to initialize a selectPicker on the DOM
 */
function initializeSelectPicker() {
    $('.selectpicker').selectpicker();
}

//<editor-fold>

//</editor-fold desc="Utility functions">

/**
 * used in place of just defining an empty function
 */
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
 * Log helper
 * @param str
 */
function errorLine(str) {
    console.error(str + '\n');
}

/**
 * Helper to log error and notification
 * @param str
 */
function errorLineNotification(str) {
    errorLine(str);
    notification(str);
}

/**
 * Helper function to log line and notification
 * @param str
 */
function logLineNotification(str) {
    logLine(str);
    notification(str, 'info');
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
 * @param value
 * @returns {boolean}
 */
function isValid(value) {
    return typeof value !== 'undefined';
}

/**
 * @param value
 * @returns {boolean}
 */
function isValidInt(value) {
    return isValid(value) && _.isNumber(value) && !isNaN(value);
}

/**
 * Most integer IDs in the system do not allow values <= 0
 * @param variable
 * @returns {boolean}
 */
function isValidIntID(value) {
    return isValidInt(value) && value > 0;
}

function buildExamDatesTimes(start, cutoff, length) {
    var cutoffStr = datetimeString(cutoff);
    var startStr = datetimeString(start);

    var end = new Date(start.getTime() + minutesAsMS(length));
    var endStr = datetimeString(end);

    var timesStr = startStr + ' - ' + endStr + ' (' + length + ' minutes)';

    return {
        cutoff: cutoffStr,
        start: startStr,
        times: timesStr
    };
}

function datetimeString(datetime) {
    var year = datetime.getFullYear();
    var month = datetime.getMonth() + 1;
    var day = datetime.getDate();
    var hours = datetime.getHours();
    var minutes = datetime.getMinutes();

    if (month < 10) {
        month = '0' + month;
    }
    if (day < 10) {
        day = '0' + day;
    }
    if (hours < 10) {
        hours = '0' + hours;
    }
    if (minutes < 10) {
        minutes = '0' + minutes;
    }

    // ':00' for php side
    return year + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':00';
}

function minutesAsMS(min) {
    return (min * (1000 * 60));
}

/**
 * From https://stackoverflow.com/a/7394787
 * To decode HTML strings, from twig usually
 *
 * @param html
 */
function decodeHtml(html) {
    var tempHTML = document.createElement("textarea");
    tempHTML.innerHTML = html;

    var decoded = tempHTML.value;
    tempHTML.remove();

    return decoded;
}

//</editor-fold>