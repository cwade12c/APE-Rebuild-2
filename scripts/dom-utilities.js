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