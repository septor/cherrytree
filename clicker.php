<?php include '_header.php'; ?>
<h2>AutoClicker Scripts!</h2>
<p>Get your script added here! You can either send me a PM on the CherryTree Discord, or open a new <a href="https://github.com/septor/cherrytree/labels/clickerscript">issue</a> with the clickerscript label.</p>
<p>If you're requesting a new script, be sure to include what AutoClicker it's for and for which platform!</p>
<?php
foreach (glob("clickerscripts\*.txt") as $filename) {
    $data = file_get_contents($filename);
    $content = explode("\n", $data);
    $application = explode("::", $content[1]);
    echo '<div>
    <h3>'.$content[0].' using <a href="'.$application[1].'">'.$application[0].'</a></h3>
    <p class="code"><code>
    '.$content[2].'
    </code>
    </p>
    </div>
    <br>';
}

include '_footer.php';