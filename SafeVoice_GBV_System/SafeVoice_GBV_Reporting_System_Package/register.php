<?php
require_once 'config/db.php';
require_once 'includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($full_name === '' || $email === '' || $phone === '' || $password === '') {
        flash('danger', 'All fields are required.');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flash('danger', 'Please enter a valid email address.');
    } elseif (strlen($password) < 6) {
        flash('danger', 'Password must have at least 6 characters.');
    } elseif ($password !== $confirm_password) {
        flash('danger', 'Passwords do not match.');
    } else {
        $stmt = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();

        if ($stmt->get_result()->num_rows > 0) {
            flash('danger', 'Email address is already registered.');
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = 'user';
            $stmt = $conn->prepare('INSERT INTO users (full_name, email, phone, password, role) VALUES (?, ?, ?, ?, ?)');
            $stmt->bind_param('sssss', $full_name, $email, $phone, $hashed_password, $role);
            $stmt->execute();
            flash('success', 'Account created successfully. Please login.');
            redirect_to('login.php');
        }
    }
}

$page_title = 'Register - SafeVoice';
include 'includes/header.php';
?>
<main class="auth-page">
    <section class="auth-card">
        <h1>Create Account</h1>
        <p>Register to submit and track your reports.</p>
        <?php show_flash(); ?>
        <form method="POST">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" minlength="6" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" minlength="6" required>
            </div>
            <button class="btn" type="submit">Register</button>
        </form>
        <p class="note">Already registered? <a href="login.php">Login</a></p>
    </section>
</main>
<?php include 'includes/footer.php'; ?>
