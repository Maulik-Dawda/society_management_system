<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Maintenance - Meridian Heights CHS</title>
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
  .stat.warn .val{color:var(--rust);}
  .stat .sub{font-size:12px; color:var(--ink-soft); margin-top:4px;}
  .barwrap{background:var(--paper-raised); border:1px solid var(--line); border-radius:var(--radius); padding:16px 20px; margin-bottom:24px; display:flex; align-items:center; gap:16px;}
  .bar{flex:1; height:8px; background:var(--line); border-radius:6px; overflow:hidden;}
  .bar .fill{height:100%; background:var(--green); width:78%;}
  .controls{display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; flex-wrap:wrap; gap:12px;}
  .chips{display:flex; gap:8px;}
  .chip{font-size:12.5px; padding:6px 13px; border-radius:20px; border:1px solid var(--line); background:var(--paper-raised); color:var(--ink-soft); cursor:pointer;}
  .chip.active{background:var(--green); border-color:var(--green); color:#fff;}
  .btn{border:none; font-family:'Inter',sans-serif; font-weight:500; font-size:13.5px; padding:11px 20px; border-radius:var(--radius); cursor:pointer; background:var(--green); color:#fff;}
  .btn.ghost{background:var(--paper-raised); color:var(--ink); border:1px solid var(--line);}
  .ledger{background:var(--paper-raised); border:1px solid var(--line); border-radius:var(--radius); overflow:hidden;}
  .lrow{display:grid; align-items:center; padding:14px 20px; border-bottom:1px solid var(--line); gap:10px;}
  .lrow.head{background:var(--green-tint); font-size:11px; text-transform:uppercase; color:var(--green-dark); font-weight:600;}
  .flat{font-family:'IBM Plex Mono',monospace; font-size:13px; font-weight:500;}
  .owner{font-size:13.5px; font-weight:500;}
  .owner .sub{display:block; font-size:11.5px; color:var(--ink-soft); font-weight:400;}
  .amt{font-family:'IBM Plex Mono',monospace; font-size:14px; font-weight:500; text-align:right;}
  .amt.due{color:var(--rust); font-weight:500;}
  .date{font-family:'IBM Plex Mono',monospace; font-size:12.5px; color:var(--ink-soft);}
  .status{font-size:11px; padding:4px 10px; border-radius:20px; font-weight:500;}
  .status.paid{background:var(--green-tint); color:var(--green-dark);}
  .status.overdue{background:var(--rust-tint); color:var(--rust);}
  .status.partial{background:var(--gold-tint); color:var(--gold);}
  .rowbtn{font-size:11.5px; padding:6px 10px; border-radius:7px; border:1px solid var(--line); background:#fff; color:var(--green-dark); cursor:pointer;}
</style>
</head>
<body>

<div class="app">
  <?php $activePage = 'maintenance'; require_once __DIR__ . '/../layouts/sidebar.php'; ?>

  <div class="main">
    <div class="content-wrap">

      <div class="topbar">
        <h1>Maintenance</h1>
        <div class="meta">Billing cycle <b>Aug 2026</b><br>84 flats · 4 wings</div>
      </div>

      <div class="stats">
        <div class="stat"><div class="label">Billed this cycle</div><div class="val">₹8,40,000</div><div class="sub">84 bills raised</div></div>
        <div class="stat"><div class="label">Collected</div><div class="val">₹6,55,200</div><div class="sub">61 flats paid</div></div>
        <div class="stat warn"><div class="label">Outstanding</div><div class="val">₹1,84,800</div><div class="sub">23 flats due</div></div>
        <div class="stat warn"><div class="label">Overdue &gt; 30 days</div><div class="val">9</div><div class="sub">flats, late fee applies</div></div>
      </div>

      <div class="barwrap">
        <div class="lbl" style="font-size:12.5px; color:var(--ink-soft);">Collection progress</div>
        <div class="bar"><div class="fill"></div></div>
        <div style="font-family:'Fraunces',serif; font-weight:600; font-size:15px;">78%</div>
      </div>

      <div class="controls">
        <div class="chips">
          <div class="chip active">All wings</div><div class="chip">Wing A</div><div class="chip">Wing B</div><div class="chip">Wing C</div><div class="chip">Overdue only</div>
        </div>
        <div style="display:flex; gap:10px;">
          <button class="btn ghost" onclick="document.getElementById('remind').classList.add('open')">Send reminders</button>
          <button class="btn" onclick="document.getElementById('genbill').classList.add('open')">+ Generate bill</button>
        </div>
      </div>

      <div class="ledger">
        <div class="lrow head" style="grid-template-columns:70px 1.1fr 90px 110px 110px 110px 130px 90px;"><div>Flat</div><div>Owner</div><div>Area</div><div style="text-align:right">Billed</div><div style="text-align:right">Due</div><div>Last paid</div><div style="text-align:center">Status</div><div></div></div>
        
        <div class="lrow" style="grid-template-columns:70px 1.1fr 90px 110px 110px 110px 130px 90px;">
          <div class="flat">A-102</div><div class="owner">Rekha Iyer<span class="sub">Owner-occupied</span></div><div class="area">980 sq.ft</div><div class="amt">₹10,000</div><div class="amt">₹0</div><div class="date">08 Aug</div>
          <div style="display:flex; justify-content:center"><span class="status paid">Paid</span></div><div><button class="rowbtn">Receipt</button></div>
        </div>

        <div class="lrow" style="grid-template-columns:70px 1.1fr 90px 110px 110px 110px 130px 90px;">
          <div class="flat">B-304</div><div class="owner">Vikram Shah<span class="sub">Tenant</span></div><div class="area">1150 sq.ft</div><div class="amt">₹11,500</div><div class="amt due">₹11,500</div><div class="date">—</div>
          <div style="display:flex; justify-content:center"><span class="status overdue">Overdue</span></div><div><button class="rowbtn" onclick="document.getElementById('collect').classList.add('open')">Record</button></div>
        </div>

        <div class="lrow" style="grid-template-columns:70px 1.1fr 90px 110px 110px 110px 130px 90px;">
          <div class="flat">C-201</div><div class="owner">Farhan Sheikh<span class="sub">Owner-occupied</span></div><div class="area">1050 sq.ft</div><div class="amt">₹10,500</div><div class="amt due">₹4,500</div><div class="date">02 Jul</div>
          <div style="display:flex; justify-content:center"><span class="status partial">Partial</span></div><div><button class="rowbtn" onclick="document.getElementById('collect').classList.add('open')">Record</button></div>
        </div>
      </div>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layouts/drawers.php'; ?>
</body>
</html>
