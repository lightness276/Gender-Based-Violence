<?php
require_once 'includes/helpers.php';

if (current_user()) {
    if (current_user()['role'] === 'admin') {
        redirect_to('admin/dashboard.php');
    }
    redirect_to('dashboard.php');
}

redirect_to('login.php');
?>
