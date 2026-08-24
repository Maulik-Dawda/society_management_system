<?php 
$pageTitle = "Login Member";
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Member Login</h2>
            <p>Welcome back! Please sign in with your mobile number.</p>
        </div>

        <form action="/login" method="POST">
            <div class="form-group">
                <label for="mobile_number">Mobile Number</label>
                <input type="tel" id="mobile_number" name="mobile_number" class="form-control" placeholder="e.g. 9876543210" required pattern="[0-9]{10,15}">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn" style="margin-top: 10px;">Sign In to Dashboard</button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="/register">Register New Member</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
