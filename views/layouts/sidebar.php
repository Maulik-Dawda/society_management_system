<!-- ===== Sidebar Component ===== -->
<div class="sidebar">
  <div class="brand">Meridian Heights</div>
  <div class="subbrand">Cooperative Housing Society</div>

  <div class="langswitch">
    <div class="active">English</div>
    <div>ગુજરાતી</div>
  </div>

  <div class="navgroup">
    <div class="navlabel">Overview</div>
    <a href="/dashboard" class="navitem <?= (isset($activePage) && $activePage === 'dashboard') ? 'active' : '' ?>"><span class="ic">◆</span><span>Dashboard</span></a>
  </div>

  <div class="navgroup">
    <div class="navlabel">Setup</div>
    <a href="/registration" class="navitem <?= (isset($activePage) && $activePage === 'registration') ? 'active' : '' ?>"><span class="ic">⚙</span><span>Society registration</span></a>
  </div>

  <div class="navgroup">
    <div class="navlabel">Society</div>
    <a href="/members" class="navitem <?= (isset($activePage) && $activePage === 'members') ? 'active' : '' ?>"><span class="ic">☰</span><span>Members</span></a>
    <a href="/notices" class="navitem <?= (isset($activePage) && $activePage === 'notices') ? 'active' : '' ?>"><span class="ic">▤</span><span>Notice board</span></a>
    <a href="/vehicles" class="navitem <?= (isset($activePage) && $activePage === 'vehicles') ? 'active' : '' ?>"><span class="ic">▭</span><span>Vehicles</span></a>
  </div>

  <div class="navgroup">
    <div class="navlabel">Finance</div>
    <a href="/maintenance" class="navitem <?= (isset($activePage) && $activePage === 'maintenance') ? 'active' : '' ?>"><span class="ic">%</span><span>Maintenance</span></a>
    <a href="/payments" class="navitem <?= (isset($activePage) && $activePage === 'payments') ? 'active' : '' ?>"><span class="ic">₹</span><span>Payments</span></a>
    <a href="/expenses" class="navitem <?= (isset($activePage) && $activePage === 'expenses') ? 'active' : '' ?>"><span class="ic">–</span><span>Expenses</span></a>
    <a href="/reports" class="navitem <?= (isset($activePage) && $activePage === 'reports') ? 'active' : '' ?>"><span class="ic">▤</span><span>Reports & Tally</span></a>
  </div>

  <div class="sidebar-foot">
    <div style="display:flex; align-items:center; gap:8px;">
      <div class="avatar"><?= strtoupper(substr(Session::get('user_name') ?? 'MH', 0, 2)) ?></div>
      <div><?= htmlspecialchars(Session::get('user_name') ?? 'Member') ?> · Member</div>
    </div>
    <a href="/logout" style="color:#F4E1D8; text-decoration:none; font-size:11px; background:rgba(177,74,46,0.3); padding:4px 8px; border-radius:4px;">Logout</a>
  </div>
</div>
