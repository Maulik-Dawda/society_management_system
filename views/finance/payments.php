<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payments - Meridian Heights CHS</title>
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
  .tabs{display:flex; gap:4px; margin-bottom:16px; border-bottom:1px solid var(--line);}
  .tab{font-size:13.5px; font-weight:500; padding:10px 18px; color:var(--ink-soft); cursor:pointer; border-bottom:2px solid transparent;}
  .tab.active{color:var(--green-dark); border-bottom-color:var(--green);}
  .controls{display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; flex-wrap:wrap; gap:12px;}
  .chips{display:flex; gap:8px;}
  .chip{font-size:12.5px; padding:6px 13px; border-radius:20px; border:1px solid var(--line); background:var(--paper-raised); color:var(--ink-soft); cursor:pointer;}
  .chip.active{background:var(--green); border-color:var(--green); color:#fff;}
  .search{font-size:13px; border:1px solid var(--line); background:var(--paper-raised); border-radius:8px; padding:9px 12px; width:200px;}
  .btn{border:none; font-family:'Inter',sans-serif; font-weight:500; font-size:13.5px; padding:11px 20px; border-radius:var(--radius); cursor:pointer; background:var(--green); color:#fff;}
  .ledger{background:var(--paper-raised); border:1px solid var(--line); border-radius:var(--radius); overflow:hidden;}
  .lrow{display:grid; align-items:center; padding:14px 20px; border-bottom:1px solid var(--line); gap:10px;}
  .lrow.head{background:var(--green-tint); font-size:11px; text-transform:uppercase; color:var(--green-dark); font-weight:600;}
  .date{font-family:'IBM Plex Mono',monospace; font-size:12.5px; color:var(--ink-soft);}
  .flat{font-family:'IBM Plex Mono',monospace; font-size:13px; font-weight:500;}
  .owner{font-size:13.5px; font-weight:500;}
  .owner .sub{display:block; font-size:11.5px; color:var(--ink-soft); font-weight:400;}
  .amt{font-family:'IBM Plex Mono',monospace; font-size:14px; font-weight:500; text-align:right;}
  .mode{font-size:12.5px; color:var(--ink-soft); display:flex; align-items:center; gap:6px;}
  .dot{width:6px; height:6px; border-radius:50%; background:var(--green);}
  .dot.gold{background:var(--gold);}
  .ref{font-family:'IBM Plex Mono',monospace; font-size:12px; color:var(--ink-soft);}
  .status{font-size:11px; padding:4px 10px; border-radius:20px; font-weight:500;}
  .status.settled{background:var(--green-tint); color:var(--green-dark);}
  .status.processing{background:var(--gold-tint); color:var(--gold);}
  .status.failed{background:var(--rust-tint); color:var(--rust);}
  .rowbtn{font-size:11.5px; padding:6px 10px; border-radius:7px; border:1px solid var(--line); background:#fff; color:var(--green-dark); cursor:pointer;}
</style>
</head>
<body>

<div class="app">
  <?php $activePage = 'payments'; require_once __DIR__ . '/../layouts/sidebar.php'; ?>

  <div class="main">
    <div class="content-wrap">

      <div class="topbar">
        <h1>Payments</h1>
        <div class="meta">Bank account <b>HDFC ····4471</b><br>Gateway: Razorpay · Connected</div>
      </div>

      <div class="stats">
        <div class="stat"><div class="label">Collected today</div><div class="val">₹1,04,200</div><div class="sub">9 transactions</div></div>
        <div class="stat"><div class="label">Collected this month</div><div class="val">₹6,55,200</div><div class="sub">61 transactions</div></div>
        <div class="stat"><div class="label">Processing</div><div class="val">₹8,400</div><div class="sub">1 gateway payment</div></div>
        <div class="stat"><div class="label">Receipts issued</div><div class="val">61</div><div class="sub">this month</div></div>
      </div>

      <div class="tabs"><div class="tab active">All payments</div><div class="tab">Online</div><div class="tab">Manual entry</div></div>

      <div class="controls">
        <div class="chips">
          <div class="chip active">All modes</div><div class="chip">UPI</div><div class="chip">Card</div><div class="chip">Bank transfer</div><div class="chip">Cash</div><div class="chip">Cheque</div>
        </div>
        <div style="display:flex; gap:10px; align-items:center;">
          <input class="search" placeholder="Search flat or owner">
          <button class="btn" onclick="document.getElementById('collect').classList.add('open')">+ Collect payment</button>
        </div>
      </div>

      <div class="ledger">
        <div class="lrow head" style="grid-template-columns:110px 70px 1fr 100px 100px 120px 90px 70px;"><div>Date</div><div>Flat</div><div>Owner</div><div style="text-align:right">Amount</div><div>Mode</div><div>Reference</div><div style="text-align:center">Status</div><div></div></div>
        
        <div class="lrow" style="grid-template-columns:110px 70px 1fr 100px 100px 120px 90px 70px;">
          <div class="date">21 Aug, 9:14 AM</div><div class="flat">A-102</div><div class="owner">Rekha Iyer<span class="sub">Aug maintenance</span></div><div class="amt">₹10,000</div>
          <div class="mode"><span class="dot"></span>UPI</div><div class="ref">pay_HJ8x21Kp</div><div style="display:flex; justify-content:center"><span class="status settled">Settled</span></div><div><button class="rowbtn" onclick="openReceipt()">Receipt</button></div>
        </div>

        <div class="lrow" style="grid-template-columns:110px 70px 1fr 100px 100px 120px 90px 70px;">
          <div class="date">20 Aug, 6:47 PM</div><div class="flat">D-301</div><div class="owner">Meera Nair<span class="sub">Aug maintenance</span></div><div class="amt">₹8,400</div>
          <div class="mode"><span class="dot gold"></span>Card</div><div class="ref">pay_GQ4z90Lm</div><div style="display:flex; justify-content:center"><span class="status processing">Processing</span></div><div><button class="rowbtn" style="opacity:.5">Receipt</button></div>
        </div>

        <div class="lrow" style="grid-template-columns:110px 70px 1fr 100px 100px 120px 90px 70px;">
          <div class="date">19 Aug, 4:12 PM</div><div class="flat">B-115</div><div class="owner">Kavita Joshi<span class="sub">Aug maintenance</span></div><div class="amt">₹9,200</div>
          <div class="mode"><span class="dot"></span>Cheque</div><div class="ref">Chq #004521</div><div style="display:flex; justify-content:center"><span class="status failed">Bounced</span></div><div><button class="rowbtn">Follow up</button></div>
        </div>
      </div>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layouts/drawers.php'; ?>
</body>
</html>
