<?php 
$pageTitle = "Select Society";
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="auth-container" style="max-width: 640px;">
    <div class="auth-card" style="padding: 40px 36px;">
        <div class="auth-header">
            <h2>Select Society</h2>
            <p>You belong to multiple societies. Please select which society to enter.</p>
        </div>

        <form action="/select-society" method="POST">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
                <?php if (!empty($societies)): ?>
                    <?php foreach ($societies as $idx => $s): ?>
                        <label style="display: block; background: var(--paper); border: 2px solid var(--line); border-radius: 10px; padding: 20px 18px; cursor: pointer; transition: all 0.15s ease;" class="soc-card">
                            <input type="radio" name="society_id" value="<?= $s['id'] ?>" <?= $idx === 0 ? 'checked' : '' ?> style="margin-bottom: 10px; accent-color: var(--green);">
                            <div style="font-family: 'Fraunces', serif; font-size: 18px; font-weight: 600; color: var(--green-dark); margin-bottom: 4px;"><?= htmlspecialchars($s['name']) ?></div>
                            <div style="font-family: 'IBM Plex Mono', monospace; font-size: 12.5px; color: var(--ink-soft); font-weight: 500;">Flat: <?= htmlspecialchars($s['flat_number']) ?></div>
                            <div style="margin-top: 10px;">
                                <span style="font-size: 11px; padding: 3px 10px; border-radius: 12px; font-weight: 600; background: var(--green-tint); color: var(--green-dark); display: inline-block;">
                                    <?= htmlspecialchars($s['committee_role'] ?? 'Resident') ?>
                                </span>
                            </div>
                        </label>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn">Continue to Selected Society</button>
        </form>

        <div class="auth-footer" style="margin-top: 20px;">
            Want to switch accounts? <a href="/logout">Logout</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
