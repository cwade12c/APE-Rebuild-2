function activeLink () {
    var url = window.location.href.split('/');
    url = url[url.length - 1].split('?')[0];

    $('ul.nav a[href="' + url + '"]').parent().addClass('active');

    var targetUrl = $('ul.nav a').filter(function () {
        return url.indexOf(this.href);
    }).first();

    targetUrl.parent().addClass('active').parent().parent().addClass('active');
}