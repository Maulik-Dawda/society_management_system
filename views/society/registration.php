<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Society Registration - Meridian Heights CHS</title>
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
  .reg-shell{max-width:840px; margin:0 auto;}
  .reg-intro{text-align:center; margin-bottom:8px;}
  .reg-intro .sub{font-size:13px; color:var(--ink-soft); margin-top:6px;}
  .steps{display:flex; align-items:center; justify-content:center; gap:6px; margin:24px 0 32px;}
  .step{display:flex; align-items:center; gap:8px;}
  .stepnum{width:26px; height:26px; border-radius:50%; background:var(--line); color:var(--ink-soft); font-size:12px; font-weight:600; display:flex; align-items:center; justify-content:center; font-family:'Fraunces',serif;}
  .step.done .stepnum{background:var(--green); color:#fff;}
  .step.current .stepnum{background:var(--gold); color:#fff;}
  .steplbl{font-size:12px; color:var(--ink-soft);}
  .step.done .steplbl, .step.current .steplbl{color:var(--ink); font-weight:500;}
  .steplink{width:34px; height:1px; background:var(--line);}
  .regcard{background:var(--paper-raised); border:1px solid var(--line); border-radius:var(--radius); padding:28px 30px; margin-bottom:22px;}
  .regcard h2{font-family:'Fraunces',serif; font-size:18px; font-weight:600; margin-bottom:4px;}
  .regcard .desc{font-size:12.5px; color:var(--ink-soft); margin-bottom:20px;}
  .field{margin-bottom:16px;}
  .field label{display:block; font-size:12px; font-weight:500; text-transform:uppercase; letter-spacing:.05em; color:var(--ink-soft); margin-bottom:6px;}
  .field input, .field select{width:100%; border:1px solid var(--line); background:var(--paper); border-radius:7px; padding:10px 12px; font-family:'Inter',sans-serif; font-size:13.5px; color:var(--ink);}
  .row2{display:grid; grid-template-columns:1fr 1fr; gap:12px;}
  .row3{display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px;}
  .balancegrid{display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:6px;}
  .balancecard{border:1px solid var(--line); border-radius:8px; padding:16px 18px; background:var(--paper);}
  .balancecard .lbl{font-size:11px; text-transform:uppercase; color:var(--ink-soft); margin-bottom:8px;}
  .balancecard .amtinput{font-family:'IBM Plex Mono',monospace; font-size:20px; font-weight:500; border:none; background:none; width:100%; color:var(--ink);}
  .duesledger{background:var(--paper); border:1px solid var(--line); border-radius:8px; overflow:hidden; margin-bottom:14px;}
  .duesrow{display:grid; grid-template-columns:90px 1fr 130px 40px; gap:10px; align-items:center; padding:10px 14px; border-bottom:1px solid var(--line);}
  .duesrow.head{background:var(--green-tint); font-size:10.5px; text-transform:uppercase; color:var(--green-dark); font-weight:600;}
  .duesrow input{border:1px solid var(--line); background:#fff; border-radius:6px; padding:7px 9px; font-size:13px; width:100%;}
  .duesrow .rm{background:none; border:none; color:var(--ink-soft); cursor:pointer; font-size:14px;}
  .addrow{width:100%; border:1.5px dashed var(--line); background:none; border-radius:8px; padding:11px; font-size:13px; color:var(--ink-soft); cursor:pointer; margin-bottom:16px;}
  .totalstrip{display:flex; justify-content:space-between; align-items:center; background:var(--gold-tint); border:1px solid var(--line); border-radius:8px; padding:14px 18px;}
  .totalstrip .val{font-family:'Fraunces',serif; font-size:22px; font-weight:600;}
  .summarycard{background:var(--green-dark); color:#EFE9DA; border-radius:var(--radius); padding:24px 28px; margin-bottom:24px;}
  .summarygrid{display:grid; grid-template-columns:repeat(4,1fr); gap:16px;}
  .summarygrid .item .val{font-family:'Fraunces',serif; font-size:19px; font-weight:600; color:#fff;}
  .actionsrow{display:flex; justify-content:space-between; gap:12px; margin-top:8px;}
  .btn{border:none; font-family:'Inter',sans-serif; font-weight:500; font-size:13.5px; padding:11px 20px; border-radius:var(--radius); cursor:pointer; background:var(--green); color:#fff;}
  .btn.ghost{background:var(--paper-raised); color:var(--ink); border:1px solid var(--line);}
</style>
</head>
<body>

<div class="app">
  <?php $activePage = 'registration'; require_once __DIR__ . '/../layouts/sidebar.php'; ?>

  <div class="main">
    <div class="reg-shell">
      <div class="reg-intro">
        <h1 style="font-family:'Fraunces',serif; font-weight:600; font-size:30px;">Register your society</h1>
        <div class="sub">Set up your society's basic details, structure, and opening financial position.</div>
      </div>

      <div class="steps">
        <div class="step done"><div class="stepnum">✓</div><div class="steplbl">Society details</div></div>
        <div class="steplink"></div>
        <div class="step current"><div class="stepnum">2</div><div class="steplbl">Opening balances</div></div>
        <div class="steplink"></div>
        <div class="step"><div class="stepnum">3</div><div class="steplbl">Confirm</div></div>
      </div>

      <!-- Society details -->
      <div class="regcard">
        <h2>Society details</h2>
        <div class="desc">Basic registration information for your housing society.</div>
        <div class="field"><label>Society name</label><input type="text" value="Meridian Heights Cooperative Housing Society"></div>
        <div class="row2">
          <div class="field"><label>Registration number</label><input type="text" placeholder="e.g. GUJ/AHM/HSG/2014/1123"></div>
          <div class="field"><label>Date of registration</label><input type="date"></div>
        </div>
        <div class="field"><label>Registered address</label><input type="text" placeholder="Building name, street, city, state, PIN"></div>
        <div class="row2">
          <div class="field"><label>PAN</label><input type="text" placeholder="AAAAA0000A"></div>
          <div class="field"><label>GSTIN (if applicable)</label><input type="text" placeholder="24AAAAA0000A1Z5"></div>
        </div>
      </div>

      <!-- Structure & members -->
      <div class="regcard">
        <h2>Structure & membership</h2>
        <div class="desc">How your society is organised, and how many members it has.</div>
        <div class="row3">
          <div class="field"><label>Number of wings / buildings</label><input type="number" value="4"></div>
          <div class="field"><label>Total flats / units</label><input type="number" value="84"></div>
          <div class="field"><label>Total number of members</label><input type="number" value="84"></div>
        </div>
      </div>

      <!-- Opening balances -->
      <div class="regcard">
        <h2>Opening balances</h2>
        <div class="desc">Enter your society's cash and bank position as on the date you're starting this system.</div>
        <div class="field"><label>Balances as on</label><input type="date" value="2026-08-21" style="max-width:220px;"></div>

        <div class="balancegrid">
          <div class="balancecard">
            <div class="lbl">🏦 Bank balance</div>
            <input class="amtinput" type="number" value="418200">
            <div class="sub" style="font-size:11.5px; color:var(--ink-soft); margin-top:4px;">Enter bank name & account below</div>
          </div>
          <div class="balancecard">
            <div class="lbl">💵 Cash in hand</div>
            <input class="amtinput" type="number" value="12500">
            <div class="sub" style="font-size:11.5px; color:var(--ink-soft); margin-top:4px;">Petty cash held by treasurer</div>
          </div>
        </div>
      </div>

      <!-- Summary -->
      <div class="summarycard">
        <h2>Opening position summary</h2>
        <div class="summarygrid">
          <div class="item"><div style="font-size:10.5px; text-transform:uppercase; color:#9FB3A8;">Total members</div><div class="val">84</div></div>
          <div class="item"><div style="font-size:10.5px; text-transform:uppercase; color:#9FB3A8;">Bank + cash</div><div class="val">₹4,30,700</div></div>
          <div class="item"><div style="font-size:10.5px; text-transform:uppercase; color:#9FB3A8;">Pending dues</div><div class="val">₹28,000</div></div>
          <div class="item"><div style="font-size:10.5px; text-transform:uppercase; color:#9FB3A8;">Net position</div><div class="val">₹4,58,700</div></div>
        </div>
      </div>

      <div class="actionsrow">
        <button class="btn ghost">Back</button>
        <button class="btn">Save & continue</button>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layouts/drawers.php'; ?>
</body>
</html>
