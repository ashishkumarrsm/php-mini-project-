(function () {
    var flashes = document.querySelectorAll('.message, .cake-error');
    if (!flashes.length) {
        return;
    }

    window.setTimeout(function () {
        for (var i = 0; i < flashes.length; i++) {
            flashes[i].style.opacity = '0';
            flashes[i].style.transition = 'opacity 0.35s ease';
        }
    }, 3200);
})();
