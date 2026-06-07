<?php
require_once 'config/db.php';
require_once 'includes/auth.php';
require_user_role();

function save_evidence_file($file) {
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return '';
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'];
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, $allowed_extensions, true)) {
        return false;
    }

    if ($file['size'] > 5 * 1024 * 1024) {
        return false;
    }

    $upload_dir = __DIR__ . '/uploads/evidence/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_name = 'evidence_' . time() . '_' . random_int(1000, 9999) . '.' . $extension;
    $target = $upload_dir . $file_name;

    if (!move_uploaded_file($file['tmp_name'], $target)) {
        return false;
    }

    return 'uploads/evidence/' . $file_name;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $incident_type = trim($_POST['incident_type'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $incident_date = trim($_POST['incident_date'] ?? '');

    if ($incident_type === '' || $description === '' || $location === '' || $incident_date === '') {
        flash('danger', 'Please fill in all required fields.');
    } else {
        $evidence_file = save_evidence_file($_FILES['evidence_file'] ?? null);

        if ($evidence_file === false) {
            flash('danger', 'Evidence must be a JPG, PNG, or PDF file not larger than 5MB.');
        } else {
            $status = 'Pending';
            $user_id = current_user()['id'];
            $stmt = $conn->prepare('INSERT INTO reports (user_id, incident_type, description, location, incident_date, evidence_file, status) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $stmt->bind_param('issssss', $user_id, $incident_type, $description, $location, $incident_date, $evidence_file, $status);
            $stmt->execute();

            flash('success', 'Report submitted successfully.');
            redirect_to('reports.php');
        }
    }
}

$page_title = 'Submit Report - SafeVoice';
include 'includes/header.php';
?>
<div class="layout">
    <?php include 'includes/sidebar.php'; ?>
    <main class="content">
        <div class="page-title">
            <h1>Submit GBV Report</h1>
            <p>Provide clear incident details. Evidence is optional.</p>
        </div>
        <?php show_flash(); ?>
        <section class="panel">
            <form method="POST" enctype="multipart/form-data" class="form-grid">
                <div class="form-group">
                    <label for="incident_type">Incident Type</label>
                    <select id="incident_type" name="incident_type" required>
                        <option value="">Select type</option>
                        <option value="Physical Violence">Physical Violence</option>
                        <option value="Sexual Violence">Sexual Violence</option>
                        <option value="Emotional Abuse">Emotional Abuse</option>
                        <option value="Economic Abuse">Economic Abuse</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="incident_date">Date of Incident</label>
                    <input type="date" id="incident_date" name="incident_date" required>
                </div>
                <div class="form-group full">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" required>
                </div>
                <div class="form-group full">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                <div class="form-group full">
                    <label for="evidence_file">Evidence File</label>
                    <input type="file" id="evidence_file" name="evidence_file" accept=".jpg,.jpeg,.png,.pdf">
                    <p class="note">Accepted files: JPG, PNG, PDF. Maximum size: 5MB.</p>
                </div>
                <div class="form-group full">
                    <button class="btn" type="submit">Submit Report</button>
                </div>
            </form>
        </section>
    </main>
</div>
<?php include 'includes/footer.php'; ?>
