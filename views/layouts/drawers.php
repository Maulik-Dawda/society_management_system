<!-- ===================== DRAWERS & MODALS WITH ENHANCED FORM CSS ===================== -->
<style>
  :root {
    --paper:#F3F1E9; --paper-raised:#FFFEFA; --ink:#23281F; --ink-soft:#5B5F52;
    --line:#DAD5C4; --green:#1F5C4A; --green-dark:#123D31; --green-tint:#E4EDE7;
    --gold:#B9812A; --gold-tint:#F5E9D2; --rust:#B14A2E; --rust-tint:#F4E1D8; --radius:10px;
  }
  
  /* Overlays & Drawers */
  .overlay { position: fixed; inset: 0; background: rgba(35,40,31,.45); backdrop-filter: blur(2px); display: none; align-items: stretch; justify-content: flex-end; z-index: 1000; }
  .overlay.open { display: flex !important; animation: fadeIn 0.2s ease-out; }
  .drawer { width: 460px; max-width: 90vw; background: var(--paper-raised); height: 100%; padding: 32px 30px 40px; overflow-y: auto; box-shadow: -10px 0 30px rgba(0,0,0,.15); position: relative; display: flex; flex-direction: column; }
  .drawer h2 { font-family: 'Fraunces', serif; font-weight: 600; font-size: 24px; color: var(--green-dark); margin-bottom: 4px; }
  .drawer .hint { font-size: 13px; color: var(--ink-soft); margin-bottom: 24px; line-height: 1.4; }
  .close-btn { position: absolute; top: 26px; right: 26px; background: rgba(0,0,0,0.04); border: 1px solid var(--line); width: 32px; height: 32px; border-radius: 50%; font-size: 16px; color: var(--ink-soft); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.15s ease; }
  .close-btn:hover { background: var(--rust-tint); color: var(--rust); border-color: var(--rust); }
  
  /* Form Field Styles */
  .field { margin-bottom: 18px; }
  .field label { display: block; font-size: 11.5px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: var(--ink-soft); margin-bottom: 6px; }
  .field input[type="text"], .field input[type="number"], .field input[type="email"], .field input[type="date"], .field input[type="month"], .field select, .field textarea {
    width: 100%; border: 1px solid var(--line); background: var(--paper); border-radius: 8px; padding: 11px 14px; font-family: 'Inter', sans-serif; font-size: 13.5px; color: var(--ink); transition: border-color 0.15s ease, box-shadow 0.15s ease;
  }
  .field input:focus, .field select:focus, .field textarea:focus { outline: none; border-color: var(--green); background: #fff; box-shadow: 0 0 0 3px rgba(31,92,74,0.12); }
  .field select { cursor: pointer; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%235B5F52' d='M6 8.825L1.175 4 2.238 2.938 6 6.7 9.763 2.938 10.825 4z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 14px center; padding-right: 36px; }
  .field textarea { resize: vertical; min-height: 90px; }
  
  .row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
  .sectionlbl { font-family: 'Fraunces', serif; font-size: 15px; font-weight: 600; color: var(--green-dark); margin: 20px 0 14px; padding-top: 18px; border-top: 1px dashed var(--line); }
  
  /* Rent & Tenant Box */
  .rentcheck { display: flex; align-items: flex-start; gap: 12px; background: var(--paper); border: 1px solid var(--line); border-radius: 8px; padding: 14px; margin-bottom: 12px; cursor: pointer; }
  .rentcheck input[type="checkbox"] { margin-top: 3px; accent-color: var(--green); width: 16px; height: 16px; }
  .rentcheck .txt { font-size: 13.5px; font-weight: 500; color: var(--ink); }
  .rentcheck .sub { display: block; font-size: 11.5px; color: var(--ink-soft); font-weight: 400; margin-top: 2px; }
  .tenantbox { background: var(--gold-tint); border: 1px solid rgba(185,129,42,0.3); border-radius: 8px; padding: 16px; margin: 10px 0 20px; display: none; }
  .tenantbox.show { display: block; animation: fadeIn 0.2s ease; }
  .tenantbox .tlbl { font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--gold); margin-bottom: 12px; }
  
  /* Buttons */
  .save-btn { width: 100%; background: var(--green); color: #fff; border: none; padding: 14px; border-radius: 8px; font-family: 'Inter', sans-serif; font-weight: 600; font-size: 14.5px; cursor: pointer; margin-top: 16px; transition: background 0.15s ease, transform 0.1s ease; }
  .save-btn:hover { background: var(--green-dark); }
  .save-btn:active { transform: scale(0.99); }
  
  /* Receipt Modal */
  .modal-overlay { position: fixed; inset: 0; background: rgba(35,40,31,.5); backdrop-filter: blur(3px); display: none; align-items: center; justify-content: center; z-index: 1100; }
  .modal-overlay.open { display: flex !important; animation: fadeIn 0.2s ease-out; }
  .receipt { width: 420px; background: #FFFEFA; border: 1px solid var(--line); border-radius: 12px; padding: 32px 30px; box-shadow: 0 12px 36px rgba(0,0,0,.2); position: relative; }
  .receipt .stamp { position: absolute; top: 28px; right: 28px; border: 2px solid var(--green); color: var(--green); font-family: 'Fraunces', serif; font-weight: 600; font-size: 14px; letter-spacing: 0.1em; padding: 4px 10px; border-radius: 6px; transform: rotate(-8deg); opacity: 0.85; }
  .receipt-top { border-bottom: 1.5px dashed var(--line); padding-bottom: 16px; margin-bottom: 18px; }
  .receipt-top .rbrand { font-family: 'Fraunces', serif; font-size: 18px; font-weight: 600; color: var(--green-dark); }
  .receipt-top .rtitle { font-size: 12px; text-transform: uppercase; letter-spacing: 0.06em; color: var(--ink-soft); margin-top: 2px; }
  .receipt-body .rline { display: flex; justify-content: space-between; font-size: 13.5px; margin-bottom: 10px; color: var(--ink-soft); }
  .receipt-body .rline b { color: var(--ink); font-weight: 500; }
  .rtotal { display: flex; justify-content: space-between; align-items: center; background: var(--green-tint); border-radius: 8px; padding: 14px 16px; margin: 18px 0; }
  .rtotal .lbl { font-size: 12px; text-transform: uppercase; font-weight: 600; color: var(--green-dark); }
  .rtotal .val { font-family: 'IBM Plex Mono', monospace; font-size: 20px; font-weight: 600; color: var(--green-dark); }
  .rfoot { font-size: 11px; color: var(--ink-soft); text-align: center; }
  .rclose { position: absolute; top: 12px; right: 12px; background: none; border: none; font-size: 18px; cursor: pointer; color: var(--ink-soft); }
  
  @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>

<!-- Add Member Drawer -->
<div class="overlay" id="memberform">
  <form class="drawer" action="/members/add" method="POST">
    <button type="button" class="close-btn" onclick="document.getElementById('memberform').classList.remove('open')">✕</button>
    <h2>Add member</h2>
    <div class="hint">Flat, ownership, and vehicle details in one record.</div>
    <div class="sectionlbl">Flat & owner details</div>
    <div class="row2">
      <div class="field"><label>Flat number *</label><input type="text" name="flat_number" placeholder="e.g. A-102" required></div>
      <div class="field"><label>Area (sq.ft)</label><input type="number" name="area_sqft" placeholder="980"></div>
    </div>
    <div class="field"><label>Owner name *</label><input type="text" name="owner_name" placeholder="Full name" required></div>
    <div class="row2">
      <div class="field"><label>Owner phone</label><input type="text" name="owner_phone" placeholder="+91"></div>
      <div class="field"><label>Owner email</label><input type="email" name="owner_email" placeholder="name@email.com"></div>
    </div>
    <div class="sectionlbl">Occupancy</div>
    <label class="rentcheck">
      <input type="checkbox" id="rentCheck" name="is_rented" value="1" onchange="toggleRent()">
      <div class="txt">This flat is on rent<span class="sub">Check if a tenant occupies this flat instead of owner</span></div>
    </label>
    <div class="tenantbox" id="tenantBox">
      <div class="tlbl">Tenant details</div>
      <div class="row2">
        <div class="field"><label>Tenant name</label><input type="text" name="tenant_name" placeholder="Full name"></div>
        <div class="field"><label>Tenant phone</label><input type="text" name="tenant_phone" placeholder="+91"></div>
      </div>
      <div class="row2">
        <div class="field"><label>Agreement start</label><input type="date" name="agreement_start"></div>
        <div class="field"><label>Agreement end</label><input type="date" name="agreement_end"></div>
      </div>
      <div class="field"><label>ID proof</label><input type="text" name="id_proof" placeholder="Aadhaar / Passport no."></div>
    </div>
    <button type="submit" class="save-btn">Save Member to Database</button>
  </form>
</div>

<!-- Register Vehicle Drawer -->
<div class="overlay" id="regform">
  <form class="drawer" action="/vehicles/add" method="POST">
    <button type="button" class="close-btn" onclick="document.getElementById('regform').classList.remove('open')">✕</button>
    <h2>Register vehicle</h2>
    <div class="hint">Add a resident's vehicle and assign a parking slot.</div>
    <div class="field"><label>Flat number *</label><input type="text" name="flat_number" placeholder="e.g. A-102" required></div>
    <div class="row2">
      <div class="field">
        <label>Vehicle type</label>
        <select name="vehicle_type"><option value="Car">Car</option><option value="Two-wheeler">Two-wheeler</option></select>
      </div>
      <div class="field"><label>Parking slot</label><input type="text" name="parking_slot" placeholder="e.g. A-P14"></div>
    </div>
    <div class="field"><label>Vehicle number *</label><input type="text" name="vehicle_number" placeholder="MH 04 AB 1234" required></div>
    <div class="row2">
      <div class="field"><label>Make & model</label><input type="text" name="make_model" placeholder="e.g. Maruti Swift"></div>
      <div class="field"><label>Colour</label><input type="text" name="colour" placeholder="e.g. White"></div>
    </div>
    <button type="submit" class="save-btn">Save Vehicle</button>
  </form>
</div>

<!-- Generate Bill Drawer -->
<div class="overlay" id="genbill">
  <form class="drawer" action="/maintenance/generate" method="POST">
    <button type="button" class="close-btn" onclick="document.getElementById('genbill').classList.remove('open')">✕</button>
    <h2>Generate bill</h2>
    <div class="hint">Raise maintenance bills for a billing cycle.</div>
    <div class="field"><label>Billing cycle *</label><input type="month" name="billing_cycle" value="<?= date('Y-m') ?>" required></div>
    <div class="field">
      <label>Apply to</label>
      <select name="apply_to"><option value="All">All flats</option><option value="Wing A">Wing A only</option><option value="Overdue">Overdue flats only</option></select>
    </div>
    <div class="field">
      <label>Charge basis</label>
      <select name="charge_basis"><option value="Fixed">Fixed</option><option value="Per sq.ft">Per sq.ft</option><option value="Slab-based">Slab-based</option></select>
    </div>
    <div class="row2">
      <div class="field"><label>Amount (₹) *</label><input type="number" name="amount" placeholder="10000" value="10000" required></div>
      <div class="field"><label>Due date *</label><input type="date" name="due_date" value="<?= date('Y-m-10', strtotime('+1 month')) ?>" required></div>
    </div>
    <div class="field"><label>Late fee rule</label><input type="text" name="late_fee_rule" value="₹200 flat + 1.5% monthly"></div>
    <button type="submit" class="save-btn">Generate Maintenance Bills</button>
  </form>
</div>

<!-- Collect Payment Drawer -->
<div class="overlay" id="collect">
  <form class="drawer" action="/payments/collect" method="POST">
    <button type="button" class="close-btn" onclick="document.getElementById('collect').classList.remove('open')">✕</button>
    <h2>Collect payment</h2>
    <div class="hint">Record a maintenance payment received.</div>
    <div class="field"><label>Flat number *</label><input type="text" name="flat_number" placeholder="e.g. B-304" required></div>
    <div class="field"><label>Owner / Resident Name</label><input type="text" name="owner_name" placeholder="Full name"></div>
    <div class="row2">
      <div class="field"><label>Amount received (₹) *</label><input type="number" name="amount" placeholder="11500" required></div>
      <div class="field"><label>Payment date</label><input type="date" name="payment_date" value="<?= date('Y-m-d') ?>"></div>
    </div>
    <div class="field">
      <label>Payment Mode</label>
      <select name="payment_mode"><option value="UPI">UPI</option><option value="Bank transfer">Bank transfer</option><option value="Cash">Cash</option><option value="Cheque">Cheque</option></select>
    </div>
    <div class="field"><label>Reference / Transaction No.</label><input type="text" name="reference_no" placeholder="UPI Ref / Cheque No."></div>
    <button type="submit" class="save-btn">Record Payment & Generate Receipt</button>
  </form>
</div>

<!-- Add Expense Drawer -->
<div class="overlay" id="overlay-exp">
  <form class="drawer" action="/expenses/add" method="POST">
    <button type="button" class="close-btn" onclick="document.getElementById('overlay-exp').classList.remove('open')">✕</button>
    <h2>Add expense</h2>
    <div class="hint">Fill in the bill details and attach a copy for the record.</div>
    <div class="row2">
      <div class="field"><label>Date *</label><input type="date" name="expense_date" value="<?= date('Y-m-d') ?>" required></div>
      <div class="field">
        <label>Category *</label>
        <select name="category" required><option value="Electricity">Electricity</option><option value="Housekeeping">Housekeeping</option><option value="Lift AMC">Lift AMC</option><option value="Repairs">Repairs</option><option value="Security">Security</option></select>
      </div>
    </div>
    <div class="field"><label>Vendor name *</label><input type="text" name="vendor_name" placeholder="CleanPro / DHBVN / OTIS" required></div>
    <div class="row2">
      <div class="field"><label>Amount (₹) *</label><input type="number" name="amount" placeholder="0.00" required></div>
      <div class="field"><label>GST %</label><input type="number" name="gst_pct" value="18"></div>
    </div>
    <div class="row2">
      <div class="field"><label>Bill number</label><input type="text" name="bill_number" placeholder="Vendor's invoice no."></div>
      <div class="field">
        <label>Payment mode</label>
        <select name="payment_mode"><option value="Bank transfer">Bank transfer</option><option value="UPI">UPI</option><option value="Cash">Cash</option></select>
      </div>
    </div>
    <div class="field"><label>Notes</label><textarea name="notes" rows="3" placeholder="Additional details"></textarea></div>
    <button type="submit" class="save-btn">Save Expense to Database</button>
  </form>
</div>

<!-- Post Notice Modal -->
<div class="overlay" id="postNoticeModal">
  <form class="drawer" action="/notices/add" method="POST">
    <button type="button" class="close-btn" onclick="document.getElementById('postNoticeModal').classList.remove('open')">✕</button>
    <h2>Post Notice</h2>
    <div class="hint">Publish a notice for all society members.</div>
    <div class="field"><label>Notice Date *</label><input type="date" name="notice_date" value="<?= date('Y-m-d') ?>" required></div>
    <div class="field"><label>Title *</label><input type="text" name="title" placeholder="Notice subject" required></div>
    <div class="field">
      <label>Category</label>
      <select name="category"><option value="General">General</option><option value="Maintenance">Maintenance</option><option value="Event">Event</option><option value="Security">Security</option></select>
    </div>
    <div class="field">
      <label><input type="checkbox" name="is_urgent" value="1"> ⚠️ Mark as URGENT Notice</label>
    </div>
    <div class="field"><label>Content *</label><textarea name="content" rows="4" placeholder="Enter notice announcement text..." required></textarea></div>
    <button type="submit" class="save-btn">Publish Notice</button>
  </form>
</div>

<!-- Receipt Preview Modal -->
<div class="modal-overlay" id="receiptModal">
  <div class="receipt">
    <button type="button" class="rclose" onclick="document.getElementById('receiptModal').classList.remove('open')">✕</button>
    <div class="stamp">PAID</div>
    <div class="receipt-top"><div class="rbrand">Meridian Heights CHS</div><div class="rtitle">Payment Receipt</div></div>
    <div class="receipt-body">
      <div class="rline"><span>Receipt No.</span><b id="recNo">RC-2026-0847</b></div>
      <div class="rline"><span>Date</span><b><?= date('d M Y') ?></b></div>
      <div class="rline"><span>Flat</span><b id="recFlat">B-304</b></div>
      <div class="rline"><span>Towards</span><b>Maintenance Collection</b></div>
      <div class="rline"><span>Status</span><b style="color:var(--green-dark);">Confirmed & Recorded</b></div>
    </div>
    <div class="rtotal"><span class="lbl">Amount Paid</span><span class="val" id="recAmt">₹ 11,500.00</span></div>
    <div class="rfoot">Auto-generated system receipt. No signature required.</div>
  </div>
</div>

<script>
function toggleRent() {
    const rentCheck = document.getElementById('rentCheck');
    const tenantBox = document.getElementById('tenantBox');
    if (rentCheck && tenantBox) {
        tenantBox.className = rentCheck.checked ? 'tenantbox show' : 'tenantbox';
    }
}

function openReceipt(receiptNo, flat, amount) {
    if (receiptNo) document.getElementById('recNo').textContent = receiptNo;
    if (flat) document.getElementById('recFlat').textContent = flat;
    if (amount) document.getElementById('recAmt').textContent = '₹ ' + amount;
    const modal = document.getElementById('receiptModal');
    if (modal) modal.classList.add('open');
}
</script>
