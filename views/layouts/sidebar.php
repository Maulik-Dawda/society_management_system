<?php
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$baseUrl = ($scriptName === '/' || $scriptName === '\\') ? '' : rtrim(str_replace('\\', '/', $scriptName), '/');
$currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$activeSocietyName = Session::get('active_society_name') ?? 'Meridian Heights';
$activeUserRole = Session::get('user_role') ?? 'Resident';
$isAdmin = !empty(Session::get('is_admin'));
?>
<!-- ===== Sidebar Component ===== -->
<div class="sidebar">
  <div class="brand"><?= htmlspecialchars($isAdmin ? 'System Admin Portal' : $activeSocietyName) ?></div>
  <div class="subbrand" style="display:flex; justify-content:space-between; align-items:center;">
    <span><?= $isAdmin ? 'Multi-Society Management' : 'Cooperative Housing' ?></span>
    <?php if (Session::has('user_societies') && count(Session::get('user_societies')) > 1): ?>
      <a href="/select-society" style="font-size:10.5px; color:#9FB3A8; text-decoration:underline;">🔁 Switch</a>
    <?php endif; ?>
  </div>

  <div class="langswitch">
    <div class="active">English</div>
    <div>ગુજરાતી</div>
  </div>

  <div class="navgroup">
    <div class="navlabel">Overview</div>
    <a href="/dashboard" data-page="dashboard" class="navitem <?= (isset($activePage) && $activePage === 'dashboard') ? 'active' : '' ?>"><span class="ic">◆</span><span>Dashboard</span></a>
  </div>

  <?php if ($isAdmin): ?>
  <div class="navgroup">
    <div class="navlabel">Admin Rights & Management</div>
    <a href="/registration?new=1" data-page="registration" class="navitem <?= (isset($activePage) && $activePage === 'registration') ? 'active' : '' ?>"><span class="ic">⚙</span><span>Register New Society</span></a>
    <a href="/members" data-page="members" class="navitem <?= (isset($activePage) && $activePage === 'members') ? 'active' : '' ?>"><span class="ic">☰</span><span>Create & Add Members</span></a>
    <a href="/committee" data-page="committee" class="navitem <?= (isset($activePage) && $activePage === 'committee') ? 'active' : '' ?>"><span class="ic">★</span><span>Make Committee Members</span></a>
  </div>
  <?php else: ?>
  <div class="navgroup">
    <div class="navlabel">Society</div>
    <a href="/members" data-page="members" class="navitem <?= (isset($activePage) && $activePage === 'members') ? 'active' : '' ?>"><span class="ic">☰</span><span>Members</span></a>
    <a href="/committee" data-page="committee" class="navitem <?= (isset($activePage) && $activePage === 'committee') ? 'active' : '' ?>"><span class="ic">★</span><span>Committee</span></a>
    <a href="/complaints" data-page="complaints" class="navitem <?= (isset($activePage) && $activePage === 'complaints') ? 'active' : '' ?>"><span class="ic">💬</span><span>Complaints</span></a>
    <a href="/notices" data-page="notices" class="navitem <?= (isset($activePage) && $activePage === 'notices') ? 'active' : '' ?>"><span class="ic">▤</span><span>Notice board</span></a>
    <a href="/vehicles" data-page="vehicles" class="navitem <?= (isset($activePage) && $activePage === 'vehicles') ? 'active' : '' ?>"><span class="ic">▭</span><span>Vehicles</span></a>
  </div>

  <div class="navgroup">
    <div class="navlabel">Finance</div>
    <a href="/maintenance" data-page="maintenance" class="navitem <?= (isset($activePage) && $activePage === 'maintenance') ? 'active' : '' ?>"><span class="ic">%</span><span>Maintenance</span></a>
    <a href="/payments" data-page="payments" class="navitem <?= (isset($activePage) && $activePage === 'payments') ? 'active' : '' ?>"><span class="ic">₹</span><span>Payments</span></a>
    <a href="/expenses" data-page="expenses" class="navitem <?= (isset($activePage) && $activePage === 'expenses') ? 'active' : '' ?>"><span class="ic">–</span><span>Expenses</span></a>
    <a href="/reports" data-page="reports" class="navitem <?= (isset($activePage) && $activePage === 'reports') ? 'active' : '' ?>"><span class="ic">▤</span><span>Reports & Tally</span></a>
  </div>
  <?php endif; ?>

  <div class="sidebar-foot">
    <div style="display:flex; align-items:center; gap:8px;">
      <div class="avatar"><?= strtoupper(substr(Session::get('user_name') ?? 'MH', 0, 2)) ?></div>
      <div>
        <?= htmlspecialchars(Session::get('user_name') ?? 'User') ?><br>
        <span style="font-size:10px; color:#B9812A; font-weight:600;"><?= htmlspecialchars($activeUserRole) ?></span>
      </div>
    </div>
    <a href="/logout" onclick="localStorage.clear();" style="color:#F4E1D8; text-decoration:none; font-size:11px; background:rgba(177,74,46,0.3); padding:4px 8px; border-radius:4px;">Logout</a>
  </div>
</div>

<script>
// Sync session values to localStorage on page render
(function syncLocalStorage() {
    <?php if (Session::has('user_id')): ?>
        localStorage.setItem('user_id', <?= json_encode(Session::get('user_id')) ?>);
        localStorage.setItem('user_name', <?= json_encode(Session::get('user_name')) ?>);
        localStorage.setItem('user_mobile', <?= json_encode(Session::get('user_mobile')) ?>);
        localStorage.setItem('active_society_id', <?= json_encode(Session::get('active_society_id') ?? 1) ?>);
        localStorage.setItem('active_society_name', <?= json_encode(Session::get('active_society_name') ?? 'Meridian Heights') ?>);
        localStorage.setItem('user_role', <?= json_encode(Session::get('user_role') ?? 'Resident') ?>);
        localStorage.setItem('is_admin', <?= json_encode(Session::get('is_admin') ?? 0) ?>);
    <?php endif; ?>
})();

document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.sidebar');
    if (!sidebar) return;

    sidebar.addEventListener('click', function(e) {
        const item = e.target.closest('.navitem');
        if (!item) return;

        const page = item.getAttribute('data-page') || item.getAttribute('href');
        if (page && page !== '#') {
            e.preventDefault();
            let target = page.replace(/^#/, '');
            if (!target.startsWith('/') && !target.startsWith('http')) {
                target = '/' + target;
            }
            window.location.href = target;
        }
    });
});
</script>
