<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - Meridian Heights CHS</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
  :root{
    --paper:#F3F1E9; --paper-raised:#FFFEFA; --ink:#23281F; --ink-soft:#5B5F52;
    --line:#DAD5C4; --green:#1F5C4A; --green-dark:#123D31; --green-tint:#E4EDE7;
    --gold:#B9812A; --gold-tint:#F5E9D2; --rust:#B14A2E; --rust-tint:#F4E1D8; --radius:10px;
  }
  *{box-sizing:border-box; margin:0; padding:0;}
  body{background:var(--paper); color:var(--ink); font-family:'Inter',sans-serif;}

  /* ---------- app shell ---------- */
  .app{display:flex; min-height:100vh;}
  .sidebar{width:230px; background:var(--green-dark); color:#EFE9DA; flex-shrink:0; padding:26px 18px; display:flex; flex-direction:column;}
  .sidebar .brand{font-family:'Fraunces',serif; font-weight:600; font-size:16px; letter-spacing:.02em; margin-bottom:2px;}
  .sidebar .subbrand{font-size:11px; color:#9FB3A8; margin-bottom:30px;}
  .navgroup{margin-bottom:22px;}
  .navlabel{font-size:10.5px; text-transform:uppercase; letter-spacing:.08em; color:#7C9488; padding:0 12px; margin-bottom:8px;}
  .navitem{display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:8px; font-size:13.5px; font-weight:500; color:#D9E5DD; cursor:pointer; margin-bottom:2px; text-decoration:none;}
  .navitem .ic{width:18px; text-align:center; font-size:14px; opacity:.85;}
  .navitem:hover{background:rgba(255,255,255,.06);}
  .navitem.active{background:#EFE9DA; color:var(--green-dark);}
  .navitem.active .ic{opacity:1;}
  .langswitch{display:flex; border:1px solid rgba(255,255,255,.18); border-radius:8px; overflow:hidden; margin-bottom:18px;}
  .langswitch div{flex:1; text-align:center; padding:8px 6px; font-size:12px; font-weight:500; cursor:pointer; color:#B9C7BE;}
  .langswitch div.active{background:#EFE9DA; color:var(--green-dark);}
  .sidebar-foot{margin-top:auto; padding-top:16px; border-top:1px solid rgba(255,255,255,.12); font-size:11.5px; color:#9FB3A8; display:flex; align-items:center; gap:8px; justify-content:space-between;}
  .avatar{width:26px; height:26px; border-radius:50%; background:var(--gold); color:#fff; display:flex; align-items:center; justify-content:center; font-family:'Fraunces',serif; font-weight:600; font-size:11px; flex-shrink:0;}

  .main{flex:1; padding:32px 40px 80px; overflow-x:hidden;}
  .content-wrap{max-width:1180px; margin:0 auto;}

  .topbar{display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:28px; border-bottom:1.5px solid var(--ink); padding-bottom:18px;}
  .topbar h1{font-family:'Fraunces',serif; font-weight:600; font-size:32px;}
  .topbar .meta{text-align:right; font-size:12.5px; color:var(--ink-soft); line-height:1.6;}
  .topbar .meta b{color:var(--ink); font-weight:500;}

  .stats{display:grid; grid-template-columns:repeat(4,1fr); gap:1px; background:var(--line); border:1px solid var(--line); margin-bottom:24px; border-radius:var(--radius); overflow:hidden;}
  .stat{background:var(--paper-raised); padding:18px 20px;}
  .stat .label{font-size:11.5px; text-transform:uppercase; letter-spacing:.07em; color:var(--ink-soft); margin-bottom:8px;}
  .stat .val{font-family:'Fraunces',serif; font-size:26px; font-weight:600;}
  .stat.warn .val{color:var(--rust);}
  .stat .sub{font-size:12px; color:var(--ink-soft); margin-top:4px;}

  .profile-card{background:var(--paper-raised); border:1px solid var(--line); border-radius:var(--radius); padding:24px; margin-bottom:28px;}
  .profile-card h3{font-family:'Fraunces',serif; font-size:20px; margin-bottom:16px; color:var(--green-dark); border-bottom: 1px solid var(--line); padding-bottom: 10px;}
  .profile-grid{display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;}
  .profile-item label{font-size:11px; text-transform:uppercase; letter-spacing:.06em; color:var(--ink-soft); display:block; margin-bottom:4px;}
  .profile-item span{font-size:14.5px; font-weight:500; color:var(--ink); font-family: 'IBM Plex Mono', monospace;}

  .status-badge{font-size:11px; padding:4px 10px; border-radius:20px; font-weight:600; display:inline-block; background:var(--green-tint); color:var(--green-dark);}

  .ledger{background:var(--paper-raised); border:1px solid var(--line); border-radius:var(--radius); overflow:hidden; margin-bottom:24px;}
  .lrow{display:grid; grid-template-columns: 1fr 1.5fr 1fr 1fr; align-items:center; padding:14px 20px; border-bottom:1px solid var(--line); gap:10px;}
  .lrow:last-child{border-bottom:none;}
  .lrow.head{background:var(--green-tint); font-size:11px; text-transform:uppercase; letter-spacing:.06em; color:var(--green-dark); font-weight:600; padding:11px 20px;}
</style>
</head>
<body>

<div class="app">
    <!-- ===== Reusable Sidebar ===== -->
    <?php $activePage = 'dashboard'; require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="main">
        <div class="content-wrap">

            <?php
            $flashSuccess = Session::getFlash('success');
            $flashError = Session::getFlash('error');
            $flashInfo = Session::getFlash('info');
            ?>
            <?php if ($flashSuccess): ?>
                <div style="padding:12px 16px; border-radius:8px; font-size:13px; margin-bottom:18px; background:var(--green-tint); color:var(--green-dark); border:1px solid rgba(31,92,74,0.3);"><?= htmlspecialchars($flashSuccess) ?></div>
            <?php endif; ?>
            <?php if ($flashError): ?>
                <div style="padding:12px 16px; border-radius:8px; font-size:13px; margin-bottom:18px; background:var(--rust-tint); color:var(--rust); border:1px solid rgba(177,74,46,0.3);"><?= htmlspecialchars($flashError) ?></div>
            <?php endif; ?>
            <?php if ($flashInfo): ?>
                <div style="padding:12px 16px; border-radius:8px; font-size:13px; margin-bottom:18px; background:var(--gold-tint); color:var(--gold); border:1px solid rgba(185,129,42,0.3);"><?= htmlspecialchars($flashInfo) ?></div>
            <?php endif; ?>

            <?php if (!empty($isAdmin)): ?>
                <!-- ================= SYSTEM ADMIN MULTI-SOCIETY DASHBOARD ================= -->
                <div class="topbar">
                    <div>
                        <h1>System Admin Dashboard</h1>
                        <div style="font-size:13.5px; color:var(--ink-soft); margin-top:4px;">
                            Multi-Society Management — Logged in as <b><?= htmlspecialchars($user['name'] ?? 'System Admin') ?></b>
                        </div>
                    </div>
                    <div>
                        <a href="/registration" style="display:inline-block; padding:10px 18px; border-radius:8px; background:var(--gold); color:#fff; font-weight:600; text-decoration:none; font-size:13.5px;">＋ Register New Society</a>
                    </div>
                </div>

                <!-- Admin Quick Stats -->
                <div class="stats">
                    <div class="stat">
                        <div class="label">Registered Societies</div>
                        <div class="val"><?= count($allSocieties ?? []) ?></div>
                        <div class="sub">Active housing societies</div>
                    </div>
                    <div class="stat">
                        <div class="label">Total System Flats</div>
                        <div class="val"><?= array_sum(array_column($allSocieties ?? [], 'total_flats')) ?: 84 ?></div>
                        <div class="sub">Across all societies</div>
                    </div>
                    <div class="stat">
                        <div class="label">Total Members</div>
                        <div class="val"><?= array_sum(array_column($allSocieties ?? [], 'total_members')) ?: 84 ?></div>
                        <div class="sub">On system record</div>
                    </div>
                    <div class="stat">
                        <div class="label">System Balance</div>
                        <div class="val">₹ <?= number_format(array_sum(array_column($allSocieties ?? [], 'bank_balance')), 2) ?></div>
                        <div class="sub">Combined bank balance</div>
                    </div>
                </div>

                <!-- All Societies Portfolio Grid -->
                <h3 style="font-family:'Fraunces',serif; font-size:22px; margin-bottom:16px; color:var(--green-dark);">All Registered Societies</h3>
                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(340px, 1fr)); gap:20px; margin-bottom:32px;">
                    <?php if (!empty($allSocieties)): ?>
                        <?php foreach ($allSocieties as $soc): ?>
                            <div style="background:var(--paper-raised); border:1px solid var(--line); border-radius:var(--radius); padding:24px; display:flex; flex-direction:column; justify-content:space-between;">
                                <div>
                                    <div style="font-family:'Fraunces',serif; font-size:20px; font-weight:600; color:var(--green-dark); margin-bottom:4px;">
                                        <?= htmlspecialchars($soc['name']) ?>
                                    </div>
                                    <div style="font-size:12.5px; color:var(--ink-soft); margin-bottom:12px; line-height:1.4;">
                                        Reg No: <b><?= htmlspecialchars($soc['registration_number'] ?: 'GUJ/AHM/HSG/2014/1123') ?></b><br>
                                        📍 <?= htmlspecialchars($soc['registered_address'] ?: 'Registered Address') ?>
                                    </div>
                                    
                                    <div style="display:flex; gap:8px; margin-bottom:16px; flex-wrap:wrap;">
                                        <span class="status-badge"><?= htmlspecialchars($soc['total_wings'] ?? 4) ?> Wings</span>
                                        <span class="status-badge" style="background:var(--gold-tint); color:var(--gold);"><?= htmlspecialchars($soc['total_flats'] ?? 84) ?> Flats</span>
                                        <span class="status-badge" style="background:#EBF3F5; color:#1C6B72;"><?= htmlspecialchars($soc['total_members'] ?? 84) ?> Members</span>
                                    </div>

                                    <div style="font-size:13px; color:var(--ink-soft); border-top:1px dashed var(--line); padding-top:12px; margin-bottom:16px;">
                                        Bank Balance: <strong style="font-family:'IBM Plex Mono',monospace; color:var(--green-dark);">₹ <?= number_format($soc['bank_balance'] ?? 0, 2) ?></strong><br>
                                        PAN: <span style="font-family:'IBM Plex Mono',monospace;"><?= htmlspecialchars($soc['pan_number'] ?: 'N/A') ?></span>
                                    </div>
                                </div>

                                <div style="display:flex; gap:10px;">
                                    <a href="/select-active-society?id=<?= $soc['id'] ?>" style="flex:1; text-align:center; padding:11px; border-radius:8px; background:var(--green); color:#fff; font-weight:600; text-decoration:none; font-size:13px;">View & Manage Members →</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <!-- Action Card to Create New Society -->
                    <a href="/registration" style="border:2px dashed var(--line); border-radius:var(--radius); padding:24px; text-decoration:none; display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; min-height:220px; transition:all 0.15s ease;">
                        <div style="width:48px; height:48px; border-radius:50%; background:var(--gold-tint); color:var(--gold); display:flex; align-items:center; justify-content:center; font-size:24px; font-weight:bold; margin-bottom:12px;">＋</div>
                        <div style="font-family:'Fraunces',serif; font-size:18px; font-weight:600; color:var(--ink); margin-bottom:4px;">Register New Society</div>
                        <div style="font-size:12.5px; color:var(--ink-soft);">Add another housing society to your admin portfolio</div>
                    </a>
                </div>

            <?php else: ?>
                <!-- ================= RESIDENT / MEMBER DASHBOARD ================= -->
                <!-- Topbar -->
                <div class="topbar">
                    <div>
                        <h1>Society Dashboard</h1>
                        <div style="font-size:13.5px; color:var(--ink-soft); margin-top:4px;">
                            Welcome back, <b><?= htmlspecialchars($user['name']) ?></b>!
                        </div>
                    </div>
                    <div class="meta">
                        Society: <b><?= htmlspecialchars($society['name'] ?? 'Meridian Heights') ?></b><br>
                        Date: <b><?= date('d M Y') ?></b>
                    </div>
                </div>

                <!-- Profile Summary Card -->
                <div class="profile-card">
                    <h3>Member Profile Overview</h3>
                    <div class="profile-grid">
                        <div class="profile-item">
                            <label>Member Name</label>
                            <span><?= htmlspecialchars($user['name']) ?></span>
                        </div>
                        <div class="profile-item">
                            <label>Society Name</label>
                            <span><?= htmlspecialchars($society['name'] ?? 'Meridian Heights') ?></span>
                        </div>
                        <div class="profile-item">
                            <label>Registered Mobile</label>
                            <span><?= htmlspecialchars($user['mobile_number'] ?? 'N/A') ?></span>
                        </div>
                        <div class="profile-item">
                            <label>Account Status</label>
                            <div><span class="status-badge"><?= strtoupper(htmlspecialchars($user['status'] ?? 'ACTIVE')) ?></span></div>
                        </div>
                        <div class="profile-item">
                            <label>Member ID</label>
                            <span>MH-2026-00<?= $user['id'] ?></span>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="stats">
                    <div class="stat">
                        <div class="label">Total Wings / Flats</div>
                        <div class="val"><?= htmlspecialchars($society['total_wings'] ?? 4) ?> / <?= htmlspecialchars($society['total_flats'] ?? 84) ?></div>
                        <div class="sub">Occupied</div>
                    </div>
                    <div class="stat">
                        <div class="label">Registered Members</div>
                        <div class="val"><?= htmlspecialchars($society['total_members'] ?? 84) ?></div>
                        <div class="sub">Active on Portal</div>
                    </div>
                    <div class="stat warn">
                        <div class="label">Pending Maintenance</div>
                        <div class="val">₹ 14,500</div>
                        <div class="sub">3 Flats Overdue</div>
                    </div>
                    <div class="stat">
                        <div class="label">Current Balance</div>
                        <div class="val">₹ <?= number_format($society['bank_balance'] ?? 418200, 2) ?></div>
                        <div class="sub">Bank Account Net</div>
                    </div>
                </div>

                <!-- Recent Activity Ledger -->
                <h3 style="font-family:'Fraunces',serif; font-size:20px; margin-bottom:14px; color:var(--green-dark);">Recent Society Ledger</h3>
                <div class="ledger">
                    <div class="lrow head">
                        <div>Date</div>
                        <div>Flat & Member</div>
                        <div>Category</div>
                        <div style="text-align:right;">Amount</div>
                    </div>
                    <div class="lrow">
                        <div style="font-family:'IBM Plex Mono',monospace; font-size:12.5px; color:var(--ink-soft);"><?= date('Y-m-d') ?></div>
                        <div><strong>Flat A-302</strong> — <?= htmlspecialchars($user['name']) ?></div>
                        <div>Maintenance Collection</div>
                        <div style="text-align:right; font-family:'IBM Plex Mono',monospace; font-weight:600; color:var(--green);">+ ₹ 3,500</div>
                    </div>
                    <div class="lrow">
                        <div style="font-family:'IBM Plex Mono',monospace; font-size:12.5px; color:var(--ink-soft);"><?= date('Y-m-d', strtotime('-2 days')) ?></div>
                        <div><strong>Flat B-104</strong> — Standard Maintenance</div>
                        <div>Quarterly Billing</div>
                        <div style="text-align:right; font-family:'IBM Plex Mono',monospace; font-weight:600; color:var(--green);">+ ₹ 3,500</div>
                    </div>
                    <div class="lrow">
                        <div style="font-family:'IBM Plex Mono',monospace; font-size:12.5px; color:var(--ink-soft);"><?= date('Y-m-d', strtotime('-4 days')) ?></div>
                        <div><strong>Security Vendor</strong> — Security Payroll</div>
                        <div>Monthly Expense</div>
                        <div style="text-align:right; font-family:'IBM Plex Mono',monospace; font-weight:600; color:var(--rust);">- ₹ 28,000</div>
                    </div>
                </div>
            <?php endif; ?>

        </div>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/drawers.php'; ?>
</body>
</html>
