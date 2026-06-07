<?php
require_once 'config/db.php';
require_once 'includes/auth.php';
require_user_role();

$user_id = current_user()['id'];

$total = $pending = $review = $resolved = 0;
$stmt = $conn->prepare('SELECT status, COUNT(*) AS total FROM reports WHERE user_id = ? GROUP BY status');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $total += (int)$row['total'];
    if ($row['status'] === 'Pending') {
        $pending = (int)$row['total'];
    } elseif ($row['status'] === 'Under Review') {
        $review = (int)$row['total'];
    } elseif ($row['status'] === 'Resolved') {
        $resolved = (int)$row['total'];
    }
}

$page_title = 'User Dashboard - SafeVoice';
include 'includes/header.php';
?>
<div class="layout">
    <?php include 'includes/sidebar.php'; ?>
    <main class="content">
        <div class="topbar">
            <div class="page-title">
                <h1>User Dashboard</h1>
                <p>Welcome, <?php echo e(current_user()['full_name']); ?>.</p>
            </div>
            <a class="btn" href="report_new.php">Submit Report</a>
        </div>
        <?php show_flash(); ?>
        <section class="grid cards">
            <div class="card"><span>Total Reports</span><strong><?php echo $total; ?></strong></div>
            <div class="card"><span>Pending</span><strong><?php echo $pending; ?></strong></div>
            <div class="card"><span>Under Review</span><strong><?php echo $review; ?></strong></div>
            <div class="card"><span>Resolved</span><strong><?php echo $resolved; ?></strong></div>
        </section>
    </main>
</div>
<?php include 'includes/footer.php'; ?>
