<?php 
$pageTitle = "Create Password";
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Create Password</h2>
            <p>Set a secure password for account: <strong><?= htmlspecialchars($user['name']) ?></strong> (<?= htmlspecialchars($user['mobile_number']) ?>)</p>
        </div>

        <form action="/set-password" method="POST" id="passwordForm">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="••••••••" required>
            </div>

            <!-- Password Policy Checklist -->
            <div style="background: var(--paper); padding: 14px; border-radius: 8px; border: 1px solid var(--line); margin-bottom: 18px;">
                <span style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--ink-soft); display: block; margin-bottom: 6px;">Password Requirements:</span>
                <ul class="rule-list">
                    <li id="rule-length" class="rule-item invalid">✖ At least 8 characters long</li>
                    <li id="rule-upper" class="rule-item invalid">✖ At least one uppercase letter (A-Z)</li>
                    <li id="rule-lower" class="rule-item invalid">✖ At least one lowercase letter (a-z)</li>
                    <li id="rule-special" class="rule-item invalid">✖ At least one special character (!@#$%^&*)</li>
                    <li id="rule-match" class="rule-item invalid">✖ Passwords match</li>
                </ul>
            </div>

            <button type="submit" id="submitBtn" class="btn">Set Password & Login</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    
    const ruleLength = document.getElementById('rule-length');
    const ruleUpper = document.getElementById('rule-upper');
    const ruleLower = document.getElementById('rule-lower');
    const ruleSpecial = document.getElementById('rule-special');
    const ruleMatch = document.getElementById('rule-match');

    function validate() {
        const val = password.value;
        const confirmVal = confirmPassword.value;

        // Min 8 chars
        if (val.length >= 8) {
            ruleLength.className = 'rule-item valid';
            ruleLength.textContent = '✔ At least 8 characters long';
        } else {
            ruleLength.className = 'rule-item invalid';
            ruleLength.textContent = '✖ At least 8 characters long';
        }

        // Uppercase
        if (/[A-Z]/.test(val)) {
            ruleUpper.className = 'rule-item valid';
            ruleUpper.textContent = '✔ At least one uppercase letter (A-Z)';
        } else {
            ruleUpper.className = 'rule-item invalid';
            ruleUpper.textContent = '✖ At least one uppercase letter (A-Z)';
        }

        // Lowercase
        if (/[a-z]/.test(val)) {
            ruleLower.className = 'rule-item valid';
            ruleLower.textContent = '✔ At least one lowercase letter (a-z)';
        } else {
            ruleLower.className = 'rule-item invalid';
            ruleLower.textContent = '✖ At least one lowercase letter (a-z)';
        }

        // Special Character
        if (/[!@#$%^&*(),.?":{}|<>]/.test(val)) {
            ruleSpecial.className = 'rule-item valid';
            ruleSpecial.textContent = '✔ At least one special character (!@#$%^&*)';
        } else {
            ruleSpecial.className = 'rule-item invalid';
            ruleSpecial.textContent = '✖ At least one special character (!@#$%^&*)';
        }

        // Match
        if (val !== '' && val === confirmVal) {
            ruleMatch.className = 'rule-item valid';
            ruleMatch.textContent = '✔ Passwords match';
        } else {
            ruleMatch.className = 'rule-item invalid';
            ruleMatch.textContent = '✖ Passwords match';
        }
    }

    password.addEventListener('input', validate);
    confirmPassword.addEventListener('input', validate);
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
