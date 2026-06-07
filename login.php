<?php
require_once 'config/db.php';
require_once 'includes/helpers.php';

if (current_user()) {
    redirect_to(current_user()['role'] === 'admin' ? 'admin/dashboard.php' : 'dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        flash('danger', 'Email and password are required.');
    } else {
        $stmt = $conn->prepare('SELECT id, full_name, email, phone, password, role FROM users WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            $_SESSION['user'] = $user;
            redirect_to($user['role'] === 'admin' ? 'admin/dashboard.php' : 'dashboard.php');
        }

        flash('danger', 'Invalid email or password.');
    }
}

$page_title = 'Login - SafeVoice';
include 'includes/header.php';
?>
<main class="auth-page">
    <section class="auth-card">
        <h1>SafeVoice</h1>
        <p>Login to submit or manage GBV reports securely.</p>
        <?php show_flash(); ?>
        <form method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button class="btn" type="submit">Login</button>
        </form>
        <p class="note">No account? <a href="register.php">Create one</a></p>
    </section>
</main>
<?php include 'includes/footer.php'; ?>
