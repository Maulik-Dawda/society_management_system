<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Notice Board - Meridian Heights CHS</title>
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
  .controls{display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px;}
  .chips{display:flex; gap:8px;}
  .chip{font-size:12.5px; padding:6px 13px; border-radius:20px; border:1px solid var(--line); background:var(--paper-raised); color:var(--ink-soft); cursor:pointer;}
  .chip.active{background:var(--green); border-color:var(--green); color:#fff;}
  .btn{border:none; font-family:'Inter',sans-serif; font-weight:500; font-size:13.5px; padding:11px 20px; border-radius:var(--radius); cursor:pointer; background:var(--green); color:#fff;}
  .noticegrid{display:grid; grid-template-columns:1fr 1fr; gap:20px;}
  .ncard{background:var(--paper-raised); border:1px solid var(--line); border-radius:var(--radius); padding:24px; position:relative;}
  .ncard.urgent{border-left:4px solid var(--rust);}
  .ncard .tag{font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:var(--gold); margin-bottom:8px;}
  .ncard.urgent .tag{color:var(--rust);}
  .ncard h2{font-family:'Fraunces',serif; font-size:19px; font-weight:600; margin-bottom:10px; color:var(--green-dark);}
  .ncard .body{font-size:13.5px; color:var(--ink-soft); line-height:1.6; margin-bottom:16px;}
  .ncard .foot{font-size:11.5px; color:var(--ink-soft); border-top:1px solid var(--line); padding-top:12px; display:flex; justify-content:space-between;}
  .alert { padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 18px; line-height: 1.5; }
  .alert-danger { background: var(--rust-tint); color: var(--rust); border: 1px solid rgba(177,74,46,0.3); }
  .alert-success { background: var(--green-tint); color: var(--green-dark); border: 1px solid rgba(31,92,74,0.3); }
</style>
</head>
<body>

<div class="app">
  <?php $activePage = 'notices'; require_once __DIR__ . '/../layouts/sidebar.php'; ?>

  <div class="main">
    <div class="content-wrap">

      <div class="topbar">
        <h1>Notice Board</h1>
        <div class="meta">Society Announcements<br><b><?= count($notices ?? []) ?></b> published notices</div>
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

      <div class="controls">
        <div class="chips">
          <div class="chip active">All notices</div>
          <div class="chip">General</div>
          <div class="chip">Maintenance</div>
          <div class="chip">Urgent</div>
        </div>
        <button class="btn" onclick="document.getElementById('postNoticeModal').classList.add('open')">＋ Post Notice</button>
      </div>

      <div class="noticegrid">
        <?php if (!empty($notices)): ?>
          <?php foreach ($notices as $n): ?>
            <div class="ncard <?= $n['is_urgent'] ? 'urgent' : '' ?>">
              <div class="tag"><?= $n['is_urgent'] ? '⚠️ URGENT · ' : '' ?><?= htmlspecialchars($n['category']) ?></div>
              <h2><?= htmlspecialchars($n['title']) ?></h2>
              <div class="body"><?= nl2br(htmlspecialchars($n['content'])) ?></div>
              <div class="foot">
                <span>Posted on <?= date('d M Y', strtotime($n['notice_date'])) ?></span>
                <span>Managing Committee</span>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <!-- Demo notices if DB is fresh -->
          <div class="ncard urgent">
            <div class="tag">⚠️ URGENT · Water Supply</div>
            <h2>Water Tank Cleaning Schedule</h2>
            <div class="body">Overhead water tank cleaning is scheduled for this Sunday from 9:00 AM to 2:00 PM. Water supply will remain suspended during this window. Please store sufficient water in advance.</div>
            <div class="foot"><span>Posted on <?= date('d M Y') ?></span><span>Managing Committee</span></div>
          </div>

          <div class="ncard">
            <div class="tag">General · Annual Meeting</div>
            <h2>Annual General Body Meeting (AGM)</h2>
            <div class="body">Notice is hereby given that the 12th Annual General Body Meeting of Meridian Heights CHS will be held on 15th September 2026 at the Clubhouse. All members are requested to attend.</div>
            <div class="foot"><span>Posted on <?= date('d M Y', strtotime('-3 days')) ?></span><span>Secretary</span></div>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layouts/drawers.php'; ?>
</body>
</html>
