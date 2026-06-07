<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
require_admin();

$stmt = $conn->prepare("SELECT id, full_name, email, phone, role, created_at FROM users WHERE role = 'user' ORDER BY created_at DESC");
$stmt->execute();
$users = $stmt->get_result();

$base_path = '../';
$page_title = 'Users - SafeVoice';
include '../includes/header.php';
?>
<div class="layout">
    <?php include '../includes/sidebar.php'; ?>
    <main class="content">
        <div class="page-title">
            <h1>Users</h1>
            <p>View registered users of the reporting system.</p>
        </div>
        <section class="panel table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $users->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo e($user['id']); ?></td>
                            <td><?php echo e($user['full_name']); ?></td>
                            <td><?php echo e($user['email']); ?></td>
                            <td><?php echo e($user['phone']); ?></td>
                            <td><?php echo e($user['role']); ?></td>
                            <td><?php echo e($user['created_at']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>
<?php include '../includes/footer.php'; ?>
