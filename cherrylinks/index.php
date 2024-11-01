<?php
$websites = explode("\n", file_get_contents('websites.txt'));
$spreadsheets = explode("\n", file_get_contents('spreadsheets.txt'));
$repos = explode("\n", file_get_contents('repos.txt'));

function processData($title, $dataList) {
    $output = "<div class='link-row'>";
    $output .= "<h2>$title</h2><ul>";
    foreach ($dataList as $data) {
        $dataArray = explode("|||", $data);
        $output .= '<li><a href="'.$dataArray[0].'">'.$dataArray[1].'</a></li>';
    }
    $output .= "</ul></div>";
    return $output;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CherryLinks</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>
</head>
<body class="normal-mode">
    <div class="container">
        <div id="header">
            <p class="title">CherryLinks</p>
            <button id="modeToggle">Click for Banshen Mode</button>
        </div>
        <p>Here are some links to resources, tools, or outside projects that can help expand your CherryTree enjoyment. Something to add? DM/ping me: @notseptor</p>
        
        <?php
        echo processData("Websites", $websites);
        echo processData("Spreadsheets", $spreadsheets);
        echo processData("Repositories", $repos);
        ?>
    </div>
</body>
</html>
