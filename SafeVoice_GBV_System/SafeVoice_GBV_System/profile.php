<?php
require_once 'config/db.php';
require_once 'includes/auth.php';
require_user_role();

$user = current_user();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if ($full_name === '' || $phone === '') {
        flash('danger', 'Full name and phone number are required.');
    } else {
        $stmt = $conn->prepare('UPDATE users SET full_name = ?, phone = ? WHERE id = ?');
        $stmt->bind_param('ssi', $full_name, $phone, $user['id']);
        $stmt->execute();

        $_SESSION['user']['full_name'] = $full_name;
        $_SESSION['user']['phone'] = $phone;
        flash('success', 'Profile updated successfully.');
        redirect_to('profile.php');
    }
}

$page_title = 'Profile - SafeVoice';
include 'includes/header.php';
?>
<div class="layout">
    <?php include 'includes/sidebar.php'; ?>
    <main class="content">
        <div class="page-title">
            <h1>Profile</h1>
            <p>Keep your contact information up to date.</p>
        </div>
        <?php show_flash(); ?>
        <section class="panel">
            <form method="POST" class="form-grid">
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" value="<?php echo e($user['full_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" value="<?php echo e($user['email']); ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" value="<?php echo e($user['phone']); ?>" required>
                </div>
                <div class="form-group full">
                    <button class="btn" type="submit">Update Profile</button>
                </div>
            </form>
        </section>
    </main>
</div>
<?php include 'includes/footer.php'; ?>
