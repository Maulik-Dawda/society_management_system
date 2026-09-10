<?php 
$pageTitle = "Login";
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Welcome Back</h2>
            <p>Sign in to manage your society and residents.</p>
        </div>

        <div style="display:flex; border:1px solid var(--line); border-radius:8px; overflow:hidden; margin-bottom:24px; background:var(--paper);">
            <button type="button" id="tabUserBtn" onclick="switchLoginTab('user')" style="flex:1; padding:10px; border:none; background:var(--green); color:#fff; font-family:'Inter',sans-serif; font-weight:600; font-size:13px; cursor:pointer;">User Login (Mobile)</button>
            <button type="button" id="tabAdminBtn" onclick="switchLoginTab('admin')" style="flex:1; padding:10px; border:none; background:transparent; color:var(--ink-soft); font-family:'Inter',sans-serif; font-weight:600; font-size:13px; cursor:pointer;">Admin Login (Email)</button>
        </div>

        <form action="/login" method="POST" id="loginForm">
            <input type="hidden" name="login_type" id="loginTypeInput" value="user">

            <!-- User Mobile Field -->
            <div class="form-group" id="mobileGroup">
                <label for="mobile_number">Mobile Number</label>
                <input type="tel" id="mobile_number" name="mobile_number" class="form-control" placeholder="e.g. 9876543210">
            </div>

            <!-- Admin Email Field -->
            <div class="form-group" id="emailGroup" style="display:none;">
                <label for="email">Admin Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="admin@society.com">
                <div style="font-size:11.5px; color:var(--ink-soft); margin-top:6px; background:var(--paper); padding:8px 10px; border-radius:6px; border:1px solid var(--line);">
                    🔑 <b>Default Admin Credentials:</b><br>
                    Email: <code style="color:var(--green-dark);">admin@society.com</code><br>
                    Password: <code style="color:var(--green-dark);">AdminPassword123!</code>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn" style="margin-top: 10px;">Sign In</button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="/register">Register Account</a>
        </div>
    </div>
</div>

<script>
function switchLoginTab(type) {
    document.getElementById('loginTypeInput').value = type;
    const tabUser = document.getElementById('tabUserBtn');
    const tabAdmin = document.getElementById('tabAdminBtn');
    const mobileGrp = document.getElementById('mobileGroup');
    const emailGrp = document.getElementById('emailGroup');
    const mobileInput = document.getElementById('mobile_number');
    const emailInput = document.getElementById('email');

    if (type === 'admin') {
        tabAdmin.style.background = 'var(--green)';
        tabAdmin.style.color = '#fff';
        tabUser.style.background = 'transparent';
        tabUser.style.color = 'var(--ink-soft)';
        
        emailGrp.style.display = 'block';
        mobileGrp.style.display = 'none';
        emailInput.required = true;
        mobileInput.required = false;
        if (!emailInput.value) emailInput.value = 'admin@society.com';
    } else {
        tabUser.style.background = 'var(--green)';
        tabUser.style.color = '#fff';
        tabAdmin.style.background = 'transparent';
        tabAdmin.style.color = 'var(--ink-soft)';
        
        mobileGrp.style.display = 'block';
        emailGrp.style.display = 'none';
        mobileInput.required = true;
        emailInput.required = false;
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
