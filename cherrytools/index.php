<?php $tool = isset($_GET['tool']) ? $_GET['tool'] : 'quick'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CherryTree Tools</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body class="normalMode">
    <header>
        <h1>CherryTree Tools</h1>
        <div class="button-container">
            <button id="themeToggle">To Banshen Mode</button>
            <button id="colorToggle">Swap to Pink</button>
        </div>
    </header>
    <nav>
        <span>Cherry</span>
        <button id="cherryQuickButton"<?= $tool === 'quick' ? ' class="active"' : ''; ?>>Quick</button>
        <button id="cherryHuntsButton"<?= $tool === 'hunts' ? ' class="active"' : ''; ?>>Hunts</button>
        <button id="cherryRaresButton"<?= $tool === 'rares' ? ' class="active"' : ''; ?>>Rares</button>
    </nav>
    <main>

        <div id="cherryQuick" class="tool<?= $tool === 'quick' ? ' active' : ''; ?>">
            <h2>CherryQuick</h2>
            <p>All data can be entered at once, or by itself. Output will include everything. Quest level(s) require a quest line. Quest lines do not require quest levels.</p>
            <label for="baseCampData">Base Camp Level(s):</label>
            <input type="text" id="baseCampData" placeholder="various (1,8,49) or list (1-3)">
            <br><br>
            <label for="questLine">Quest Line:</label>
            <select id="questLine"></select><br>
            <label for="questLevels">Quest Level(s) (optional):</label>
            <input type="text" id="questLevels" placeholder="various (1,8,20) or list (1-3)">
            <br>
            <button onclick="fetchData()">Fetch It!</button>
        </div>

        <div id="cherryHunts" class="tool<?= $tool === 'hunts' ? ' active' : ''; ?>">
            <h2>CherryHunts</h2>
            <p>If you're working on your treasure scrolls, you'll realize all methods for hunting down the answers suck. Try this instead!</p>
            <input type="text" id="treasureHint" placeholder="Start typing your treasure scroll hint...">
        </div>

        <div id="cherryRares" class="tool<?= $tool === 'rares' ? ' active' : ''; ?>">
            <h2>CherryRares</h2>
            <div id="modifiers">
                <div class="checkbox-container">
                    <div class="checkbox-item">
                        <input type="checkbox" id="maxedWishingWell">
                        <label for="maxedWishingWell">Maxed Wishing Well</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" id="hasBaseCamp65">
                        <label for="hasBaseCamp65">Have Base Camp 65</label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" id="hasRingOfSecrets">
                        <label for="hasRingOfSecrets">Have Ring of Secrets Equipped</label>
                    </div>
                </div>
            </div>

            <p>Type in a rare item below, and you'll be presented with how to get it and it's drop rate. The list is sorted by best to worst drop rate.</p>
            <input type="text" id="rareItem" placeholder="Start typing your rare item...">
        </div>

        <div id="results"></div>
        
    </main>
    <script src="script.js"></script>
</body>
</html>
