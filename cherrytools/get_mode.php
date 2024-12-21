<?php
session_start();
if (isset($_SESSION['mode'])) {
    echo json_encode(['mode' => $_SESSION['mode']]);
} else {
    echo json_encode(['mode' => 'normalMode']);
}