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

    $.getJSON('https://raw.githubusercontent.com/septor/cherrytree/refs/heads/main/treasures.json', function(data) {
        const searchBox = document.getElementById('searchBox');
        const resultsDiv = document.getElementById('results');
    
        function displayResults(filteredData) {
            resultsDiv.innerHTML = '';
    
            for (const [key, value] of Object.entries(filteredData)) {
                const div = document.createElement('div');
                div.classList.add('result-item');
                div.innerHTML = `<strong>${key}</strong>: ${value}`;
                resultsDiv.appendChild(div);
    
                const br = document.createElement('br');
                resultsDiv.appendChild(br);
            }
        }
    
        searchBox.addEventListener('input', () => {
            const query = searchBox.value.toLowerCase();
    
            if (!query.trim()) {
                resultsDiv.innerHTML = '';
                return;
            }
    
            const filteredData = Object.fromEntries(
                Object.entries(data).filter(([key, value]) =>
                    key.toLowerCase().includes(query) || value.toLowerCase().includes(query)
                )
            );
            displayResults(filteredData);
        });
    });
    
});