<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $report_id = (int)($_POST['report_id'] ?? 0);
    $status = $_POST['status'] ?? 'Pending';
    $allowed_statuses = ['Pending', 'Under Review', 'Resolved'];

    if (in_array($status, $allowed_statuses, true)) {
        $stmt = $conn->prepare('UPDATE reports SET status = ? WHERE id = ?');
        $stmt->bind_param('si', $status, $report_id);
        $stmt->execute();
        flash('success', 'Report status updated.');
    }

    redirect_to('reports.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_report'])) {
    $report_id = (int)($_POST['report_id'] ?? 0);
    $stmt = $conn->prepare('DELETE FROM reports WHERE id = ?');
    $stmt->bind_param('i', $report_id);
    $stmt->execute();
    flash('success', 'Report deleted successfully.');
    redirect_to('reports.php');
}

$search = trim($_GET['search'] ?? '');

if ($search !== '') {
    $term = '%' . $search . '%';
    $stmt = $conn->prepare('SELECT reports.*, users.full_name, users.email FROM reports INNER JOIN users ON users.id = reports.user_id WHERE reports.incident_type LIKE ? OR reports.location LIKE ? OR reports.status LIKE ? OR users.full_name LIKE ? ORDER BY reports.created_at DESC');
    $stmt->bind_param('ssss', $term, $term, $term, $term);
} else {
    $stmt = $conn->prepare('SELECT reports.*, users.full_name, users.email FROM reports INNER JOIN users ON users.id = reports.user_id ORDER BY reports.created_at DESC');
}

$stmt->execute();
$reports = $stmt->get_result();

$base_path = '../';
$page_title = 'Manage Reports - SafeVoice';
include '../includes/header.php';
?>
<div class="layout">
    <?php include '../includes/sidebar.php'; ?>
    <main class="content">
        <div class="page-title">
            <h1>Reports</h1>
            <p>Search reports, update their status, or remove inappropriate entries.</p>
        </div>
        <?php show_flash(); ?>
        <section class="panel">
            <form method="GET" class="search-bar">
                <input type="text" name="search" value="<?php echo e($search); ?>" placeholder="Search by type, location, status, or user">
                <button class="btn" type="submit">Search</button>
                <a class="btn secondary" href="reports.php">Clear</a>
            </form>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Incident</th>
                            <th>Location</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Evidence</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($report = $reports->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo e($report['id']); ?></td>
                                <td><?php echo e($report['full_name']); ?><br><span class="note"><?php echo e($report['email']); ?></span></td>
                                <td><?php echo e($report['incident_type']); ?><br><span class="note"><?php echo e(substr($report['description'], 0, 70)); ?></span></td>
                                <td><?php echo e($report['location']); ?></td>
                                <td><?php echo e($report['incident_date']); ?></td>
                                <td><span class="badge <?php echo e(strtolower(str_replace(' ', '-', $report['status']))); ?>"><?php echo e($report['status']); ?></span></td>
                                <td>
                                    <?php if ($report['evidence_file']): ?>
                                        <a href="../<?php echo e($report['evidence_file']); ?>" target="_blank">View</a>
                                    <?php else: ?>
                                        None
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="actions">
                                        <form method="POST">
                                            <input type="hidden" name="report_id" value="<?php echo e($report['id']); ?>">
                                            <select name="status">
                                                <option value="Pending" <?php echo $report['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option value="Under Review" <?php echo $report['status'] === 'Under Review' ? 'selected' : ''; ?>>Under Review</option>
                                                <option value="Resolved" <?php echo $report['status'] === 'Resolved' ? 'selected' : ''; ?>>Resolved</option>
                                            </select>
                                            <button class="btn secondary" name="update_status" value="1" type="submit">Update</button>
                                        </form>
                                        <form method="POST">
                                            <input type="hidden" name="report_id" value="<?php echo e($report['id']); ?>">
                                            <button class="btn danger" name="delete_report" value="1" type="submit" data-confirm="Delete this report?">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>
<?php include '../includes/footer.php'; ?>
