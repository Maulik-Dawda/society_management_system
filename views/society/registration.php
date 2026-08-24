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
  
  /* Details Display Card Styles */
  .display-card{background:var(--paper-raised); border:1px solid var(--line); border-radius:var(--radius); padding:28px 32px; margin-bottom:24px;}
  .display-header{display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid var(--line); padding-bottom:16px; margin-bottom:20px;}
  .display-header h2{font-family:'Fraunces',serif; font-size:22px; font-weight:600; color:var(--green-dark);}
  .badge-registered{background:var(--green-tint); color:var(--green-dark); font-size:12px; font-weight:600; padding:6px 14px; border-radius:20px; border:1px solid rgba(31,92,74,0.3);}
  .grid-2col{display:grid; grid-template-columns:1fr 1fr; gap:20px;}
  .detail-group{margin-bottom:16px;}
  .detail-group label{font-size:11px; text-transform:uppercase; letter-spacing:.06em; color:var(--ink-soft); display:block; margin-bottom:4px;}
  .detail-group .val{font-size:14.5px; font-weight:500; color:var(--ink); font-family:'Inter',sans-serif;}
  .detail-group .mono-val{font-family:'IBM Plex Mono',monospace; font-size:15px; font-weight:600; color:var(--green-dark);}

  .regcard{background:var(--paper-raised); border:1px solid var(--line); border-radius:var(--radius); padding:28px 30px; margin-bottom:22px;}
  .regcard h2{font-family:'Fraunces',serif; font-size:18px; font-weight:600; margin-bottom:4px;}
  .regcard .desc{font-size:12.5px; color:var(--ink-soft); margin-bottom:20px;}
  .field{margin-bottom:16px;}
  .field label{display:block; font-size:12px; font-weight:500; text-transform:uppercase; letter-spacing:.05em; color:var(--ink-soft); margin-bottom:6px;}
  .field input, .field select, .field textarea{width:100%; border:1px solid var(--line); background:var(--paper); border-radius:7px; padding:10px 12px; font-family:'Inter',sans-serif; font-size:13.5px; color:var(--ink);}
  .field input:focus, .field select:focus{outline:none; border-color:var(--green);}
  .req-star{color: var(--rust); font-weight: bold;}
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
  .alert { padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 18px; line-height: 1.5; }
  .alert-danger { background: var(--rust-tint); color: var(--rust); border: 1px solid rgba(177,74,46,0.3); }
  .alert-success { background: var(--green-tint); color: var(--green-dark); border: 1px solid rgba(31,92,74,0.3); }
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
        <h1 style="font-family:'Fraunces',serif; font-weight:600; font-size:30px;">Society Registration</h1>
        <div class="sub">View and manage your society's registered details, structure, and opening financial position.</div>
      </div>

      <?php
      $flashError = Session::getFlash('error');
      $flashSuccess = Session::getFlash('success');
      $flashErrors = Session::getFlash('errors');
      $old = Session::getFlash('old') ?? [];
      $s = $society ?? [];
      $isRegistered = !empty($s['pan_number']) || !empty($s['registered_address']);
      ?>

      <?php if ($flashError): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($flashError) ?></div>
      <?php endif; ?>
      <?php if ($flashSuccess): ?>
        <div class="alert alert-success"><?= htmlspecialchars($flashSuccess) ?></div>
      <?php endif; ?>
      <?php if (!empty($flashErrors)): ?>
        <div class="alert alert-danger">
          <ul style="padding-left: 20px;">
            <?php foreach ($flashErrors as $err): ?>
              <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <!-- ================= DISPLAY VIEW (If Data Exists in DB) ================= -->
      <?php if ($isRegistered): ?>
        <div id="registeredDetailsDisplay" class="display-card">
          <div class="display-header">
            <div>
              <h2><?= htmlspecialchars($s['name'] ?? 'Society Details') ?></h2>
              <div style="font-size:12.5px; color:var(--ink-soft); margin-top:4px;">
                Registration No: <b><?= htmlspecialchars($s['registration_number'] ?: 'Not Specified') ?></b>
              </div>
            </div>
            <span class="badge-registered">✓ REGISTERED IN DATABASE</span>
          </div>

          <div class="grid-2col">
            <div>
              <div class="detail-group">
                <label>Society Full Name</label>
                <div class="val"><?= htmlspecialchars($s['name'] ?? '') ?></div>
              </div>
              <div class="detail-group">
                <label>Registered Address</label>
                <div class="val"><?= htmlspecialchars($s['registered_address'] ?? 'Not Specified') ?></div>
              </div>
              <div class="detail-group">
                <label>PAN Number</label>
                <div class="mono-val"><?= htmlspecialchars($s['pan_number'] ?? 'Not Specified') ?></div>
              </div>
              <div class="detail-group">
                <label>GSTIN</label>
                <div class="mono-val"><?= htmlspecialchars($s['gstin'] ?: 'N/A') ?></div>
              </div>
              <div class="detail-group">
                <label>Date of Registration</label>
                <div class="val"><?= htmlspecialchars($s['registration_date'] ?: 'Not Specified') ?></div>
              </div>
            </div>

            <div>
              <div class="detail-group">
                <label>Structure & Membership</label>
                <div class="val">
                  <b><?= htmlspecialchars($s['total_wings'] ?? 4) ?></b> Wings · 
                  <b><?= htmlspecialchars($s['total_flats'] ?? 84) ?></b> Flats · 
                  <b><?= htmlspecialchars($s['total_members'] ?? 84) ?></b> Members
                </div>
              </div>
              <div class="detail-group">
                <label>Opening Bank Balance</label>
                <div class="mono-val">₹ <?= number_format($s['bank_balance'] ?? 0, 2) ?></div>
              </div>
              <div class="detail-group">
                <label>Opening Cash in Hand</label>
                <div class="mono-val">₹ <?= number_format($s['cash_in_hand'] ?? 0, 2) ?></div>
              </div>
              <div class="detail-group">
                <label>Bank Name & Account</label>
                <div class="val">
                  <?= htmlspecialchars($s['bank_name'] ?: 'Not Specified') ?> 
                  (Account: <?= htmlspecialchars($s['account_number'] ?: 'N/A') ?>)
                </div>
              </div>
            </div>
          </div>

          <div class="actionsrow" style="margin-top: 24px; border-top: 1px solid var(--line); padding-top: 18px;">
            <a href="/dashboard" class="btn ghost" style="text-decoration:none;">Back to Dashboard</a>
            <button class="btn" onclick="toggleFormView()">✏️ Edit Society Details</button>
          </div>
        </div>
      <?php endif; ?>

      <!-- ================= EDITABLE FORM (Displayed directly if not registered, or toggled) ================= -->
      <form action="/registration" method="POST" id="societyRegForm" style="<?= $isRegistered ? 'display: none;' : '' ?>">
        <div class="steps">
          <div class="step done"><div class="stepnum">✓</div><div class="steplbl">Society details</div></div>
          <div class="steplink"></div>
          <div class="step current"><div class="stepnum">2</div><div class="steplbl">Opening balances</div></div>
          <div class="steplink"></div>
          <div class="step"><div class="stepnum">3</div><div class="steplbl">Confirm</div></div>
        </div>

        <!-- Card 1: Society details -->
        <div class="regcard">
          <h2>Society details</h2>
          <div class="desc">Basic registration information for your housing society.</div>
          
          <div class="field">
            <label for="society_name">Society name <span class="req-star">*</span></label>
            <input type="text" id="society_name" name="society_name" value="<?= htmlspecialchars($old['society_name'] ?? ($s['name'] ?? Session::get('society_name') ?? 'Meridian Heights Cooperative Housing Society')) ?>" required>
          </div>

          <div class="row2">
            <div class="field">
              <label for="registration_number">Registration number</label>
              <input type="text" id="registration_number" name="registration_number" placeholder="e.g. GUJ/AHM/HSG/2014/1123" value="<?= htmlspecialchars($old['registration_number'] ?? ($s['registration_number'] ?? '')) ?>">
            </div>
            <div class="field">
              <label for="registration_date">Date of registration</label>
              <input type="date" id="registration_date" name="registration_date" value="<?= htmlspecialchars($old['registration_date'] ?? ($s['registration_date'] ?? '')) ?>">
            </div>
          </div>

          <div class="field">
            <label for="registered_address">Registered address <span class="req-star">* (Required)</span></label>
            <input type="text" id="registered_address" name="registered_address" placeholder="Building name, street, city, state, PIN" value="<?= htmlspecialchars($old['registered_address'] ?? ($s['registered_address'] ?? '')) ?>" required>
          </div>

          <div class="row2">
            <div class="field">
              <label for="pan_number">PAN Number <span class="req-star">* (Required)</span></label>
              <input type="text" id="pan_number" name="pan_number" placeholder="e.g. AAAAA0000A" maxlength="10" style="text-transform: uppercase;" value="<?= htmlspecialchars($old['pan_number'] ?? ($s['pan_number'] ?? '')) ?>" required pattern="[A-Za-z]{5}[0-9]{4}[A-Za-z]{1}">
            </div>
            <div class="field">
              <label for="gstin">GSTIN (if applicable)</label>
              <input type="text" id="gstin" name="gstin" placeholder="24AAAAA0000A1Z5" style="text-transform: uppercase;" value="<?= htmlspecialchars($old['gstin'] ?? ($s['gstin'] ?? '')) ?>">
            </div>
          </div>
        </div>

        <!-- Card 2: Structure & members -->
        <div class="regcard">
          <h2>Structure & membership</h2>
          <div class="desc">How your society is organised, and how many members it has.</div>
          <div class="row3">
            <div class="field">
              <label for="total_wings">Number of wings / buildings</label>
              <input type="number" id="total_wings" name="total_wings" value="<?= htmlspecialchars($old['total_wings'] ?? ($s['total_wings'] ?? 4)) ?>">
            </div>
            <div class="field">
              <label for="total_flats">Total flats / units</label>
              <input type="number" id="total_flats" name="total_flats" value="<?= htmlspecialchars($old['total_flats'] ?? ($s['total_flats'] ?? 84)) ?>">
            </div>
            <div class="field">
              <label for="memberCount">Total number of members</label>
              <input type="number" id="memberCount" name="total_members" value="<?= htmlspecialchars($old['total_members'] ?? ($s['total_members'] ?? 84)) ?>" oninput="syncMemberCount()">
            </div>
          </div>
          <div class="helptext" style="margin-top:-6px;">This should match your total flats unless some units are vacant or unsold. You can add individual member records later from the Members page.</div>
        </div>

        <!-- Card 3: Opening balances -->
        <div class="regcard">
          <h2>Opening balances</h2>
          <div class="desc">Enter your society's cash and bank position as on the date you're starting this system.</div>
          <div class="field"><label>Balances as on</label><input type="date" value="2026-08-21" style="max-width:220px;"></div>

          <div class="balancegrid">
            <div class="balancecard">
              <div class="lbl">🏦 Bank balance</div>
              <input class="amtinput" type="number" placeholder="0.00" name="bank_balance" value="<?= htmlspecialchars($old['bank_balance'] ?? ($s['bank_balance'] ?? 418200)) ?>" id="bankInput" oninput="recalcSummary()">
              <div class="sub">Enter bank name & account below</div>
            </div>
            <div class="balancecard">
              <div class="lbl">💵 Cash in hand</div>
              <input class="amtinput" type="number" placeholder="0.00" name="cash_in_hand" value="<?= htmlspecialchars($old['cash_in_hand'] ?? ($s['cash_in_hand'] ?? 12500)) ?>" id="cashInput" oninput="recalcSummary()">
              <div class="sub">Petty cash held by treasurer</div>
            </div>
          </div>

          <div class="row2" style="margin-top:16px;">
            <div class="field">
              <label for="bank_name">Bank name</label>
              <input type="text" id="bank_name" name="bank_name" placeholder="e.g. HDFC Bank" value="<?= htmlspecialchars($old['bank_name'] ?? ($s['bank_name'] ?? 'HDFC Bank')) ?>">
            </div>
            <div class="field">
              <label for="account_number">Account number</label>
              <input type="text" id="account_number" name="account_number" placeholder="····4471" value="<?= htmlspecialchars($old['account_number'] ?? ($s['account_number'] ?? '')) ?>">
            </div>
          </div>
        </div>

        <!-- Summary Card -->
        <div class="summarycard">
          <h2>Opening position summary</h2>
          <div class="summarygrid">
            <div class="item"><div class="lbl">Total members</div><div class="val" id="sumMembers"><?= htmlspecialchars($s['total_members'] ?? 84) ?></div></div>
            <div class="item"><div class="lbl">Bank + cash</div><div class="val" id="sumCash">₹4,30,700</div></div>
            <div class="item"><div class="lbl">Pending dues</div><div class="val" id="sumDues">₹28,000</div></div>
            <div class="item"><div class="lbl">Net opening position</div><div class="val" id="sumNet">₹4,58,700</div></div>
          </div>
        </div>

        <div class="actionsrow">
          <?php if ($isRegistered): ?>
            <button type="button" class="btn ghost" onclick="toggleFormView()">Cancel</button>
          <?php else: ?>
            <a href="/dashboard" class="btn ghost" style="text-decoration:none;">Back to Dashboard</a>
          <?php endif; ?>
          <button type="submit" class="btn">Save & Update Database</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layouts/drawers.php'; ?>

<script>
function toggleFormView() {
    const display = document.getElementById('registeredDetailsDisplay');
    const form = document.getElementById('societyRegForm');
    if (form.style.display === 'none') {
        form.style.display = 'block';
        if (display) display.style.display = 'none';
    } else {
        form.style.display = 'none';
        if (display) display.style.display = 'block';
    }
}

function syncMemberCount() {
    const val = document.getElementById('memberCount').value;
    document.getElementById('sumMembers').textContent = val;
}

function recalcSummary() {
    const bank = parseFloat(document.getElementById('bankInput').value) || 0;
    const cash = parseFloat(document.getElementById('cashInput').value) || 0;
    const cashTotal = bank + cash;

    document.getElementById('sumCash').textContent = '₹' + cashTotal.toLocaleString('en-IN');
    document.getElementById('sumNet').textContent = '₹' + (cashTotal + 28000).toLocaleString('en-IN');
}
</script>

</body>
</html>
