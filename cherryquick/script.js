function fetchData() {
    var data = $('#inputData').val();
    var questLine = $('#questLine').val();
    var questLevels = $('#questLevels').val();
    $.ajax({
        url: 'fetch.php',
        type: 'GET',
        data: { 
            data: data,
            questLine: questLine,
            questLevels: questLevels 
        },
        success: function(response) {
            $('#results').html(response);
        }
    });
}

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

    $.getJSON('https://raw.githubusercontent.com/septor/treefrog/refs/heads/main/data/quests.json', function(data) {
        var questLineSelect = $('#questLine');
        questLineSelect.append($('<option>', {
            value: '',
            text: ''
        }));
        $.each(data, function(key, value) {
            const words = key.split(" ");
            for (let i = 0; i < words.length; i++) {
                words[i] = words[i][0].toUpperCase() + words[i].substr(1);
            }
            questLineSelect.append($('<option>', {
                value: key,
                text: words.join(' ')
            }));
        });
    });
});