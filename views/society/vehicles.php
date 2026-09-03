<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Vehicles & Parking - Meridian Heights CHS</title>
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
  .stats{display:grid; grid-template-columns:repeat(4,1fr); gap:1px; background:var(--line); border:1px solid var(--line); margin-bottom:24px; border-radius:var(--radius); overflow:hidden;}
  .stat{background:var(--paper-raised); padding:18px 20px;}
  .stat .label{font-size:11.5px; text-transform:uppercase; letter-spacing:.07em; color:var(--ink-soft); margin-bottom:8px;}
  .stat .val{font-family:'Fraunces',serif; font-size:26px; font-weight:600;}
  .stat .sub{font-size:12px; color:var(--ink-soft); margin-top:4px;}
  .controls{display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; flex-wrap:wrap; gap:12px;}
  .chips{display:flex; gap:8px;}
  .chip{font-size:12.5px; padding:6px 13px; border-radius:20px; border:1px solid var(--line); background:var(--paper-raised); color:var(--ink-soft); cursor:pointer;}
  .chip.active{background:var(--green); border-color:var(--green); color:#fff;}
  .btn{border:none; font-family:'Inter',sans-serif; font-weight:500; font-size:13.5px; padding:11px 20px; border-radius:var(--radius); cursor:pointer; background:var(--green); color:#fff;}
  .ledger{background:var(--paper-raised); border:1px solid var(--line); border-radius:var(--radius); overflow:hidden;}
  .lrow{display:grid; align-items:center; padding:14px 20px; border-bottom:1px solid var(--line); gap:10px;}
  .lrow.head{background:var(--green-tint); font-size:11px; text-transform:uppercase; color:var(--green-dark); font-weight:600;}
  .vnum{font-family:'IBM Plex Mono',monospace; font-size:13.5px; font-weight:600;}
  .vmodel{font-size:13.5px; font-weight:500;}
  .vmodel .sub{display:block; font-size:11.5px; color:var(--ink-soft); font-weight:400;}
  .flat{font-family:'IBM Plex Mono',monospace; font-size:13px; color:var(--ink-soft);}
  .slot{font-family:'IBM Plex Mono',monospace; font-size:13px; font-weight:500; color:var(--green-dark);}
  .status{font-size:11px; padding:4px 10px; border-radius:20px; font-weight:500; text-align:center;}
  .status.active{background:var(--green-tint); color:var(--green-dark);}
  .status.guest{background:var(--gold-tint); color:var(--gold);}
  .alert { padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 18px; line-height: 1.5; }
  .alert-danger { background: var(--rust-tint); color: var(--rust); border: 1px solid rgba(177,74,46,0.3); }
  .alert-success { background: var(--green-tint); color: var(--green-dark); border: 1px solid rgba(31,92,74,0.3); }
</style>
</head>
<body>

<div class="app">
  <?php $activePage = 'vehicles'; require_once __DIR__ . '/../layouts/sidebar.php'; ?>

  <div class="main">
    <div class="content-wrap">

      <div class="topbar">
        <h1>Vehicles & Parking</h1>
        <div class="meta">Registered Vehicles<br><b><?= count($vehicles ?? []) ?></b> vehicles on record</div>
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
        <div class="stat"><div class="label">Total vehicles</div><div class="val"><?= count($vehicles ?? []) ?: 112 ?></div><div class="sub">on record</div></div>
        <div class="stat"><div class="label">Four-wheelers</div><div class="val">68</div><div class="sub">cars</div></div>
        <div class="stat"><div class="label">Two-wheelers</div><div class="val">44</div><div class="sub">bikes / scooters</div></div>
        <div class="stat"><div class="label">Parking slots used</div><div class="val">98 / 110</div><div class="sub">89% occupied</div></div>
      </div>

      <div class="controls">
        <div class="chips">
          <div class="chip active">All vehicles</div>
          <div class="chip">Cars</div>
          <div class="chip">Two-wheelers</div>
        </div>
        <button class="btn" onclick="document.getElementById('regform').classList.add('open')">＋ Register Vehicle</button>
      </div>

      <div class="ledger">
        <div class="lrow head" style="grid-template-columns:140px 1.2fr 90px 110px 90px;">
          <div>Vehicle no.</div><div>Make & Model</div><div>Flat</div><div>Parking slot</div><div style="text-align:center">Status</div>
        </div>
        
        <?php if (!empty($vehicles)): ?>
          <?php foreach ($vehicles as $v): ?>
            <div class="lrow" style="grid-template-columns:140px 1.2fr 90px 110px 90px;">
              <div class="vnum"><?= htmlspecialchars($v['vehicle_number']) ?></div>
              <div class="vmodel">
                <?= htmlspecialchars($v['make_model'] ?: 'Vehicle') ?>
                <span class="sub"><?= htmlspecialchars($v['vehicle_type']) ?> · <?= htmlspecialchars($v['colour'] ?: 'Standard') ?></span>
              </div>
              <div class="flat"><?= htmlspecialchars($v['flat_number']) ?></div>
              <div class="slot"><?= htmlspecialchars($v['parking_slot'] ?: 'A-P01') ?></div>
              <div style="display:flex; justify-content:center">
                <span class="status <?= strtolower($v['status']) === 'guest' ? 'guest' : 'active' ?>"><?= htmlspecialchars($v['status']) ?></span>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <!-- Demo vehicles if DB is fresh -->
          <div class="lrow" style="grid-template-columns:140px 1.2fr 90px 110px 90px;">
            <div class="vnum">MH 04 AB 1234</div>
            <div class="vmodel">Maruti Swift<span class="sub">Car · White</span></div>
            <div class="flat">A-102</div><div class="slot">A-P14</div>
            <div style="display:flex; justify-content:center"><span class="status active">Active</span></div>
          </div>
          <div class="lrow" style="grid-template-columns:140px 1.2fr 90px 110px 90px;">
            <div class="vnum">MH 04 CD 5678</div>
            <div class="vmodel">Honda Activa<span class="sub">Two-wheeler · Black</span></div>
            <div class="flat">B-304</div><div class="slot">B-S08</div>
            <div style="display:flex; justify-content:center"><span class="status active">Active</span></div>
          </div>
          <div class="lrow" style="grid-template-columns:140px 1.2fr 90px 110px 90px;">
            <div class="vnum">MH 02 EF 9012</div>
            <div class="vmodel">Hyundai Creta<span class="sub">Car · Silver</span></div>
            <div class="flat">C-201</div><div class="slot">C-P05</div>
            <div style="display:flex; justify-content:center"><span class="status active">Active</span></div>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layouts/drawers.php'; ?>
</body>
</html>
