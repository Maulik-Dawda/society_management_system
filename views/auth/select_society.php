<?php 
$pageTitle = "Select Society";
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="auth-container" style="max-width: 680px; margin: 40px auto;">
    <div class="auth-card" style="padding: 40px 36px;">
        <div class="auth-header" style="text-align: center; margin-bottom: 28px;">
            <h2 style="font-family: 'Fraunces', serif; font-size: 26px; color: var(--green-dark);">Select Society to Continue</h2>
            <p style="font-size: 13.5px; color: var(--ink-soft); margin-top: 6px;">You are registered in multiple societies. Please choose which society you want to access.</p>
        </div>

        <?php
        $flashError = Session::getFlash('error');
        if ($flashError): ?>
            <div style="padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 18px; background: var(--rust-tint); color: var(--rust); border: 1px solid rgba(177,74,46,0.3);"><?= htmlspecialchars($flashError) ?></div>
        <?php endif; ?>

        <form action="/select-society" method="POST">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; margin-bottom: 28px;">
                <?php if (!empty($societies)): ?>
                    <?php foreach ($societies as $idx => $s): ?>
                        <?php 
                        $role = $s['committee_role'] ?? 'Resident';
                        $badgeStyle = "background: var(--paper); color: var(--ink-soft); border: 1px solid var(--line);";
                        if ($role === 'Chairman') $badgeStyle = "background: var(--gold-tint); color: var(--gold); border: 1px solid rgba(185,129,42,0.4);";
                        elseif ($role === 'Secretary') $badgeStyle = "background: var(--green-tint); color: var(--green-dark); border: 1px solid rgba(31,92,74,0.4);";
                        elseif ($role === 'Treasurer') $badgeStyle = "background: var(--rust-tint); color: var(--rust); border: 1px solid rgba(177,74,46,0.4);";
                        elseif ($role === 'Committee Member') $badgeStyle = "background: #EBF3F5; color: #1C6B72; border: 1px solid rgba(28,107,114,0.4);";
                        ?>
                        <label onclick="selectCard(this)" style="display: flex; flex-direction: column; justify-content: space-between; background: var(--paper-raised); border: 2px solid <?= $idx === 0 ? 'var(--green)' : 'var(--line)' ?>; border-radius: 12px; padding: 22px 20px; cursor: pointer; transition: all 0.15s ease;" class="soc-card <?= $idx === 0 ? 'selected' : '' ?>">
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                    <input type="radio" name="society_id" value="<?= $s['id'] ?>" <?= $idx === 0 ? 'checked' : '' ?> style="accent-color: var(--green); transform: scale(1.2);">
                                    <span style="font-size: 11px; padding: 4px 10px; border-radius: 12px; font-weight: 600; <?= $badgeStyle ?>">
                                        <?= htmlspecialchars($role) ?>
                                    </span>
                                </div>
                                <div style="font-family: 'Fraunces', serif; font-size: 19px; font-weight: 600; color: var(--green-dark); margin-bottom: 6px;"><?= htmlspecialchars($s['name']) ?></div>
                                <div style="font-family: 'IBM Plex Mono', monospace; font-size: 13px; color: var(--ink-soft);">Flat / Unit: <b><?= htmlspecialchars($s['flat_number'] ?: 'N/A') ?></b></div>
                            </div>
                            <div style="margin-top: 14px; padding-top: 12px; border-top: 1px dashed var(--line); font-size: 12px; color: var(--ink-soft); display: flex; justify-content: space-between; align-items: center;">
                                <span>Click to select</span>
                                <span style="font-weight: 600; color: var(--green-dark);">Enter →</span>
                            </div>
                        </label>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 20px; color: var(--ink-soft);">
                        No registered societies found for this user account.
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn" style="width: 100%; padding: 14px; font-size: 15px; background: var(--green); color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">Continue to Selected Society</button>
        </form>

        <div class="auth-footer" style="margin-top: 24px; text-align: center; font-size: 13px;">
            Logged in as <b><?= htmlspecialchars(Session::get('user_name') ?? 'User') ?></b> (<?= htmlspecialchars(Session::get('user_mobile') ?? '') ?>) · <a href="/logout" style="color: var(--rust); font-weight: 600; text-decoration: none;">Logout / Switch Account</a>
        </div>
    </div>
</div>

<script>
function selectCard(labelEl) {
    document.querySelectorAll('.soc-card').forEach(card => {
        card.style.borderColor = 'var(--line)';
        card.style.background = 'var(--paper-raised)';
    });
    labelEl.style.borderColor = 'var(--green)';
    labelEl.style.background = '#F6FAF8';
    const radio = labelEl.querySelector('input[type="radio"]');
    if (radio) radio.checked = true;
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
