function loadScript(src) {
    var script = document.createElement('script');
    script.src = src;
    script.type = 'text/javascript';
    document.head.appendChild(script);
}

var hasChoices = document.querySelectorAll("[data-choices]").length;
var hasFlatpickr = document.querySelectorAll("[data-provider]").length;

if (hasChoices || hasFlatpickr) {
    if (hasChoices) {
        loadScript(window.APP_URL + '/assets/libs/choices.js/public/assets/scripts/choices.min.js');
    }
    if (hasFlatpickr) {
        loadScript(window.APP_URL + '/assets/libs/flatpickr/flatpickr.min.js');
    }
}