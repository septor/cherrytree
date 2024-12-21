<?php
if (isset($_POST['mode'])) {
    session_start();
    $mode = $_POST['mode'];
    $_SESSION['mode'] = $mode;
    echo json_encode(['status' => 'success', 'message' => 'Mode saved successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Mode not provided.']);
}