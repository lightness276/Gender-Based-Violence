<?php
require_once 'config/db.php';
require_once 'includes/auth.php';
require_user_role();

$report_id = (int)($_GET['id'] ?? 0);
$user_id = current_user()['id'];

$stmt = $conn->prepare('SELECT * FROM reports WHERE id = ? AND user_id = ? LIMIT 1');
$stmt->bind_param('ii', $report_id, $user_id);
$stmt->execute();
$report = $stmt->get_result()->fetch_assoc();

if (!$report) {
    flash('danger', 'Report not found.');
    redirect_to('reports.php');
}

$page_title = 'Report Details - SafeVoice';
include 'includes/header.php';
?>
<div class="layout">
    <?php include 'includes/sidebar.php'; ?>
    <main class="content">
        <div class="topbar">
            <div class="page-title">
                <h1>Report #<?php echo e($report['id']); ?></h1>
                <p>Status: <span class="badge <?php echo e(strtolower(str_replace(' ', '-', $report['status']))); ?>"><?php echo e($report['status']); ?></span></p>
            </div>
            <a class="btn secondary" href="reports.php">Back</a>
        </div>
        <section class="panel">
            <p><strong>Incident Type:</strong> <?php echo e($report['incident_type']); ?></p>
            <p><strong>Location:</strong> <?php echo e($report['location']); ?></p>
            <p><strong>Date of Incident:</strong> <?php echo e($report['incident_date']); ?></p>
            <p><strong>Submitted:</strong> <?php echo e($report['created_at']); ?></p>
            <p><strong>Description:</strong></p>
            <p><?php echo nl2br(e($report['description'])); ?></p>
            <p><strong>Evidence:</strong>
                <?php if ($report['evidence_file']): ?>
                    <a href="<?php echo e($report['evidence_file']); ?>" target="_blank">View Evidence</a>
                <?php else: ?>
                    No evidence uploaded
                <?php endif; ?>
            </p>
        </section>
    </main>
</div>
<?php include 'includes/footer.php'; ?>
