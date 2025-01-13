<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $color = $_POST['color'] ?? '#ea9ab2';
    $_SESSION['color'] = $color;
    echo json_encode(['success' => true]);
}