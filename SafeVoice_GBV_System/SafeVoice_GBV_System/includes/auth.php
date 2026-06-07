<?php
require_once __DIR__ . '/helpers.php';

function require_login() {
    if (!current_user()) {
        flash('danger', 'Please login to continue.');
        redirect_to('login.php');
    }
}

function require_admin() {
    if (!current_user() || current_user()['role'] !== 'admin') {
        flash('danger', 'Administrator access is required.');
        redirect_to('../login.php');
    }
}

function require_user_role() {
    if (!current_user() || current_user()['role'] !== 'user') {
        flash('danger', 'User access is required.');
        redirect_to('login.php');
    }
}
?>
