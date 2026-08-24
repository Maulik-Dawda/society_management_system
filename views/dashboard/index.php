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

            <!-- Topbar -->
            <div class="topbar">
                <div>
                    <h1>Society Dashboard</h1>
                    <div style="font-size:13.5px; color:var(--ink-soft); margin-top:4px;">
                        Welcome back, <b><?= htmlspecialchars($user['name']) ?></b>!
                    </div>
                </div>
                <div class="meta">
                    Society: <b><?= htmlspecialchars($user['society_name']) ?></b><br>
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
                        <span><?= htmlspecialchars($user['society_name']) ?></span>
                    </div>
                    <div class="profile-item">
                        <label>Registered Mobile</label>
                        <span><?= htmlspecialchars($user['mobile_number']) ?></span>
                    </div>
                    <div class="profile-item">
                        <label>Account Status</label>
                        <div><span class="status-badge"><?= strtoupper(htmlspecialchars($user['status'])) ?></span></div>
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
                    <div class="val">48 / 48</div>
                    <div class="sub">100% Occupied</div>
                </div>
                <div class="stat">
                    <div class="label">Registered Members</div>
                    <div class="val">42</div>
                    <div class="sub">Active on Portal</div>
                </div>
                <div class="stat warn">
                    <div class="label">Pending Maintenance</div>
                    <div class="val">₹ 14,500</div>
                    <div class="sub">3 Flats Overdue</div>
                </div>
                <div class="stat">
                    <div class="label">Current Balance</div>
                    <div class="val">₹ 4,82,900</div>
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

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/drawers.php'; ?>
</body>
</html>
