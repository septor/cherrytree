function saveMode(mode) {
    $.ajax({
        url: 'color_mode.php',
        type: 'POST',
        data: { mode: mode }
    });
}

$(document).ready(function() {

    if (localStorage.getItem('mode') === 'normal') {
        $('body').removeClass('banshen-mode').addClass('normal-mode');
        $('#modeToggle').text('Click for Banshen Mode');
    } else {
        $('body').removeClass('normal-mode').addClass('banshen-mode');
        $('#modeToggle').text('Click for Normal Mode');
    }

    $('#modeToggle').click(function() {
        $('body').toggleClass('banshen-mode normal-mode');
        let mode = $('body').hasClass('normal-mode') ? 'normal' : 'banshen';
        let buttonText = mode === 'normal' ? 'Click for Banshen Mode' : 'Click for Normal Mode';
        $('#modeToggle').text(buttonText);
        localStorage.setItem('mode', mode);
        saveMode(mode);
    });
});