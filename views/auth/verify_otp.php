<?php 
$pageTitle = "Verify OTP";
require_once __DIR__ . '/../layouts/header.php';
$simulatedOtp = Session::get('last_simulated_otp');
$dispatchInfo = Session::getFlash('dispatch_info') ?? Session::get('last_simulated_notifications');
?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Verify Mobile OTP</h2>
            <p>Enter the 6-digit verification code sent to <strong><?= htmlspecialchars($mobile) ?></strong></p>
        </div>

        <?php if (!empty($simulatedOtp) && $simulatedOtp['mobile'] === $mobile): ?>
            <div class="alert alert-info" style="border-left: 4px solid var(--gold);">
                <strong>📱 [Simulated SMS Gateway]</strong><br>
                <span><?= htmlspecialchars($simulatedOtp['message']) ?></span><br>
                <small style="color: var(--ink-soft);">Use Code: <b style="font-family:'IBM Plex Mono',monospace; font-size: 15px; color:var(--ink);"><?= htmlspecialchars($simulatedOtp['code']) ?></b></small>
            </div>
        <?php endif; ?>

        <?php if ($verified == 1 && !empty($dispatchInfo)): ?>
            <div class="alert alert-success" style="border-left: 4px solid var(--green);">
                <h4 style="margin-bottom: 6px;">💬 WhatsApp & SMS Delivery Sent!</h4>
                <p style="margin-bottom: 8px;">A password setup link has been sent to your mobile via WhatsApp & SMS.</p>
                <div style="background: rgba(255,255,255,0.7); padding: 10px; border-radius: 6px; font-size: 12px; margin-bottom: 10px;">
                    <strong>Text Message:</strong> <?= htmlspecialchars($dispatchInfo['sms']) ?>
                </div>
                <a href="<?= htmlspecialchars($dispatchInfo['url']) ?>" class="btn btn-outline" style="display: block; text-align: center; text-decoration: none;">
                    👉 Open Password Creation Link Now
                </a>
            </div>
        <?php else: ?>
            <form action="/verify-otp" method="POST">
                <input type="hidden" name="mobile_number" value="<?= htmlspecialchars($mobile) ?>">
                
                <div class="form-group">
                    <label for="otp_code">6-Digit OTP Code</label>
                    <input type="text" id="otp_code" name="otp_code" class="form-control" placeholder="123456" required maxlength="6" pattern="[0-9]{6}" style="letter-spacing: 4px; text-align: center; font-size: 20px; font-family: 'IBM Plex Mono', monospace;" value="<?= isset($simulatedOtp['code']) ? htmlspecialchars($simulatedOtp['code']) : '' ?>">
                </div>

                <button type="submit" class="btn">Verify & Dispatch Link</button>
            </form>
        <?php endif; ?>

        <div class="auth-footer">
            Incorrect number? <a href="/register">Change mobile number</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
