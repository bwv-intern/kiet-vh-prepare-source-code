$(document).ready(function () {
    var currentPath = window.location.pathname;
    var currentHost = window.location.origin;

    $('.nav-link').each(function () {
        var targetHref = $(this).attr('href');

        var targetPath = targetHref.replace(currentHost, '');

        if (currentPath.indexOf(targetPath) != -1) {
            $(this).addClass('active');
        }
    });
});
