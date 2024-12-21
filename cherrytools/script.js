const themeToggle = document.getElementById('themeToggle');

const updateThemeButtonText = () => {
    const isBanshenMode = document.body.classList.contains('banshenMode');
    themeToggle.textContent = isBanshenMode ? 'Switch to Normal Mode' : 'Switch to Banshen Mode';
};

const saveThemeMode = (mode) => {
    fetch('set_mode.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `mode=${mode}`,
    });
};

const applySavedTheme = () => {
    fetch('get_mode.php')
        .then((response) => response.json())
        .then((data) => {
            if (data.mode === 'banshenMode') {
                document.body.classList.add('banshenMode');
                document.body.classList.remove('normalMode');
            } else {
                document.body.classList.add('normalMode');
                document.body.classList.remove('banshenMode');
            }
            updateThemeButtonText();
        })
        .catch((error) => {
            console.error('Error fetching the saved mode:', error);
        });
};

const clearResults = () => {
    resultsDiv.innerHTML = '';
};

function fetchData() {
    var data = $('#baseCampData').val();
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

themeToggle.addEventListener('click', () => {   
    const isBanshenMode = document.body.classList.toggle('banshenMode');
    document.body.classList.toggle('normalMode', !isBanshenMode);

    updateThemeButtonText();

    const currentMode = isBanshenMode ? 'banshenMode' : 'normalMode';
    saveThemeMode(currentMode);
});

applySavedTheme();


const cherryQuickButton = document.getElementById('cherryQuickButton');
const cherryHuntsButton = document.getElementById('cherryHuntsButton');
const cherryQuick = document.getElementById('cherryQuick');
const cherryHunts = document.getElementById('cherryHunts');
const resultsDiv = document.getElementById('results');


cherryQuickButton.addEventListener('click', () => {
    cherryQuickButton.classList.add('active');
    cherryHuntsButton.classList.remove('active');
    cherryQuick.classList.add('active');
    cherryHunts.classList.remove('active');
    clearResults();
});

cherryHuntsButton.addEventListener('click', () => {
    cherryHuntsButton.classList.add('active');
    cherryQuickButton.classList.remove('active');
    cherryHunts.classList.add('active');
    cherryQuick.classList.remove('active');
    clearResults();
});

$(document).ready(function() {
    $.getJSON('https://raw.githubusercontent.com/septor/cherrytree/refs/heads/main/quests.json', function (questData) {
        var questLineSelect = $('#questLine');
        questLineSelect.append($('<option>', {
            value: '',
            text: ''
        }));
        $.each(questData, function (key, value) {
            const numberOfLevels = Object.keys(value).length;

            const words = key.split(" ");
            for (let i = 0; i < words.length; i++) {
                words[i] = words[i][0].toUpperCase() + words[i].substr(1);
            }

            questLineSelect.append($('<option>', {
                value: key,
                text: `${words.join(' ')} (${numberOfLevels})`
            }));
        });
    });

    $.getJSON('https://raw.githubusercontent.com/septor/cherrytree/refs/heads/main/treasures.json', function(huntData) {
        const treasureHint = document.getElementById('treasureHint');
    
        function displayResults(filteredData) {
            resultsDiv.innerHTML = '';
    
            for (const [key, value] of Object.entries(filteredData)) {
                const div = document.createElement('div');
                div.classList.add('result-item');
                div.innerHTML = `<h3>${key}</h3><em>${value}</em>`;
                resultsDiv.appendChild(div);
            }
        }
    
        treasureHint.addEventListener('input', () => {
            const query = treasureHint.value.toLowerCase();
    
            if (!query.trim()) {
                resultsDiv.innerHTML = '';
                return;
            }
    
            const filteredData = Object.fromEntries(
                Object.entries(huntData).filter(([key, value]) =>
                    key.toLowerCase().includes(query) || value.toLowerCase().includes(query)
                )
            );
            displayResults(filteredData);
        });
    });
});