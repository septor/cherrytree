<?php
session_start();
header('Content-Type: application/json');
$color = $_SESSION['color'] ?? '#ea9ab2';
echo json_encode(['color' => $color]);