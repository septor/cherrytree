<?php
include '_header.php';
$data = json_decode(file_get_contents("https://raw.githubusercontent.com/septor/treefrog/main/basecamp.json"), true);
$all_materials = [];

if (isset($_GET['level'])) {
    $level = intval($_GET['level']);
    foreach ($data as $key => $value) {
        if ($key > $level) {
            foreach ($value as $item => $amount) {
                if (array_key_exists($item, $all_materials)) {
                    $all_materials[$item] += $amount;
                } else {
                    $all_materials[$item] = $amount;
                }
            }
        }
    }
} else {
    foreach ($data as $value) {
        foreach ($value as $item => $amount) {
            if (array_key_exists($item, $all_materials)) {
                $all_materials[$item] += $amount;
            } else {
                $all_materials[$item] = $amount;
            }
        }
    }
}

?>
<h2>Base Camp Materials Needed</h2>
<div>
    <?php
    for ($i=0; $i < 50; $i++) { 
        echo '<a class="level" href="?level='.$i.'">'.$i.'</a> ';
    }
    ?>

    <?php
    foreach($all_materials as $item => $amount) {
        echo '<p>'.number_format($amount).'x <span class="item">'.$item.'</span></p>';
    }
    ?>
</div>
<?php include '_footer.php';