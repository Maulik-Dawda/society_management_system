<?php 
$pageTitle = "Select Society, Flat & Role";
require_once __DIR__ . '/../layouts/header.php';

// Prepare grouped societies data for JS dynamic cascading
$userModel = new User();
$userId = Session::get('user_id');
$mobile = Session::get('user_mobile');
$groupedSocieties = $userModel->getUserSocietiesGrouped($mobile, $userId);
?>

<div class="auth-container" style="max-width: 680px; margin: 40px auto;">
    <div class="auth-card" style="padding: 40px 36px;">
        <div class="auth-header" style="text-align: center; margin-bottom: 28px;">
            <h2 style="font-family: 'Fraunces', serif; font-size: 26px; color: var(--green-dark);">Complete Your Login</h2>
            <p style="font-size: 13.5px; color: var(--ink-soft); margin-top: 6px;">Select your Society, Flat Number, and Role to enter the dashboard.</p>
        </div>

        <?php
        $flashError = Session::getFlash('error');
        if ($flashError): ?>
            <div style="padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 18px; background: var(--rust-tint); color: var(--rust); border: 1px solid rgba(177,74,46,0.3);"><?= htmlspecialchars($flashError) ?></div>
        <?php endif; ?>

        <form action="/select-society" method="POST" id="loginSelectionForm">
            <!-- Step Indicators -->
            <div style="display: flex; justify-content: space-between; margin-bottom: 24px; background: var(--paper); padding: 12px 16px; border-radius: 10px; border: 1px solid var(--line);">
                <div style="font-size: 12px; font-weight: 600; color: var(--green-dark);">Step 1: Select Society</div>
                <div style="font-size: 12px; font-weight: 600; color: var(--green-dark);">Step 2: Choose Flat</div>
                <div style="font-size: 12px; font-weight: 600; color: var(--green-dark);">Step 3: Select Role</div>
            </div>

            <!-- Step 1: Select Society -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; font-size: 14px; margin-bottom: 8px; color: var(--ink);">
                    1. Select Society <span style="color: var(--rust);">*</span>
                </label>
                <select name="society_id" id="societySelect" onchange="onSocietyChange()" style="width: 100%; padding: 12px 14px; border-radius: 8px; border: 1.5px solid var(--line); font-size: 14px; background: #fff; color: var(--ink); font-weight: 500;" required>
                    <?php if (!empty($groupedSocieties)): ?>
                        <?php foreach ($groupedSocieties as $idx => $soc): ?>
                            <option value="<?= $soc['id'] ?>" <?= $idx === 0 ? 'selected' : '' ?>>
                                <?= htmlspecialchars($soc['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="1">Meridian Heights CHS</option>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Step 2: Choose Flat Number -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; font-size: 14px; margin-bottom: 8px; color: var(--ink);">
                    2. Choose Flat Number <span style="color: var(--rust);">*</span>
                </label>
                <select name="flat_number" id="flatSelect" onchange="onFlatChange()" style="width: 100%; padding: 12px 14px; border-radius: 8px; border: 1.5px solid var(--line); font-size: 14px; background: #fff; color: var(--ink); font-weight: 500;" required>
                    <!-- Populated dynamically via JS -->
                </select>
            </div>

            <!-- Step 3: Select Role -->
            <div style="margin-bottom: 28px;">
                <label style="display: block; font-weight: 600; font-size: 14px; margin-bottom: 8px; color: var(--ink);">
                    3. Select Role <span style="color: var(--rust);">*</span>
                </label>
                <select name="role" id="roleSelect" style="width: 100%; padding: 12px 14px; border-radius: 8px; border: 1.5px solid var(--line); font-size: 14px; background: #fff; color: var(--ink); font-weight: 500;" required>
                    <option value="Resident">Resident</option>
                    <option value="Chairman">Chairman</option>
                    <option value="Secretary">Secretary</option>
                    <option value="Treasurer">Treasurer</option>
                    <option value="Committee Member">Committee Member</option>
                </select>
            </div>

            <button type="submit" class="btn" style="width: 100%; padding: 14px; font-size: 15px; background: var(--green); color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                Login & Enter Dashboard →
            </button>
        </form>

        <div class="auth-footer" style="margin-top: 24px; text-align: center; font-size: 13px;">
            Logged in as <b><?= htmlspecialchars(Session::get('user_name') ?? 'User') ?></b> (<?= htmlspecialchars(Session::get('user_mobile') ?? '') ?>) · <a href="/logout" style="color: var(--rust); font-weight: 600; text-decoration: none;">Logout / Switch Account</a>
        </div>
    </div>
</div>

<script>
const groupedData = <?= json_encode($groupedSocieties ?? []) ?>;

function onSocietyChange() {
    const socId = parseInt(document.getElementById('societySelect').value);
    const flatSelect = document.getElementById('flatSelect');
    flatSelect.innerHTML = '';

    const selectedSoc = groupedData.find(s => parseInt(s.id) === socId);
    if (selectedSoc && selectedSoc.flats && selectedSoc.flats.length > 0) {
        selectedSoc.flats.forEach(f => {
            const opt = document.createElement('option');
            opt.value = f.flat_number;
            opt.text = 'Flat ' + f.flat_number + ' (' + f.committee_role + ')';
            opt.dataset.role = f.committee_role;
            flatSelect.appendChild(opt);
        });
    } else {
        const opt = document.createElement('option');
        opt.value = 'A-101';
        opt.text = 'Flat A-101 (Resident)';
        opt.dataset.role = 'Resident';
        flatSelect.appendChild(opt);
    }

    onFlatChange();
}

function onFlatChange() {
    const flatSelect = document.getElementById('flatSelect');
    const selectedOpt = flatSelect.options[flatSelect.selectedIndex];
    if (selectedOpt && selectedOpt.dataset.role) {
        const roleSelect = document.getElementById('roleSelect');
        roleSelect.value = selectedOpt.dataset.role;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    onSocietyChange();
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
