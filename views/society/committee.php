<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Managing Committee - Meridian Heights CHS</title>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
  :root{ --paper:#F3F1E9; --paper-raised:#FFFEFA; --ink:#23281F; --ink-soft:#5B5F52; --line:#DAD5C4; --green:#1F5C4A; --green-dark:#123D31; --green-tint:#E4EDE7; --gold:#B9812A; --gold-tint:#F5E9D2; --rust:#B14A2E; --rust-tint:#F4E1D8; --radius:10px; }
  *{box-sizing:border-box; margin:0; padding:0;}
  body{background:var(--paper); color:var(--ink); font-family:'Inter',sans-serif;}
  .app{display:flex; min-height:100vh;}
  .sidebar{width:230px; background:var(--green-dark); color:#EFE9DA; flex-shrink:0; padding:26px 18px; display:flex; flex-direction:column;}
  .sidebar .brand{font-family:'Fraunces',serif; font-weight:600; font-size:16px; margin-bottom:2px;}
  .sidebar .subbrand{font-size:11px; color:#9FB3A8; margin-bottom:30px;}
  .navgroup{margin-bottom:22px;}
  .navlabel{font-size:10.5px; text-transform:uppercase; letter-spacing:.08em; color:#7C9488; padding:0 12px; margin-bottom:8px;}
  .navitem{display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:8px; font-size:13.5px; font-weight:500; color:#D9E5DD; cursor:pointer; margin-bottom:2px; text-decoration:none;}
  .navitem .ic{width:18px; text-align:center; font-size:14px; opacity:.85;}
  .navitem:hover{background:rgba(255,255,255,.06);}
  .navitem.active{background:#EFE9DA; color:var(--green-dark);}
  .langswitch{display:flex; border:1px solid rgba(255,255,255,.18); border-radius:8px; overflow:hidden; margin-bottom:18px;}
  .langswitch div{flex:1; text-align:center; padding:8px 6px; font-size:12px; font-weight:500; color:#B9C7BE;}
  .langswitch div.active{background:#EFE9DA; color:var(--green-dark);}
  .sidebar-foot{margin-top:auto; padding-top:16px; border-top:1px solid rgba(255,255,255,.12); font-size:11.5px; color:#9FB3A8; display:flex; align-items:center; justify-content:space-between;}
  .avatar{width:26px; height:26px; border-radius:50%; background:var(--gold); color:#fff; display:flex; align-items:center; justify-content:center; font-family:'Fraunces',serif; font-weight:600; font-size:11px;}
  .main{flex:1; padding:32px 40px 80px; overflow-x:hidden;}
  .content-wrap{max-width:1180px; margin:0 auto;}
  .topbar{display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:28px; border-bottom:1.5px solid var(--ink); padding-bottom:18px;}
  .topbar h1{font-family:'Fraunces',serif; font-weight:600; font-size:32px;}
  .topbar .meta{text-align:right; font-size:12.5px; color:var(--ink-soft);}
  
  .stats{display:grid; grid-template-columns:repeat(4,1fr); gap:1px; background:var(--line); border:1px solid var(--line); margin-bottom:28px; border-radius:var(--radius); overflow:hidden;}
  .stat{background:var(--paper-raised); padding:18px 20px;}
  .stat .label{font-size:11.5px; text-transform:uppercase; letter-spacing:.07em; color:var(--ink-soft); margin-bottom:8px;}
  .stat .val{font-family:'Fraunces',serif; font-size:26px; font-weight:600;}
  .stat .sub{font-size:12px; color:var(--ink-soft); margin-top:4px;}

  /* Executive Cards Grid */
  .exec-grid{display:grid; grid-template-columns:repeat(3, 1fr); gap:20px; margin-bottom:32px;}
  .exec-card{background:var(--paper-raised); border:1px solid var(--line); border-radius:var(--radius); padding:24px; position:relative; box-shadow:0 2px 8px rgba(0,0,0,0.02);}
  .exec-card.chairman{border-top:4px solid var(--gold);}
  .exec-card.secretary{border-top:4px solid var(--green);}
  .exec-card.treasurer{border-top:4px solid var(--rust);}
  .exec-card .role-tag{font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; margin-bottom:12px; display:inline-block; padding:3px 10px; border-radius:12px;}
  .exec-card.chairman .role-tag{background:var(--gold-tint); color:var(--gold);}
  .exec-card.secretary .role-tag{background:var(--green-tint); color:var(--green-dark);}
  .exec-card.treasurer .role-tag{background:var(--rust-tint); color:var(--rust);}
  
  .exec-card .person{display:flex; align-items:center; gap:14px; margin-bottom:16px;}
  .exec-card .person-avatar{width:46px; height:46px; border-radius:50%; background:var(--green-tint); color:var(--green-dark); font-family:'Fraunces',serif; font-weight:600; font-size:18px; display:flex; align-items:center; justify-content:center;}
  .exec-card.chairman .person-avatar{background:var(--gold-tint); color:var(--gold);}
  .exec-card.treasurer .person-avatar{background:var(--rust-tint); color:var(--rust);}
  
  .exec-card .name{font-family:'Fraunces',serif; font-size:18px; font-weight:600; color:var(--ink);}
  .exec-card .flat-info{font-family:'IBM Plex Mono',monospace; font-size:12.5px; color:var(--ink-soft); margin-top:2px;}
  .exec-card .contact-info{font-size:12.5px; color:var(--ink-soft); line-height:1.5; border-top:1px dashed var(--line); padding-top:12px; margin-bottom:16px;}
  .exec-card .action-btn{width:100%; border:1px solid var(--line); background:var(--paper); padding:8px; border-radius:6px; font-size:12.5px; font-weight:500; cursor:pointer; color:var(--ink); transition:all 0.15s Ease;}
  .exec-card .action-btn:hover{background:var(--green-tint); border-color:var(--green); color:var(--green-dark);}

  .controls{display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; flex-wrap:wrap; gap:12px;}
  .chips{display:flex; gap:8px;}
  .chip{font-size:12.5px; padding:6px 13px; border-radius:20px; border:1px solid var(--line); background:var(--paper-raised); color:var(--ink-soft); cursor:pointer;}
  .chip.active{background:var(--green); border-color:var(--green); color:#fff;}
  .btn{border:none; font-family:'Inter',sans-serif; font-weight:500; font-size:13.5px; padding:11px 20px; border-radius:var(--radius); cursor:pointer; background:var(--green); color:#fff;}
  
  .ledger{background:var(--paper-raised); border:1px solid var(--line); border-radius:var(--radius); overflow:hidden;}
  .lrow{display:grid; align-items:center; padding:14px 20px; border-bottom:1px solid var(--line); gap:10px;}
  .lrow.head{background:var(--green-tint); font-size:11px; text-transform:uppercase; color:var(--green-dark); font-weight:600;}
  .flat{font-family:'IBM Plex Mono',monospace; font-size:13px; font-weight:500;}
  .owner{font-size:13.5px; font-weight:500;}
  .owner .sub{display:block; font-size:11.5px; color:var(--ink-soft); font-weight:400;}
  .contact{font-size:12.5px; color:var(--ink-soft);}
  .badge{font-size:11px; padding:4px 10px; border-radius:20px; font-weight:600; text-align:center; display:inline-block;}
  .badge.chairman{background:var(--gold-tint); color:var(--gold); border:1px solid rgba(185,129,42,0.3);}
  .badge.secretary{background:var(--green-tint); color:var(--green-dark); border:1px solid rgba(31,92,74,0.3);}
  .badge.treasurer{background:var(--rust-tint); color:var(--rust); border:1px solid rgba(177,74,46,0.3);}
  .badge.committee{background:#EBF3F5; color:#1C6B72; border:1px solid rgba(28,107,114,0.3);}
  .badge.resident{background:var(--paper); color:var(--ink-soft); border:1px solid var(--line);}
  
  .rowbtn{font-size:11.5px; padding:6px 12px; border-radius:7px; border:1px solid var(--line); background:#fff; color:var(--green-dark); cursor:pointer; font-weight:500;}
  .rowbtn:hover{background:var(--green-tint); border-color:var(--green);}

  .alert { padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 18px; line-height: 1.5; }
  .alert-danger { background: var(--rust-tint); color: var(--rust); border: 1px solid rgba(177,74,46,0.3); }
  .alert-success { background: var(--green-tint); color: var(--green-dark); border: 1px solid rgba(31,92,74,0.3); }
</style>
</head>
<body>

<div class="app">
  <?php $activePage = 'committee'; require_once __DIR__ . '/../layouts/sidebar.php'; ?>

  <div class="main">
    <div class="content-wrap">

      <?php
      // Filter out maulik@septixtechnologies.com from user/member list display
      $filteredMembers = array_filter($allMembers ?? [], function($m) {
          return strtolower(trim($m['owner_email'] ?? '')) !== 'maulik@septixtechnologies.com';
      });

      // Extract specific office bearers
      $chairman = null;
      $secretary = null;
      $treasurer = null;
      $generalCommittee = [];
      $normalResidents = [];

      foreach ($filteredMembers as $m) {
          $role = $m['committee_role'] ?? 'Resident';
          if ($role === 'Chairman') $chairman = $m;
          elseif ($role === 'Secretary') $secretary = $m;
          elseif ($role === 'Treasurer') $treasurer = $m;
          elseif ($role === 'Committee Member') $generalCommittee[] = $m;
          else $normalResidents[] = $m;
      }
      ?>

      <div class="topbar">
        <h1>Managing Committee</h1>
        <div class="meta">Society Executive Body<br><b><?= count($filteredMembers) ?></b> residents total</div>
      </div>

      <?php
      $flashSuccess = Session::getFlash('success');
      $flashError = Session::getFlash('error');
      ?>
      <?php if ($flashSuccess): ?>
        <div class="alert alert-success"><?= htmlspecialchars($flashSuccess) ?></div>
      <?php endif; ?>
      <?php if ($flashError): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($flashError) ?></div>
      <?php endif; ?>

      <!-- Statistics Bar -->
      <div class="stats">
        <div class="stat"><div class="label">Office Bearers</div><div class="val"><?= ($chairman ? 1 : 0) + ($secretary ? 1 : 0) + ($treasurer ? 1 : 0) ?></div><div class="sub">Chairman, Sec., Treas.</div></div>
        <div class="stat"><div class="label">Committee Members</div><div class="val"><?= count($generalCommittee) ?></div><div class="sub">Executive members</div></div>
        <div class="stat"><div class="label">Normal Residents</div><div class="val"><?= count($normalResidents) ?></div><div class="sub">General members</div></div>
        <div class="stat"><div class="label">Total Roster</div><div class="val"><?= count($filteredMembers) ?></div><div class="sub">Active flat records</div></div>
      </div>

      <!-- Executive Office Bearers Cards -->
      <h2 style="font-family:'Fraunces',serif; font-size:20px; margin-bottom:16px; color:var(--green-dark);">Executive Office Bearers</h2>
      <div class="exec-grid">
        
        <!-- Chairman Card -->
        <div class="exec-card chairman">
          <span class="role-tag">★ Society Chairman</span>
          <div class="person">
            <div class="person-avatar"><?= $chairman ? strtoupper(substr($chairman['owner_name'], 0, 1)) : '?' ?></div>
            <div>
              <div class="name"><?= $chairman ? htmlspecialchars($chairman['owner_name']) : 'Not Appointed' ?></div>
              <div class="flat-info">Flat: <?= $chairman ? htmlspecialchars($chairman['flat_number']) : 'N/A' ?></div>
            </div>
          </div>
          <div class="contact-info">
            📞 Phone: <?= $chairman ? htmlspecialchars($chairman['owner_phone'] ?: 'N/A') : 'N/A' ?><br>
            ✉ Email: <?= $chairman ? htmlspecialchars($chairman['owner_email'] ?: 'N/A') : 'N/A' ?>
          </div>
          <button class="action-btn" onclick="openAssignModal(<?= $chairman ? $chairman['id'] : '' ?>, 'Chairman')">Assign / Change Chairman</button>
        </div>

        <!-- Secretary Card -->
        <div class="exec-card secretary">
          <span class="role-tag">📜 General Secretary</span>
          <div class="person">
            <div class="person-avatar"><?= $secretary ? strtoupper(substr($secretary['owner_name'], 0, 1)) : '?' ?></div>
            <div>
              <div class="name"><?= $secretary ? htmlspecialchars($secretary['owner_name']) : 'Not Appointed' ?></div>
              <div class="flat-info">Flat: <?= $secretary ? htmlspecialchars($secretary['flat_number']) : 'N/A' ?></div>
            </div>
          </div>
          <div class="contact-info">
            📞 Phone: <?= $secretary ? htmlspecialchars($secretary['owner_phone'] ?: 'N/A') : 'N/A' ?><br>
            ✉ Email: <?= $secretary ? htmlspecialchars($secretary['owner_email'] ?: 'N/A') : 'N/A' ?>
          </div>
          <button class="action-btn" onclick="openAssignModal(<?= $secretary ? $secretary['id'] : '' ?>, 'Secretary')">Assign / Change Secretary</button>
        </div>

        <!-- Treasurer Card -->
        <div class="exec-card treasurer">
          <span class="role-tag">💰 Society Treasurer</span>
          <div class="person">
            <div class="person-avatar"><?= $treasurer ? strtoupper(substr($treasurer['owner_name'], 0, 1)) : '?' ?></div>
            <div>
              <div class="name"><?= $treasurer ? htmlspecialchars($treasurer['owner_name']) : 'Not Appointed' ?></div>
              <div class="flat-info">Flat: <?= $treasurer ? htmlspecialchars($treasurer['flat_number']) : 'N/A' ?></div>
            </div>
          </div>
          <div class="contact-info">
            📞 Phone: <?= $treasurer ? htmlspecialchars($treasurer['owner_phone'] ?: 'N/A') : 'N/A' ?><br>
            ✉ Email: <?= $treasurer ? htmlspecialchars($treasurer['owner_email'] ?: 'N/A') : 'N/A' ?>
          </div>
          <button class="action-btn" onclick="openAssignModal(<?= $treasurer ? $treasurer['id'] : '' ?>, 'Treasurer')">Assign / Change Treasurer</button>
        </div>

      </div>

      <!-- Controls & Roster Filter -->
      <div class="controls">
        <h2 style="font-family:'Fraunces',serif; font-size:20px; color:var(--green-dark);">Society Roster & Designation Assignment</h2>
        <button class="btn" onclick="document.getElementById('assignCommitteeModal').classList.add('open')">＋ Assign Committee Role</button>
      </div>

      <!-- Complete Society Roster Ledger -->
      <div class="ledger">
        <div class="lrow head" style="grid-template-columns:90px 1.2fr 1.2fr 140px 110px;">
          <div>Flat</div><div>Member Name</div><div>Contact</div><div style="text-align:center">Designated Role</div><div style="text-align:center">Action</div>
        </div>
        
        <?php if (!empty($filteredMembers)): ?>
          <?php foreach ($filteredMembers as $m): ?>
            <?php 
              $role = $m['committee_role'] ?? 'Resident';
              $badgeClass = 'resident';
              if ($role === 'Chairman') $badgeClass = 'chairman';
              elseif ($role === 'Secretary') $badgeClass = 'secretary';
              elseif ($role === 'Treasurer') $badgeClass = 'treasurer';
              elseif ($role === 'Committee Member') $badgeClass = 'committee';
            ?>
            <div class="lrow" style="grid-template-columns:90px 1.2fr 1.2fr 140px 110px;">
              <div class="flat"><?= htmlspecialchars($m['flat_number']) ?></div>
              <div class="owner">
                <?= htmlspecialchars($m['owner_name']) ?>
                <span class="sub"><?= $m['is_rented'] ? 'Tenant: ' . htmlspecialchars($m['tenant_name']) : 'Owner Occupied' ?> · <?= htmlspecialchars($m['area_sqft']) ?> sq.ft</span>
              </div>
              <div class="contact">
                <?= htmlspecialchars($m['owner_phone'] ?: '+91 98200 11234') ?><br>
                <small><?= htmlspecialchars($m['owner_email'] ?: 'resident@society.com') ?></small>
              </div>
              <div style="display:flex; justify-content:center">
                <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($role) ?></span>
              </div>
              <div style="text-align:center">
                <button class="rowbtn" onclick="openAssignModal(<?= $m['id'] ?>, '<?= htmlspecialchars($role) ?>')">Edit Role</button>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="lrow" style="grid-template-columns:90px 1.2fr 1.2fr 140px 110px;">
            <div class="flat">A-102</div><div class="owner">Rekha Iyer<span class="sub">Owner · 980 sq.ft</span></div>
            <div class="contact">+91 98200 11234</div>
            <div style="display:flex; justify-content:center"><span class="badge resident">Resident</span></div>
            <div style="text-align:center"><button class="rowbtn" onclick="document.getElementById('assignCommitteeModal').classList.add('open')">Edit Role</button></div>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layouts/drawers.php'; ?>

<script>
function openAssignModal(memberId, role) {
    if (memberId) {
        const select = document.getElementById('assignMemberId');
        if (select) select.value = memberId;
    }
    if (role) {
        const roleSelect = document.getElementById('assignCommitteeRoleSelect');
        if (roleSelect) roleSelect.value = role;
    }
    const modal = document.getElementById('assignCommitteeModal');
    if (modal) modal.classList.add('open');
}
</script>
</body>
</html>
