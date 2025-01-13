const themeToggle = document.getElementById('themeToggle');
const colorToggle = document.getElementById('colorToggle');

const updateThemeButtonText = () => {
    const isBanshenMode = document.body.classList.contains('banshenMode');
    themeToggle.textContent = isBanshenMode ? 'To Normal Mode' : 'To Banshen Mode';
};

const updateColorButtonText = (color) => {
    if (color === '#ea9ab2') {
        colorToggle.textContent = 'Swap to Pink';
    } else if (color === '#5aa9e6') {
        colorToggle.textContent = 'Swap to Blue';
    }
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

const saveColorPreference = (color) => {
    fetch('set_color.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `color=${color}`,
    });
};

const applySavedColor = () => {
    fetch('get_color.php')
        .then((response) => response.json())
        .then((data) => {
            const primaryColor = data.color || '#ea9ab2';
            document.documentElement.style.setProperty('--primary-color', primaryColor);
        })
        .catch((error) => {
            console.error('Error fetching the saved color:', error);
        });
};

colorToggle.addEventListener('click', () => {
    const currentColor = getComputedStyle(document.documentElement).getPropertyValue('--primary-color').trim();
    const newColor = currentColor === '#ea9ab2' ? '#5aa9e6' : '#ea9ab2';
    document.documentElement.style.setProperty('--primary-color', newColor);
    saveColorPreference(newColor);
    updateColorButtonText(newColor);
});

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
applySavedColor();


const cherryQuickButton = document.getElementById('cherryQuickButton');
const cherryHuntsButton = document.getElementById('cherryHuntsButton');
const cherryRaresButton = document.getElementById('cherryRaresButton');
const cherryQuick = document.getElementById('cherryQuick');
const cherryHunts = document.getElementById('cherryHunts');
const cherryRares = document.getElementById('cherryRares');
const resultsDiv = document.getElementById('results');


cherryQuickButton.addEventListener('click', () => {
    cherryQuickButton.classList.add('active');
    cherryHuntsButton.classList.remove('active');
    cherryRaresButton.classList.remove('active');
    cherryQuick.classList.add('active');
    cherryHunts.classList.remove('active');
    cherryRares.classList.remove('active');
    clearResults();
});

cherryHuntsButton.addEventListener('click', () => {
    cherryHuntsButton.classList.add('active');
    cherryQuickButton.classList.remove('active');
    cherryRaresButton.classList.remove('active');
    cherryHunts.classList.add('active');
    cherryQuick.classList.remove('active');
    cherryRares.classList.remove('active');
    clearResults();
});

cherryRaresButton.addEventListener('click', () => {
    cherryRaresButton.classList.add('active');
    cherryHuntsButton.classList.remove('active');
    cherryQuickButton.classList.remove('active');
    cherryRares.classList.add('active');
    cherryHunts.classList.remove('active');
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

    $.getJSON('https://raw.githubusercontent.com/septor/cherrytree/refs/heads/main/rares.json', function(rareData) {
        const rareItem = document.getElementById('rareItem');
    
        function displayResults(filteredData) {
            resultsDiv.innerHTML = '';
    
            for (const [itemName, itemData] of Object.entries(filteredData)) {
                const itemDiv = document.createElement('div');
                itemDiv.classList.add('result-item');

                const benefitHTML = `<h3>${itemName}</h3><p>${itemData.benefit}</p>`;

                let obtainedArray = [];
                for (const [location, rate] of Object.entries(itemData.obtained)) {
                    if (rate.includes('/')) {
                        const [baseRate, wishingWellRate] = rate.split('/').map(num => parseInt(num));
                        const baseRatePercentage = 1 / baseRate * 100;
                        const wishingWellPercentage = 1 / wishingWellRate * 100;
                        obtainedArray.push({
                            location,
                            baseRate,
                            wishingWellRate,
                            baseRatePercentage,
                            wishingWellPercentage
                        });
                    } else {
                        const baseRate = parseInt(rate);
                        const baseRatePercentage = 1 / baseRate * 100;
                        obtainedArray.push({
                            location,
                            baseRate,
                            wishingWellRate: null,
                            baseRatePercentage,
                            wishingWellPercentage: null
                        });
                    }
                }
                obtainedArray.sort((a, b) => b.baseRatePercentage - a.baseRatePercentage);
                let obtainedHTML = '<ul>';
                for (const entry of obtainedArray) {
                    obtainedHTML += `
                        <li>
                            <strong>${entry.location}:</strong>
                            <ul>
                                <li>Base Rate: 1/${entry.baseRate.toLocaleString()} (${entry.baseRatePercentage.toFixed(6)}%)</li>
                                ${
                                    entry.wishingWellRate
                                        ? `<li>Max WW: 1/${entry.wishingWellRate.toLocaleString()} (${entry.wishingWellPercentage.toFixed(6)}%)</li>`
                                        : ''
                                }
                            </ul>
                        </li>`;
                }
                obtainedHTML += '</ul>';
                itemDiv.innerHTML = benefitHTML + obtainedHTML;
    
                resultsDiv.appendChild(itemDiv);
            }
        }
    
        rareItem.addEventListener('input', () => {
            const query = rareItem.value.toLowerCase();
    
            if (!query.trim()) {
                resultsDiv.innerHTML = '';
                return;
            }

            const filteredData = Object.fromEntries(
                Object.entries(rareData).filter(([itemName]) =>
                    itemName.toLowerCase().includes(query)
                )
            );
    
            displayResults(filteredData);
        });
    });
});