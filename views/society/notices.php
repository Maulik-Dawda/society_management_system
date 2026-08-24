<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
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
  .stats{display:grid; grid-template-columns:repeat(4,1fr); gap:1px; background:var(--line); border:1px solid var(--line); margin-bottom:24px; border-radius:var(--radius); overflow:hidden;}
  .stat{background:var(--paper-raised); padding:18px 20px;}
  .stat .label{font-size:11.5px; text-transform:uppercase; letter-spacing:.07em; color:var(--ink-soft); margin-bottom:8px;}
  .stat .val{font-family:'Fraunces',serif; font-size:26px; font-weight:600;}
  .stat .sub{font-size:12px; color:var(--ink-soft); margin-top:4px;}
  .btn{border:none; font-family:'Inter',sans-serif; font-weight:500; font-size:13.5px; padding:11px 20px; border-radius:var(--radius); cursor:pointer; background:var(--green); color:#fff;}
  .ledger{background:var(--paper-raised); border:1px solid var(--line); border-radius:var(--radius); overflow:hidden;}
  .lrow{display:grid; align-items:center; padding:14px 20px; border-bottom:1px solid var(--line); gap:10px;}
  .lrow.head{background:var(--green-tint); font-size:11px; text-transform:uppercase; color:var(--green-dark); font-weight:600;}
  .date{font-family:'IBM Plex Mono',monospace; font-size:12.5px; color:var(--ink-soft);}
  .cat{font-size:13.5px; font-weight:500;}
  .cat .sub{display:block; font-size:11.5px; color:var(--ink-soft); font-weight:400;}
  .status{font-size:11px; padding:4px 10px; border-radius:20px; font-weight:500; width:fit-content;}
  .status.paid{background:var(--green-tint); color:var(--green-dark);}
  .status.pending{background:var(--gold-tint); color:var(--gold);}
  .status.rejected{background:var(--rust-tint); color:var(--rust);}
</style>
</head>
<body>

<div class="app">
  <?php $activePage = 'notices'; require_once __DIR__ . '/../layouts/sidebar.php'; ?>

  <div class="main">
    <div class="content-wrap">

      <div class="topbar">
        <h1>Notice Board</h1>
        <div class="meta">4 wings · 84 flats</div>
      </div>

      <div class="stats">
        <div class="stat"><div class="label">Active notices</div><div class="val">6</div><div class="sub">2 marked urgent</div></div>
        <div class="stat"><div class="label">Posted this month</div><div class="val">11</div><div class="sub">avg. 82% read rate</div></div>
        <div class="stat"><div class="label">Unread by residents</div><div class="val">17</div><div class="sub">across all notices</div></div>
        <div class="stat"><div class="label">Circulars attached</div><div class="val">4</div><div class="sub">PDF documents</div></div>
      </div>

      <div class="ledger">
        <div class="lrow head" style="grid-template-columns:100px 1fr 110px 100px;"><div>Date</div><div>Notice</div><div style="text-align:center">Category</div><div style="text-align:center">Read</div></div>
        
        <div class="lrow" style="grid-template-columns:100px 1fr 110px 100px;">
          <div class="date">20 Aug</div>
          <div class="cat">Water supply shutdown on 23 Aug, 10am–2pm<span class="sub">Maintenance work on the main line</span></div>
          <div style="display:flex; justify-content:center"><span class="status rejected">Urgent</span></div>
          <div style="text-align:center" class="contact">61 / 84</div>
        </div>

        <div class="lrow" style="grid-template-columns:100px 1fr 110px 100px;">
          <div class="date">18 Aug</div>
          <div class="cat">Ganesh Chaturthi celebration — RSVP<span class="sub">Clubhouse, 27 Aug 6pm onward</span></div>
          <div style="display:flex; justify-content:center"><span class="status paid">Event</span></div>
          <div style="text-align:center" class="contact">74 / 84</div>
        </div>

        <div class="lrow" style="grid-template-columns:100px 1fr 110px 100px;">
          <div class="date">12 Aug</div>
          <div class="cat">AGM minutes — July 2026<span class="sub">Circular attached</span></div>
          <div style="display:flex; justify-content:center"><span class="status pending">General</span></div>
          <div style="text-align:center" class="contact">55 / 84</div>
        </div>
      </div>

      <div style="margin-top:20px;">
        <button class="btn">+ Post notice</button>
      </div>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layouts/drawers.php'; ?>
</body>
</html>
