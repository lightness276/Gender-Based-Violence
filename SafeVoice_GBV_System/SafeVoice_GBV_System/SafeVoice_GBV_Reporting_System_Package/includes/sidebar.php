<?php $user = current_user(); ?>
<button class="menu-toggle" type="button" data-menu-toggle>Menu</button>
<aside class="sidebar" data-sidebar>
    <div class="brand">
        <span class="brand-mark">SV</span>
        <div>
            <strong>SafeVoice</strong>
            <small>GBV Reporting</small>
        </div>
    </div>
    <nav>
        <?php if ($user && $user['role'] === 'admin'): ?>
            <a href="../admin/dashboard.php">Dashboard</a>
            <a href="../admin/reports.php">Reports</a>
            <a href="../admin/users.php">Users</a>
            <a href="../logout.php">Logout</a>
        <?php else: ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="report_new.php">Submit Report</a>
            <a href="reports.php">My Reports</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        <?php endif; ?>
    </nav>
</aside>
