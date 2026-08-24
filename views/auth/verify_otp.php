<?php 
$pageTitle = "Verify OTP";
require_once __DIR__ . '/../layouts/header.php';
$simulatedOtp = Session::get('last_simulated_otp');
$dispatchInfo = Session::getFlash('dispatch_info') ?? Session::get('last_simulated_notifications');
$freeWaLink = $simulatedOtp['free_whatsapp_link'] ?? ('https://api.whatsapp.com/send?phone=' . preg_replace('/[^0-9]/', '', $mobile) . '&text=' . urlencode('Meridian Heights OTP verification code: ' . ($simulatedOtp['code'] ?? '')));
?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Verify Mobile OTP</h2>
            <p>Enter the 6-digit verification code sent to <strong><?= htmlspecialchars($mobile) ?></strong></p>
        </div>

        <!-- 100% Free WhatsApp Direct Launch Button -->
        <div style="margin-bottom: 20px;">
            <a href="<?= htmlspecialchars($freeWaLink) ?>" target="_blank" class="btn" style="background: #25D366; color: #fff; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px; font-weight: 600;">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                Receive / View OTP on WhatsApp (Free)
            </a>
        </div>

        <?php if (!empty($simulatedOtp) && $simulatedOtp['mobile'] === $mobile): ?>
            <div class="alert alert-info" style="border-left: 4px solid var(--green); background: var(--paper);">
                <strong>📱 [Free On-Screen SMS Gateway]</strong><br>
                <span><?= htmlspecialchars($simulatedOtp['message']) ?></span><br>
                <div style="margin-top: 6px;">
                    <small>Code: <b style="font-family:'IBM Plex Mono',monospace; font-size: 16px; color:var(--green-dark);"><?= htmlspecialchars($simulatedOtp['code']) ?></b></small>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($verified == 1 && !empty($dispatchInfo)): ?>
            <div class="alert alert-success" style="border-left: 4px solid var(--green);">
                <h4 style="margin-bottom: 6px;">💬 WhatsApp & Text Password Link Generated!</h4>
                <p style="margin-bottom: 12px;">Your registration is verified. Click the button below to receive or open your password setup link:</p>
                
                <?php if (!empty($dispatchInfo['free_whatsapp_link'])): ?>
                    <a href="<?= htmlspecialchars($dispatchInfo['free_whatsapp_link']) ?>" target="_blank" class="btn" style="background: #25D366; color: #fff; text-decoration: none; margin-bottom: 10px; display: block; text-align: center;">
                        💬 Receive Password Link via WhatsApp (Free)
                    </a>
                <?php endif; ?>

                <a href="<?= htmlspecialchars($dispatchInfo['url']) ?>" class="btn btn-outline" style="display: block; text-align: center; text-decoration: none;">
                    👉 Open Password Creation Form Directly
                </a>
            </div>
        <?php else: ?>
            <form action="/verify-otp" method="POST">
                <input type="hidden" name="mobile_number" value="<?= htmlspecialchars($mobile) ?>">
                
                <div class="form-group">
                    <label for="otp_code">Enter 6-Digit OTP Code</label>
                    <input type="text" id="otp_code" name="otp_code" class="form-control" placeholder="123456" required maxlength="6" pattern="[0-9]{6}" style="letter-spacing: 4px; text-align: center; font-size: 22px; font-weight: 600; font-family: 'IBM Plex Mono', monospace;" value="<?= isset($simulatedOtp['code']) ? htmlspecialchars($simulatedOtp['code']) : '' ?>">
                </div>

                <button type="submit" class="btn">Verify OTP & Set Password</button>
            </form>
        <?php endif; ?>

        <div class="auth-footer">
            Incorrect number? <a href="/register">Change mobile number</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
