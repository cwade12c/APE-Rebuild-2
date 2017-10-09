function post (payload) {
  $.post("promise.php", {name: payload}).done(function (response) {
      notification(response, true);
  });
}

function notification (message, canClose) {
/*    $.notifyDefaults({
        type: 'success',
        allow_dismiss: false,
        placement: {
            from: "top",
            align: "right"
        },
        animate: {
            enter: 'animated fadeInDown',
            exit: 'animated fadeOutUp'
        },
        delay: 0
    });
    $.notify(message);*/

    $.notify({
        // options
        icon: 'glyphicon glyphicon-ok',
        message: message,
        url: 'https://google.com',
        target: '_blank'
    },{
        // settings
        type: "success",
        allow_dismiss: canClose,
        placement: {
            from: "top",
            align: "right"
        },
        offset: 20,
        spacing: 10,
        z_index: 1031,
        delay: 5000,
        timer: 1000,
        url_target: '_blank',
        animate: {
            enter: 'animated fadeInDown',
            exit: 'animated fadeOutUp'
        },
        onShow: null,
        onShown: null,
        onClose: null,
        onClosed: null,
        icon_type: 'class'
    });
}