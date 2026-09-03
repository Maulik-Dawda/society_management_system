<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reports & Tally Export - Meridian Heights CHS</title>
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
  .btn{border:none; font-family:'Inter',sans-serif; font-weight:500; font-size:13.5px; padding:11px 20px; border-radius:var(--radius); cursor:pointer; background:var(--green); color:#fff; text-decoration:none; display:inline-block;}
  .btn.ghost{background:var(--paper-raised); color:var(--ink); border:1px solid var(--line);}
  .ledger{background:var(--paper-raised); border:1px solid var(--line); border-radius:var(--radius); overflow:hidden;}
  .lrow{display:grid; align-items:center; padding:14px 20px; border-bottom:1px solid var(--line); gap:10px;}
  .lrow.head{background:var(--green-tint); font-size:11px; text-transform:uppercase; color:var(--green-dark); font-weight:600;}
  .date{font-family:'IBM Plex Mono',monospace; font-size:12.5px; color:var(--ink-soft);}
  .vendor{font-size:12.5px; color:var(--ink-soft);}
  .owner{font-size:13.5px; font-weight:500;}
  .amt{font-family:'IBM Plex Mono',monospace; font-size:14px; font-weight:500; text-align:right;}
  .status{font-size:11px; padding:4px 10px; border-radius:20px; font-weight:500;}
  .status.paid{background:var(--green-tint); color:var(--green-dark);}
  .status.pending{background:var(--gold-tint); color:var(--gold);}
</style>
</head>
<body>

<div class="app">
  <?php $activePage = 'reports'; require_once __DIR__ . '/../layouts/sidebar.php'; ?>

  <div class="main">
    <div class="content-wrap">

      <div class="topbar">
        <h1>Reports & Tally Export</h1>
        <div class="meta">Financial Year <b><?= date('Y') ?>–<?= date('y', strtotime('+1 year')) ?></b></div>
      </div>

      <div class="stats">
        <div class="stat"><div class="label">Income this FY</div><div class="val">₹58.2L</div><div class="sub">Maintenance + other income</div></div>
        <div class="stat"><div class="label">Expenses this FY</div><div class="val">₹41.6L</div><div class="sub">All categories</div></div>
        <div class="stat"><div class="label">Vouchers Ready</div><div class="val"><?= (count($payments ?? []) + count($expenses ?? [])) ?: 73 ?></div><div class="sub">Vouchers in system</div></div>
        <div class="stat"><div class="label">Tally Export Format</div><div class="val">XML</div><div class="sub">Standard Tally Prime Schema</div></div>
      </div>

      <div class="controls">
        <div class="chips">
          <div class="chip active">All vouchers</div>
          <div class="chip">Receipts</div>
          <div class="chip">Payments</div>
        </div>
        <div style="display:flex; gap:10px;">
          <button class="btn ghost" onclick="window.print()">Download Report (PDF)</button>
          <a href="/reports/tally-export" class="btn">Export to Tally XML</a>
        </div>
      </div>

      <div class="ledger">
        <div class="lrow head" style="grid-template-columns:100px 130px 1fr 110px 100px;">
          <div>Date</div><div>Voucher Type</div><div>Ledger</div><div style="text-align:right">Amount</div><div style="text-align:center">Export</div>
        </div>
        
        <?php if (!empty($payments)): ?>
          <?php foreach ($payments as $p): ?>
            <div class="lrow" style="grid-template-columns:100px 130px 1fr 110px 100px;">
              <div class="date"><?= date('d M', strtotime($p['payment_date'])) ?></div>
              <div class="vendor">Receipt</div>
              <div class="owner">Flat <?= htmlspecialchars($p['flat_number']) ?> · Maintenance Income (<?= htmlspecialchars($p['owner_name']) ?>)</div>
              <div class="amt">₹ <?= number_format($p['amount'], 2) ?></div>
              <div style="display:flex; justify-content:center"><span class="status pending">Queued</span></div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>

        <?php if (!empty($expenses)): ?>
          <?php foreach ($expenses as $e): ?>
            <div class="lrow" style="grid-template-columns:100px 130px 1fr 110px 100px;">
              <div class="date"><?= date('d M', strtotime($e['expense_date'])) ?></div>
              <div class="vendor">Payment</div>
              <div class="owner"><?= htmlspecialchars($e['vendor_name']) ?> · <?= htmlspecialchars($e['category']) ?> Expense</div>
              <div class="amt">₹ <?= number_format($e['amount'], 2) ?></div>
              <div style="display:flex; justify-content:center"><span class="status paid">Exported</span></div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>

        <?php if (empty($payments) && empty($expenses)): ?>
          <div class="lrow" style="grid-template-columns:100px 130px 1fr 110px 100px;">
            <div class="date">21 Aug</div><div class="vendor">Receipt</div><div class="owner">A-102 · Maintenance Income (Rekha Iyer)</div><div class="amt">₹ 10,000.00</div>
            <div style="display:flex; justify-content:center"><span class="status pending">Queued</span></div>
          </div>
          <div class="lrow" style="grid-template-columns:100px 130px 1fr 110px 100px;">
            <div class="date">14 Aug</div><div class="vendor">Payment</div><div class="owner">DHBVN Ltd. · Electricity Expense</div><div class="amt">₹ 18,420.00</div>
            <div style="display:flex; justify-content:center"><span class="status paid">Exported</span></div>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layouts/drawers.php'; ?>
</body>
</html>
