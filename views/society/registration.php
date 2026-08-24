<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register Your Society - Meridian Heights CHS</title>
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
  .field input, .field select, .field textarea{width:100%; border:1px solid var(--line); background:var(--paper); border-radius:7px; padding:10px 12px; font-family:'Inter',sans-serif; font-size:13.5px; color:var(--ink);}
  .field input:focus, .field select:focus{outline:none; border-color:var(--green);}
  .row2{display:grid; grid-template-columns:1fr 1fr; gap:12px;}
  .row3{display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px;}
  .helptext{font-size:11.5px; color:var(--ink-soft); margin-top:5px;}
  .balancegrid{display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:6px;}
  .balancecard{border:1px solid var(--line); border-radius:8px; padding:16px 18px; background:var(--paper);}
  .balancecard .lbl{font-size:11px; text-transform:uppercase; letter-spacing:.06em; color:var(--ink-soft); margin-bottom:8px; display:flex; align-items:center; gap:6px;}
  .balancecard .amtinput{font-family:'IBM Plex Mono',monospace; font-size:20px; font-weight:500; border:none; background:none; width:100%; color:var(--ink); padding:0;}
  .balancecard .amtinput:focus{outline:none;}
  .balancecard .sub{font-size:11.5px; color:var(--ink-soft); margin-top:4px;}
  .duesledger{background:var(--paper); border:1px solid var(--line); border-radius:8px; overflow:hidden; margin-bottom:14px;}
  .duesrow{display:grid; grid-template-columns:90px 1fr 130px 40px; gap:10px; align-items:center; padding:10px 14px; border-bottom:1px solid var(--line);}
  .duesrow:last-child{border-bottom:none;}
  .duesrow.head{background:var(--green-tint); font-size:10.5px; text-transform:uppercase; letter-spacing:.06em; color:var(--green-dark); font-weight:600; padding:9px 14px;}
  .duesrow input{border:1px solid var(--line); background:#fff; border-radius:6px; padding:7px 9px; font-size:13px; font-family:'Inter',sans-serif; width:100%;}
  .duesrow input.amt-field{font-family:'IBM Plex Mono',monospace; text-align:right;}
  .duesrow .rm{background:none; border:none; color:var(--ink-soft); cursor:pointer; font-size:14px;}
  .duesrow .rm:hover{color:var(--rust);}
  .addrow{width:100%; border:1.5px dashed var(--line); background:none; border-radius:8px; padding:11px; font-size:13px; color:var(--ink-soft); cursor:pointer; margin-bottom:16px;}
  .addrow:hover{border-color:var(--green); color:var(--green);}
  .totalstrip{display:flex; justify-content:space-between; align-items:center; background:var(--gold-tint); border:1px solid var(--line); border-radius:8px; padding:14px 18px;}
  .totalstrip .lbl{font-size:12px; text-transform:uppercase; letter-spacing:.05em; color:var(--gold);}
  .totalstrip .val{font-family:'Fraunces',serif; font-size:22px; font-weight:600;}
  .bulklink{font-size:12px; color:var(--green-dark); text-decoration:underline; cursor:pointer; margin-bottom:16px; display:inline-block;}
  .summarycard{background:var(--green-dark); color:#EFE9DA; border-radius:var(--radius); padding:24px 28px; margin-bottom:24px;}
  .summarycard h2{font-family:'Fraunces',serif; font-size:16px; font-weight:600; margin-bottom:16px; color:#fff;}
  .summarygrid{display:grid; grid-template-columns:repeat(4,1fr); gap:16px;}
  .summarygrid .item .lbl{font-size:10.5px; text-transform:uppercase; letter-spacing:.06em; color:#9FB3A8; margin-bottom:6px;}
  .summarygrid .item .val{font-family:'Fraunces',serif; font-size:19px; font-weight:600; color:#fff;}
  .actionsrow{display:flex; justify-content:space-between; gap:12px; margin-top:8px;}
  .btn{border:none; font-family:'Inter',sans-serif; font-weight:500; font-size:13.5px; padding:11px 20px; border-radius:var(--radius); cursor:pointer; background:var(--green); color:#fff;}
  .btn:hover{background:var(--green-dark);}
  .btn.ghost{background:var(--paper-raised); color:var(--ink); border:1px solid var(--line);}
  .btn.ghost:hover{border-color:var(--ink-soft);}
</style>
</head>
<body>

<div class="app">
  <!-- ===== Sidebar ===== -->
  <?php $activePage = 'registration'; require_once __DIR__ . '/../layouts/sidebar.php'; ?>

  <!-- ===== Main Content Area ===== -->
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
          <div class="field"><label>Total number of members</label><input type="number" id="memberCount" value="84" oninput="syncMemberCount()"></div>
        </div>
        <div class="helptext" style="margin-top:-6px;">This should match your total flats unless some units are vacant or unsold. You can add individual member records later from the Members page.</div>
      </div>

      <!-- Opening balances -->
      <div class="regcard">
        <h2>Opening balances</h2>
        <div class="desc">Enter your society's cash and bank position as on the date you're starting this system.</div>
        <div class="field"><label>Balances as on</label><input type="date" value="2026-08-21" style="max-width:220px;"></div>

        <div class="balancegrid">
          <div class="balancecard">
            <div class="lbl">🏦 Bank balance</div>
            <input class="amtinput" type="number" placeholder="0.00" value="418200" id="bankInput" oninput="recalcSummary()">
            <div class="sub">Enter bank name & account below</div>
          </div>
          <div class="balancecard">
            <div class="lbl">💵 Cash in hand</div>
            <input class="amtinput" type="number" placeholder="0.00" value="12500" id="cashInput" oninput="recalcSummary()">
            <div class="sub">Petty cash held by treasurer</div>
          </div>
        </div>

        <div class="row2" style="margin-top:16px;">
          <div class="field"><label>Bank name</label><input type="text" placeholder="e.g. HDFC Bank"></div>
          <div class="field"><label>Account number</label><input type="text" placeholder="····4471"></div>
        </div>
      </div>

      <!-- Pending maintenance per member -->
      <div class="regcard">
        <h2>Pending maintenance (opening dues)</h2>
        <div class="desc">If any members have outstanding maintenance dues from before you started using this system, record them here so their balance carries forward correctly.</div>

        <span class="bulklink" onclick="alert('This would open a CSV upload — flat, member name, pending amount.')">Or upload a CSV instead</span>

        <div class="duesledger" id="duesLedger">
          <div class="duesrow head"><div>Flat</div><div>Member name</div><div style="text-align:right">Pending amount</div><div></div></div>

          <div class="duesrow">
            <input type="text" value="B-304" oninput="updateTotal()">
            <input type="text" value="Vikram Shah" oninput="updateTotal()">
            <input class="amt-field due-amt" type="number" value="11500" oninput="updateTotal()">
            <button class="rm" onclick="this.closest('.duesrow').remove(); updateTotal();">✕</button>
          </div>
          <div class="duesrow">
            <input type="text" value="C-201" oninput="updateTotal()">
            <input type="text" value="Farhan Sheikh" oninput="updateTotal()">
            <input class="amt-field due-amt" type="number" value="4500" oninput="updateTotal()">
            <button class="rm" onclick="this.closest('.duesrow').remove(); updateTotal();">✕</button>
          </div>
          <div class="duesrow">
            <input type="text" value="D-110" oninput="updateTotal()">
            <input type="text" value="Suresh Rao" oninput="updateTotal()">
            <input class="amt-field due-amt" type="number" value="12000" oninput="updateTotal()">
            <button class="rm" onclick="this.closest('.duesrow').remove(); updateTotal();">✕</button>
          </div>
        </div>

        <button class="addrow" onclick="addDuesRow()">+ Add another flat with pending dues</button>

        <div class="totalstrip">
          <div class="lbl">Total opening dues</div>
          <div class="val" id="totalDues">₹28,000</div>
        </div>
      </div>

      <!-- Summary -->
      <div class="summarycard">
        <h2>Opening position summary</h2>
        <div class="summarygrid">
          <div class="item"><div class="lbl">Total members</div><div class="val" id="sumMembers">84</div></div>
          <div class="item"><div class="lbl">Bank + cash</div><div class="val" id="sumCash">₹4,30,700</div></div>
          <div class="item"><div class="lbl">Pending dues</div><div class="val" id="sumDues">₹28,000</div></div>
          <div class="item"><div class="lbl">Net opening position</div><div class="val" id="sumNet">₹4,58,700</div></div>
        </div>
      </div>

      <div class="actionsrow">
        <a href="/dashboard" class="btn ghost" style="text-decoration:none;">Back to Dashboard</a>
        <button class="btn" onclick="finalizeSetup()">Save & continue</button>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layouts/drawers.php'; ?>

<script>
function syncMemberCount() {
    const val = document.getElementById('memberCount').value;
    document.getElementById('sumMembers').textContent = val;
}

function addDuesRow() {
    const ledger = document.getElementById('duesLedger');
    const div = document.createElement('div');
    div.className = 'duesrow';
    div.innerHTML = `
      <input type="text" placeholder="Flat no." oninput="updateTotal()">
      <input type="text" placeholder="Member name" oninput="updateTotal()">
      <input class="amt-field due-amt" type="number" placeholder="0" oninput="updateTotal()">
      <button class="rm" onclick="this.closest('.duesrow').remove(); updateTotal();">✕</button>
    `;
    ledger.appendChild(div);
}

function updateTotal() {
    let total = 0;
    document.querySelectorAll('.due-amt').forEach(inp => {
        const val = parseFloat(inp.value) || 0;
        total += val;
    });
    document.getElementById('totalDues').textContent = '₹' + total.toLocaleString('en-IN');
    document.getElementById('sumDues').textContent = '₹' + total.toLocaleString('en-IN');
    recalcSummary();
}

function recalcSummary() {
    const bank = parseFloat(document.getElementById('bankInput').value) || 0;
    const cash = parseFloat(document.getElementById('cashInput').value) || 0;
    const cashTotal = bank + cash;
    
    let duesTotal = 0;
    document.querySelectorAll('.due-amt').forEach(inp => {
        duesTotal += parseFloat(inp.value) || 0;
    });

    document.getElementById('sumCash').textContent = '₹' + cashTotal.toLocaleString('en-IN');
    document.getElementById('sumNet').textContent = '₹' + (cashTotal + duesTotal).toLocaleString('en-IN');
}

function finalizeSetup() {
    alert('Society registration and opening balance setup saved successfully!');
    window.location.href = '/dashboard';
}
</script>

</body>
</html>
