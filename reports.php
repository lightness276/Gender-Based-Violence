<?php
require_once 'config/db.php';
require_once 'includes/auth.php';
require_user_role();

$user_id = current_user()['id'];
$stmt = $conn->prepare('SELECT * FROM reports WHERE user_id = ? ORDER BY created_at DESC');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$reports = $stmt->get_result();

$page_title = 'My Reports - SafeVoice';
include 'includes/header.php';
?>
<div class="layout">
    <?php include 'includes/sidebar.php'; ?>
    <main class="content">
        <div class="topbar">
            <div class="page-title">
                <h1>My Reports</h1>
                <p>Track report status and view submitted details.</p>
            </div>
            <a class="btn" href="report_new.php">New Report</a>
        </div>
        <?php show_flash(); ?>
        <section class="panel table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Report ID</th>
                        <th>Incident Type</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($report = $reports->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo e($report['id']); ?></td>
                            <td><?php echo e($report['incident_type']); ?></td>
                            <td><?php echo e($report['location']); ?></td>
                            <td><?php echo e($report['incident_date']); ?></td>
                            <td><span class="badge <?php echo e(strtolower(str_replace(' ', '-', $report['status']))); ?>"><?php echo e($report['status']); ?></span></td>
                            <td><?php echo e($report['created_at']); ?></td>
                            <td><a class="btn secondary" href="report_view.php?id=<?php echo e($report['id']); ?>">View</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>
<?php include 'includes/footer.php'; ?>
