function changeLang(lang) {
    lang = lang || localStorage.getItem('app-lang') || 'es';
    localStorage.setItem('app-lang', lang);
    var elmnts = document.querySelectorAll('[data-tr]');

    $.ajax({
        url: 'views/lang/' + lang + '.json',
            type: 'GET',
            dataType: 'JSON',
            success: function (data) {
                for (var i = 0; i < elmnts.length; i++) {
                    elmnts[i].innerHTML = data.hasOwnProperty(lang) ? data[lang][elmnts[i].dataset.tr] : elmnts[i].dataset.tr;
                }
            }
    })
}

$(document).ready(function() {
    changeLang();
    $("#btn-es").on("click", function() {
        changeLang('es')
        });
    $("#btn-en").on("click", function() {
        changeLang('en')
        });
    $("#btn-val").on("click", function() {
        changeLang('val')
    });
});