<?php
if (isset($_POST['mode'])) {
    $mode = $_POST['mode'];
    session_start();
    $_SESSION['mode'] = $mode;
}
?>