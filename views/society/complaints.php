<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Complaints - Meridian Heights CHS</title>
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
  .main{flex:1; padding:32px 40px 80px; overflow-x:hidden;}
  .content-wrap{max-width:1180px; margin:0 auto;}
  .topbar{display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:28px; border-bottom:1.5px solid var(--ink); padding-bottom:18px;}
  .topbar h1{font-family:'Fraunces',serif; font-weight:600; font-size:32px;}
  .topbar .meta{text-align:right; font-size:12.5px; color:var(--ink-soft);}
  .stats{display:grid; grid-template-columns:repeat(4,1fr); gap:1px; background:var(--line); border:1px solid var(--line); margin-bottom:24px; border-radius:var(--radius); overflow:hidden;}
  .stat{background:var(--paper-raised); padding:18px 20px;}
  .stat .label{font-size:11.5px; text-transform:uppercase; letter-spacing:.07em; color:var(--ink-soft); margin-bottom:8px;}
  .stat .val{font-family:'Fraunces',serif; font-size:26px; font-weight:600;}
  .controls{display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; flex-wrap:wrap; gap:12px;}
  .btn{border:none; font-family:'Inter',sans-serif; font-weight:500; font-size:13.5px; padding:11px 20px; border-radius:var(--radius); cursor:pointer; background:var(--green); color:#fff;}
  .ledger{background:var(--paper-raised); border:1px solid var(--line); border-radius:var(--radius); overflow:hidden;}
  .lrow{display:grid; align-items:center; padding:14px 20px; border-bottom:1px solid var(--line); gap:10px;}
  .lrow.head{background:var(--green-tint); font-size:11px; text-transform:uppercase; color:var(--green-dark); font-weight:600;}
  .flat{font-family:'IBM Plex Mono',monospace; font-size:13px; font-weight:500;}
  .status{font-size:11px; padding:4px 10px; border-radius:20px; font-weight:600; text-align:center; display:inline-block;}
  .status.open{background:var(--rust-tint); color:var(--rust);}
  .status.in-progress{background:var(--gold-tint); color:var(--gold);}
  .status.resolved{background:var(--green-tint); color:var(--green-dark);}
  .alert { padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 18px; line-height: 1.5; }
  .alert-danger { background: var(--rust-tint); color: var(--rust); border: 1px solid rgba(177,74,46,0.3); }
  .alert-success { background: var(--green-tint); color: var(--green-dark); border: 1px solid rgba(31,92,74,0.3); }
</style>
</head>
<body>

<div class="app">
  <?php $activePage = 'complaints'; require_once __DIR__ . '/../layouts/sidebar.php'; ?>

  <div class="main">
    <div class="content-wrap">

      <div class="topbar">
        <h1>Resident Complaints</h1>
        <div class="meta">Helpdesk & Support<br><b><?= count($complaints ?? []) ?></b> issues registered</div>
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

      <div class="stats">
        <div class="stat"><div class="label">Total Complaints</div><div class="val"><?= count($complaints ?? []) ?></div><div class="sub">All time</div></div>
        <div class="stat"><div class="label">Open Issues</div><div class="val">
          <?= count(array_filter($complaints ?? [], fn($c) => $c['status'] === 'Open')) ?>
        </div><div class="sub">Pending review</div></div>
        <div class="stat"><div class="label">In Progress</div><div class="val">
          <?= count(array_filter($complaints ?? [], fn($c) => $c['status'] === 'In Progress')) ?>
        </div><div class="sub">Work assigned</div></div>
        <div class="stat"><div class="label">Resolved</div><div class="val">
          <?= count(array_filter($complaints ?? [], fn($c) => $c['status'] === 'Resolved')) ?>
        </div><div class="sub">Closed issues</div></div>
      </div>

      <div class="controls">
        <h2 style="font-family:'Fraunces',serif; font-size:20px; color:var(--green-dark);">Complaints Register</h2>
        <button class="btn" onclick="document.getElementById('fileComplaintModal').classList.add('open')">＋ File Complaint</button>
      </div>

      <div class="ledger">
        <div class="lrow head" style="grid-template-columns:80px 1.2fr 1fr 100px 110px 120px;">
          <div>Flat</div><div>Subject / Issue</div><div>Description</div><div style="text-align:center">Category</div><div style="text-align:center">Status</div><div style="text-align:center">Update Status</div>
        </div>
        
        <?php if (!empty($complaints)): ?>
          <?php foreach ($complaints as $comp): ?>
            <?php 
              $stVal = $comp['status'] ?? 'Open';
              $stClass = strtolower(str_replace(' ', '-', $stVal));
              $createdTime = !empty($comp['created_at']) ? date('d M Y, h:i A', strtotime($comp['created_at'])) : 'Recent';
            ?>
            <div class="lrow" style="grid-template-columns:80px 1.2fr 1fr 100px 110px 120px;">
              <div class="flat"><?= htmlspecialchars($comp['flat_number'] ?? 'N/A') ?></div>
              <div style="font-weight:500; font-size:13.5px;">
                <?= htmlspecialchars($comp['title'] ?? 'Untitled') ?>
                <span style="display:block; font-size:11.5px; color:var(--ink-soft); font-weight:400;"><?= $createdTime ?></span>
              </div>
              <div style="font-size:12.5px; color:var(--ink-soft); line-height:1.4;"><?= htmlspecialchars($comp['description'] ?? '') ?></div>
              <div style="text-align:center; font-size:12px; color:var(--ink-soft);"><?= htmlspecialchars($comp['category'] ?? 'General') ?></div>
              <div style="display:flex; justify-content:center">
                <span class="status <?= $stClass ?>"><?= htmlspecialchars($stVal) ?></span>
              </div>
              <div style="text-align:center">
                <form action="/complaints/update-status" method="POST" style="display:inline-block;">
                  <input type="hidden" name="complaint_id" value="<?= $comp['id'] ?? 0 ?>">
                  <select name="status" onchange="this.form.submit()" style="font-size:11px; padding:4px 6px; border-radius:6px; border:1px solid var(--line);">
                    <option value="Open" <?= $stVal === 'Open' ? 'selected' : '' ?>>Open</option>
                    <option value="In Progress" <?= $stVal === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                    <option value="Resolved" <?= $stVal === 'Resolved' ? 'selected' : '' ?>>Resolved</option>
                  </select>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="lrow" style="grid-template-columns:80px 1.2fr 1fr 100px 110px 120px;">
            <div class="flat">A-102</div>
            <div style="font-weight:500; font-size:13.5px;">Water Leakage in Main Pipe<span style="display:block; font-size:11.5px; color:var(--ink-soft);">10 Sep 2026</span></div>
            <div style="font-size:12.5px; color:var(--ink-soft);">Water leaking continuously from the ceiling pipe near entrance.</div>
            <div style="text-align:center; font-size:12px;">Plumbing</div>
            <div style="display:flex; justify-content:center"><span class="status open">Open</span></div>
            <div style="text-align:center"><span style="font-size:11.5px; color:var(--ink-soft);">Admin / Committee Action</span></div>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layouts/drawers.php'; ?>
</body>
</html>
