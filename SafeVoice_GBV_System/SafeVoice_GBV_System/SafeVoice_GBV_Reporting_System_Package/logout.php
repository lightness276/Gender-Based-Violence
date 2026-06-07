<?php
require_once 'includes/helpers.php';
session_destroy();
session_start();
flash('success', 'You have logged out successfully.');
redirect_to('login.php');
?>
