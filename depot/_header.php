<?php
$quests = json_decode(file_get_contents("https://raw.githubusercontent.com/septor/cherrytree/refs/heads/main/quests.json"), true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CherryTree Depot</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<header>
    <div class="menu-toggle" onclick="toggleMenu()"><i class="fas fa-bars"></i></div>
    <h1>CherryTree Depot</h1>
</header>
<div class="container">
    <nav>
        <ul>
            <li><a href="./">Home</a></li>
            <li><a href="base.php">Base Camp Materials</a></li>
                    <li>
                        <a href="#">Quests Materials</a>
                        <ul>
                            <li><a href="quests.php">All Materials</a></li>
                            <?php
                            foreach($quests as $key => $value) {
                                echo '<li><a href="quests.php?line='.urlencode($key).'">'.$key.'</a>';
                            }
                            ?>
                        </ul>
                    </li>
        </ul>
    </nav>
    <div class="content">