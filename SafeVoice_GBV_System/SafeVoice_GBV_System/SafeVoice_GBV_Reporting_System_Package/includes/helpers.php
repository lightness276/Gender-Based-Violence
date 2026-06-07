<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function redirect_to($path) {
    header('Location: ' . $path);
    exit;
}

function flash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function show_flash() {
    if (!isset($_SESSION['flash'])) {
        return;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    echo '<div class="alert alert-' . e($flash['type']) . '">' . e($flash['message']) . '</div>';
}

function current_user() {
    return $_SESSION['user'] ?? null;
}
?>
