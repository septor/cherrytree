<?php
include '_header.php';
$data = $quests;
$all_materials = [];

if(isset($_GET['line'])) {
    $line = urldecode($_GET['line']);
    if(isset($_GET['level'])) {
        $level = $_GET['level'];
        foreach($data[$line][$level] as $item => $amount) {
            if (array_key_exists($item, $all_materials)) {
                $all_materials[$item] += $amount;
            } else {
                $all_materials[$item] = $amount;
            }
        }
    } else {
        foreach($data[$line] as $level) {
            foreach($level as $item => $amount) {
                if (array_key_exists($item, $all_materials)) {
                    $all_materials[$item] += $amount;
                } else {
                    $all_materials[$item] = $amount;
                }
            }
        }
    }
} else {
    foreach($data as $key => $value) {
        foreach($data[$key] as $level) {
            foreach($level as $item => $amount) {
                if (array_key_exists($item, $all_materials)) {
                    $all_materials[$item] += $amount;
                } else {
                    $all_materials[$item] = $amount;
                }
            }
        }
    }
}
?>
<h2>Quest Materials Needed</h2>
<div>
<?php
if(isset($_GET['line'])) {
    echo '<span><a href="?line='.urlencode($_GET['line']).'">ALL</a></span> ';
    foreach($data[urldecode($_GET['line'])] as $category => $level) {   
        echo '<span><a href="?line='.urlencode($_GET['line']).'&level='.$category.'">'.$category.'</a></span> ';
    }
} else {
    foreach($data as $key => $value) {
        echo '<span><a href="?line='.urlencode($key).'">'.$key.'</a></span> ';
    }
}
?>
</div>

<?php
foreach($all_materials as $item => $amount) {
    echo '<p>'.number_format($amount).'x <span class="item">'.$item.'</span></p>';
}
include '_footer.php';