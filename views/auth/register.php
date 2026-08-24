<?php 
$pageTitle = "Register Member";
require_once __DIR__ . '/../layouts/header.php';
$old = Session::getFlash('old') ?? [];
?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Register Member</h2>
            <p>Enter your details to register with Meridian Heights CHS</p>
        </div>

        <form action="/register" method="POST">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="e.g. Rajesh Kumar" value="<?= htmlspecialchars($old['name'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="society_name">Society Name</label>
                <input type="text" id="society_name" name="society_name" class="form-control" placeholder="e.g. Meridian Heights CHS" value="<?= htmlspecialchars($old['society_name'] ?? 'Meridian Heights CHS') ?>" required>
            </div>

            <div class="form-group">
                <label for="mobile_number">Mobile Number</label>
                <input type="tel" id="mobile_number" name="mobile_number" class="form-control" placeholder="e.g. 9876543210" value="<?= htmlspecialchars($old['mobile_number'] ?? '') ?>" required pattern="[0-9]{10,15}">
            </div>

            <button type="submit" class="btn" style="margin-top: 10px;">Receive OTP via SMS</button>
        </form>

        <div class="auth-footer">
            Already registered? <a href="/login">Login here</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
