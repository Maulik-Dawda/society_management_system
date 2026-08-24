<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Vehicles - Meridian Heights CHS</title>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
  :root{ --paper:#F3F1E9; --paper-raised:#FFFEFA; --ink:#23281F; --ink-soft:#5B5F52; --line:#DAD5C4; --green:#1F5C4A; --green-dark:#123D31; --green-tint:#E4EDE7; --gold:#B9812A; --gold-tint:#F5E9D2; --rust:#B14A2E; --radius:10px; }
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
  .btn{border:none; font-family:'Inter',sans-serif; font-weight:500; font-size:13.5px; padding:11px 20px; border-radius:var(--radius); cursor:pointer; background:var(--green); color:#fff;}
  .ledger{background:var(--paper-raised); border:1px solid var(--line); border-radius:var(--radius); overflow:hidden; margin-bottom:24px;}
  .lrow{display:grid; align-items:center; padding:14px 20px; border-bottom:1px solid var(--line); gap:10px;}
  .lrow.head{background:var(--green-tint); font-size:11px; text-transform:uppercase; color:var(--green-dark); font-weight:600;}
  .flat{font-family:'IBM Plex Mono',monospace; font-size:13px; font-weight:500;}
  .owner{font-size:13.5px; font-weight:500;}
  .owner .sub{display:block; font-size:11.5px; color:var(--ink-soft); font-weight:400;}
  .plate{background:var(--paper); border:1px solid var(--line); border-radius:5px; padding:4px 8px; font-family:'IBM Plex Mono',monospace; font-size:12.5px;}
  .status{font-size:11px; padding:4px 10px; border-radius:20px; font-weight:500;}
  .status.active{background:var(--green-tint); color:var(--green-dark);}
  .status.expiring{background:var(--gold-tint); color:var(--gold);}
  .rowbtn{font-size:11.5px; padding:6px 10px; border-radius:7px; border:1px solid var(--line); background:#fff; color:var(--green-dark); cursor:pointer;}
  .sectionhead{display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; margin-top:32px;}
  .sectionhead h3{font-family:'Fraunces',serif; font-size:18px; font-weight:600;}
</style>
</head>
<body>

<div class="app">
  <?php $activePage = 'vehicles'; require_once __DIR__ . '/../layouts/sidebar.php'; ?>

  <div class="main">
    <div class="content-wrap">

      <div class="topbar">
        <h1>Vehicles</h1>
        <div class="meta">4 wings · 84 flats<br><b>112</b> vehicles on record</div>
      </div>

      <div class="stats">
        <div class="stat"><div class="label">Registered vehicles</div><div class="val">112</div><div class="sub">76 cars · 36 two-wheelers</div></div>
        <div class="stat"><div class="label">Parking slots used</div><div class="val">98 / 110</div><div class="sub">12 slots free</div></div>
        <div class="stat"><div class="label">Guest vehicles today</div><div class="val">7</div><div class="sub">3 currently inside</div></div>
        <div class="stat"><div class="label">Stickers expiring</div><div class="val">5</div><div class="sub">within 30 days</div></div>
      </div>

      <div class="tabs">
        <div class="tab active">Registered vehicles</div>
        <div class="tab">Visitor log</div>
      </div>

      <div class="controls">
        <div class="chips">
          <div class="chip active">All wings</div>
          <div class="chip">Car</div>
          <div class="chip">Two-wheeler</div>
          <div class="chip">Sticker expiring</div>
        </div>
        <button class="btn" onclick="document.getElementById('regform').classList.add('open')">+ Register vehicle</button>
      </div>

      <div class="ledger">
        <div class="lrow head" style="grid-template-columns:70px 1fr 130px 90px 100px 110px 60px;"><div>Flat</div><div>Owner</div><div>Vehicle no.</div><div>Type</div><div>Slot</div><div style="text-align:center">Sticker</div><div></div></div>
        
        <div class="lrow" style="grid-template-columns:70px 1fr 130px 90px 100px 110px 60px;">
          <div class="flat">A-102</div><div class="owner">Rekha Iyer<span class="sub">Maruti Swift · White</span></div><div class="plate">MH 04 AB 3312</div><div class="type">Car</div><div class="slot">A-P14</div>
          <div style="display:flex; justify-content:center"><span class="status active">Valid</span></div><div><button class="rowbtn">Edit</button></div>
        </div>

        <div class="lrow" style="grid-template-columns:70px 1fr 130px 90px 100px 110px 60px;">
          <div class="flat">B-304</div><div class="owner">Anil Mehta<span class="sub">Honda Activa · Black</span></div><div class="plate">MH 04 CD 9081</div><div class="type">Two-wheeler</div><div class="slot">B-P07</div>
          <div style="display:flex; justify-content:center"><span class="status expiring">Expiring</span></div><div><button class="rowbtn">Renew</button></div>
        </div>

        <div class="lrow" style="grid-template-columns:70px 1fr 130px 90px 100px 110px 60px;">
          <div class="flat">C-201</div><div class="owner">Farhan Sheikh<span class="sub">Hyundai Creta · Grey</span></div><div class="plate">MH 04 EF 5527</div><div class="type">Car</div><div class="slot">C-P02</div>
          <div style="display:flex; justify-content:center"><span class="status active">Valid</span></div><div><button class="rowbtn">Edit</button></div>
        </div>
      </div>

      <div class="sectionhead"><h3>Today's visitor log</h3><div class="note">21 Aug 2026</div></div>
      
      <div class="ledger">
        <div class="lrow head" style="grid-template-columns:80px 1fr 120px 100px 100px;"><div>Time</div><div>Visitor / vehicle</div><div>Visiting</div><div>Status</div><div></div></div>
        <div class="lrow" style="grid-template-columns:80px 1fr 120px 100px 100px;"><div class="date">10:12 AM</div><div class="owner">Swiggy delivery<span class="sub">MH 04 XY 0021 · Bike</span></div><div class="type">B-304</div><div><span class="status active">Exited</span></div><div></div></div>
        <div class="lrow" style="grid-template-columns:80px 1fr 120px 100px 100px;"><div class="date">11:40 AM</div><div class="owner">Ramesh (guest)<span class="sub">MH 04 QW 7712 · Car</span></div><div class="type">A-408</div><div><span class="status expiring">Inside</span></div><div><button class="rowbtn">Mark exit</button></div></div>
      </div>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layouts/drawers.php'; ?>
</body>
</html>
