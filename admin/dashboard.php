<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
require_admin();

$stats = [
    'reports' => 0,
    'users' => 0,
    'pending' => 0,
    'review' => 0,
    'resolved' => 0
];

$result = $conn->query('SELECT COUNT(*) AS total FROM reports');
$stats['reports'] = (int)$result->fetch_assoc()['total'];

$result = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role = 'user'");
$stats['users'] = (int)$result->fetch_assoc()['total'];

$result = $conn->query('SELECT status, COUNT(*) AS total FROM reports GROUP BY status');
while ($row = $result->fetch_assoc()) {
    if ($row['status'] === 'Pending') {
        $stats['pending'] = (int)$row['total'];
    } elseif ($row['status'] === 'Under Review') {
        $stats['review'] = (int)$row['total'];
    } elseif ($row['status'] === 'Resolved') {
        $stats['resolved'] = (int)$row['total'];
    }
}

$base_path = '../';
$page_title = 'Admin Dashboard - SafeVoice';
include '../includes/header.php';
?>
<div class="layout">
    <?php include '../includes/sidebar.php'; ?>
    <main class="content">
        <div class="page-title">
            <h1>Admin Dashboard</h1>
            <p>Manage GBV reports, users, and simple system statistics.</p>
        </div>
        <?php show_flash(); ?>
        <section class="grid cards">
            <div class="card"><span>Total Reports</span><strong><?php echo $stats['reports']; ?></strong></div>
            <div class="card"><span>Registered Users</span><strong><?php echo $stats['users']; ?></strong></div>
            <div class="card"><span>Pending</span><strong><?php echo $stats['pending']; ?></strong></div>
            <div class="card"><span>Under Review</span><strong><?php echo $stats['review']; ?></strong></div>
            <div class="card"><span>Resolved</span><strong><?php echo $stats['resolved']; ?></strong></div>
        </section>
    </main>
</div>
<?php include '../includes/footer.php'; ?>
